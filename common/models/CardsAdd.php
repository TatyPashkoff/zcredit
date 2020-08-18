<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * CardsAdd model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property string $card
 * @property string $exp
 * @property string $status
 */
class CardsAdd extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cards_add}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','created_at','type','status'], 'integer'],
            [['card', 'exp'], 'string'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app','id Клиента'),
            'created_at' => Yii::t('app','создан'),
            'card' => Yii::t('app','номер карты'),
            'exp' => Yii::t('app','срок карты'),
            'type' => Yii::t('app','тип карты'),
            'status' => Yii::t('app','Статус'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    // получаем scoring
    public function getScoring()
    {
        return $this->hasOne(ScoringAdd::className(), ['cards_add' => 'id']);
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
