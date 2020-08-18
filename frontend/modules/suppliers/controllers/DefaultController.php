<?php

namespace app\modules\suppliers\controllers;

use common\helpers\PolisHelper;
use common\helpers\SmsHelper;
use common\helpers\UtilsHelper;
use common\models\Asko;
use common\models\Contracts;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Kyc;
use common\models\Notify;
use common\models\Paymo;
use common\models\Polises;
use common\models\Scoring;
use common\models\Stock;
use common\models\StockItems;
use common\models\SuppliersSettings;
use common\models\User;
use common\models\Uzcard;
use Paycom\Payment;
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
        if( $credits = Credits::find()->with('creditItems')->where(['supplier_id'=>$this->user->id])->andWhere(['user_confirm' => 1])->all()) {

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

        if( Yii::$app->request->method == 'POST'){

            if( $user = User::create(User::ROLE_CLIENT) ) {

                Yii::$app->session->set('user_id', $user->id);

                return $this->redirect('/suppliers');
            }

            Yii::$app->session->setFlash('info',Yii::t('app','Ошибка при создании клиента!'));

        }

        return $this->render('add-user',[
            'model' => $this->user,
            'model_kyc' => new Kyc(),
        ]);
    }


    // шаг 11. отправка смс от uzcard cards.new.otp и оферту клиенту
    public function actionSendUserSms(){

        $post = Yii::$app->request->post();
        $credit_id = $post['credit_id'];
        $path = '/get-offer?id=' . $credit_id;

        $credit_info = strip_tags( $post['info']);
        $id = $post['id'];
        
        if($user=User::find()->where(['status'=>1,'id'=>$id,'role'=>User::ROLE_CLIENT])->one()){
            $phone = User::correctPhone($user->phone);
        }else{
            return json_encode(['status'=>0,'info'=>Yii::t('app','Телефон не задан! ' . $id)],JSON_UNESCAPED_UNICODE);

        }


        $info = Yii::t('app','На ваш номер отправлен смс с кодом для подтверждения договора!');
        UtilsHelper::debug('Add-credir.send-user-sms. sms-phone:'.$phone);

        // для теста не отправлять смс
        if($_SERVER['SERVER_NAME'] != 'zmarket.loc' && $phone ) {
            $code = SmsHelper::generateCode(4);
            UtilsHelper::debug(' sms-phone:'.$phone.'. sms-code:'.$code);


            //$link = 'http://' . $_SERVER['SERVER_NAME'] .'/publicoffer.pdf';
            $link = 'http://' . $_SERVER['SERVER_NAME'] . $path;
            $text = Yii::t('app','Vas privetstvuet platforma zMarket. Publichnaya oferta (_link_). Vash kod podtverzhdeniya _code_. (_info_) Platforma zMarket blagodarit Vas za pokupku!');
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
        if($user_sms_code==$code || $_SERVER['SERVER_NAME']=='crm1.loc' )  {
            // удаляем сессию после проверки смс
            Yii::$app->session->remove('user_sms_credit');

            //получаем последний prefix_act
            $credits = Credits::find()->where(['not',['prefix_act'=>null]])->orderBy(['id' => SORT_DESC])->one();
            $prefix_act_last = $credits->prefix_act ? $credits->prefix_act : 24;
            $prefix_act_last = $prefix_act_last + 1;

            $credit_id = Yii::$app->session->has('credit_id') ? Yii::$app->session->get('credit_id') : 0;
            if($credit = Credits::findOne($credit_id)) {
                $credit->user_confirm = 1;
                $credit->prefix_act = $prefix_act_last; // нумерация актов начиная с 25 (нужно для внутренней отчетности)
                $credit->save();
                return json_encode(['status' => 1], JSON_UNESCAPED_UNICODE);
            }
        }
        return json_encode(['status'=>0,'info'=>''],JSON_UNESCAPED_UNICODE);

    }

    // шаг 10. оформление кредита. Поставщик подтвердил кредит
    public function actionSendOrder(){

        $post = Yii::$app->request->post();

        $stock_id = (isset($post['Credits']['stock_id']) && $post['Credits']['stock_id'] != 0 ) ? $post['Credits']['stock_id'] : null;  // учавствует в акции или нет
        $stock_sum = (isset($post['Credits']['stock_sum']) && $post['Credits']['stock_sum'] != 0 ) ? $post['Credits']['stock_sum'] : null;
        $user_id = $post['Credits']['user_id'];
        $stock_discount = (isset($post['Credits']['stock_discount']) && $post['Credits']['stock_discount'] != 0 ) ? $post['Credits']['stock_discount'] : null;
        $stock_current_sum = (isset($post['Credits']['stock_current_sum']) && $post['Credits']['stock_current_sum'] != 0 ) ? $post['Credits']['stock_current_sum'] : null;

        if($stock_id && $stock = StockItems::find()->where(['user_id' => $user_id])->orderBy(['id' => SORT_DESC])->one()){
            $stock_items_sum = ($stock->stock_sum - $stock_sum) >= 0 ? $stock->stock_sum - $stock_sum : false;
        }else{
            $stock_items_sum = ($stock_current_sum - $stock_sum) >=0 ? $stock_current_sum - $stock_sum : null;
        }

        $credit = new Credits();
        if( $credit->load($post) ){
            $credit->created_at = time();

            if(!$credit->save() ) {
                return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании кредита!')]);
            }else{
                // если по акции
                if ($stock_id) {
                    $stock_items = new StockItems();
                    $stock_items->user_id = $user_id;
                    $stock_items->stock_id = $stock_id;
                    $stock_items->credit_id = $credit->id;
                    $stock_items->credit_sum = $stock_sum;
                    $stock_items->stock_sum = $stock_items_sum;
                    $stock_items->stock_discount = $stock_discount;
                    if(!$stock_items->save() ) {
                        return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при записи акции!')]);
                    }
                }
            }
            // товары кредита
            $price = 0;
            $clear_price = 0;
            $quantity = 0;
            UtilsHelper::debug('create-credit');
            UtilsHelper::debug($post);

            $discount = Yii::$app->user->identity->discount ? Yii::$app->user->identity->discount : 0; // скидка от магазина

            foreach($post['product'] as $id=>$title){

                $discount_s = $post['amount'][$id] * $discount / 100;
                $discount_sum = $post['amount'][$id] - $discount_s; // стоимость товара со скидкой на момент заключения договора

                $credit_items = new CreditItems();
                $price += $post['price'][$id];
                $clear_price += $post['amount'][$id];
                $quantity += $post['quantity'][$id];
                $credit_items->title = $title;
                $credit_items->price = $post['price'][$id];
                $credit_items->amount = $post['amount'][$id];
                $credit_items->discount_sum = $discount_sum;
                $credit_items->quantity = $post['quantity'][$id];
                $credit_items->credit_id = $credit->id;

                if(!$credit_items->save() ){
                    $credit->delete();
                    return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании списка товаров кредита!')]);
                }
            }

//            $nds = $this->user->nds_state == 1 ? 1+$this->user->nds / 100 : 1;
            //if(!isset($post["Credits"]["nds"])) $nds = 0;
            $credit->nds = $this->user->nds_state;
            $credit->price = $price;
            $credit->credit = $price - $credit->deposit_first ;
            $credit->quantity = $quantity;
            if(!$credit->save() ) {
                $credit->delete();
                return json_encode(['status'=>0,'error'=> Yii::t('app','Ошибка при создании кредита!')]);
            }

            $d = date('d',time());
            $m = date('m',time());
            $y = date('Y',time());

            $credit->date_start = strtotime($d.'.'.$m .'.'.$y .' 00:00:00');


            $m2 = $m+$credit->credit_limit ; // оплата со следующего месяца
            $y2= $y;

            while($m2>12){
                $m2-=12;
                $y2++;
            }

            $credit->credit_date = strtotime($d.'.'.$m2 .'.'.$y2 .' 00:00:00');

            $credit->user_confirm = 0;

            if($user = User::find()->where(['id'=>$credit->user_id])->one()){

                $code = SmsHelper::generateCode(4);

                $credit->code_confirm = $code;
                Yii::$app->session->set('credit_id',$credit->id);

                $s = Credits::getPaymentSumAll($user->id) ;

                if($user->kyc->credit_year - $s - $clear_price < 0 ){
                    $credit->delete();
                    return json_encode(['status'=>0,'error'=> Yii::t('app','Недостаточный годовой лимит для оформления договора! '.$user->kyc->credit_year.' Общая сумма взятых кредитов: '.$s)]);
                }

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
            $credit->contract_id = $contract->id;
            $credit->save();

            return json_encode(['status'=>1,'credit_id'=>$credit->id]);

        }

        return json_encode(['status'=>0,'error'=>Yii::t('app','Ошибка при создании кредита, нет данных!')]);

    }

    public function actionAddCredit(){

        if(!$settings = SuppliersSettings::find()->where(['supplier_id'=>$this->user->id])->one() ){
            $settings = new SuppliersSettings();
        }
        // найти все акции на текущую дату
        $date = date("Y-m-d H:i:s");
        $d = date('d', time());
        $m = date('m', time());
        $y = date('Y', time());
        $date = strtotime($d . '.' . $m . '.' . $y  );


        if(!$stock = Stock::find()->where(['<=', 'date_start', $date])->andWhere(['>=', 'date_end', $date])->andWhere(['status' => 1])->one() ){
            $stock = null;
            $stock_id = null;
            $stock_company = null;
        }else{
            $stock_company = explode(',', $stock->company);
            $stock_id = $stock->id;
            $stock_sum = $stock->sum;
        }

        return $this->render('add-credit',[
            'model' => $this->user,
            'settings' => $settings,
            'stock' => $stock,
            'stock_company' => $stock_company,
            'stock_id' => $stock_id,
            'stock_sum' => $stock_sum,
        ]);

    }
	
	public function actionCreditHistory(){

        if( $creditsQuery = Credits::find()->with(['payments','supplier','client'])->where(['supplier_id'=>$this->user->id,'user_confirm'=>1])->orderBy('created_at DESC') ) {

            $pagination = new Pagination([
                'totalCount' => $creditsQuery->count(),
                'pageSize' => Credits::ITEMS_COUNT,
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

        //if (Yii::$app->session->has('user_id')) {
            if (!$settings = SuppliersSettings::find()->where(['supplier_id' => $this->user->id])->one()) {
                $settings = new SuppliersSettings();
                $settings->supplier_id = $this->user->id;
                $settings->save();
            }
            if ($this->user->updateModel()) {
                $pw = $this->user->password_login;
                $this->user->password = $pw;
                $this->user->setPassword($pw);
                $this->user->generateAuthKey();
                $this->user->password_login = null;
                $this->user->save();

                Yii::$app->session->setFlash('info', 'Сохранение успешно!');

                $settings->updateModel();

                return $this->refresh();

            //}
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

        $allCreditsSum = Credits::getPaymentSumAll($client->id);

        // остаток суммы кредита по акции
        //$balance_stock = $client->stockItems->stock_sum ? $client->stockItems->stock_sum : false;
        if($stock_items = StockItems::find()->where(['user_id' => $client->id])->orderBy(['id' => SORT_DESC])->one()){
            $balance_stock = $stock_items->stock_sum;
            $end_stock = $balance_stock == 0 ? 1 : 0;
        }else{
            $end_stock = 0;
        }

        $delay = Credits::getPaymentDelaySumAll($client->id);

        if($find && isset($client->kyc) ){
            $phone = mb_substr($client->phone,8,6);
            $prefix = str_repeat('*',8);
            return json_encode([
                'balance_stock' => $balance_stock,
                'end_stock' => $end_stock,
                'status'=>1,
                'client_verify'=> $client->kyc->status_verify ? '<i class="fa fa-check"></i>':'', //Yii::t('app','Да') : Yii::t('app','Нет'),
                'client_date_verify'=> date('d.m.Y',$client->kyc->date_verify ),
                //'client_delay'=> $client->kyc->delay ? Yii::t('app','Да') : Yii::t('app','Нет'),
                'client_delay'=> $delay > 0 ? Yii::t('app','Да') : Yii::t('app','Нет'),
                'client_id' => $client->id,
                'client_phone' => $prefix . $phone,
                'client_summ' => number_format($client->summ,2,'.',' '),
                'username'=> $client->username,
                'lastname'=> $client->lastname,
                'patronymic'=> $client->patronymic,
                'passport_self'=>$client->passport_self,
                'user_id'=> $client->id,
                'phone_full'=>$client->phone,
                //'zmarket_sum' => number_format(Credits::ZMARKET_SUM - Credits::getPaymentSumAll($client->id),2,'.',' ')
                'zmarket_sum' => number_format($client->kyc->credit_year - $allCreditsSum,2,'.',' '),
                'all_credits_sum' => $allCreditsSum
            ]);
        }

        return json_encode(['status'=>0,'info'=>$info]);

    }


    public function actionContracts()
    {

        if($orderQuery = Contracts::find()->with(['client','supplier','credit','creditItems'])->where(['supplier_id'=>$this->user->id])){
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

                /*$contract = new Contracts();
                $contract->created_at = time();
                $contract->credit_id = $credit->id;
                $contract->user_id = $credit->user_id;
                $contract->supplier_id = $this->user->id;
                $contract->date_start = $credit->date_start;
                $contract->date_end = $credit->credit_date;
                $contract->status = 0;
                if(!$contract->save()){
                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при создании договора! ' . json_encode($contract->getErrors(),256))]);

                }*/

                $allowed = true;  // вкл-выкл страховой
                if($allowed) {
                    // отправка договора на страхование Asko
                    $user_id = $credit->user_id;
                    $amount = $credit->price;
                    $term = $credit->credit_limit;
                    $credit = $credit->id;

                    if (!$user = User::find()->where(['id' => $user_id])->one()) {
                        return json_encode(['status' => 1, 'info' => Yii::t('app', 'Клиент не найден!')], JSON_UNESCAPED_UNICODE);
                    }

                    $asko = new Asko();
                    $result = Asko::askoInfo($user, $amount, $term, $credit);

                    foreach ($result as $k => $v) {
                        $asko->$k = $v;
                    }
                    $asko->created_at = time();
                    $asko->credit_id = $credit;
                    $asko->client_id = $user_id;
                    $asko->supplier_id = $this->user->id;
                    if (!$asko->save(false)) {
                        return json_encode(['status' => 1, 'info' => Yii::t('app', 'Ошибка сохранения данных от страховой компании Asko!')], JSON_UNESCAPED_UNICODE);
                    }
                    if ($contract = Contracts::find()->where(['credit_id' => $credit])->one()) {
                        $contract->status_polis = 1;
                        $contract->save();
                    }
                }
                // отправка договора на страхование - старый
                /*$result = PolisHelper::getPolisForCredit('zMarket_' . $credit->contract_id, $credit);
                $result = PolisHelper::сheckTransaction('zMarket_' . $credit->contract_id, $credit);

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
                    $polis->contract_id = $credit->contract_id;

                    if (!$polis->save()) {
                        return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
                    }

                    if ($contract = Contracts::find()->where(['credit_id' => $credit->id])->one()) {
                        $contract->status_polis = 1;
                        $contract->save();
                    }

                } else {

                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.') . json_encode($result,JSON_UNESCAPED_UNICODE)],JSON_UNESCAPED_UNICODE);

                }*/


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
    public function actionGetOffer($id){

        $this->layout = '@frontend/views/layouts/cabinet.php';
        $ref = Yii::$app->request->referrer;

        // поиск только своих кредитов
        if( !$credit = Credits::find()->with(['client','supplier','creditItems','paymentsAsc'])->where(['id'=>$id])->one() ){

            return $this->redirect($ref);
        }

        return $this->render('_offer',[
            'credit' => $credit
        ]);


    }


    public function actionLogout(){
        Yii::$app->user->logout(true);
        return $this->redirect('/');
    }


}
