<?php

namespace common\models;

use common\helpers\UtilsHelper;
use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "scoring".
 *
 * @property integer $id
    
 * @property integer $user_id
 * 
 * @property integer $cards_add_id
    
 * @property integer $created_at
    
 * @property integer $updated_at
    
 * @property integer $date_start
    
 * @property integer $date_end
    
 * @property string $pan
    
 * @property string $exp
    
 * @property string $phone
    
 * @property string $fullname
    
 * @property string $balance
    
 * @property string $summ
    
 * @property string $data
    
 * @property integer $sms
    
 */
class Scoring extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scoring';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cards_add_id','created_at', 'updated_at', 'date_start', 'date_end','sms','type'], 'integer'],
            [['data'], 'string'],
            [['pan', 'exp', 'phone', 'balance', 'summ','bank_c','card_h'], 'string', 'max' => 32],
            [['fullname','token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'cards_add_id' => 'Card ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'pan' => 'Pan',
            'exp' => 'Exp',
            'bank_c' => 'bank_c',
            'card_h' => 'card_h',
            'phone' => 'Phone',
            'fullname' => 'Fullname',
            'balance' => 'Balance',
            'summ' => 'Summ',
            'data' => 'Data',
            'sms' => 'Sms',
        ];
    }


    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается только один раз при создании
                //$this->date = time();
            }

            
            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');
                print_r($this->getErrors());
                exit;

                return true;
            }


            
            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }


    // оправка смс на подтверждение
    // $user_id - поставщик, если он регал иначе, если сам 0
    public static function sendOtp($user_id=0,$card_id=null){

        if(!$user = User::find()->where(['id'=>$user_id])->one() ) {
            return ['status' => 0, 'info' => Yii::t('app','Клиент не найден!')];
        }

        $card = Yii::$app->session->get('card');
        $exp = Yii::$app->session->get('exp');
        $type = Yii::$app->session->get('type');

        if($card=='') return ['status'=>0,'info'=>Yii::t('app','Не указан номер карты')];
        if($exp=='') return ['status'=>0,'info'=>Yii::t('app','Не указан срок карты')];
        if($type=='') return ['status'=>0,'info'=>Yii::t('app','Не определен тип карты')];


        if($type == 0){
            return ['status'=>0,'info'=>Yii::t('app','Указан неверный тип платежной системы ' .(string)$type)];
        }
        // смс оповещение клиента с кодом подтверждения кредита
        // вызов otp

        $scoring_data = Uzcard::cardOtp($card,$exp);
        if( $scoring_data['status'] ){
            if ( !$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                $scoring = new Scoring();
            }
            if($card_id){
                $scoring = new Scoring();
            }
            //$scoring = new Scoring();
            $scoring->load($scoring_data);
            $scoring->user_id = $user->id;
            if($card_id)  $scoring->cards_add_id = $card_id;
            $scoring->created_at = time();
            if(!$scoring->save()){
                $info = Yii::t('app','Ошибка при сохранении данных скоринга!') . ' ' . json_encode($scoring->getErrors());
                return ['status'=>0,'info'=>$info];
            }
            $info = Yii::t('app','Клиент успешно подлючен к системе Uzcard!');

        }else{
            $info = Yii::t('app','Ошибка при получении токена UZCARD!') . ' ' . json_encode($scoring_data) ;
        }

        /*switch ($type){
            case 1: // Uzcard - отправка смс
                // вызов otp

                $scoring_data = Uzcard::cardOtp($card,$exp);
                if( $scoring_data['status'] ){
                    if ( !$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                        $scoring = new Scoring();
                    }
                    //$scoring = new Scoring();
                    $scoring->load($scoring_data);
                    $scoring->user_id = $user->id;
                    $scoring->created_at = time();
                    if(!$scoring->save()){
                        $info = Yii::t('app','Ошибка при сохранении данных скоринга!') . ' ' . json_encode($scoring->getErrors());
                        return ['status'=>0,'info'=>$info];
                    }
                    $info = Yii::t('app','Клиент успешно подлючен к системе Uzcard!');

                }else{
                    $info = Yii::t('app','Ошибка при получении токена UZCARD!') . ' ' . json_encode($scoring_data) ;
                }

                break;
            case 2: // paymo - нет смс

                $scoring_data = Paymo::addCard($card,$exp);
                if( $scoring_data['token'] ){
                    $scoring = new Scoring();
                    $scoring->load($scoring_data);
                    $scoring->user_id = $user->id; // после сохранения клиента внесем
                    $scoring->created_at = time();
                    if(!$scoring->save()){
                        $info = Yii::t('app','Ошибка при сохранении данных Paymo!') . ' ' . json_encode($scoring->getErrors());
                        return ['status'=>0,'info'=>$info];
                    }
                    $info = Yii::t('app','Клиент успешно подлючен к системе Paymo!');

                }else{
                    $info = Yii::t('app','Ошибка при получении токена Paymo!') . ' ' . json_encode($scoring_data);
                }
                break;

            default:
                return ['status'=>0,'info'=>Yii::t('app','Указан неверный тип платежной системы ' .(string)$type)];

        }*/

        // токен карты или ее id
        if(isset($scoring)) Yii::$app->session->set('token',$scoring->token);

        return ['status'=>1,'info'=>$info];

    }


    public static function checkOtp(){

        UtilsHelper::debug('check-otp');

        $post = Yii::$app->request->post();

        $code = @$post['code'];

        UtilsHelper::debug('verify');

        UtilsHelper::debug($post);

        $user_id = Yii::$app->session->get('user_id');

        if(!$user = User::find()->with('scoring')->where(['id'=>$user_id])->one() ) {
            return ['status' => 0, 'info' => Yii::t('app','Клиент не найден!')];
        }

        if(!isset($user->scoring)){
            return ['status' => 0, 'info' => Yii::t('app','Токен карты для клиента не найден!')];

        }
        $token = $user->scoring->token; //Yii::$app->session->has('token') ? Yii::$app->session->get('token') : false;

        if($token=='' || is_null($token)) return ['status'=>0,'info'=>Yii::t('app','Токен не найден')];

        UtilsHelper::debug($token);

        $token = Yii::$app->session->get('token');

        $result = Uzcard::cardVerify($token,$code);
        if( isset($result['status']) && $result['status']==1 ) { //} || isset($result['result']) ) {
            UtilsHelper::debug('Uzcard Autodiscard OK!');

            return ['status'=>1,'info'=>'Привязка Uzcard прошла успешно!'];
        }else{
            UtilsHelper::debug($result);

            return ['status'=>0,'info'=>Yii::t('app','Ошибка при подключении Uzcard!!!') . ' ' . json_encode($result) ];

        }

        /*switch ($user->auto_discard_type){
            case 1: // Uzcard
                $token = Yii::$app->session->get('token');
                
                $result = Uzcard::cardVerify($token,$code);
                if( isset($result['status']) && $result['status']==1 ) { //} || isset($result['result']) ) {
                    UtilsHelper::debug('Uzcard Autodiscard OK!');

                    return ['status'=>1,'info'=>'Привязка Uzcard прошла успешно!'];
                }else{
                    UtilsHelper::debug($result);

                    return ['status'=>0,'info'=>Yii::t('app','Ошибка при подключении Uzcard!!!') . ' ' . json_encode($result) ];

                }
                break;
            case 2: // Paymo нет смс

                return ['status'=>1,'info'=>'Автосписание Paymo успешно подтверждено!'];

                break;
        }*/

        return ['status'=>0,'info'=>'Платежная система не указана']; // ??

    }



}
