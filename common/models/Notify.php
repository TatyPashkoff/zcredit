<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "notify".
 *
 * @property string $id
    
 * @property string $created_at
    
 * @property string $user_id
    
 * @property string $code
    
 * @property integer $status
    
 * @property integer $state
    
 * @property string $msg
    
 */
class Notify extends \yii\db\ActiveRecord
{

    const ITEMS_COUNT = 30;

    /*----------------------------------*/
    const EVENT_CREATE_USER = 1;
    const EVENT_SEND_USER_EMAIL_CONFIRM = 2;
    const EVENT_USER_LOGIN = 3;
    const EVENT_EMAIL_CONFIRM = 4;
    const EVENT_USER_LOGIN_SOC = 5;
    const EVENT_SEND_EMAIL_PASSWORD = 6;
    const EVENT_USER_REGISTER_SOC = 7;

    const EVENT_USER_FOLLOW = 10;
    const EVENT_USER_SUBSCRIBE = 11;
    const EVENT_USER_UNSUBSCRIBE = 12;
    //const EVENT_USER_LIKE = 13;

    const EVENT_PRODUCT_ADD = 20;
    const EVENT_PRODUCT_BUY = 21;
    const EVENT_PRODUCT_SALE = 22;
    const EVENT_PRODUCT_CANCEL = 23;
    const EVENT_PRODUCT_BACK = 24;
    const EVENT_PRODUCT_LIKE = 25;

    const EVENT_CREATE_ORDER = 30;


    const EVENT_CREATE_USER_ERR = 100;
    const EVENT_SEND_USER_EMAIL_CONFIRM_ERR = 101;

    const EVENT_SEND_EMAIL_PASSWORD_ERR = 106;

    const EVENT_CREATE_ORDER_ERR = 130;

   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','view_at', 'user_id', 'code','status', 'user_from','state'], 'integer'],
            [['msg'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'view_at' => 'Дата просмотра',
            'user_id' => 'Пользователь',
            'code' => 'Код',
            'status' => 'Статус',
            'state' => 'Состояние',
            'msg' => 'Сообщение',
        ];
    }


    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_from']);
    }

    public static function add($user_id,$code,$status=1,$state=0)
    {
        $notify = new Notify();
        $notify->created_at = time();
        $notify->user_id = $user_id;
        $notify->state = $state;
        $notify->status = $status;
        $notify->code = $code;
        if(!$notify->save()){
            print_r($notify->getErrors());
            exit;
        }
        return null;
    }

    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается только один раз при создании
                //$this->date = time();
            }

            
            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');


                return true;
            }

            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }

    // получение уведомления
    public static function getNotify($code){

       $MESSAGES = [
            // общие
            1 => Yii::t('app','Создан пользователь'),
            2 =>  Yii::t('app','На Ваш номер отправлено СМС сообщение с кодом для подтверждения получения кредита'),
            3 => Yii::t('app', 'Вход пользователя'),
            4 => Yii::t('app', 'Кредит подтвержден'),
            5 => Yii::t('app', 'Кредит оформлен'),
            6 => Yii::t('app', 'Кредит оплачен'),
            7 => Yii::t('app', 'Внесена ежемесячная оплата'),



            130 => Yii::t('app', 'Ошибка при оформлении кредита'),

            // поставщик

        ];

        
        return $MESSAGES[$code];
        
    }



}