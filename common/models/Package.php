<?php


namespace common\models;

use Yii;
use common\helpers\TextHelper;



/**
 * This is the model class for table "package".
 *
 * @property integer $id

 * @property integer $bordero_id

 * @property integer $client_id

 * @property integer $created_at

 * @property integer $data

 * @property integer $credit_id

 * @property string $polisNumber

 * @property integer $sum

 */
class Package extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'client_id', 'created_at', 'date', 'bordero_id', 'sum'], 'integer'],
            [['polisNumber'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'client_id' => 'Client ID',
            'created_at' => 'Created At',
            'date' => 'date',
            'bordero_id' => 'bordero Id',
            'polisNumber' => 'Polis Number',
            'sum' => 'Sum',
        ];
    }

    // получаем клиента
    public function getPolisList()
    {
        return $this->hasOne(PolisCustomerList::className(), ['bordero_id' => 'bordero_id']);
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

