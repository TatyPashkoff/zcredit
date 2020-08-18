<?php
namespace common\models;


use common\helpers\TextHelper;
use Exception;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property string $password write-only password
 */
class ApiUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $password = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%api_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at' ], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['login', 'password_hash','auth_key','token'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app','Имя'),
            'lastname' => Yii::t('app','Фамилия'),
            'phone' => Yii::t('app','Телефон'),
            'role' => Yii::t('app','Роль'),
            'password' => Yii::t('app','Пароль'),
            'status' => Yii::t('app','Статус'),
            'state' => Yii::t('app','Состояние'),
            'company' => Yii::t('app','Компания'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByToken($token)
    {
        return static::findOne(['token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /*public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }*/

    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    // получаем kyc
    public function getKyc()
    {
        return $this->hasOne(Kyc::className(), ['client_id' => 'id']);
    }


    public static function create($role)
    {
        $user = new User();

        if ($user->updateModel(true) ){
                $user->role = $role;
                $user->save();
                return $user;
        }else{
            Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при регистрации пользователя!'));

        }

        return false;

    }


    public function updateModel($new=false)
    {

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается
                $this->created_at = time();
                $this->status = 1;
                $this->phone_confirm = 0;
            }

            $this->updated_at = time();

            $this->phone = isset($post['User']['phone']) ? User::correctPhone($post['User']['phone']) : '';

            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error', json_encode($this->getErrors()));

                return false;
            }

            try {

                $path = Yii::getAlias("@frontend/web/uploads/users/");

                if (!is_dir($path)) @mkdir($path);


                if ($file = UploadedFile::getInstance($this, 'passport_main')) {

                    if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                    $fname =time() . '.'. $file->extension;

                    $path = Yii::getAlias("@frontend/web/uploads/users/" . $this->id . '/');

                    @unlink($path . $this->passport_main);

                    if (!is_dir($path)) @mkdir($path);

                    // основная картинка - оригинал
                    $file->saveAs($path . $fname);

                    $this->passport_main = $fname;

                }

                if ($file = UploadedFile::getInstance($this, 'passport_address')) {

                    if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                    $fname =time()+1 . '.'. $file->extension;

                    $path = Yii::getAlias("@frontend/web/uploads/users/" . $this->id . '/');

                    @unlink($path . $this->passport_address);

                    if (!is_dir($path)) @mkdir($path);

                    // основная картинка - оригинал
                    $file->saveAs($path . $fname);

                    $this->passport_address = $fname;

                }

            }catch (Exception $e) {
                return false;
            }

            if( !$this->save() ){

                Yii::$app->session->setFlash('info-error', json_encode($this->getErrors()));
               // print_r($this->getErrors());
                return false;
            }

            Yii::$app->session->setFlash('info-success',Yii::t('app','Пользователь успешно сохранен!'));

            return true;
        }

        return false;

    }

    public static function correctPhone($phone){
        return preg_replace('/[^0-9]/','',$phone);
    }


    // скачать архив обложки и прописки
    public static function getDocuments($id){

        // не все могут скачать документы
        if(!in_array(Yii::$app->user->identity->role,[User::ROLE_SUPPLIER,User::ROLE_KYC,User::ROLE_ADMIN]) )       exit;

        if($user = User::find()->where(['id'=>$id,'role'=>User::ROLE_CLIENT])->one()) {
            $error = '';
            $path = Yii::getAlias('@frontend/web/uploads/users/' . $user->id .'/');
            $zip_name = TextHelper::Transliterate($user->username . ' ' . $user->lastname) . '.zip';

            $zip = new \ZipArchive();
            if ($zip->open($zip_name, \ZIPARCHIVE::CREATE) !== TRUE) {
                $error .= "* Sorry ZIP creation failed at this time<br/>";
                exit;
            }

            $zip->addFile($path . $user->passport_main, $user->passport_main);
            $zip->addFile($path . $user->passport_address, $user->passport_address);
            $zip->close();

            if (file_exists($zip_name)) {
                header('Content-type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zip_name . '"');
                readfile($zip_name);
            }
        }

    }



}
