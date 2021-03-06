<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "scoring_history".
 *
 * @property integer $id
    
 * @property integer $scoring_id
    
 * @property integer $user_id
    
 * @property integer $created_at
    
 * @property integer $date
    
 * @property integer $status
    
 * @property string $info
    
 */
class ScoringHistory extends \yii\db\ActiveRecord
{

    const ITEMS_COUNT = 7;
    public $count;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scoring_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scoring_id', 'user_id', 'created_at', 'date','status'], 'integer'],
            [['info'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scoring_id' => 'Scoring ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'date' => 'Date',
            'status' => 'Status',
            'info' => 'Info',
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
