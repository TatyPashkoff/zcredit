<?php

namespace app\modules\suppliers\controllers;

use common\helpers\PolisHelper;
use common\helpers\SmsHelper;
use common\helpers\UtilsHelper;
use common\models\Contracts;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Kyc;
use common\models\Notify;
use common\models\Paymo;
use common\models\Polises;
use common\models\Scoring;
use common\models\SuppliersSettings;
use common\models\User;
use common\models\Uzcard;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class DefaultController extends BaseController
{


    public function actionIndex()
    {

        $credits_count = 0;
        $credits_stop = 0;
        $products_sale = 0;
        if( $credits = Credits::find()->with('creditItems')->where(['supplier_id'=>$this->user->id])->all()) {

            foreach ($credits as $credit) {
                if ($credit->status) {
                    $credits_stop++;
                } else {
                    $credits_count++;
                }
                foreach ($credit->creditItems as $item) {
                    $products_sale += $item->quantity;
                }
            }
        }

        return $this->render('index',[
            'credits_count' => $credits_count,
            'credits_stop' => $credits_stop,
            'products_sale' => $products_sale,
            'user' => $this->user,

        ]);
    }

    // добавление нового пользователя
    public function actionAddUser(){

        //UtilsHelper::debug('user add');

        if( Yii::$app->request->method == 'POST'){

            //UtilsHelper::debug('user post model');

            if( $user = User::create(User::ROLE_CLIENT) ) {

                //UtilsHelper::debug('user create');

                Yii::$app->session->set('user_id', $user->id);

                return $this->redirect('/suppliers');
            }

            Yii::$app->session->setFlash('info',Yii::t('app','Ошибка при создании клиента!'));
            //Yii::$app->session->set('user_id',$user->id);

            //return $this->redirect('/suppliers');

        }

        return $this->render('add-user',[
            'model' => $this->user,
            'model_kyc' => new Kyc(),
        ]);
    }


    // шаг 11. отправка смс от uzcard cards.new.otp и оферту клиенту
    public function actionSendUserSms(){

        $post = Yii::$app->request->post();

        $credit_info = strip_tags( $post['info']);

       $phone = User::correctPhone($post['phone']);
        //UtilsHelper::debug('Add-credir.send-user-sms');
        //UtilsHelper::debug('sms-phone:'.$phone);

        $info = Yii::t('app','На ваш номер отправлен смс с кодом для подтверждения договора!');

        // для теста не отправлять смс
        if($_SERVER['SERVER_NAME'] != 'crm1.loc' && $phone ) {
            $code = SmsHelper::generateCode(4);

            //UtilsHelper::debug('sms-code:'.$code);

            $link = 'http://' . $_SERVER['SERVER_NAME'] .'/offer';
            $text = Yii::t('app','Здравствуйте Ув. Пользователь! Вас приветствует платформа zMarket. Публичная оферта (_link_). Ваш код подтверждения _code_. (_info_) Платформа zMarket благодарит Вас за покупку!');
            $text = str_replace('_link_',$link,$text);
            $text = str_replace('_code_',$code,$text);
            $text = str_replace('_info_',$credit_info,$text);

            // смс оповещение клиента с кодом подтверждения кредита
            SmsHelper::sendSms($phone,Yii::t('app',$text ));

        }else{ // для тестов локаль
            $code = 1234;
        }
        Yii::$app->session->set('user_sms_credit',$code);

        return json_encode(['status'=>1,'info'=>$info],JSON_UNESCAPED_UNICODE);

    }

    // проверка смс клиента
    public function actionCheckUserSms(){

        $post = Yii::$app->request->post();

        $code = $post['code'];

        $user_sms_code = Yii::$app->session->has('user_sms_credit') ? Yii::$app->session->get('user_sms_credit') : uniqid();

        // смс оповещение клиента с кодом подтверждения кредита
        if($user_sms_code==$code)  return json_encode(['status'=>1],JSON_UNESCAPED_UNICODE);
        return json_encode(['status'=>0,'info'=>''],JSON_UNESCAPED_UNICODE);

    }

    // шаг 10. оформление кредита. Поставщик подтвердил кредит
    public function actionSendOrder(){

        $post = Yii::$app->request->post();
        $credit = new Credits();

        if( $credit->load($post) ){
            $credit->created_at = time();

            if(!$credit->save() ) {
                return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании кредита!')]);
            }
            // товары кредита
            $price = 0;
            $quantity = 0;

            foreach($post['product'] as $id=>$title){

                $credit_items = new CreditItems();
                $price += $post['price'][$id] * $post['quantity'][$id];
                $quantity += $post['quantity'][$id];
                $credit_items->title = $title;
                $credit_items->price = $post['price'][$id];
                $credit_items->amount = $post['amount'][$id];
                $credit_items->quantity = $post['quantity'][$id];
                $credit_items->credit_id = $credit->id;

                if(!$credit_items->save() ){
                    $credit->delete();
                    return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании списка товаров кредита!')]);
                }
            }

            $nds = $this->user->nds_state == 1 ? 1+$this->user->nds / 100 : 1;

            $credit->price = $price * $nds;
            $credit->credit = $price * $nds - $credit->deposit_first ;
            $credit->quantity = $quantity;
            if(!$credit->save() ) {
                $credit->delete();
                return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании кредита!')]);
            }

            $d = date('d',time());
            $m = date('m',time());
            $y = date('Y',time());

            $credit->date_start = strtotime($d.'.'.$m .'.'.$y .' 00:00:00');


            $m2 = $m+$credit->credit_limit+1 ; // оплата со следующего месяца
            $y2= $y;

            while($m2>12){
                $m2-=12;
                $y2++;
            }

            $credit->credit_date = strtotime($d.'.'.$m2 .'.'.$y2 .' 00:00:00');

            $credit->user_confirm = 0;

            if($user = User::find()->/*select('phone')->*/where(['id'=>$credit->user_id])->one()){

                $code = SmsHelper::generateCode(4);

                $credit->code_confirm = $code;

                // УТОЧНИТЬ
                if($user->summ - $credit->deposit_first >=0 ) {
                    // здесь от суммы на балансе клиента отнимается первоначальный взнос?
                    // это должен учесть менеджер в KYC чтобы продолжить
                    $user->summ -= $credit->deposit_first; // ВЫЧИТАЕМ ИЗ БАЛАНСА КЛИЕНТА СУММУ ПЕРВОНАЧАЛЬНОГО ВЗНОСА ??? УТОЧНИТЬ
                }else{
                    // для теста разрешить создание

                    //$credit->delete();
                    //return json_encode(['status'=>0,'error'=> Yii::t('app','Недостаточно средств для списания!') ]);
                }

                // шаг 12. оповещение uzcard о заключении договора на автоматическое безакцептное списание средств
                //Uzcard::sendOrder($credit,$user); // у узкард списание при регистрации

            }

            $credit->save();

            // создание план графика оплат на весь срок оплаты
            $cnt = $credit->credit_limit; // Credits::CREDIT_TYPES[$credit->credit_limit];
            $m++;
            for($n=0;$n<$cnt;$n++){
                if($m>12) {
                    $m=1;
                    $y++;
                }
                $credit_history = new CreditHistory();
                $credit_history->credit_id =$credit->id;
                $credit_history->delay = 0;
                $credit_history->credit_date = strtotime( $d . '.' . $m . '.'. $y .' 00:00:00');
                $credit_history->payment_status = 0;
                $credit_history->payment_type = 0;
                if($n+1==$cnt){ // учет копеек
                    $credit_history->price = $credit->deposit_month + ($credit->price - $credit->deposit_first - $cnt*$credit->deposit_month);
                }else {
                    $credit_history->price = $credit->deposit_month;
                }
                if(!$credit_history->save()) {
                    $credit->delete();
                    return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании план графика оплат!')]);

                }
                $m++;
            }
            return json_encode(['status'=>1,'credit_id'=>$credit->id]);

        }

        return json_encode(['status'=>0,'error'=>Yii::t('app','Ошибка при создании кредита, нет данных!')]);

    }

	public function actionAddCredit(){
		
        if(!$settings = SuppliersSettings::find()->where(['supplier_id'=>$this->user->id])->one() ){
            $settings = new SuppliersSettings();
        }

        return $this->render('add-credit',[
            'model' => $this->user,
            'settings' => $settings
        ]);
		
	}
	
	public function actionCreditHistory(){

        if( $creditsQuery = Credits::find()->with(['payments','supplier','client'])->where(['supplier_id'=>$this->user->id])->orderBy('created_at DESC') ) {

            $pagination = new Pagination([
                'totalCount' => $creditsQuery->count(),
                'pageSize' => 2, //Credits::ITEMS_COUNT,
                'pageSizeParam' => false,
            ]);

            if( ! $credits = $creditsQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all() ) {
                $credits = false;
            }

        }else{
            $credits = false;
            $pagination = false;
        }

        if( !$kyc = Kyc::find()->where(['supplier_id'=>$this->user->id])->one() ){
            $kyc = false;
        }

        return $this->render('credit-history',[
            'credits' => $credits,
            'model' => Yii::$app->user->identity,
            'model_kyc' => $kyc,
            'pagination' => $pagination,

        ]);
		
	}

	public function actionSetDeliveryDate(){

        $post = Yii::$app->request->post();
        $id = isset($post['id'])?(int)$post['id']:0;
        if( $credit = Credits::find()->where(['id'=>$id, 'supplier_id'=>$this->user->id])->one() ){
            $date = time();
            $credit->delivery_date = $date;
            if($credit->save()) {
                $date = date('d.m.Y',$date);
                return json_encode(['status' => 1, 'date' => Yii::t('app','Дата доставки товара') .': ' . $date],JSON_UNESCAPED_UNICODE);
            }

        }
        return json_encode(['status'=>0]);
    }


    // план график оплаты
    public function actionCreditPlan(){

        $credit_id = Yii::$app->request->get('id');

        if(!$credit = Credits::find()->with(['creditItems','payments','supplier'])->where(['id'=>$credit_id,'supplier_id'=>$this->user->id])->one()){
            $credit = false;
        }

        if( !$kyc = Kyc::find()->where(['supplier_id'=>$this->user->id])->one() ){
            $kyc = false;
        }

        return $this->render('credit-plan',[
            'credit' => $credit,
            //'user' => $this->user,
            'model' => Yii::$app->user->identity,
            'model_kyc' => $kyc,

        ]);

    }


    public function actionNotify(){

        return $this->render('notify',[
            'model' => $this->user,
        ]);

    }

	// получение уведомлений от пользователей
	public function actionGetNotify(){

        $post = Yii::$app->request->post();
        $id = isset($post['id'])?(int)$post['id']:0;
        if($notify = Notify::find()->where(['id'=>$id])->one()){
            return json_encode(['status'=>1,'count'=>rand(0,12)]);
        }
        return json_encode(['status'=>1,'count'=> rand(0,10)]);
    }


	// настройка
	public function actionSettings(){

        if(!$settings = SuppliersSettings::find()->where(['supplier_id'=>$this->user->id])->one()){
            $settings = new SuppliersSettings();
            $settings->supplier_id = $this->user->id;
            $settings->save();
        }
        if( $this->user->updateModel()){
            $this->user->save();

            Yii::$app->session->setFlash('info','Сохранение успешно!');

            $settings->updateModel();

            return $this->refresh();

        }

        return $this->render('settings',[
            'model' => $this->user,
			'settings'=> $settings,
        ]);

    }

    // ajax - поиск клиента для выдачи кредита
    // поиск по телефону или по id
    public function actionGetUser(){

        $post = Yii::$app->request->post();
        if(isset($post['phone'])) {
            $id = User::correctPhone($post['phone']);
        }else {
            return json_encode(['status' => 0]);
        }

        $find = false;
        if( $client = User::find()->with('kyc')->where(['phone'=>$id,'role'=>User::ROLE_CLIENT,'status'=>1])->one() ) $find = true;
        if( !$find && $client = User::find()->with('kyc')->where(['id'=>$id,'role'=>User::ROLE_CLIENT,'status'=>1])->one() ) $find = true;

        $info = Yii::t('app','Клиент не найден!');

        if(!isset($client->kyc)){
            return json_encode(['status'=>0,'info'=>$info]);

        }

        if($find && $client->status!=1 ){
            $info = Yii::t('app','Клиент заблокирован!');
            return json_encode(['status'=>0,'info'=>$info]);
        }

        if( $client->kyc->status_verify ==0 ){
            $info = Yii::t('app','Клиент не верифицирован!');
            return json_encode(['status'=>0,'info'=>$info]);

        }


        if($find && isset($client->kyc) ){
            return json_encode([
                'status'=>1,
                'client_verify'=> $client->kyc->status_verify ? '<i class="fa fa-check"></i>':'', //Yii::t('app','Да') : Yii::t('app','Нет'),
                'client_date_verify'=> date('d.m.Y',$client->kyc->date_verify ),
                'client_delay'=> $client->kyc->delay ? Yii::t('app','Да') : Yii::t('app','Нет'),
                'client_id' => $client->id,
                'client_phone' => $client->phone,
                'client_summ' => number_format($client->summ,2,'.',' '),
                'username'=> $client->username,
                'lastname'=> $client->lastname,
                'user_id'=> $client->id,
            ]);
        }

        return json_encode(['status'=>0,'info'=>$info]);

    }


    public function actionContracts()
    {

        if($orderQuery = Contracts::find()->with(['client','supplier','credit'])->where(['supplier_id'=>$this->user->id])){
            $pagination = new Pagination( [
                'totalCount' => $orderQuery->count(),
                'pageSize' =>  Contracts::ITEMS_COUNT,
            ]);

            if( !$model_order = $orderQuery->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all() ) {
                $model_order = false;
            }

        }else{
            $model_order = false;
            $pagination = false;

        }

        return $this->render('contracts',[
            'user' => $this->user,
            'model_order' => $model_order,
            'pagination' => $pagination,

        ]);

    }

    // шаг 14. подтверждение кредита поставщиком
    // формирование договора
    public function actionCreditConfirm(){

        $id = (int)Yii::$app->request->post('id');

        // поиск только своих кредитов
        if($credit = Credits::find()->where(['id'=>$id,'supplier_id'=>$this->user->id])->one() ){
            $credit->confirm = 1;
            $credit->confirm_date = time();
            if($credit->save()) {
                // оформления договора после подтверждения поставщика

                $contract = new Contracts();
                $contract->created_at = time();
                $contract->credit_id = $credit->id;
                $contract->user_id = $credit->user_id;
                $contract->supplier_id = $this->user->id;
                $contract->date_start = $credit->date_start;
                $contract->date_end = $credit->credit_date;
                $contract->status = 0;
                if(!$contract->save()){
                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при создании договора! ' . json_encode($contract->getErrors(),256))]);

                }

                /*  ПОКА НЕ ОТПРАВЛЯТЬ ДОГОВОР НА СТРАХОВКУ
                // отправка договора на страхование
                $result = PolisHelper::getPolisForCredit('zMarket_' . $contract->id, $credit);
                $result = PolisHelper::сheckTransaction('zMarket_' . $contract->id, $credit);

                if( isset($result['original']) && isset($result['original']['contractRegistrationID']) ) {
                    // создание полиса
                    $polis = new Polises();
                    $polis->status = 1;
                    $polis->created_at = time();
                    $polis->contractRegistrationID = $result['original']['contractRegistrationID'];
                    $polis->polisSeries = $result['original']['polisSeries'];
                    $polis->polisNumber = (string)$result['original']['polisNumber'];
                    $polis->client_id = $credit->user_id;
                    $polis->credit_id = $credit->id;
                    $polis->supplier_id = $credit->supplier_id;
                    $polis->contract_id = $contract->id;

                    if (!$polis->save()) {
                        return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
                    }

                    $contract->status_polis = 1;
                    $contract->save();

                }else{

                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.') . json_encode($result,JSON_UNESCAPED_UNICODE)],JSON_UNESCAPED_UNICODE);

                }
                */

                return json_encode(['status'=>1,'info'=>Yii::t('app','Договор успешно подтвержден!'),'html'=>'<i class="fa fa-check"></i> '. Yii::t('app','Подвержден')]);
            }
        }


        return json_encode(['status'=>0,'info'=>Yii::t('app','Кредит не найден!')]);

    }

    public function actionClients()
    {

        if($clientsQuery = User::find()->where(['role'=>User::ROLE_CLIENT,'supplier_id'=>$this->user->id])){
            $pagination = new Pagination( [
                'totalCount' => $clientsQuery->count(),
                'pageSize' => 2, //Kyc::ITEMS_COUNT,
            ]);

            if( !$model_clients = $clientsQuery->offset($pagination->offset)->limit($pagination->limit)->orderBy('id DESC')->all() ) {
                $model_clients = false;
            }

        }else{
            $model_clients = false;
            $pagination = false;

        }

        return $this->render('clients',[
            'user' => $this->user,
            'model_clients' => $model_clients,
            'pagination' => $pagination,

        ]);

    }

    public function actionPrintAct(){

        $this->layout = 'print';
        $get = Yii::$app->request->get();

        $credit_id = isset($get['credit_id']) ?(int)$get['credit_id']:0;

        if( !$credit = Credits::find()->with(['client','supplier','creditItems','paymentsAsc'])->where(['id'=>$credit_id,'supplier_id'=>$this->user->id])->one() ){
            //$credit = false;
            exit;
        }
        return $this->render('print-act',[
            'credit'=>$credit
        ]);


    }



    public function actionLogout(){
        Yii::$app->user->logout(true);
        return $this->redirect('/');
    }


}
