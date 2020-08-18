<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "uzcard_transpay".
 *
 * @property integer $id
    
 * @property integer $user_id
    
 * @property integer $payment_id
    
 * @property integer $created_at
    
 * @property string $trans_id
    
 * @property string $refNum
    
 * @property string $ext
    
 * @property string $pan
    
 * @property string $exp
    
 * @property string $tranType
    
 * @property string $date12
    
 * @property string $amount
    
 * @property string $field38
    
 * @property string $respSV
    
 * @property string $respText
    
 * @property string $status
    
 */
class UzcardTranspay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uzcard_transpay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'payment_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['trans_id', 'refNum', 'ext', 'tranType', 'status'], 'string', 'max' => 32],
            [['pan', 'field38', 'respSV'], 'string', 'max' => 20],
            [['exp'], 'string', 'max' => 4],
            [['date12'], 'string', 'max' => 12],
            [['respText'], 'string', 'max' => 255]
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
            'payment_id' => 'Payment ID',
            'created_at' => 'Created At',
            'trans_id' => 'Trans ID',
            'refNum' => 'Ref Num',
            'ext' => 'Ext',
            'pan' => 'Pan',
            'exp' => 'Exp',
            'tranType' => 'Tran Type',
            'date12' => 'Date12',
            'amount' => 'Amount',
            'field38' => 'Field38',
            'respSV' => 'Resp Sv',
            'respText' => 'Resp Text',
            'status' => 'Status',
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

}
