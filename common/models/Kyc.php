<?php

namespace common\models;

use common\helpers\SmsHelper;
use Yii;
use common\models\Images;
use yii\web\View;


/**
 * This is the model class for table "kyc".
 *
 * @property integer $id
    
 * @property integer $created_at
    
 * @property integer $client_id
    
 * @property integer $status_verify
    
 * @property integer $date_verify
    
 * @property integer $status
    
 * @property integer $delay
    
 * @property string $salary
    
 * @property string $credit_month
    
 * @property string $credit_year
    
 * @property string $credit_rating
    
 */
class Kyc extends \yii\db\ActiveRecord
{

    const ITEMS_COUNT = 50;



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kyc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at','updated_at','client_id', 'date_verify','status','status_verify','delay'], 'integer'],
            [['salary', 'credit_month', 'credit_year'], 'number'],
            [['credit_rating'], 'string', 'max' => 3],
            [['comments'], 'string'],
            [['credit_year'], 'default', 'value'=> 3000000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Yii::t('app','Создан'),
            'client_id' => Yii::t('app','Клиент'),
            'status_verify' => Yii::t('app','Статус верификации'),
            'date_verify' => Yii::t('app','Дата верификации'),
            'status' => Yii::t('app','Статус'),
            'delay' => Yii::t('app','Просрочка'),
            'salary' => Yii::t('app','Зарплата'),
            'credit_month' => Yii::t('app','Кредит в месяц'),
            'credit_year' => Yii::t('app','Кредит в год'),
            'credit_rating' => Yii::t('app','Кредитный рейтинг'),
        ];
    }


    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'client_id'])->where(['role'=>User::ROLE_CLIENT]);
    }

    public function getSupplier(){
        return $this->hasOne(User::className(),['id'=>'supplier_id'])->where(['role'=>User::ROLE_SUPPLIER]);
    }

    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается только один раз при создании
                //$this->date = time();
            }

            if(isset($post['date_verify'])) {
                $date = strtotime($post['date_verify'] . ' 00:00:00');
                if ($date < 0) $date = 0;
                $this->date_verify = $date;

            }

            //$attr = $this->getDirtyAttributes(['status']);
            $attr = $this->isAttributeChanged('status');

            // отправка смс с сообщением при изменении статуса подтверждения
            if( isset($attr['status']) && $attr['status']==1 ){
                SmsHelper::sendSms($this->client->phone,$this->getMessage($this->status));
                Yii::$app->session->setFlash('info',Yii::t('app','Сохранение успешно завершено! Клиенту отправлено смс со статусом подтверждения!'));
            }else{
                Yii::$app->session->setFlash('info',Yii::t('app','Сохранение успешно завершено!'));

            }

            if( !$this->save() ){
                Yii::$app->session->setFlash('info',Yii::t('app','Ошибка при сохранении!'));
               return false;
            }


            return true;
        }
        return false;

    }

    public static function addUser($user_id,$suppler_id){

        $kyc = new Kyc();
        $kyc->client_id = $user_id;
        $kyc->supplier_id = $suppler_id;
        $kyc->status_verify = 0;
        $kyc->date_verify = 0;
        $kyc->created_at = time();
        $kyc->updated_at = time();
        $kyc->status = 0;
        $kyc->delay = 0;
        $kyc->salary = 0;
        $kyc->credit_month = 0;
        $kyc->credit_year = 3000000; //Кредитный лимит на год
        $kyc->credit_rating = '';
        if(!$kyc->save()){
            print_r($kyc->getErrors());
            exit;
        }
        return $kyc;

    }

    public function getMessage($status){
        $msg = [
            0 => 'Zmarket. '. Yii::t('app','К сожалению вы не можете воспользоваться услугами нашего сервиса!'),
            1 => 'Zmarket. '. Yii::t('app','Ваш аккаунт успешно подтвержден!'),
        ];
        return $msg[$status];

    }

}
