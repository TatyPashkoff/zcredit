<?php


namespace common\models;

use Yii;
use common\helpers\TextHelper;



/**
 * This is the model class for table "polise_customer_list".
 *
 * @property integer $id

 * @property integer $uniqueBorderoID

 * @property string $responseId

 * @property integer $created_at

 * @property integer $status

 */
class PolisCustomerList extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'polis_customer_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bordero_id', 'created_at', 'status'], 'integer'],
            [[ 'responseId'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'bordero_id' => 'Bordero ID',
            'responseId' => 'response Id',
            'status' => 'Status',
        ];
    }

    // получаем пакет
    public function getPackage()
    {
        return $this->hasMany(Package::className(), ['bordero_id' => 'bordero_id']);
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

