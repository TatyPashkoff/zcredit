<?php

namespace frontend\controllers;

use common\models\BillingHistory;
use common\models\Payment;
use common\models\PaymeTransactions;
use common\models\PaymeOrders;
use common\models\User;
use common\helpers\PaycomException;


use Yii;
use yii\web\Controller;

Class PaymeController extends Controller{
	


    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
		
		
	    public function actionInit()
    {

		$request_body = file_get_contents('php://input');
		$request = json_decode($request_body,true);


		$key = 'Paycom:MxYun8QZxHv11w8653OixGI2IwTty5bB5o6V';
			
		
            // authorize session
		$headers = getallheaders();
		
        if (!$headers || !isset($headers['Authorization']) ||
            !preg_match('/^\s*Basic\s+(\S+)\s*$/i', $headers['Authorization'], $matches) ||
            base64_decode($matches[1]) != $key
        ) {
			$this->debug('Ошибка -32504 Не достаточно привелегий при входе');
				$arr_error = array('ru'=>'Недостаточно привелегий при входе','en'=>'Not enough privileges to execute method','uz'=>'Usulni bajarishga huquqlar etarli emas');
				return $this->error(
					PaycomException::ERROR_INSUFFICIENT_PRIVILEGE,
					$arr_error,
					$request['method']
				);
        }
        
            // handle request
            switch ($request['method']) {
                case 'CheckPerformTransaction':
                    return $this->CheckPerformTransaction($request);
                    break;
                case 'CheckTransaction':
                    return $this->CheckTransaction($request);
                    break;
                case 'CreateTransaction':
                    return $this->CreateTransaction($request);
                    break;
                case 'PerformTransaction':
                    return $this->PerformTransaction($request);
                    break;
                case 'CancelTransaction':
                    return $this->CancelTransaction($request);
                    break;
                case 'ChangePassword':
                    return $this->ChangePassword($request);
                    break;
                default:
				$this->debug('Ошибка 32601 не верно указан метод при инициализации');
				$arr_error = array('ru'=>'Метод не найден','en'=>'Method doesnt exist','uz'=>'Amaliyotni bajarib bo`lmadi');
				return $this->error(
					PaycomException::ERROR_METHOD_NOT_FOUND,
					$arr_error,
					$request['method']
				);
				 break;
				
            }

    }

/* OLD 	
    // callback от Payme для определения прошел платеж или нет /payments/order_id/transaction_id
    public function actionPayments($order_id=null,$id=null)
    {
   
        // поиск транзакции
        if( $transact = PaymeTransactions::find()->where(['paycom_transaction_id'=>$id])->one() ){

            if($transact->state==2){ // успешная транзакция

                // $billing_history создается в perform
                if($billing_history = BillingHistory::find()->where(['id'=>$transact->billing_id])) {
                    $billing_history->status = 1;

                    if ($billing_history->save()) {
                        Yii::$app->session->setFlash('info', Yii::t('app', 'Пополнение баланса успешно завершено!'));
                    } else {
                        Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате!'));
                    }

                    if($user = User::find()->where(['id'=>$billing_history->user_id])->one() ) {
                        $user->summ += $billing_history->summ; // увеличение баланса клиента
                        $user->save(false);
                    }

                    Payment::payment($user->id); // погашение просроченных кредитов


                    return $this->redirect('/clients/checkout');// отображение, что оплата прошла упешно

                }

                //if($payment = Payment::payment($order_id,Payment::PAYMENT_TYPE_PAYME) ) { // завершение оплаты
                    // Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата успешно завершена!'));
                //}

                Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при оплате!') );

            }

        }else{ // если транзакции нет, значит нажата кнопка отмена
            if( $payment = Payment::find()->where(['id'=>$order_id])->one() ){
                $payment->state *=-1; // установить статус отмена
                $payment->created_at = time();
                $payment->save();
                Yii::$app->session->setFlash('info', Yii::t('app','Оплата отменена!') );
            }else{ // если заказа нет в бд
                // ошибка в системе
                Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при оплате!') );
            }
        }

        return $this->redirect('/clients/credit-history'); // назад


    }
    */
    
    	 private function CheckPerformTransaction($request)
    {
		
       		 //Проверка на лицевой счет
			if(!$user = User::find()->where(['id'=> $request['params']['account']['client_id'],'role'=>User::ROLE_CLIENT])->one()) {
				$this->debug('Ошибка -31050 Лицевой счет не найден');
				$arr_error = array('ru'=>'Ввведеные данные не корректны','en'=>'Entered data is not correct','uz'=>'Kiritilgan ma`lumotlar noto`g`ri');
				return $this->error(
						PaycomException::ERROR_INVALID_ACCOUNT,
						$arr_error
					);
			}
			

			//Если сумма и лицевой счет сходится то отправляем true
			        $response = [];
					$response['jsonrpc'] = '2.0';
					$response['result']['allow']  = true;
					return json_encode($response);
            
	}
    
	
	    private function CheckTransaction($request)
    {
	        // Проверка отправлены ли все параметры
        if(!isset($request['params']['id'])){
				$arr_error = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
				$this->debug('Error -31008 in request from payme');
				return $this->error(
					PaycomException::ERROR_COULD_NOT_PERFORM,
					$arr_error
				);

		 }
        
       if($payme_transact = PaymeTransactions::find()->where(['paycom_transaction_id'=> $request['params']['id']])->one() ) {
			
		$response = [];
		$response['jsonrpc'] = '2.0';
		$response['result']['create_time'] = intval($payme_transact->create_time);
		
		if($payme_transact->perform_time == null or $payme_transact->perform_time == 0  ) {
			$response['result']['perform_time'] = 0;
		}else{
			$response['result']['perform_time'] = intval($payme_transact->perform_time);
		}
		
		if($payme_transact->cancel_time == null or $payme_transact->cancel_time == 0 ) {
			$response['result']['cancel_time'] = 0;
		}else{
			$response['result']['cancel_time'] = intval($payme_transact->cancel_time);
		}
		
		$response['result']['transaction'] = $payme_transact->paycom_transaction_id;
		$response['result']['state'] = $payme_transact->state;
		$response['result']['reason'] = $payme_transact->reason;
		return json_encode($response);
		
		}else{
			$arr_error = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
			$this->debug('Ошибка -31003 Транзакция не найдена');
			return $this->error(
				PaycomException::ERROR_INVALID_ACCOUNT,
				$arr_error
			);
		}
	}
	
	

    private function CreateTransaction($request)
    {
		
		// Проверка отправлено-ли все параметры
			if(
				isset($request['time'])
			){
				$arr_error = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
				$this->debug('Error -31008 in request from payme');
				return $this->error(
					PaycomException::ERROR_COULD_NOT_PERFORM,
					$arr_error
				);

			}
			$amount = $request['params']['amount'] / 100;
			
				

		 //Проверка на лицевой счет
			if(!$user = User::find()->where(['id'=> $request['params']['account']['client_id'],'role'=>User::ROLE_CLIENT])->one()) {
				$this->debug('Ошибка -31050 Лицевой счет не найден');
				$arr_error = array('ru'=>'Ввведеные данные не корректны','en'=>'Entered data is not correct','uz'=>'Kiritilgan ma`lumotlar noto`g`ri');
				return $this->error(
						PaycomException::ERROR_INVALID_ACCOUNT,
						$arr_error
					);
			}
			
		//Получение юзера	
			$user = User::find()->where(['id'=> $request['params']['account']['client_id'],'role'=>User::ROLE_CLIENT])->one();
			

        
		
		if(!$payme_transact = PaymeTransactions::find()->where(['paycom_transaction_id'=>$request['params']['id']])->one() ) {
			
			

		//Создаем запись в истории биллинга
            $billing_history = new BillingHistory();
            $billing_history->user_id = $user->id;
            $billing_history->created_at = time();
            $billing_history->summ = $amount; // ПОПОЛНЕНИЕ баланса в суммах, не тийинах
            $billing_history->payment_type = Payment::PAYMENT_TYPE_PAYME;
            $billing_history->status = 0;
            $billing_history->state = 0;
            $billing_history->payme_trans = $request['params']['id'];
			$billing_history->save();
			
		//Создаем транзакцию				
			$payme_transact = new PaymeTransactions();
			$payme_transact->paycom_transaction_id = $request['params']['id'];
			$payme_transact->billing_id = $request['id'];
			$payme_transact->paycom_time = $request['params']['time'];
			$payme_transact->paycom_time_datetime = $request['params']['time'];
			$payme_transact->reason = null;
			$payme_transact->order_id = null;
			$payme_transact->cancel_time = 0;
			$payme_transact->perform_time = 0;
			$payme_transact->create_time = $request['params']['time'];
			$payme_transact->state = PaymeOrders::STATE_CREATED;
			$payme_transact->amount = $amount;
			$payme_transact->client_id = $request['params']['account']['client_id'];
			$payme_transact->save(false);
			
		//Сохранение транзакции в лог файле 
			$this->debug_transactions($request);
			$response = [];
			$response['jsonrpc'] = '2.0';
			$response['result']['create_time'] = intval($request['params']['time']);
			$response['result']['transaction'] = $request['params']['id'];
			$response['result']['state'] = PaymeOrders::STATE_CREATED;
			return json_encode($response);
		
		}else{
			$response = [];
			$response['jsonrpc'] = '2.0';
			$response['result']['create_time'] = intval($request['params']['time']);
			$response['result']['transaction'] = $request['params']['id'];
			$response['result']['state'] = PaymeOrders::STATE_CREATED;
			return json_encode($response);
		}

	}
	


    private function PerformTransaction($request)
    {
		
        // Проверка отправлены ли все параметры
        if(
            !isset($request['params']['id'])
        ){
        	$this->debug('Error in request from payme');
			$arr_error = array('ru'=>'Невозможно выполнить операцию','en'=>'Unable to complete the operation','uz'=>'Amaliyotni bajarib bo`lmadi');
			return $this->error(
				PaycomException::ERROR_COULD_NOT_PERFORM,
				$arr_error
			);

        }
        
        
        
        //Поиск транзакции
         if($transact = PaymeTransactions::find()->where(['paycom_transaction_id'=> $request['params']['id']])->one() ){
			 
			 if($transact->perform_time == 0) {
				$transact->perform_time = time() * 1000;
				$transact->save(false);
			}
			
			 $transact->state = 2;
			 $transact->save(false);
			 
			//Пополнение лицевого счета клиента
			if($billing_history = BillingHistory::find()->where(['payme_trans'=> $request['params']['id']])->andWhere(['status'=> 0])->one()) {
				$user = User::find()->where(['id'=> $transact->client_id,'role'=>User::ROLE_CLIENT])->one();
				$user->summ += $billing_history->summ; // увеличение баланса клиента
				$user->save(false);
			}
			
			Payment::payment($user->id); // погашение просроченных кредитов
	
			//Поиск оплаты истории биллинга
			 $billing_history = BillingHistory::find()->where(['payme_trans'=> $request['params']['id']])->one();
		
			 $billing_history->status = 1;
			 $billing_history->state = 1;
			 $billing_history->save(false);
			
			

							 
			}else{
				$arr_error = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
				$this->debug('Ошибка -31003 Подготовленная транзакция не найдена');
				return $this->error(
					PaycomException::ERROR_TRANSACTION_NOT_FOUND,
					$arr_error
				);
			}
		


			$response = [];
			$response['jsonrpc'] = '2.0';
			$response['result']['transaction'] = $transact->paycom_transaction_id;
			$response['result']['perform_time'] = intval($transact->perform_time);
			$response['result']['state'] = 2;
			return json_encode($response);
		
	}
    

    private function CancelTransaction($request)
    {
        //Поиск транзакции и отмена ее
         if($transact = PaymeTransactions::find()->where(['paycom_transaction_id'=> $request['params']['id']])->one() ) {
			 if($transact->cancel_time == 0) {
				 $transact->cancel_time = time() * 1000;
				 $transact->save(false);
			 }

			
			 
		//Поиск в истории биллинга и отмена неоплаченного платежа
		 if($billing_history = BillingHistory::find()->where(['payme_trans'=> $request['params']['id']])->andWhere(['status' => 0])->one() ) {
			 $billing_history->state = -1;
			 $billing_history->save(false);
			 
			 $transact->reason = $request['params']['reason'];
			 $transact->state = -1;
			 $transact->save(false);
			 
			$response = [];
			$response['state'] = '2.0';
			$response['result']['transaction'] = $transact->paycom_transaction_id;
			$response['result']['cancel_time'] = intval($transact->cancel_time);
			$response['result']['state'] = -1;
			return json_encode($response);
			}
			
		//Поиск в истории биллинга и отмена оплаченного платежа
		if($billing_history = BillingHistory::find()->where(['payme_trans'=> $request['params']['id']])->andWhere(['status' => 1])->andWhere(['state' => 1])->one()  ) {
			 $billing_history->state = -1;
			 $billing_history->save(false);
			 
			 $user = User::find()->where(['id'=> $transact->client_id,'role'=>User::ROLE_CLIENT])->one();
			 $user->summ -= $billing_history->summ; // снятие пополненных средств с баланса клиента
			 $user->save(false);
			 
			 
			 $transact->reason = $request['params']['reason'];
			 $transact->state = -2;
			 $transact->save(false);
			 
			$response = [];
			$response['state'] = '2.0';
			$response['result']['transaction'] = $transact->paycom_transaction_id;
			$response['result']['cancel_time'] = intval($transact->cancel_time);
			$response['result']['state'] = -2;
			return json_encode($response);
		} else {
			$response = [];
			$response['state'] = '2.0';
			$response['result']['transaction'] = $transact->paycom_transaction_id;
			$response['result']['cancel_time'] = intval($transact->cancel_time);
			$response['result']['state'] = -2;
			return json_encode($response);
		}
			
			
			  

			  
		}else{
			$arr_error = array('ru'=>'Транзакция не найдена','en'=>'Transaction not found','uz'=>'Transakciya topilmadi');
			$this->debug('Ошибка -31003 транзакция в методе CanselTransaction не найдена');
			return $this->error(
				PaycomException::ERROR_TRANSACTION_NOT_FOUND,
				$arr_error
			);
		}		
		
      }
    

    private function ChangePassword($request)
    {
		//TODO
			return $this->error(
				PaycomException::ERROR_INTERNAL_SYSTEM
			);
    }
	
	

	
	    public function error($code, $message = null, $data = null)
    {
		$response = array();
        $response['jsonrpc'] = '2.0';
        $response['error']['code']   = $code;
		$response['error']['message']   = PaycomException::message($message);
		$response['id']      = $request['params']['id'];
        return json_encode($response);
    }


	

	private function debug($data)
    {

        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/payme_debug.txt','a');
        $data = date('d.m H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);



    }
	
	private function debug_transactions($data) {
		$f = fopen($_SERVER['DOCUMENT_ROOT'] .'/payme_debug_trans.txt','a');
        $data = date('d.m H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
	}
	
}
