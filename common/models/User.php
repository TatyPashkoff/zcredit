<?php
namespace common\models;


use common\helpers\SmsHelper;
use common\helpers\TextHelper;
use common\helpers\UtilsHelper;
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
 * @property integer $supplier_id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $auto_discard
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    const ROLE_CLIENT = 1;
    const ROLE_SUPPLIER = 2;
    const ROLE_KYC = 3;
    const ROLE_API_USER = 4;
    const ROLE_ADMIN = 5;
	
	const ORENTITY_ENTITY = 1; //Юридическое лицо
    const ORENTITY_INDIVIDUAL = 2;  //Физическое лицо


    public $password = '';
    public $image_path = '/uploads/users/';


    const TEST_MODE = true;

	    /**
     * @return array statuses orentity
     */
    public static function orentitys()
    {
        return [
            self::ORENTITY_ENTITY => 'Юридическое лицо',
            self::ORENTITY_INDIVIDUAL => 'Физическое лицо',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
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
            [['role','supplier_id','nds_state','orentity','region_id' ,'auto_discard','discount','status_client_complete','filial','seal_number','margin_three', 'margin_six','margin_nine','service_type','code','cashback'], 'integer'],
            ['summ', 'number'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
			['orentity', 'default', 'value' => self::ORENTITY_INDIVIDUAL],
            //['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['login','passport_main', 'passport_address','passport_self', 'passport_serial','passport_date','passport_id','passport_issuer','nds','uzcard','exp','inn','password_login','address_filial','brand','pnfl'], 'string'],
            [['username', 'lastname', 'password_hash', 'password', 'phone','phone_home','company','uzcard_month','uzcard_year','address','printer_number','patronymic','birthday','work_place','passport_date_end','permanent_address'], 'string'],
            ['phone', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app','Данный номер телефона уже существует, укажите другой!')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app','Имя'),
            'lastname' => Yii::t('app','Фамилия'),
            'patronymic' => Yii::t('app','Отчество'),
            'work_place' => Yii::t('app','Место работы/учебы'),
            'permanent_address' => Yii::t('app','Адрес постоянного места жительства'),
            'phone' => Yii::t('app','Телефон'),
            'phone_home' => Yii::t('app','Домашний телефон'),
            'role' => Yii::t('app','Роль'),
            'password' => Yii::t('app','Пароль'),
            'password_login' => Yii::t('app','Пароль для входа вендоров'),
            'status' => Yii::t('app','Статус'),
            'state' => Yii::t('app','Состояние'),
            'company' => Yii::t('app','Компания'),
            'summ' => Yii::t('app','Первоначальная сумма'),
            'nds' => Yii::t('app','Ставка НДС'),
            'nds_state' => Yii::t('app','НДС плательщик'),
            'orientity' => Yii::t('app','Тип'),
            'passport_serial' => Yii::t('app','Серия паспорта'),
            'passport_id' => Yii::t('app','ID паспорта'),
            'passport_date' => Yii::t('app','Дата выдачи паспорта'),
            'passport_issuer' => Yii::t('app','Кем выдан'),
            'passport_address' => Yii::t('app','Юридический адрес'),
            'address_filial' => Yii::t('app','Адрес филиала магазина'),
            'filial' => Yii::t('app','Филиал'),
            'uzcard' => Yii::t('app','Номер карты'),
            'exp' => Yii::t('app','Срок годности'),
            'discount' => Yii::t('app','Скидка от магазина'),
            'printer_number' => Yii::t('app','Номер принтера'),
            'seal_number' => Yii::t('app','Номер печати'),
            'created_at' => Yii::t('app','Дата создания'),
            'supplier_id' => Yii::t('app','ID'),
            'margin_three' => Yii::t('app','Маржа 3 мес'),
            'margin_six' => Yii::t('app','Маржа 6 мес'),
            'margin_nine' => Yii::t('app','Маржа 9 мес'),
            'brand' => Yii::t('app','Бренд'),
            'service_type' => Yii::t('app','Вид оказываемых услуг'),

        ];
    }

    public function getVendor(){

        return $this->hasOne(VendorItems::className(),['user_id'=>'id']);
    }

   
    public function getContract(){
        return $this->hasOne(Kyc::className(),['client_id'=>'id']);
    }

    public static function getBalance($id=null){
        if( $user = User::find()->where(['id'=>$id,'role'=>User::ROLE_CLIENT])->one() ){
            return $user->summ;
        }
        return 0;
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
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findVendorByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'role' => 2, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
    }

    public static function checkComplete($id)
    {
        $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        if ($user->uzcard && $user->exp && $user->auto_discard_type == 1 && $user->passport_main && $user->passport_address && $user->passport_self) {
            return 4; // завершил
        }
        if($user->uzcard && $user->exp && $user->auto_discard_type == 1){
            return 2; // карта
        }
        if($user->passport_main && $user->passport_address && $user->passport_self){
            return 3; // паспорт
        }
        if($user->phone && $user->phone_confirm == 1){
            return 1; // тел
        }
        return false;
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
    // получаем scoring
    public function getScoring()
    {
        return $this->hasOne(Scoring::className(), ['user_id' => 'id']);
    }


    public static function create($role)
    {
        /*if( Yii::$app->session->has('user_id_otp') ){
            // клиент создан поставщиком
            $user_id = Yii::$app->session->get('user_id_otp');
            $user = User::findOne($user_id);
        }else {

        } */
        $user = new User();

        if( $user->updateModel(true) ){

            UtilsHelper::debug('user update');

            $supplier_id = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id; // 0 если сам регистрируется, либо id поставвщика, который регистрировал клиента

            if (isset($post['User']['uzcard'])) {
                $card = preg_replace('/[^0-9]/', '', $post['User']['uzcard']);

                if( preg_match('[^8600]',$card) ){
                    $type =1;
                }else if(preg_match('[^6262]',$card) ){
                    $type = 2;
                }else{
                    $type = 0;
                }

                $exp = preg_replace('/[^0-9]/', '', $post['User']['exp']);
                $exp_m = mb_substr($exp, 0, 2);
                $exp_y = mb_substr($exp, 2, 2);
                $exp = $exp_y . $exp_m;
                $user->uzcard = mb_substr($card, 10, 6);
                $user->exp = $exp;



                $user->auto_discard_type = $type;
            }

            //$post = Yii::$app->request->post();
            /*if($role == User::ROLE_CLIENT) { // для клиентов
              if (isset($post['User']['uzcard'])) $card = preg_replace('/[^0-9]/', '', $post['User']['uzcard']);
               if (isset($post['User']['exp'])) {
                   $exp = preg_replace('/[^0-9]/', '', $post['User']['exp']);
                   $exp_m = mb_substr($exp, 0, 2);
                   $exp_y = mb_substr($exp, 2, 2);
                   $exp = $exp_y . $exp_m;
                   $user->uzcard = mb_substr($card, 10, 6);
                   $user->exp = $exp;
               }

           }*/
            $user->role = $role;
            $user->supplier_id = $supplier_id;

            if(!$user->save()){
                Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при сохранении клиента!') . ' ' . json_encode($user->getErrors()));
                return false;
            }

            UtilsHelper::debug('user save OK');

            if( $role == User::ROLE_CLIENT ) {

                $kyc = Kyc::addUser($user->id, $supplier_id);
                $kyc->status_verify = 0;
                $kyc->date_verify = time();
                if(!$kyc->save()){
                    Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при сохранении данных клиента!') . ' ' . json_encode($kyc->getErrors()));
                    return false;
                }
                UtilsHelper::debug('kyc save OK');


            }

            return $user;

        }

        Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при регистрации пользователя!'));

        return false;

    }


    public function updateModel($new=false)
{

    $post = Yii::$app->request->post();
    //UtilsHelper::debug('user upd model');

    UtilsHelper::debug($post);

    if( $this->load($post) ) {
        //UtilsHelper::debug('идет запись');


        if( $new ){ // если создается
            $this->created_at = time();
            $this->status = 1; // подтверждает kyc отдел
            $this->phone_confirm = 0;
        }

        $this->updated_at = time();

        if(isset($post['User']['cashback'])) {
            $this->cashback = $post['User']['cashback'];
        }

        if(isset($post['User']['phone_home'])) {
            $this->phone_home = isset($post['User']['phone_home']) ? User::correctPhone($post['User']['phone_home']) : '';
        }
		
		if(isset($post['User']['birthday'])) {
            $this->birthday = $post['User']['birthday'];
        }

        if(isset($post['User']['phone'])) {
            $this->phone = isset($post['User']['phone']) ? User::correctPhone($post['User']['phone']) : '';
        }
		
		
		if(isset($post['User']['passport_date_end'])) {
            $this->passport_date_end = $post['User']['passport_date_end'];
        }

        if( !$this->save() ){
            Yii::$app->session->setFlash('info', json_encode($this->getErrors()));
            UtilsHelper::debug($this->getErrors());
            return false;
        }


        try {

            $path = Yii::getAlias("@frontend/web/uploads/users/");

            if (!is_dir($path)) @mkdir($path);

            UtilsHelper::debug('save passport');

            if ($file = UploadedFile::getInstance($this, 'passport_main')) {
                //UtilsHelper::debug('start passport_main');

                if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                $fname =time() . '.'. $file->extension;

                $path = Yii::getAlias("@frontend/web/uploads/users/" . $this->id . '/');

                @unlink($path . $this->passport_main);

                if (!is_dir($path)) @mkdir($path);

                $file->saveAs($path . $fname);

                $this->passport_main = $fname;
                //UtilsHelper::debug('end passport_main');

            }

            if ($file = UploadedFile::getInstance($this, 'passport_address')) {
                //UtilsHelper::debug('start passport_address');

                if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                $fname =time()+1 . '.'. $file->extension;

                $path = Yii::getAlias("@frontend/web/uploads/users/" . $this->id . '/');

                @unlink($path . $this->passport_address);

                if (!is_dir($path)) @mkdir($path);

                $file->saveAs($path . $fname);

                $this->passport_address = $fname;
                // UtilsHelper::debug('end passport_address');

            }
            if ($file = UploadedFile::getInstance($this, 'passport_self')) {
                //UtilsHelper::debug('start passport_self');

                if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                $fname =time()+2 . '.'. $file->extension;

                $path = Yii::getAlias("@frontend/web/uploads/users/" . $this->id . '/');

                @unlink($path . $this->passport_self);

                if (!is_dir($path)) @mkdir($path);

                $file->saveAs($path . $fname);

                $this->passport_self = $fname;
                // UtilsHelper::debug('end passport_self');

            }

        }catch (Exception $e) {
            return false;
        }

        if( !$this->save() ){

            Yii::$app->session->setFlash('info', json_encode($this->getErrors()));
            UtilsHelper::debug($this->getErrors());

            return false;
        }

        if($post['User']['pnfl'] > 0 ) {
            $is_pnfl = static::find()->where(['pnfl' => $post['User']['pnfl']])->count();
            if($is_pnfl > 1) {return false;} 

        }

        Yii::$app->session->setFlash('info',Yii::t('app','Пользователь успешно сохранен!'));

        return true;
    }

    UtilsHelper::debug('нет данных для сохранения клиента');

    return false;

}

    public static function correctPhone($phone){
        return preg_replace('/[^0-9]/','',$phone);
    }

    public static function createLoginPassword(){
        // Символы, которые будут использоваться в пароле.
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";

        // Количество символов в пароле.
        $max = 10;

        // Определяем количество символов в $chars
        $size = StrLen($chars) - 1;

        $password_login = null;

        // Создаём пароль.
        while ($max--)
            $password_login .= $chars[rand(0, $size)];

        return $password_login;

    }


    // скачать архив обложки и прописки
    public static function getDocuments($id=null){

        //return false;

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

           if($user->passport_main!='') $zip->addFile($path . $user->passport_main, $user->passport_main);
           if($user->passport_address!='') $zip->addFile($path . $user->passport_address, $user->passport_address);
           if($user->passport_self!='') $zip->addFile($path . $user->passport_self, $user->passport_self);
           $zip->close();

           if (file_exists($zip_name)) {
               header('Content-type: application/zip');
               header('Content-Disposition: attachment; filename="' . $zip_name . '"');
               readfile($zip_name);
           }
       }

    }



}
