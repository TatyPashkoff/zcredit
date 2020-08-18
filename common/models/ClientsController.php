<?php

namespace app\modules\api\controllers;

use common\helpers\PolisHelper;
use common\helpers\SmsHelper;
use common\helpers\UtilsHelper;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Polises;
use common\models\User;
use common\models\Contracts;
use common\models\Uzcard;
use Yii;

/*
тестовый 
1) + Авторизация  (поставщиком)
2) + поиск клиента по id или телефону
3)  +Оформление заказа
4) + Подтверждение заказа поставщиком и клиентом
   + уведомление uzcard О новом кредите - ?
   + уведомление клиента о новом заказе по смс
   + Оформление кредита
5) + Выдача кредита
 */

class ClientsController extends BaseApiController
{
    // запрещенные для вывода поля
    public $disallow_fields = ['password_hash', 'password_reset_token', 'auth_key'];
    // разрешенные поля
    public $allow_fields = ['id', 'username', 'lastname', 'phone', 'summ'];

    /**
     * Вывод списка всех записей
     */
    public function actionIndex()
    {

       // return $this->response(['error' => 'API. ' . 'Data not found'], 404);

        $params = Yii::$app->request->get();
        $role = (int)$params['role'];
        // получать только своих клинтов
        $usersQuery = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => $role, 'supplier_id' => $this->user->id]);

        if (isset($params['page']) && isset($params['limit'])) {
            $page = (int)$params['page'];
            $limit = (int)$params['limit'];
            $usersQuery->limit($limit)->offset($limit * ($page - 1));
        }

        if (isset($params['fields'])) { // select
            $usersQuery->select(explode(',', trim($params['fields'], ',')));
        }

        if ($users = $usersQuery->asArray()->all()) {

            foreach ($users as $id => $user) {
                foreach ($this->disallow_fields as $disallow_field) {
                    unset($users[$id][$disallow_field]);
                }
                /*foreach ($this->allow_fields as $id=>$allow_field) {
                    if(!in_array($allow_field,$user)) unset($users[$id][$allow_field]);
                }*/
            }

            return $this->response(['result' => $users, 'error' => null], 200);
        }
        return $this->response(['error' => 'API. ' . 'Data not found'], 404);
    }

    /**
     * Поиск клиента по id или по телефону - вернуть или айди, или true/false если наш клиент и верифицирован (kyc->status = 1)
     * и если у клиента нет задолжености (kyc->delay = 0)
     */
    public function actionView()
    {
        return $this->response(['error' => 'API. ' . 'User error'], 404);
        $params = Yii::$app->request->get();

        //$params = Yii::$app->request->post();
        /* $params = file_get_contents('php://input');
         $params = json_decode($params,true);
         var_dump($params); exit;*/

        $id = isset($params['id']) ? (int)$params['id'] : null;
        $phone = isset($params['phone']) ? (int)$params['phone'] : null;

        $userQuery = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_CLIENT]);

        if (isset($params['fields'])) { // select
            $userQuery->select(explode(',', trim($params['fields'], ',')));
        }
        if ($id) {
            if ($user = $userQuery->andWhere(['id' => $id])->asArray()->one()) {
                return $this->response(['result' => $user, 'error' => null], 200);
            }
        }
        if ($phone) {
            if ($user = $userQuery->andWhere(['phone' => $phone])->one()) {
                if ($user->kyc->status == 1 && $user->kyc->delay == 0) {
                    return $this->response(['result' => $user->id, 'error' => null], 200); // возвращает айди клиента в нашей системе
                } else {
                    return $this->response(['error' => 'API. ' . Yii::t('app', 'Клиент не верифицирован!')], 404);
                }

            }
        }
        return $this->response(['error' => 'API. ' . 'User not found'], 404);
    }

    /**
     * Поиск клиента по id или по телефону - вернуть или айди, или true/false если наш клиент и верифицирован (kyc->status = 1)
     * и если у клиента нет задолжености (kyc->delay = 0)
     */
    public function actionVerification()
    {
        $params = Yii::$app->request->get();

        $id = isset($params['id']) ? (int)$params['id'] : null;
        $phone = isset($params['phone']) ? (int)$params['phone'] : null;

        $userQuery = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_CLIENT]);

        if ($id) {
            if ($user = $userQuery->andWhere(['id' => $id])->one()) {
                if ($user->kyc->status == 1 && $user->kyc->delay == 0) {
                    return $this->response(['status' => 1, 'result' => 'Клиент ' . $user->username . ' ' . $user->lastname . ' верифицирован!', 'ID' => $user->id, 'error' => null], 200); // возвращает айди клиента в нашей системе
                } else {
                    return $this->response(['status' => 2, 'error' => 'API. ' . Yii::t('app', 'Клиент ' . $user->username . ' ' . $user->lastname . ' не верифицирован!')], 404);
                }
            }
        }
        if ($phone) {
            if ($user = $userQuery->andWhere(['phone' => $phone])->one()) {
                if ($user->kyc->status == 1 && $user->kyc->delay == 0) {
                    return $this->response(['status' => 1, 'result' => 'Клиент ' . $user->username . ' ' . $user->lastname . ' верифицирован!', 'ID' => $user->id, 'error' => null], 200); // возвращает айди клиента в нашей системе
                } else {
                    return $this->response(['status' => 2, 'error' => 'API. ' . Yii::t('app', 'Клиент ' . $user->username . ' ' . $user->lastname . ' не верифицирован!')], 404);
                }

            }
            return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app',  'Клиент с номером телефона ' . $phone . ' не найден!')], 404);
        }
        return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app', $phone . ' not found!')], 404);
    }

    /**
     * Создание нового клиента
     */
    public function actionCreate()
    {

        $post = Yii::$app->request->post();
        print_r($post);exit;
        $user = new User();

        $user->created_at = time();
         $user->updated_at = time();
         $user->username = $post['username'];
         $user->lastname = $post['lastname'];
         $user->patronymic = $post['patronymic'];
         $user->passport_serial = $post['passport_serial'];
         $user->phone = $post['phone'];
         $user->status = 1;
         $user->role = User::ROLE_CLIENT;
         $user->phone_confirm = 0;

         if( $user->updateModel() ){ // здесь сохраняются сканы паспорта
             return $this->response($user, 200);
         }

        return $this->response(['error' => 'API. ' . 'User saving error'], 404);
    }

    /**
     * Обновление отдельной записи (по ее id)
     */
    public function actionUpdate()
    {
        return $this->response(['error' => 'API. ' . 'Update error'], 404);
    }

    /**
     * Удаление отдельной записи (по ее id)
     */
    public function actionDelete()
    {
        return $this->response(['error' => 'API. ' . 'Delete error'], 404);
    }


    /**
     * post запрос с get параметрами и post данными товаров
     * оформление кредита
     */

    public function actionAddCredit()
    {

        // телефон должен быть вендора, id должен быть клиента
        // 1 получение списка товаров с кол-вом и ценами для клиента [quantity] [amount] [title]
        // 2 получение крайнего срока кредита (дата погашения) [credit_date]
        // 3 срок кредита в мес, если расчет нет из даты погашения [credit_limit]
        // 3 сумма взноса [deposit_first] - или 0, если не указана
        // 4 расчет или получение суммы ежемесячной оплаты [deposit_month]
        // 5 расчет или получение суммы кредита [credit]
        // 6 НДС в % [nds]

        $api_user = Yii::$app->user->identity->id; //апи пользователь

        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $user_id = (int)$get['user_id'];
        $phone = (int)$get['phone'];
        $vendor_id = (int)$get['vendor_id'];
        $credit_limit = (int)$get['credit_limit'];

        // массив товаров в формате json
        $post = file_get_contents('php://input');
        $products = json_decode($post, true);



        // проверка на обязательные поля
        if (!isset($get['credit_date'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Дата погашения кредита [credit_date] не указана!')], 404);
        }
        if (!isset($get['credit_limit'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Не указан срок кредита в месяцах [credit_limit]!')], 404);
        }
        if ($credit_limit == 0) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Срок кредита должен быть 3 или 6 мес  [credit_limit]!')], 404);
        }
        /*if(!isset($get['deposit_first']) ){
            return $this->response(['error'=>'API. ' . Yii::t('app','Сумма первоначального взноса кредита [deposit_first] не указана!')],404);
        }*/
        if (!isset($get['user_id'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'ID пользователя не задан [user_id]!')], 404);
        }
        if (!isset($get['phone'])) {
            if(!isset($get['vendor_id']))
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Телефон или ID вендора не задан [phone] или [ID]!')], 404);
        }

        if (!isset($products)) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Товар(ы) не указан(ы)!')], 404);
        }

        // находим клиента
        if (!$user = User::findIdentity($user_id)) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Клиент не найден!')], 404);
        }
        // находим вендора
        if ($phone && $vendor = User::findVendorByPhone($phone)) {
        }else{
            if($vendor_id && !$vendor = User::findIdentity($vendor_id)){
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Партнер не найден!')], 404);

            }
        }




        $deposit_first = $get['deposit_first'] ? $get['deposit_first'] : 0;  // Сумма первоначального взноса кредита
        $discount = $vendor->discount; // скидка от магазина
        $count = 0;
        $sum = 0;
        $zmarket_price = [];
        $_discount_sum = [];
        $vend_nds_price = [];
        $vend_nds = [];


        if ($get['credit_limit'] == 3) {
            $nds_default = $vendor->margin_three ? $vendor->margin_three : 25;
        } else {
            $nds_default = $vendor->margin_six ? $vendor->margin_six : 35;
        }

        $vendor_nds = $vendor->nds_state != 0 ? $vendor->nds_state : 0;
        $nds = $vendor_nds == 0 ? 15 : 0;
        $nds = $nds != 0 ? (100 + $nds_default) * (100 + $nds) / 10000 : (100 + $nds_default) / 100;
        $v_nd = 1.15;

        foreach ($products as $product) {
            $discount_s = 0;
            $discount_sum = 0;
            $discount_s += $discount * $product['amount'] / 100;
            $discount_sum += $product['amount'] - $discount_s;
            $item = 0;
            $count += $product['quantity'];
            $item += $product['quantity'] * $discount_sum; // сумма товара со скидкой магазина
            $sum += $item; // сумма всех товаров со скидкой
            $item *= $nds; // price стоимость от ZMarket
            $item_nds = ceil($item / $v_nd); // стоимость товара без учета ндс вендора
            $zmarket_price[] = $item;
            $vend_nds_price[] = $item_nds;  // стоимость товара без учета ндс вендора
            $vend_nds[] = $item - $item_nds;  // ндс сумма от цены вендора
            $_discount_sum[] = $discount_sum;
        }

        // проверка чтобы нельзя было создать кредит на сумму большую чем лимил рассрочки
        if ($credit_amount = Credits::find()->select('SUM(credit_items.amount) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user_id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
            $res = $credit_amount->payment_sum ? $credit_amount->payment_sum : 0;
            $rest_credit_year = $user->kyc->credit_year - $res;
        }

        if($rest_credit_year - $sum < 0 ) {
            //UtilsHelper::debug('api_user:'. $this->user);
            return $this->response(['status' => 0, 'error' => Yii::t('app', 'Сумма товаров превышает допустимые ' . $rest_credit_year . ' сумов ! ' )]);
        }

        // учет НДС - 15% к стоимости
        $sum *= $nds; // общая сумма кредита (всех товаров)

        // расчет ежемесячного взноса с вычетом депозита
        $sum_month = (int)((($sum - $deposit_first) / $get['credit_limit']) * 100) / 100; // ежемесячный взнос

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $credit = new Credits();
        $credit->user_id = $user_id; // ID пользователя (вначале получают  get_client())
        //$credit->supplier_id = $this->user->id; // id вендора
        $credit->supplier_id = $vendor->id; // id вендора
        $credit->deposit_first = $deposit_first; // сумма первоначального взноса кредита или ноль
        $credit->deposit_month = $sum_month; // ежемесячный взнос
        $credit->price = $sum; // общая сумма кредита
        $credit->credit = $sum; // общая сумма кредита
        $credit->nds = $vendor_nds; // вендор ндсник или нет
        $credit->credit_limit = $credit_limit; // срок кредита 3 или 6 месяцев
        $credit->api_user = $api_user; //апи пользователь
        $credit->created_at = time();

        if (!$credit->save()) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Ошибка при создании кредита!') . json_encode($credit->getErrors(), JSON_UNESCAPED_UNICODE)], 404);
        }

        // товары кредита
        $price = 0;
        //$clear_price = 0;
        $quantity = 0;
        $i = 0;
        foreach ($products as $product) {
            $credit_items = new CreditItems();
            $price += $product['price'];
            //$clear_price += $product['amount'];
            $quantity += $product['quantity'];
            $credit_items->title = $product['title'];
            $credit_items->price = $zmarket_price[$i]; // цена ZMarket
            $credit_items->amount = $product['amount']; // цена от продавца
            $credit_items->discount_sum = $_discount_sum[$i]; // стоимость товара со скидкой на момент заключения договора
            $credit_items->quantity = $product['quantity'];
            $credit_items->credit_id = $credit->id;

            if (!$credit_items->save()) {
                $credit->delete();
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Ошибка при создании списка товаров кредита!')], 404);
            }
            $i++;
        }

        $credit->quantity = $quantity;

        $d = date('d', time());
        $m = date('m', time());
        $y = date('Y', time());

        $credit->date_start = strtotime($d . '.' . $m . '.' . $y . ' 00:00:00');

        $m2 = $m + $credit->credit_limit; // месяц погашения кредита ??
        $y2 = $y;
        if ($m2 > 12) {
            $m2 = $m2 - 12;
            $y2++;
        }
        $credit->credit_date = strtotime($d . '.' . $m2 . '.' . $y2 . ' 00:00:00');

        $credit->user_confirm = 0; // НЕ подтвержден клиентом - подтверждается после получения смс кода от клиента
        $credit->confirm = 0; // НЕ подтвержден поставщиком - подтверждается после получения смс кода от клиента

        $code = SmsHelper::generateCode(4);
        UtilsHelper::debug(' sms-code:'.$code);
        $credit->code_confirm = $code;
        Yii::$app->session->set('user_sms_credit', $code);
        Yii::$app->session->set('credit_id', $credit->id);



        if (!$credit->save()) {
            CreditItems::deleteAll(['credit_id' => $credit->id]);
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Ошибка при создании кредита!')], 404);

        }

        // создание план графика оплат на весь срок оплаты

        $cnt = $credit->credit_limit;
        $m++;
        for ($n = 0; $n < $cnt; $n++) {
            if ($m > 12) {
                $m = 1;
                $y++;
            }
            $credit_history = new CreditHistory();
            $credit_history->credit_id = $credit->id;
            $credit_history->delay = 0;                   // задержка
            $credit_history->credit_date = strtotime($d . '.' . $m . '.' . $y . ' 00:00:00');
            $credit_history->payment_status = 0;
            $credit_history->payment_type = 0;
            // $credit_history->price = $sum_month;
            if ($n + 1 == $cnt) { // учет копеек
                $credit_history->price = $credit->deposit_month + ($credit->price - $credit->deposit_first - $cnt * $credit->deposit_month);
            } else {
                $credit_history->price = $credit->deposit_month;
            }
            if (!$credit_history->save()) {
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Ошибка при создании план графика оплат!')], 404);
            }
            $m++;
        }

        // оформление договора ДО смс подтверждения поставщика
        $contract = new Contracts();
        $contract->created_at = time();
        $contract->credit_id = $credit->id;
        $contract->user_id = $user_id; // id клиента
        $contract->supplier_id = $vendor->id; // id вендора
        $contract->date_start = $credit->date_start;
        $contract->date_end = $credit->credit_date;
        $contract->status = 0;
        if (!$contract->save()) {
            return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при создании договора! ' . json_encode($contract->getErrors(), 256))]);
        }

        $credit->contract_id = $contract->id;
        $credit->save();
        $find = false;
        /*if(isset($get['user_id'])){
            if( $user = User::find()->select('phone')->where(['id'=>$get['user_id']])->one()) $find = true;
        } */
        if (isset($user_id)) {
            if ($user = User::find()/*->select('phone')*/ ->where(['id' => $user_id])->one()) $find = true;
        }
        /*if(!$find && isset($get['user_phone'])){
            if( $user = User::find()->select('phone')->where(['phone'=>$get['user_phone']])->one()) $find = true;
        }*/


        if ($find) {

            $phone = $user->phone;

            $msg_month = Yii::t('app', 'мес.');
            $msg_sum = Yii::t('app', 'сум');
            $msg_credit_sum = Yii::t('app', 'Сумма договора');
            $msg_count = Yii::t('app', 'Количество товаров');
            $msg_credit_limit = Yii::t('app', 'Срок погашения');
            $msg_deposit_first = Yii::t('app', 'Первоначальный взнос');
            $msg_deposit_month = Yii::t('app', 'Ежемесячный взнос');

            // сообщение для смс
            $credit_info = $msg_credit_sum . ': ' . $credit->price . " {$msg_sum} ";
            $credit_info .= $msg_count . ': ' . $quantity . ' ';
            $credit_info .= $msg_credit_limit . ': ' . $credit->credit_limit . " {$msg_month} ";
            $credit_info .= $msg_deposit_first . ': ' . $credit->deposit_first . " {$msg_sum} ";
            $credit_info .= $msg_deposit_month . ': ' . $credit->deposit_month . " {$msg_sum} ";
            $path = '/get-offer?id=' . $credit->id;
            $link = 'http://' . $_SERVER['SERVER_NAME'] . $path;
            $text = Yii::t('app','Здравствуйте Ув. Пользователь! Вас приветствует платформа zMarket. 
            Публичная оферта ' . $link . ' . Ваш код подтверждения ' . $code . '. ' . $credit_info . ' Платформа zMarket благодарит Вас за покупку!');




            $credit_items = CreditItems::find()->select(['quantity', 'title', 'price'])->where(['credit_id' => $credit->id])->asArray()->All();
            /////////////график выплат///////////////


            $d = date('d', $credit->date_start);
            $m = date('m', $credit->date_start);
            $y = date('Y', $credit->date_start);

            $plan_graph = [];
            $credit_sum = $credit->price;
            for ($i = 0; $i < $credit->credit_limit; $i++) {
                $credit_sum -= $credit->deposit_month;

                if ($credit_sum <= 0.9) $credit_sum = 0.0;
                $m++;
                if ($m > 12) {
                    $m = $m - 12;
                    $y++;
                }

                //$credit_sum = number_format($credit_sum, 2, '.', ' ');
                $date = $d . '-' . $m . '-' . $y;
                $plan_graph[] = ['credit_sum' => $credit_sum, 'deposit_month' => $credit->deposit_month, 'date' => $date];

            }
            // рассчитать сумму оставшегося лимита кредита
            $res = 0;
            if ($credit_amount = Credits::find()->select('SUM(credit_items.amount * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user->id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                $res = $credit_amount->payment_sum;
            }

            $zmarket_sum_credit = $user->kyc->credit_year - $res; // сумма оставшегося кредита


            if($phone) {
                //Uzcard::sendOrder($credit, $user); // не надо?
                SmsHelper::sendSms($phone, Yii::t('app',$text ));
            }

            UtilsHelper::debug('create-credit'); // оформление договора
            UtilsHelper::debug('Add-credit.send-user-sms. sms-phone:'.$phone);
            UtilsHelper::debug($credit_info);

            /*
             * $vend_nds_price[]  - стоимость товара без учета ндс вендора
             * $vend_nds[]  - ндс сумма от цены вендора
            */

            return $this->response(['user' => [
                'user_id' => $user->id,
                'username' => $user->username,
                'passport_id' => $user->passport_id,
                'passport_serial' => $user->passport_serial,
                'address' => $user->address,
                'phone' => $user->phone,
                'contract_id' => $contract->id, // номер
                'credit_id' => $credit->id,
                'created_at' => $credit->created_at,
                'deposit_month' => $credit->deposit_month,
                'sum' => $credit->price,
                'company' => $vendor->company,
                'zmarket_sum_credit' => $zmarket_sum_credit,

            ],
                'products' => $credit_items,
                'plan_graph' => $plan_graph,
                'vendor_nds_price' => $vend_nds_price,
                'vendor_nds' => $vend_nds,
                'error' => null], 200);

        }

        return $this->response(['error' => 'API. ' . Yii::t('app', 'Клиент не найдет!')], 404);

    }

    /**
     * проверка смс клиента
     */

    public function actionCheckUserSms()
    {

        $post = Yii::$app->request->post();
        $get = Yii::$app->request->get();

        if (!isset($get['user_id'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'ID пользователя не задан [user_id]!')], 404);
        }
        if (!isset($get['credit_id'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Credit_id пользователя не задан [credit_id]!')], 404);
        }

        $user_id = $post['user_id'] ? $post['user_id'] : $get['user_id'];
        $credit_id = $post['credit_id'] ? $post['credit_id'] : $get['credit_id'];
        $code = $post['code'] ? $post['code'] : $get['code'];

        if ($credit = Credits::findOne($credit_id))
            $user_sms_code = $credit->code_confirm;

        $contract = Contracts::find()->where(['credit_id' => $credit_id])->one();

        // смс оповещение клиента с кодом подтверждения кредита
        if ($user_sms_code == $code || $_SERVER['SERVER_NAME'] == 'crm1.loc') {

            $res = 0;
            /*if ($credit_amount = Credits::find()->select('SUM(credit_items.amount * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user_id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                $res = $credit_amount->payment_sum;
            }

            if ($user = User::findIdentity($user_id)) {
                if ($user->kyc->credit_year - $res < 0) {
                    $credit_amount->delete();
                    return $this->response(['status' => 0, 'error' => Yii::t('app', 'Недостаточный годовой лимит для оформления договора! ' . $user->kyc->credit_year . ' Общая сумма взятых кредитов: ' . $res)]);
                }
            }*/
            if ($user = User::findIdentity($user_id)) {
                if ($credit_amount = Credits::find()->select('SUM(credit_items.amount * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user_id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                    $res = $credit_amount->payment_sum ? $credit_amount->payment_sum : 0;
                    $rest_credit_year = $user->kyc->credit_year - $res;

                }


                if ($rest_credit_year - $credit->price < 0) {
                    return $this->response(['status' => 0, 'error' => Yii::t('app', 'Недостаточный годовой лимит( ' . $rest_credit_year . ' сумов ) для оформления договора! Общая сумма взятых кредитов: ' . $res . ' сумов')]);

                }
            }else{
                return $this->response(['status' => 0, 'user_id' => $user_id, 'error' => Yii::t('app', 'Клиент не найден! ')]);
            }

            $credit->user_confirm = 1;
            $credit->confirm = 1;
            $credit->save();


            // отправка договора на страхование
            /*$result = PolisHelper::getPolisForCredit('zMarket_' . $contract->id, $credit);
            $result = PolisHelper::сheckTransaction('zMarket_' . $contract->id, $credit);


            if (isset($result['original']) && isset($result['original']['contractRegistrationID'])) {
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
                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(), JSON_UNESCAPED_UNICODE))]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
                }

                $contract->status_polis = 1;
                $contract->save();

            } else {

                return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при отправке договора в страховую компанию.') . json_encode($result, JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE);

            }*/


            // return json_encode(['status' => 1], JSON_UNESCAPED_UNICODE);
            return $this->response(['status' => 1, 'credit_id' => $credit->id, 'contract_id' => $contract->id, 'message' => Yii::t('app', 'Договор успешно подтвержден! '), 'error' => null], 200);
        }
        //return json_encode(['status'=>0,'info'=>''],JSON_UNESCAPED_UNICODE);
        return $this->response(['status' => 0, 'credit_id' => $credit->id, 'contract_id' => $contract->id, 'error' => Yii::t('app', 'Договор не подтвержден! ')]);
    }

    /**
     * калькулятор суммы для асохий
     */
    public function actionCalculatePrice()
    {
        $post = Yii::$app->request->post();
        $get = Yii::$app->request->get();
        $vendor_id = (int)$get['vendor_id'];
        $phone = (int)$get['phone'];

        if (!isset($get['sum'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Сумма не задана [sum]!')], 404);
        }
        if (!isset($get['credit_limit'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Период не задан [credit_limit]!')], 404);
        }

        $sum = $get['sum'];
        $credit_limit = $get['credit_limit'];

        if ($sum <= 0) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Сумма должна быть больше 0 [sum]!')], 404);
        } elseif ($sum > 3000000) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Сумма не может превышать 3000000 [sum]!')], 404);
        }

        if ($credit_limit != 3) {
            if ($credit_limit != 6)
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Период должен быть 3 или 6 месяцев [credit_limit]!')], 404);
        }

        // находим вендора
        if ($phone && $vendor = User::findVendorByPhone($phone)) {
        }else{
            if($vendor_id && !$vendor = User::findIdentity($vendor_id)){
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Партнер не найден!')], 404);

            }
        }

        if ($sum) {
            if ($get['credit_limit'] == 3) {
                $nds_default = $vendor->margin_three ? $vendor->margin_three : 25;
            } else {
                $nds_default = $vendor->margin_six ? $vendor->margin_six : 35;
            }


            /*if ($credit_limit == 3) {
                $nds_default = 25;
            } else {
                $nds_default = 35;
            }*/
            //$nds = 15;
            //$price = $sum * (100 + $nds_default)*(100 + $nds)/10000; // если не ндс плательщик
            //$price = $sum * (100 + $nds_default) / 100; // ндс плательщик
            $vendor_nds = $vendor->nds_state != 0 ? $vendor->nds_state : 0;
            $nds = $vendor_nds == 0 ? 15 : 0;
            $nds = $nds != 0 ? (100 + $nds_default) * (100 + $nds) / 10000 : (100 + $nds_default) / 100;
            $price = $sum * $nds;
            return $this->response([
                'result' => [
                    'status' => 1,
                    'Zmarket_price' => $price,
                    'credit_limit' => $credit_limit
                ], 'error' => null], 200);

        }
        return $this->response(['result' => ['status' => 0], 'error' => 'API. ' . Yii::t('app', 'Сумма не расчитана!')], 404);

    }


    /**
     * получение статуса кредита
     */
    public function actionGetCreditStatus()
    {
        $id = (int)Yii::$app->request->get('id');
        // только свой кредит
        if ($credit = Credits::find()->where(['id' => $id, 'supplier_id' => $this->user->id])->one()) {
            return $this->response([
                'result' => [
                    'credit_id' => $credit->id, // id кредита
                    'user_confirm' => $credit->user_confirm // статус клиента
                ], 'error' => null], 200);

        }
        return $this->response(['error' => 'API. ' . Yii::t('app', 'Кредит не найдет!')], 404);

    }

    // подтверждение кредита поставщиком
    public function actionConfirmCredit()
    {

        $credit_id = (int)Yii::$app->request->get('credit_id');
        // только свой кредит
        if ($credit = Credits::find()->where(['id' => $credit_id, 'supplier_id' => $this->user->id, 'user_confirm' => 1])->one()) {

            $credit->confirm = 1; // поставщик подтверждает кредит
            $credit->confirm_date = time();
            $credit->save();

            return $this->response([
                'result' => [],
                'error' => null], 200);

        }
        return $this->response(['error' => 'API. ' . Yii::t('app', 'Подтвержденный кредит не найден!')], 404);
    }


}
