<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "contracts".
 *
 * @property integer $id
    
 * @property string $credit_id
 * @property string $user_id

 * @property string $created_at
    
 * @property string $date_start
    
 * @property string $date_end
    
 * @property string $status
    
 */
class Contracts extends \yii\db\ActiveRecord
{
    const ITEMS_COUNT = 20;

    //public $act;
    //public $invoice;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id','user_id','supplier_id', 'created_at', 'confirm_date','date_start', 'date_end', 'status_polis','status','status_invoice','send_insurance','send_jud','status_jud'], 'integer'],
            [['act','invoice','comments'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Договора',
            'credit_id' => 'ID Кредита',
            'created_at' => 'Дата создания',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'status' => 'Status',
            'price' => 'Ежемесячный взнос',
            'phone' => 'Телефон',
            'user_id' => "ID Клиента",
            'details' => "Детализация",
        ];
    }

    public function getCredit(){
        return $this->hasOne(Credits::className(),['id'=>'credit_id']);
    }
    // состав кредита
    public function getCreditItems(){

        return $this->hasMany(Credits::className(),['id'=>'credit_id'])->with('creditItems');
    }

    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'user_id']);//->viaTable('credits',['id'=>'credit_id'])->where(['role'=>User::ROLE_CLIENT]);
    }
    public function getPolis(){
        return $this->hasOne(Polises::className(),['contract_id'=>'id']);
    }
    public function getClientName(){
        return $this->hasOne(User::className(),['id'=>'user_id'])
            ->viaTable('credits',['id'=>'credit_id'])
            ->select('id,username,lastname')
            ->where(['role'=>User::ROLE_CLIENT]);
    }

    // список по месяцам план графика оплат
    public function getCreditHistory(){
        return $this->hasMany(CreditHistory::className(),['credit_id'=>'credit_id']);

    }

    public function getPrice(){
        return $this->hasone(CreditHistory::className(),['credit_id'=>'credit_id']);

    }

    // список оплат данного договора по месяцам
    public function getPayments(){
        return $this->hasMany(Payment::className(),['credit_id'=>'credit_id']);
    }

    public function getSupplier(){
        return $this->hasOne(User::className(),['id'=>'supplier_id'])->viaTable('credits',['id'=>'credit_id'])->where(['role'=>User::ROLE_SUPPLIER]);
    }
    public function getKyc(){
        return $this->hasOne(Kyc::className(),['client_id'=>'user_id'])->viaTable('credits',['id'=>'credit_id']);
    }

    // статусы при отправке договора в Суд
    public function getStatusJud($status){

        switch ($status){
            case 0: //
                $res = Yii::t('app','Нет дела');
                break;
            case 1: //
                $res = Yii::t('app','В процессе');
                break;
            case 2: //
                $res = Yii::t('app','Ожидание ответа от страховой');
                break;
            case 3: //
                $res = Yii::t('app','Возмещено страховой компанией');
                break;
            case 4: //
                $res = Yii::t('app','Дело проиграно');
                break;
        }

        return $res;
    }

    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается
            }

            $attr = $this->isAttributeChanged('status');

            if(  $attr==1 && $this->confirm_date == 0){
                $this->confirm_date = time();
            }

            $this->save();

            try {

                $path = Yii::getAlias("@frontend/web/uploads/contracts/");
                if (!is_dir($path)) @mkdir($path);

                $path = Yii::getAlias("@frontend/web/uploads/contracts/" .$this->id . '/');
                if (!is_dir($path)) @mkdir($path);


                if ($file = UploadedFile::getInstance($this, 'act')) {

                    if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                    $fname =time() . '.'. $file->extension;

                    @unlink($path . $this->act);

                    $file->saveAs($path . $fname);

                    $this->act = $fname;

                }

                if ($file = UploadedFile::getInstance($this, 'invoice')) {

                    if (!preg_match('/image\//', $file->type)) return false; // загружена не картинка!

                    $fname =time()+1 . '.'. $file->extension;

                    @unlink($path . $this->invoice);

                    $file->saveAs($path . $fname);

                    $this->invoice = $fname;

                }


            }catch (Exception $e) {
                return false;
            }

            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');
                print_r($this->getErrors());
                exit;

                return false;
            }


            
            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }

}
