<?php


namespace common\models;
use Yii;


class Asko extends \yii\db\ActiveRecord
{

    const APIURL = 'http://apiv1.asko-vostok.info/';
    const APIURL_TEST = 'http://apiv1.asko-vostok.info/';
    const TEST_MODE = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at','credit_id','client_id','supplier_id'], 'integer'],
            [['Contract_number', 'Term', 'Insurance_premium','Status'], 'string'],
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
            'credit_id' => 'credit_id',
            'client_id' => 'client_id',
            'supplier_id' => 'supplier_id',
            'contract_number' => 'contract_number',
            'term' => 'term',
            'insurance_premium' => 'insurance_premium',
            'status' => 'Status',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asko';
    }

    /**
     * @inheritdoc
     */

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


    public static function askoInfo(&$user,$amount,$term,$credit){

        if(!isset($user)) {
            return json_encode(['status'=>1,'info'=>Yii::t('app','Клиент не задан!')],JSON_UNESCAPED_UNICODE);
        }

        $client_name = $user->username . ' ' . $user->lastname . ' ' . $user->patronymic;
        $input = json_encode([
            'number_credit' => $credit,
            'client_name' => $client_name,
            'credit_amount' => $amount,
            'pass_serial' => $user->passport_serial,
            'pass_number' => $user->passport_id,
            'term' => $term,

        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, ['x-api-key: aa11aa11aa111']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['x-api-key: a7b98b92612a4915dd8416c961c2f5cec3f1dca4']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        self::debug('asko.info');
        self::debug($input);
        self::debug('result: ' .$result);

        return $result;

    }




    public static function getAuth(){

        if(self::TEST_MODE){ // если тестовый режим
            return ['url'=>self::APIURL_TEST];
        }else{
            return ['url'=>self::APIURL];
        }


    }

    public static function debug( $data,$clear=false){
        //if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_asko.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_asko.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }


}