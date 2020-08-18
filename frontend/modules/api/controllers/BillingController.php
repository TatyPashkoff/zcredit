<?php

namespace app\modules\api\controllers;

use common\helpers\UtilsHelper;
use common\models\BillingHistory;
use common\models\CreditHistory;
use common\models\Credits;
use common\models\Payment;
use common\models\User;
use Yii;

/*

При подключении автосписания вы нам отправляете основные поля:
- ID клиента (ваш системный ID. Нам неважно что это будет за идентификатор,
главное, чтоб когда мы его отправляли вам, вы нам выдавали текущую сумму задолженности, которую мы должны списать)
- токен карты (мы его вам дадим при регистрации клиента)
- дата списания
- время списания
- сроки действия автосписания

В момент автосписания мы обращаемся к вам по ID клиента.
Вы нам возвращаете текущий долг, если долг имеется, мы списываем
и отправляем результат операции с паралельным оповещением клиента
(если списано, то смс что деньги списаны, если денег нет,
то оповещение, что нужно пополнить карту для оплаты)
 */

class BillingController extends BaseApiController
{

    const ERR_USER_NOT_FOUND = 200;
    const ERR_CREDIT_NOT_FOUND = 201;
    const ERR_AMOUNT_NOT_FILL = 202;
    const ERR_USER_ID_NOT_FILL = 203;
    const ERR_NO_DEBT = 204;
    const ERR_PAYMENTS_GRAPH = 205;
    const ERR_SAVE_PAYMENT = 206;
    const ERR_SAVE_BILLING = 207;


    /**
     * получение запроса от сервиса paymo для автоснятии средств с договора contract_id
     * подготовка к оплате
     */
    public function actionPaymentPrepare()
    {
       // return $this->response(['status'=>0,'error'=> ['code'=>10,'info'=>'API. ' . Yii::t('app','В процессе разработки!')]],404);

        // поиск всех кредитов user_id
        // поиск всех просроченных credit_item_id
        // суммирование и отправка общей суммы задолженности

        UtilsHelper::debug('paymo.payment-prepare');

        $post = json_decode(file_get_contents('php://input'),true);
        UtilsHelper::debug($post);

        if( !isset($post['user_id']) ){
            return $this->response(['status'=>0,'error'=> ['code'=>self::ERR_USER_ID_NOT_FILL,'info'=>'API. ' . Yii::t('app','Параметр user_id не задан!')]],404);
        }

        $user_id = $post['user_id'];
        if(!$user = User::find()->where(['id'=>$user_id,'role'=>User::ROLE_CLIENT])->one()){
            return $this->response(['status'=>0,'error'=> ['code'=>self::ERR_USER_NOT_FOUND,'info'=>'API. ' . Yii::t('app','Клиент не найден!')]],404);
        }

        if(!$credits = Credits::find()->select('id')->where(['user_id'=>$user_id])->all()){
            return $this->response(['status'=>0,'error'=> ['code'=>self::ERR_CREDIT_NOT_FOUND,'info'=>'API. ' . Yii::t('app','Кредит для клиента не найден!')]],404);
        }
        $ids = [];
        foreach ($credits as $credit){
            $ids[] = $credit->id;
        }
        // поиск всех просроченных кредитов
        // summ сумма общей задолженности
        if( !$credit_item = CreditHistory::find()->select('SUM(price) as summ')->where(['credit_id'=>$ids,'payment_status'=>0])->andWhere(['<=','credit_date',time()])->orderBy('credit_date')->one() ) { // } sum('price') ){
            return $this->response(['status'=>0,'error'=>['code'=>self::ERR_NO_DEBT,'info'=>'API. ' . Yii::t('app','У клиента нет задолженности, оплата не требуется!')]],404);
        }

        if($credit_item->summ==0){
            return $this->response(['status'=>0,'error'=>['code'=>self::ERR_NO_DEBT,'info'=>'API. ' . Yii::t('app','У клиента нет задолженности, оплата не требуется!')]],404);

        }

        return $this->response(['status'=>1,'user_id'=>$user_id,'amount'=>$credit_item->summ,'error'=>['code'=>0,'info'=>'']], 200);

    }


    /**
     * получение запроса подтверждения от сервиса paymo для автоснятии средств с договора contract_id
     * credit_id - для оплаты за конкретный месяц кредита
     */
    public function actionPaymentComplete()
    {

        // return $this->response(['status'=>0,'error'=> ['code'=>200,'info'=>'API. ' . Yii::t('app','В процессе разработки!')]],404);

        UtilsHelper::debug('paymo.payment-complete');

        $post = json_decode(file_get_contents('php://input'),true);
        UtilsHelper::debug($post);

        if(!isset($post['user_id']) ){
            return $this->response(['status'=>0,'error'=> ['code'=>self::ERR_USER_ID_NOT_FILL,'info'=>'API. ' . Yii::t('app','user_id не задан!')]],404);
        }

        if(!isset($post['amount']) ){
            return $this->response(['status'=>0,'error'=> ['code'=>self::ERR_AMOUNT_NOT_FILL,'info'=>'API. ' . Yii::t('app','Сумма amount не задана!')]],404);
        }

        $amount = $post['amount'];
        $user_id = $post['user_id'];
        if(!$user = User::find()->where(['id'=>$user_id,'role'=>User::ROLE_CLIENT])->one()) {
            return $this->response(['status'=>0,'error'=>['code'=>self::ERR_USER_NOT_FOUND,'info'=>'API. ' . Yii::t('app','Клиент не найден!')]],404);
        }

        $user->summ += $amount; // увеличение баланса клиента

        $user->save(false);

        // история пополнений баланса
        $billing_history = new BillingHistory();
        $billing_history->created_at = time();
        $billing_history->user_id = $user_id;
        $billing_history->summ = $amount;
        $billing_history->payment_type = Payment::PAYMENT_TYPE_PAYMO;
        $billing_history->status = Payment::PAYMENT_STATE_SUCCESS;
        $billing_history->state = 2;
        $billing_history->save(false);

        // автосписание с баланса задолженностей
        Payment::payment($user_id);

        return $this->response(['status'=>1,'user_id'=>$user_id,'amount'=>$amount,'payment_id'=>$billing_history->id,'error'=>['code'=>0,'info'=>'']], 200);

    }
	
	
	public function actionSendOrdersFor1c () 
	{
		return json_encode(['info' => 'Success']);
	}





}
