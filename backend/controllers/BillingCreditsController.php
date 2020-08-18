<?php

namespace backend\controllers;

use common\helpers\PolisHelper;
use common\models\CreditHistory;
use common\models\Payment;
use common\models\Polises;
use common\models\User;
use Yii;
use common\models\Credits;
use common\models\Uzcard;
use common\models\CreditsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillingHistoryController implements the CRUD actions for BillingHistory model.
 */
class BillingCreditsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action){

        if(Yii::$app->user->isGuest){

            header('location:/login');
            exit;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all BillingHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CreditsSearch();
        $dataProvider = $searchModel->searchDelay(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetails($id) {

        if(!$contract = Contracts::find()->where(['id'=>$id])->one()){
            Yii::$app->session->setFlash('info','Договор не найден!');
            return $this->redirect('/billing-contracts/');
        }

        $searchModel = new ContractsSearch();
        $dataProvider = $searchModel->searchDetails(Yii::$app->request->queryParams,$contract);

        return $this->render('details', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetBalance(){

        $user_id = Yii::$app->request->post('user_id');

        if( !$user = User::find()->where(['id'=>$user_id])->one() ){
            exit;
        }

        if($user)
            $balance = new Uzcard;
            $balance = $balance->cardsGet($user);
            $balance = $balance['result'][0]['balance'];


        return json_encode(['balance' => $balance]);
    }

    public function actionDiscard(){
        $user_id = Yii::$app->request->post('user_id');
        $sum = (int)Yii::$app->request->post('sum') * 100;

        if( !$user = User::find()->where(['id'=>$user_id])->one() ){
            exit;
        }

        if($user)
            $discard = new Uzcard;
            $result = $discard->discard($user, $sum);
            $result = json_decode($result, true);

        return json_encode(['result' => $result]);
    }


    public function actionPay(){

        if( $post = Yii::$app->request->post()){

            $credit_id = $post['credit_id'];
            $user_id = $post['user_id'];
            $price = $post['credit_history_price']*100; // сумма в тиинах
            $summ = $post['summ'];

            $payment_status = $price > 0 ? 0 : 1;

            //return json_encode(['credit_id' => $credit_id, 'price' => $price, 'summ' => $summ]);

            if( $credit_history = CreditHistory::find()->with(['credit','client','contract'])->where(['credit_id' => $credit_id])->andWhere(['payment_status'=>'0'])->one() ) {

                //return json_encode(['credit_history' => $credit_history->price]);
                $credit_history->client->summ =- $summ;

                // списание с лицевого счета
                $credit_history->payment_status = $payment_status;  // списалась вся сумма или частично(1,0)
                $credit_history->payment_date = time();
                $credit_history->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                $credit_history->price = 0; // остаток задолженности
                $credit_history->client->save();

                if($price > 0){
                    $hprice = $credit_history->price - $price;
                    // списать с uzcard
                    if( !$user = User::find()->where(['id'=>$user_id])->one() ){
                        exit;
                    }

                    if($user)
                        $discard = new Uzcard;
                    $result = $discard->discard($user, $price);
                    $result = json_decode($result, true);
                    // если списались деньги
                    if ($result['result']['status'] == 'OK') {
                         // записать по таблицам
                        $credit_history->payment_status = $payment_status;  // списалась вся сумма или частично(1,0)
                        $credit_history->payment_date = time();
                        $credit_history->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                        $credit_history->price = $hprice; // остаток задолженности

                     }

                }


                }
            }




        $get = Yii::$app->request->get();
        $credit_id = isset($get['credit_id']) ? (int)$get['credit_id'] : 0;

        // поиск только своих кредитов
        if( !$credit = Credits::find()->with(['client','supplier','creditItems','paymentsAsc', 'history'])->where(['id'=>$credit_id])->one() ){
            exit;
        }

        return $this->render('pay',[
            'credit' => $credit
        ]);

    }

    public function actionSendCustomerList(){
        $credit_id =(int)Yii::$app->request->get('credit_id');

        if($polis = Polises::find()->where(['credit_id' => $credit_id ])->one()){

            if($credit = Credits::find()->where(['id' => $credit_id ])->one())
                $sum = $credit->price;

            $clients = json_encode([
                'clientId' => $polis->client_id,
                'polisSeries' => $polis->polisSeries,
                'polisNumber' =>(int)$polis->polisNumber,
                'sum' => $sum
            ],JSON_UNESCAPED_UNICODE);
        }

        $request_id = 'zMarket_' . $credit->contract_id;
        $result = PolisHelper::sendCustomerList($request_id,$clients);


       /* if($result){

            $polis_cust_list = new PolisCustomerList();
            $polis_cust_list->created_at = time();
            $polis_cust_list->credit_id = $credit->id;
            $polis_cust_list->client_id = $polis->client_id;
            $polis_cust_list->responseId = $responseId;
            $polis_cust_list->polisSeries = $polis->polisSeries;
            $polis_cust_list->polisNumber = $polis->polisNumber;
            $polis_cust_list->status = $status;
            if(!$polis_cust_list->save()){
                Yii::$app->session->setFlash('info','Ошибка при сохранении данных от страховой компании ' );

                //return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]);
            }else{
                Yii::$app->session->setFlash('info','сохранение данных от страховой компании прошло успешно! ' );

            }

        }*/

        return $this->render('pay',[
            'credit' => $credit
        ]);

    }

    protected function findModel($id)
    {
        if (($model = BillingHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
