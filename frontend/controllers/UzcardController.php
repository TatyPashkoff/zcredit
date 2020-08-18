<?php

namespace frontend\controllers;

use click\models\Payments;
use common\models\BillingHistory;
use common\models\Payment;
use common\models\PaymentsClick;
use common\models\User;
use common\models\UzcardLog;
use Yii;
use yii\web\Controller;


/* обработка платежной системы click */

Class UzcardController extends Controller{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    // пополнение баланса с внешних сервисов
    public function actionPaymentPrepare(){

        //$request_body = file_get_contents('php://input');
        // parsing the request body
        //$request = json_decode($request_body, true);
        $request = Yii::$app->request->post();
        
		
        // Проверка отправлено-ли все параметры
        if(!(
            isset($request['click_trans_id']) &&
            isset($request['merchant_trans_id']) &&
            isset($request['amount']) &&
            isset($request['action']) &&
            isset($request['error']) &&
            isset($request['error_note']) &&
            isset($request['sign_time']) &&
            isset($request['sign_string']) &&
            isset($request['click_paydoc_id'])
        )){
        	$this->debug('Error in request from click');
            return json_encode( array(
                'error' => -8,
                'error_note' => 'Error in request from click' . json_encode($request)
            ));

        }

		
		
        // Проверка хеша
        $sign = $request['click_trans_id'] .
            $request['service_id'] .
            Payment::CLICK_SECRET .
            $request['merchant_trans_id'] .
            $request['amount'] .
            $request['action'] .
            $request['sign_time'];


        $sign_string = md5($sign);
        // check sign string to possible
        if($sign_string != $request['sign_string']){
        	$this->debug('SIGN CHECK FAILED');
            return json_encode( array(
                'error' => -1,
                'error_note' => 'SIGN CHECK FAILED!'
            ));

        }
		
		
		
        if ((int)$request['action'] != 0 ) {
        	$this->debug('Action not found');
            return json_encode( array(
                'error' => -3,
                'error_note' => 'Action not found'
            ));

        }
        

        // merchant_trans_id - это ID пользователья который он ввел в приложении
        // Здесь нужно проверить если у нас в базе пользователь с таким ID

        if( ! $user = User::find()->where(['id'=> $request['merchant_trans_id'],'role'=>User::ROLE_CLIENT] )->one() ){
        	$this->debug('User does not exist');
            return json_encode( array(
                'error' => -5,
                'error_note' => 'User does not exist'
            ));

        }

        // Если это заказ тогда нужно проверить еще статус заказа, все еще заказ актуален или нет
        // если проверка не проходит то нужно возвращать ошибку -4
        /*if(!$billing_history = BillingHistory::find()->where(['id'=>$request['merchant_trans_id']])->one()){

        } */


        // и еще нужно проверить сумму заказа
        // если не проходит тогда нужно возвращать ошибку -2
        if($request['amount'] < 100){ // если меньше 100 сум
        	$this->debug('Amount must be at least 100 sum: ' . $request['amount']);
            return json_encode( array(
                'error' => -2,
                'error_note' => 'Amount must be at least 100 sum: ' . $request['amount']
            ));
        }

        // Еще одна проверка статуса заказа, не закрыть или нет
        // если заказ отменен тогда нужно возвращать ошибку -9

        // Все проверки прошли успешно, тог здесь будем сохранять в базу что подготовка к оплате успешно прошла
        // можно сделать отдельную таблицу чтобы сохранить входящих данных как лог
        // и присвоит на параметр merchant_prepare_id номер лога

        if( !$billing_history = BillingHistory::find()->where(['user_id'=> $user->id,'summ'=>$request['amount'],'status'=>0])->one() ) {

            $billing_history = new BillingHistory();
            $billing_history->user_id = $user->id;
            $billing_history->created_at = time();
            $billing_history->summ = $request['amount']; // ПОПОЛНЕНИЕ баланса в суммах, не тийинах
            $billing_history->payment_type = Payment::PAYMENT_TYPE_MYUZCARD;
            $billing_history->status = 0;
            $billing_history->state = 0;

            if(!$billing_history->save()){
            	$this->debug('The order was cancelled. ' . json_encode($billing_history->getErrors(),JSON_UNESCAPED_UNICODE));
                return json_encode( array(
                    'error' => -9,
                    'error_note' => 'The order was cancelled. ' . json_encode($billing_history->getErrors(),JSON_UNESCAPED_UNICODE)
                ));

            }
        }

        if($request['error']==-1) {
            $billing_history->state = 3;
            $billing_history->save();
            $this->debug('The order was cancelled. ' . json_encode($billing_history->getErrors(), JSON_UNESCAPED_UNICODE));
            return json_encode(array(
                'error' => -4,
                'error_note' => 'The order was cancelled. ' . json_encode($billing_history->getErrors(), JSON_UNESCAPED_UNICODE)
            ));
        }


        return json_encode( array(
            'error' => 0,
            'error_note' => 'Success',
            'click_trans_id' => $request['click_trans_id'],
            'merchant_trans_id' => $request['merchant_trans_id'],
            'merchant_prepare_id' => $billing_history->id,
            //'test' => 'mid: ' . $billing_history->id
        ));

    }

    public function actionPaymentComplete(){

        $request = Yii::$app->request->post();
        //$request_body = file_get_contents('php://input');
        // parsing the request body
        //$request = json_decode($request_body, true);

        // Проверка отправлено-ли все параметры
        if(!(
            isset($request['click_trans_id']) &&
            isset($request['service_id']) &&
            isset($request['merchant_trans_id']) &&
            isset($request['amount']) &&
            isset($request['action']) &&
            isset($request['error']) &&
            isset($request['error_note']) &&
            isset($request['sign_time']) &&
            isset($request['sign_string']) &&
            isset($request['click_paydoc_id'])
        )){
        	$this->debug('Error in request from click Status-complete');
            return json_encode( array(
                'error' => -8,
                'error_note' => 'Error in request from click'
            ));

        }

        // Проверка хеша
        $sign_string = md5(
            $request['click_trans_id'] .
            $request['service_id'] .
            Payment::CLICK_SECRET .
            $request['merchant_trans_id'] .
            $request['merchant_prepare_id'] .
            $request['amount'] .
            $request['action'] .
            $request['sign_time']
        );
        // check sign string to possible
        if($sign_string != $request['sign_string']){
        	$this->debug('SIGN CHECK FAILED!');
            return json_encode( array(
                'error' => -1,
                'error_note' => 'SIGN CHECK FAILED!'
            ));

        }

        if ((int)$request['action'] != 1 ) {
        	$this->debug('Action not found - Status-complete');
            return json_encode( array(
                'error' => -3,
                'error_note' => 'Action not found'
            ));

        }
		
		
		//СОХРАНЕНИЕ ТРАНЗАКЦИЙ
		$this->debug_transactions($request);

        // merchant_trans_id - это ID пользователья который он ввел в приложении
        // Здесь нужно проверить если у нас в базе пользователь с таким ID

        if( ! $user = User::find()->where(['id'=> $request['merchant_trans_id'],'role'=>User::ROLE_CLIENT] )->one() ){
        	$this->debug('User does not exist - Status-complete');
            return json_encode( array(
                'error' => -5,
                'error_note' => 'User does not exist'
            ));

        }

        if( !$billing_history = BillingHistory::find()->where(['id'=>$request['merchant_prepare_id']])->one() ){
        	$this->debug('Transaction does not exist - Status-complete');
            return json_encode( array(
                'error' => -6,
                'error_note' => 'Transaction does not exist'
            ));

        }

        if ((int)$request['action'] == -5017 && $billing_history->state == 3 ) {
        	$this->debug('The order was cancelled - Status-complete');
            return json_encode( array(
                'error' => -9,
                'error_note' => 'The order was cancelled.'
            ));

        }
        if ((int)$request['action'] == -5017 ) {
        	$this->debug('The order was cancelled - Status-complete');
            return json_encode( array(
                'error' => -9,
                'error_note' => 'The order was cancelled.'
            ));

        }

        // check to confirm order paid
        if( (int)$request['error'] == -5017 ){
            // return response array-like
            $this->debug('You not enough money- Status-complete');
            return json_encode([
                'error' => -9,
                'error_note' => 'You not enough money'
            ]);
        }

        // check to confirm order paid
        if( (int)$request['error'] == -5017 && $billing_history->state==3 ){
            // return response array-like
            $this->debug('You not enough money- Status-complete');
            return json_encode([
                'error' => -9,
                'error_note' => 'You not enough money'
            ]);
        }


        // check to cancel order paid
        if($billing_history->state == 3){
            // return response array-like
            $this->debug('Already paid- Status-complete');
            return json_encode([
                'error' => -9,
                'error_note' => 'Already paid'
            ]);
        }

        // check to confirm order paid
        if($billing_history->state == 2 && $billing_history->status == 1){
            // return response array-like
            $this->debug('Already paid- Status-complete');
            return json_encode([
                'error' => -4,
                'error_note' => 'Already paid'
            ]);
        }

        // Если это заказ тогда нужно проверить еще статус заказа, все еще заказ актуален или нет
        // если проверка не проходит то нужно возвращать ошибку -4



        // и еще нужно проверить сумму заказа
        // если не проходит тогда нужно возвращать ошибку -2
        if($request['amount'] != $billing_history->summ ){ // если сумма не соответствует
        	$this->debug('Amount is not correct. Status complete ' . $request['amount'] . ' '. $billing_history->summ);
            return json_encode( array(
                'error' => -2,
                'error_note' => 'Amount is not correct. ' . $request['amount'] . ' '. $billing_history->summ
            ));
        }

        // Еще одна проверка статуса заказа, не закрыть или нет
        // если заказ отменен тогда нужно возвращать ошибку - 9

        // Все проверки прошли успешно, тог здесь будем сохранять в базу что подготовка к оплате успешно прошла
        // можно сделать отдельную таблицу чтобы сохранить входящих данных как лог
        // и присвоит на параметр merchant_confirm_id номер лога


        // Хотя все проверки выше были в prepare тоже, нужно убедится что еще раз проверить в complete

        // Ошибка деньги с карты пользователя не списались
        if( $request['error'] < 0 ) {

            $billing_history->state = 3;
            $billing_history->save();
            $this->debug('The order was cancelled - Status-complete');
            // делаем что нибудь (если заказ отменим заказ, если пополнение тогда добавим запись что пополненние не успешно)
            return json_encode( array(
                'error' => -9,
                'error_note' => 'The order was cancelled.'
            ));

        } else {
            // Все успешно прошел деньги списаны с карты пользователя тогда записываем в базу (сумма приходит в запросе)
            $billing_history->state = 1;
            $billing_history->status = 1; // завершено
            $billing_history->save();

            $user->summ += $billing_history->summ; // увеличение баланса клиента
            $user->save(false);

            Payment::payment($user->id); // погашение просроченных кредитов
			
            //сохранение транзакции
            $uzcardlog = new UzcardLog();
            $uzcardlog->click_trans_id = $request['click_trans_id'];
            $uzcardlog->service_id = $request['service_id'];
            $uzcardlog->merchant_trans_id = $request['merchant_trans_id'];
            $uzcardlog->amount = $request['amount'];
            $uzcardlog->action = $request['action'];
            $uzcardlog->error = $request['error'];
            $uzcardlog->error_note = $request['error_note'];
            $uzcardlog->sign_time = $request['sign_time'];
            $uzcardlog->sign_string = $request['sign_string'];
            $uzcardlog->click_paydoc_id = $request['click_paydoc_id'];
            $uzcardlog->merchant_prepare_id = $request['merchant_prepare_id'];
            $uzcardlog->save();
			
            return json_encode( array(
                'error' => 0,
                'error_note' => 'Success',
                'click_trans_id' => $request['click_trans_id'],
                'merchant_trans_id' => $request['merchant_trans_id'],
                'merchant_confirm_id' => $billing_history->id,
               // 'test' => 'mid: ' . $billing_history->id . ' ' .$b

            ));

        }
    }








// - ------------------------------------------------------------------------------------------------------------------

    // оплата с сайта НЕ РАБОТАЕТ!!!!
    public function actionPrepareOld(){

        $request = Yii::$app->request->post(); // $this->request->post();

        // 20.11.2019 - для биллинга нужно в сервисе указать id договора
        // 06.12.2019 - для оплаты в merchant_trans_id будет префикс cid
        if( isset($request['contract_id']) ) { // isset($request['merchant_trans_id']) ){

            // $request['merchant_trans_id'] здесь хранится номер заказа из таблицы payment для оплаты
            // если
            // нужно передать номер договора для создания заказа для погашения в бд а также для пополнения внутреннего баланса
            // или просто пополнить баланс билинга???
            // нужно узнать как это будет реализоано на стороне click и payme

            //$pc = new PaymentsClick();
            //$pc->

        }


         // check the request data to errors
        $result = $this->request_check($request);
        $merchant_confirm_id = 0;
        $merchant_prepare_id = 0;
        if($result['error']==0) {
            // getting payment data from model
            if ($payment = PaymentsClick::find()->where(['merchant_trans_id' => $request['merchant_trans_id']])->one()) {
                $merchant_confirm_id = $payment->id;
                $merchant_prepare_id = $payment->id;
            } else {
                $result = [
                    'error' => -1002,
                    'error_note' => 'Payment not found step 1'
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }

        // complete the result to response
        $result += [
            'click_trans_id' => $request['click_trans_id'],
            'merchant_trans_id' => $request['merchant_trans_id'],
            'merchant_confirm_id' => $merchant_confirm_id,
            'merchant_prepare_id' => $merchant_prepare_id
        ];

        // change the payment status to waiting if request data will be possible
        if($result['error'] == 0){
            $payment->state = PaymentsClick::WAITING;
            if( !$payment->save() ){
                $result = [
                    'error' => -1003,
                    'error_note' => 'Cannot save payment ' . json_encode($payment->getErrors(),JSON_UNESCAPED_UNICODE)
                ];
            }
        }

        // return response array
        return json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @name complete method, the complete request method
     * @param request array-like, the request data to perform the complete method
     * @return array-like
     *
     * @example:
     *      $model = new Payments();
     *      $model->complete([
     *          ...
     *      ]);
     */
    public function actionCompleteOld(){
         // getting POST data
        $request = Yii::$app->request->post(); // $this->request->post();

        // check the request data to errors
        $result = $this->request_check($request);

        //$this->debug($result);

        //print_r($result); exit;
        $merchant_confirm_id = 0;
        $merchant_prepare_id = 0;
        if($result['error']==0) {
            // getting payment data from model
            if ($payment = PaymentsClick::find()->where(['merchant_trans_id' => $request['merchant_trans_id']])->one()) {
                $merchant_confirm_id = $payment->id;
                $merchant_prepare_id = $payment->id;
            } else {
                $result = [
                    'error' => -1002,
                    'error_note' => 'Payment not found step 2'
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }


        //$this->debug($result);


        // complete the result to response
        $result += [
            'click_trans_id' => $request['click_trans_id'],
            'merchant_trans_id' => $request['merchant_trans_id'],
            'merchant_confirm_id' => $merchant_confirm_id,
            'merchant_prepare_id' => $merchant_prepare_id
        ];

        // return json_encode($result,JSON_UNESCAPED_UNICODE);
        //$this->debug($result);


        if($request['error'] < 0 && ! in_array($result['error'], [-4, -9]) ){
            // update payment status to error if request data will be error
            $payment->state = PaymentsClick::REJECTED;
            if( !$payment->save() ){
                $result = [
                    'error' => -1003,
                    'error_note' => 'Cannot save payment'
                ];
            }else {

                $result = [
                    'error' => -9,
                    'error_note' => 'Transaction cancelled'
                ];
            }

        } elseif( $result['error'] == 0 ) {
            // update payment status to confirmed if request data will be success
            $payment->state = PaymentsClick::CONFIRMED;
            if( !$payment->save() ){
                $result = [
                    'error' => -1003,
                    'error_note' => 'Cannot save payment'
                ];
            }
            Payment::payment($request['merchant_trans_id'],Payment::PAYMENT_TYPE_CLICK); // завершение оплаты

        }
       // $this->debug($result);

        // return response array
        return json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    private function request_check($request){
        // check to error in request from click
        if($this->is_not_possible_data($request)){
            //$this->debug('Error in request from click');
            // return response array-like
            return [
                'error' => -8,
                'error_note' => 'Error in request from click'
            ];
        }


        // prepare sign string as md5 digest
        $sign_string = md5(
            $request['click_trans_id'] .
            $request['service_id'] .
            Payment::CLICK_SECRET .
            $request['merchant_trans_id'] .
            ($request['action'] == 1 ? $request['merchant_prepare_id'] : '') .
            $request['amount'] .
            $request['action'] .
            $request['sign_time']
        );

        // check sign string to possible
        if($sign_string != $request['sign_string']){

           //$this->debug('SIGN CHECK FAILED! ' . $sign_string);

            // return response array-like
            return [
                'error' => -1,
                'error_note' => 'SIGN CHECK FAILED! ' . $sign_string
            ];
        }

        // check to action not found error
        if (!((int)$request['action'] == 0 || (int)$request['action'] == 1)) {
            //$this->debug('Action not found');
            // return response array-like
            return [
                'error' => -3,
                'error_note' => 'Action not found'
            ];
        }


        // get payment data by merchant_trans_id
        //$payment = $this->model->find_by_merchant_trans_id($request['merchant_trans_id']);

        if(!$payment = PaymentsClick::find()->where(['merchant_trans_id'=>$request['merchant_trans_id']])->one()){
           // $this->debug('User does not exist');
            // return response array-like
            return [
                'error' => -5,
                'error_note' => 'User does not exist'
            ];
        }


        // get payment data by merchant_prepare_id
        if( $request['action'] == 1 ) {
            //$this->debug('Transaction does not exist	');
            if(!$payment = PaymentsClick::find()->where(['id'=>$request['merchant_prepare_id']])->one()){
                // return response array-like
                return [
                    'error' => -6,
                    'error_note' => 'Transaction does not exist	'
                ];
            }
        }



        // check to already paid
        if($payment->state == PaymentsClick::CONFIRMED){
            //$this->debug('Already paid');

            // return response array-like
            return [
                'error' => -4,
                'error_note' => 'Already paid'
            ];
        }

        // check to correct amount
        if(abs((float)$payment->total - (float)$request['amount']) > 0.01){
            //$this->debug('Incorrect parameter amount ');
            // return response array-like
            return [
                'error' => -2,
                'error_note' => 'Incorrect parameter amount ' . $payment->total .'-'. $request['amount']
            ];
        }else{
            //$this->debug('Correct parameter amount ');

        }



        // check status to transaction cancelled
        if($payment->state == PaymentsClick::REJECTED){
            //$this->debug('Transaction cancelled');

            // return response array-like
            return [
                'error' => -9,
                'error_note' => 'Transaction cancelled'
            ];
        }



        // return response array-like as success
        return [
            'error' => 0,
            'error_note' => 'Success'
        ];

    }
    /**
     * @name is_not_possible_data, this method used in request_check
     * @param request array-like
     * @return boolean
     */
    private function is_not_possible_data($request){
        if(!(
                isset($request['click_trans_id']) &&
                isset($request['service_id']) &&
                isset($request['merchant_trans_id']) &&
                isset($request['amount']) &&
                isset($request['action']) &&
                isset($request['error']) &&
                isset($request['error_note']) &&
                isset($request['sign_time']) &&
                isset($request['sign_string']) &&
                isset($request['click_paydoc_id'])
            ) || $request['action'] == 1 && ! isset($request['merchant_prepare_id'])) {
            //$this->debug('is_not:true');

            return true;
        }
        //$this->debug('is_not:false');

        return false;
    }



    private function debug($data)
    {

        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/uzcard_debug.txt','a');
        $data = date('d.m H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }
	
	private function debug_transactions($data) {
		$f = fopen($_SERVER['DOCUMENT_ROOT'] .'/uzcard_debug_trans.txt','a');
        $data = date('d.m H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
	}


}
