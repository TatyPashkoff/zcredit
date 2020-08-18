<?php

namespace app\modules\clients\controllers;

use common\helpers\HumoHelper;
use common\helpers\SmsHelper;
use common\helpers\UpayHelper;
use common\helpers\UtilsHelper;
use common\models\BillingHistory;
use common\models\BillingPayments;
use common\models\BillingServices;
use common\models\CardsAdd;
use common\models\Contracts;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Humo;
use common\models\HumoPayments;
use common\models\Kyc;
use common\models\Notify;
use common\models\Payment;
use common\models\Scoring;
use common\models\Services;
use common\models\StockItems;
use common\models\SuppliersSettings;
use common\models\User;
use common\models\Uzcard;
use common\models\UzcardPayments;
use Paycom\Payments;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class DefaultController extends BaseController
{

    public function actionIndex()
    {


        if (!$kyc = Kyc::find()->where(['client_id' => $this->user->id])->one()) {
            $kyc = new Kyc();
        }

        return $this->render('index', [
            'user' => $this->user,
            'model' => Yii::$app->user->identity,
            'model_kyc' => $kyc,
        ]);

    }


    public function actionContracts()
    {

        // Договора, которые клиент подтвердил смс кодом
        if ($contractsQuery = Contracts::find()->with('credit')->where(['user_id' => $this->user->id])->orderBy('created_at DESC')) {

            $pagination = new Pagination([
                'totalCount' => $contractsQuery->count(),
                'pageSize' => Contracts::ITEMS_COUNT,
                'pageSizeParam' => false,
            ]);

            if (!$contracts = $contractsQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all()) {
                $contracts = false;
            }

        } else {
            $contracts = false;
            $pagination = false;
        }

        return $this->render('contracts', [
            'contracts' => $contracts,
            'user' => $this->user,
            'pagination' => $pagination,
        ]);
    }


    public function actionCreditHistory()
    {

        if ($creditsQuery = Credits::find()->with(['payments', 'supplier'])->where(['user_id' => $this->user->id, 'user_confirm' => 1])->orderBy('created_at DESC')) {

            $pagination = new Pagination([
                'totalCount' => $creditsQuery->count(),
                'pageSize' => 1, //Credits::ITEMS_COUNT,
                'pageSizeParam' => false,
            ]);


            if (!$credits = $creditsQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all()) {
                $credits = false;
            }

        } else {
            $credits = false;
            $pagination = false;
        }


        return $this->render('credit-history', [
            'credits' => $credits,
            'pagination' => $pagination,


        ]);

    }

    public function actionCashback()
    {

        return $this->render('cashback', [
        ]);

    }

    public function actionCards()
    {

        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if ($user = User::find()->with('scoring')->where(['id' => $user_id])->one()) {
                // $card = $user->scoring->pan ? $user->scoring->pan : '9860'. $user->bank_c . $user->scoring->card_h;
            }


        }

        return $this->render('cards', [
            'model' => $this->user,

        ]);

    }

    public function actionZpayServices()
    {
        $user_id = Yii::$app->user->identity->id;  // убрать
        if (!$user = User::find()->where(['id' => $user_id])->one()) {

        }

        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->with('scoring')->where(['id' => $user_id])->one()) {
                return $this->redirect('/login');
            }
        }

        $get = Yii::$app->request->get();
        if (isset($get['id'])) {
            $service_id = $get['id'];
            $tel = UpayHelper::isTel($service_id);
            //$img = UpayHelper::getSrc($service_id);
            $img = Services::find()->where(['status' => 1, 'service_id' => $service_id])->one();
        }

        $post = Yii::$app->request->post();

        if ($post)
            //return json_encode(['status' => 1, 'post' => $post]);
            if (isset($post['account']) && isset($post['sum'])) {
                $account = $post['account'];
                $service_id = $post['service_id'];
                $amount = $post['sum'] * 100;
                $result = UpayHelper::BankPayment($service_id, $account, $amount);

                if ($result->return->Result->code == 'OK') {
                    $user->cashback -= $amount / 100;
                    $user->save();

                    $billing_services = new BillingServices();
                    $billing_services->user_id = $user_id;
                    $billing_services->created_at = time();
                    $billing_services->amount = $amount / 100;
                    $billing_services->service_type = $service_id;
                    $billing_services->status = 1;
                    $billing_services->save();
                    return json_encode(['result' => $result]);
                }

            }
        $services = Services::find()->where(['status' => 1])->all();

        return $this->render('zpay-services', [
            'model' => $this->user,
            'services' => $services,
            'service_id' => $service_id,
            'img' => $img,
            'tel' => $tel,
        ]);

    }

    public function actionZpayFinish()
    {
        $services = Services::find()->where(['status' => 1])->all();
        return $this->render('zpay-finish', [
            'model' => $this->user,
            'services' => $services,
        ]);

    }

    public function actionZpay()
    {
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                return $this->redirect('/login');
            }
        }
        $post = Yii::$app->request->post();
        //print_r($post);exit;
        if (isset($post['dropdown']) && isset($post['number'])) {
            $account = $post['dropdown'] . $post['number'];
            Yii::$app->session->set('account', $account);
            $result = UpayHelper::getSum($account);
            //print_r($result['fullname']);exit;
            $sum = $result['sum'];
            $fullname = $result['fullname'];
            $info = $result['info'];
            Yii::$app->session->set('sum', $sum);
            Yii::$app->session->set('fullname', $fullname);
            Yii::$app->session->set('info', $info);
            return $this->redirect('zpay-fine');
        }

        $services = Services::find()->where(['status' => 1])->all();

        return $this->render('zpay', [
            'model' => $this->user,
            'services' => $services,
        ]);

    }

    // штраф Губдд
    public function actionZpayFine()
    {
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {

            }
        }
        $p = 0.14;
        $sum = 0;
        $upay_sum = 0;
        $zmarket_sum = 0;
        $fullname = ' ';
        $info = ' ';
        $account = $sum = Yii::$app->session->get('account');
        Yii::$app->session->remove('account');

        if (Yii::$app->session->has('fullname')) {
            $sum = Yii::$app->session->get('sum');
            $fullname = Yii::$app->session->get('fullname');
            $info = Yii::$app->session->get('info');
            Yii::$app->session->remove('sum');
            Yii::$app->session->remove('fullname');
            Yii::$app->session->remove('info');
            if (is_numeric($sum)) {
                $upay_sum = $sum + ($sum * 0.01);
                $zmarket_sum = $upay_sum + ($upay_sum * $p);
            }

        }
        // получаем сумму штрафа
        if (isset($post['dropdown']) && isset($post['number'])) {
            $account = $post['dropdown'] . $post['number'];
            Yii::$app->session->set('account', $account);
            $sum = UpayHelper::getSum($account);
            Yii::$app->session->set('sum', $sum);

            // return $this->redirect('zpay-fine');
        }

        $services = Services::find()->where(['status' => 1])->all();

        return $this->render('zpay-fine', [
            'model' => $this->user,
            'account' => $account,
            'sum' => $sum,
            'upay_sum' => $upay_sum,
            'zmarket_sum' => $zmarket_sum,
            'fullname' => $fullname,
            'info' => $info,
            'services' => $services,

        ]);

    }

    // подтверждение смс кода
    public function actionUpaySms()
    {
        $user_id = Yii::$app->user->identity->id;  // убрать
        if (!$user = User::find()->where(['id' => $user_id])->one()) {

        }

        if ($user->phone) {
            $code = SmsHelper::generateCode(4);
            UtilsHelper::debug(' sms-phone:' . $user->phone . '. upay_code:' . $code);
            Yii::$app->session->set('upay_code', $code);
            $text = Yii::t('app', 'Вас приветствует платформа zMarket. Ваш код подтверждения ' . $code);
            SmsHelper::sendSms($user->phone, Yii::t('app', $text));
            UtilsHelper::debug('upay-fine ' . $text);
        }
        // проверка смс кода
        if (isset($post['code']) && isset($post['user_id'])) {
            $code = Yii::$app->session->has('upay_code') ? Yii::$app->session->get('upay_code') : $this->uniqueId;
            if ($post['code'] == $code) {
                return json_encode(['status' => 1]);
                //return $this->redirect('zpay-fine');
            } else {
                return json_encode(['status' => 0]);
            }

        }
    }

    // оплатить штраф Губдд
    public function actionUpayConfirm()
    {
        $user_id = Yii::$app->user->identity->id; // убрать
        if ($user = User::find()->where(['id' => $user_id])->one()) {

        }
        // привязка к сессии
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                return $this->redirect('/login');
            }
        }
        $service_id = UpayHelper::FINE_TRAFFIC_POLICE;
        $cnt = 3; // credit_limit
        $p = 0.14;
        $post = Yii::$app->request->post();
        if (isset($post['account']) && isset($post['sum'])) {
            $account = htmlspecialchars($post['account']);
            $sum = htmlspecialchars($post['sum']);
            $upay_sum = $sum + ($sum * 0.01); // эту сумму отправляем в upay
            $amount = $upay_sum * 100; // в тиинах


            //return json_encode(['status' => 1, 'result' => $service_id . ' - ' . $amount]);
            $result = UpayHelper::BankPayment($service_id, $account, $amount);
            return json_encode(['result' => $result]);
            // если успешный платеж
            if ($result['status'] == 1) {


                $zmarket_sum = $upay_sum + ($upay_sum * $p); // цена zmarket
                // расчет ежемесячного взноса с вычетом депозита
                $sum_month = round((($zmarket_sum) / $cnt) * 100) / 100; // ежемесячный взнос
                //return json_encode(['status' => 1, 'sum_month' => $sum_month], JSON_UNESCAPED_UNICODE);


                //получаем последний prefix_act
                $credits = Credits::find()->where(['not', ['prefix_act' => null]])->orderBy(['id' => SORT_DESC])->one();
                $prefix_act_last = $credits->prefix_act ? $credits->prefix_act : 24;
                $prefix_act_last = $prefix_act_last + 1;

                // создаем credit
                $credit = new Credits();

                $d = date('d', time());
                $m = date('m', time());
                $y = date('Y', time());

                $credit->created_at = time();
                $credit->date_start = strtotime($d . '.' . $m . '.' . $y . ' 00:00:00');
                $credit->user_id = $user_id;
                $credit->credit_limit = $cnt;
                $credit->deposit_month = $sum_month;
                $credit->price = $zmarket_sum;
                $credit->credit = $zmarket_sum;
                $credit->confirm = 1;
                $credit->user_confirm = 1;
                $credit->service_type = 1; // услуги
                $credit->prefix_act = $prefix_act_last;

                $m2 = $m + $cnt; // оплата со следующего месяца
                $y2 = $y;

                while ($m2 > 12) {
                    $m2 -= 12;
                    $y2++;
                }

                $credit->credit_date = strtotime($d . '.' . $m2 . '.' . $y2 . ' 00:00:00');

                $s = Credits::getPaymentSumAll($user_id);

                if ($user->kyc->credit_year - $s - $upay_sum < 0) {
                    return json_encode(['status' => 0, 'error' => Yii::t('app', 'Недостаточный годовой лимит для оформления договора! ' . $user->kyc->credit_year . ' Общая сумма взятых кредитов: ' . $s)]);
                }

                if (!$credit->save()) {
                    return json_encode(['status' => 0, 'error' => Yii::t('app', 'Ошибка при создании кредита!')]);
                }


                // товары кредита
                $price = 0;
                $clear_price = 0;
                $quantity = 0;
                UtilsHelper::debug('create-credit');
                UtilsHelper::debug($post);

                $credit_items = new CreditItems();
                $credit_items->title = $account;
                $credit_items->price = $zmarket_sum;
                $credit_items->amount = $upay_sum;
                $credit_items->discount_sum = $upay_sum;
                $credit_items->quantity = 1;
                $credit_items->credit_id = $credit->id;

                if (!$credit_items->save()) {
                    $credit->delete();
                    return json_encode(['status' => 0, 'error' => Yii::t('app', 'Ошибка при создании списка товаров кредита!')]);
                }
                // создание план графика оплат на весь срок оплаты
                $m++;
                for ($n = 0; $n < $cnt; $n++) {
                    if ($m > 12) {
                        $m = 1;
                        $y++;
                    }
                    $credit_history = new CreditHistory();
                    $credit_history->credit_id = $credit->id;
                    $credit_history->delay = 0;
                    $credit_history->credit_date = strtotime($d . '.' . $m . '.' . $y . ' 00:00:00');
                    $credit_history->payment_status = 0;
                    $credit_history->payment_type = 0;
                    if ($n + 1 == $cnt) { // учет копеек
                        $credit_history->price = $credit->deposit_month + ($credit->price - $credit->deposit_first - $cnt * $credit->deposit_month);
                    } else {
                        $credit_history->price = $credit->deposit_month;
                    }
                    if (!$credit_history->save()) {
                        $credit->delete();
                        return json_encode(['status' => 0, 'error' => Yii::t('app', 'Ошибка при создании план графика оплат!')]);

                    }
                    $m++;
                }
                $contract = new Contracts();
                $contract->created_at = time();
                $contract->credit_id = $credit->id;
                $contract->user_id = $credit->user_id;
                $contract->date_start = $credit->date_start;
                $contract->date_end = $credit->credit_date;
                $contract->status = 0;
                if (!$contract->save()) {
                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при создании договора! ' . json_encode($contract->getErrors(), 256))]);

                }

            }

        }

        return json_encode(['status' => 1, 'zmarket_sum' => $zmarket_sum], JSON_UNESCAPED_UNICODE);


    }


    // настройка
    public function actionNotify()
    {


        return $this->render('notify', [
            'model' => $this->user,
        ]);

    }

    // получение уведомлений от пользователей
    public function actionGetNotify()
    {

        $post = Yii::$app->request->post();
        $id = isset($post['id']) ? (int)$post['id'] : 0;
        if ($notify = Notify::find()->where(['id' => $id])->one()) {
            return json_encode(['status' => 1, 'count' => rand(0, 12)]);
        }
        return json_encode(['status' => 1, 'count' => rand(0, 10)]);
    }

    // получение уведомлений от пользователей
    public function actionCreditConfirm()
    {

        $post = Yii::$app->request->post();
        $id = isset($post['id']) ? (int)$post['id'] : 0;
        $code = isset($post['code']) ? $post['code'] : 0;
        if ($credit = Credits::find()->where(['id' => $id])->one()) {

            if ($code == $credit->code_confirm) {
                $credit->code_confirm = '';
                $credit->user_confirm = 1;
                $credit->user_confirm_date = time();
                $credit->save();
                return json_encode(['status' => 1, 'info' => Yii::t('app', 'Подтверждение кредита успешно!')]);
            }
        }
        return json_encode(['status' => 0]);
    }


    // настройка
    public function actionSettings()
    {

        if (Yii::$app->session->has('user_id')) {

            $user_id = Yii::$app->session->get('user_id');

            if ($user = User::find()->where(['id' => $user_id])->one()) {

                if ($user->kyc->status == 1) {
                    return Yii::$app->getResponse()->redirect(array('/clients'));
                }

                if ($this->user->updateModel()) {
                    $post = Yii::$app->request->post();
                    Yii::$app->session->setFlash('info', 'Сохранение успешно!');
                    $complete = $user->checkComplete($user_id);
                    $user->status_client_complete = $complete;

                    if (isset($post['User']['phone_home'])) {
                        $user->phone_home = $post['User']['phone_home'];
                    }
                    if (isset($post['User']['region_id'])) {
                        $user->region_id = $post['User']['region_id'];
                    }
                    if (isset($post['User']['username'])) {
                        $user->username = $post['User']['username'];
                    }
                    if (isset($post['User']['lastname'])) {
                        $user->lastname = $post['User']['lastname'];
                    }
                    if (isset($post['User']['work_place'])) {
                        $user->work_place = $post['User']['work_place'];
                    }
                    if (isset($post['User']['permanent_address'])) {
                        $user->permanent_address = $post['User']['permanent_address'];
                    }

                    $user->save(false);
                    return $this->refresh();
                }
            }
        }

        /*if( $this->user->updateModel()){
            $this->user->save();

            Yii::$app->session->setFlash('info','Сохранение успешно!');

            return $this->refresh();

        }*/

        return $this->render('settings', [
            'model' => $this->user
        ]);

    }

    // дополнительные карты через кабинет клиента
    public function actionRegisterCard()
    {
        // если клиент не найден
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                return json_encode(['status' => 0, 'message' => 'Клиент не найден!'], JSON_UNESCAPED_UNICODE);
            }
        }

        $post = Yii::$app->request->post();

        if (isset($post['card']) && isset($post['exp'])) {

            $card = preg_replace('/[^0-9]/', '', $post['card']);
            $exp = preg_replace('/[^0-9]/', '', $post['exp']);

            $bank_c = mb_substr($card, 4, 2);
            $card_h = mb_substr($card, 6, 10);
            $exp_m = mb_substr($exp, 0, 2);
            $exp_y = mb_substr($exp, 2, 2);
            $exp = $exp_y . $exp_m;

            Yii::$app->session->set('bank_c', $bank_c);
            Yii::$app->session->set('card_h', $card_h);
            Yii::$app->session->set('card', $card); // используется в скоринге
            Yii::$app->session->set('exp', $exp);

            $cards = new CardsAdd();
            $cards->card = mb_substr($card, 10, 6);
            $cards->exp = $exp;
            $cards->created_at = time();
            $cards->user_id = $user_id;
            $cards->status = 0;

            if (preg_match('[^8600]', $card)) {
                $cards->type = 1;
            } else if (preg_match('[^9860]', $card)) {
                $cards->type = 2;
            } else {
                $cards->type = 0;
            }
            // print_r($user->auto_discard_type); exit;

            $type = $cards->type;
            /*if($user_id){
                $cards->save(false);
            }else{
                return json_encode(['status'=>0,'message' => 'Не удалось сохранить данные. Пожалуйста, авторизуйтесь на сайте!'],JSON_UNESCAPED_UNICODE);
            }*/

            $cards->save(false);

            Yii::$app->session->set('type', $type);

            if ($cards->type == 1) {
                $result = Scoring::sendOtp($user->id, $cards->id);
                if ((int)$result['status'] == 1) {
                    return json_encode(['status' => 1, 'card_id' => $cards->id], JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['status' => 0, 'message' => 'Возникла ошибка uzcard!'], JSON_UNESCAPED_UNICODE);
                }
            }

            if ($cards->type == 2) {
                // проверка подключено ли смс информирование
                $card = Yii::$app->session->get('card');;
                $bank_c = Yii::$app->session->get('bank_c');
                $card_h = Yii::$app->session->get('card_h');
                $exp = Yii::$app->session->get('exp');
                $user_id = Yii::$app->session->get('user_id');

                $bank_c = mb_substr($card, 4, 2);
                $exp_m = mb_substr($exp, 0, 2);
                $exp_y = mb_substr($exp, 2, 2);
                $exp_humo = $exp_y . $exp_m;
                $inform_humo = HumoHelper::humoSmsBanking($card, $bank_c);  // телефон мобиль банкинга
                $phone_humo = $inform_humo[0];
                $fio = $inform_humo[1];
                Yii::$app->session->set('phone_humo', $phone_humo);
                Yii::$app->session->set('fio', $fio);
                if ($inform_humo[2] != $exp_humo) {
                    return json_encode(['status' => 0, 'message' => 'Указан неправильный срок карты!'], JSON_UNESCAPED_UNICODE);
                }

                // var_dump($phone_humo); exit;
                if ($phone_humo) {
                    $code_humo = SmsHelper::generateCode(4);
                    UtilsHelper::debug('Add-card.sms-phone:' . $phone_humo);
                    UtilsHelper::debug(' sms-phone:' . $phone_humo . '. sms-code:' . $code_humo);
                    $text = Yii::t('app', '_code_ - kod podtverzhdeniya dlya dobavleniya karti Humo k partneru ZMARKET. Nikomu ne soobshayte danniy kod.');
                    $text = str_replace('_code_', $code_humo, $text);

                    // смс оповещение клиента с кодом подтверждения автосписания хумо
                    SmsHelper::sendSms($phone_humo, Yii::t('app', $text));
                    Yii::$app->session->set('code_humo', $code_humo);
                    return json_encode(['status' => 1, 'card_id' => $cards->id], JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['status' => 0, 'message' => 'Возникла ошибка Humo: не подключен смс банкинг!'], JSON_UNESCAPED_UNICODE);
                }
            }

        }

    }

    public function actionSmsConfirm()
    {

        // если клиент не найден
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                return json_encode(['status' => 0, 'message' => 'Клиент не найден!'], JSON_UNESCAPED_UNICODE);
            }
        }

        $post = Yii::$app->request->post();
        //return json_encode(['status'=>1, 'code' => $post],JSON_UNESCAPED_UNICODE);
        if (isset($post['code']) && isset($post['card_id'])) {
            //return json_encode(['status'=>1, 'code' => $post['code']],JSON_UNESCAPED_UNICODE);

            $card_id = $post['card_id'];
            $type = Yii::$app->session->get('type');
            $code_humo = Yii::$app->session->get('code_humo');
            // $card = Yii::$app->session->get('card');;
            $bank_c = Yii::$app->session->get('bank_c');
            $card_h = Yii::$app->session->get('card_h');
            $exp = Yii::$app->session->get('exp');
            $phone_humo = Yii::$app->session->get('phone_humo');
            $fio = Yii::$app->session->get('fio');
            $user_id = Yii::$app->session->get('user_id');

            if (/*$user_id && */ isset($post['code'])) {
                if ($type == 1) {
                    $result = Scoring::checkOtp();
                    if ((int)$result['status'] == 1) {
                        return json_encode(['status' => 1], JSON_UNESCAPED_UNICODE);
                    }
                }
                if ($type == 2) {
                    if ($code_humo == $post['code']) {
                        $scoring = new Scoring();
                        $scoring->user_id = $user_id;
                        $scoring->cards_add_id = $card_id;
                        $scoring->bank_c = $bank_c;
                        $scoring->card_h = $card_h;
                        $scoring->exp = $exp;
                        $scoring->phone = $phone_humo;
                        $scoring->fullname = $fio;
                        $scoring->sms = 1;
                        $scoring->created_at = time();
                        if (!$scoring->save()) {
                            return json_encode(['status' => 0, 'message' => 'Ошибка при сохранении данных Humo!' . $scoring->getErrors()], JSON_UNESCAPED_UNICODE);
                        }
                        return json_encode(['status' => 1], JSON_UNESCAPED_UNICODE);

                    } else {
                        return json_encode(['status' => 0, 'message' => 'Возникла ошибка Humo: не правильный номер смс!'], JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            return json_encode(['status' => 0, 'message' => 'Не удалось сохранить данные. Пожалуйста, авторизуйтесь на сайте!'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actionPay()
    {
        $user_id = Yii::$app->user->identity->id; // убрать на боевом
        if ($user = User::find()->where(['id' => $user_id])->one()) {

        }
        // привязка к сессии
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                return $this->redirect('/login');
            }
        }

        if ($post = Yii::$app->request->post()) {
            $type = $user->auto_discard_type;

            // если есть сумма списания
            if (isset($post['amount'])) {
                $amount = $post['amount'] * 100;
                if($user->auto_discard_type == 1) {
                    $uzcard = new Uzcard;
                    $result = $uzcard->discard($user, $amount);
                }
                if($user->auto_discard_type == 2) {
                    $result = HumoHelper::HumoDiscard($user->scoring->card_h, $user->scoring->bank_c, $user->scoring->exp, $amount);
                }

                $result = json_decode($result, true);
                $allow = false;
                if (isset($result['result']['status']) && $result['result']['status'] == 'OK') {  // uzcard
                    $allow = true;

                }
                if (isset($result['merchant_id'])) {  // Humo
                    $allow = true;
                }
                if ($allow) {
                    if($user->auto_discard_type == 1) {
                        $trans_id = $result['result']['id'];
                        unset($result['result']['id']);
                        $utp = new UzcardPayments(); // учет снятия средств
                        //if( $utp->load($data) ){
                        foreach ($result["result"] as $k => $v)
                            $utp->$k = strval($v);

                        $utp->user_id = $user->id;
                        $utp->payment_id = null;
                        $utp->created_at = time();
                        $utp->trans_id = $trans_id;
                        $utp->credit_item_id = null;
                        if (!$utp->save()) {
                            return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving UzcardPayments')]);
                        }
                    }
                    if($user->auto_discard_type == 2) {
                        if($result['payment_id']){
                            $humo_payments = new HumoPayments();
                            $humo_payments->user_id = $user->id;
                            $humo_payments->credit_item_id = null;
                            $humo_payments->created_at = time();
                            foreach($result as $k => $v){
                                $humo_payments->$k = $v;
                                if(!$humo_payments->save()){
                                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving HumoPayments')]);
                                }
                            }
                        }
                    }

                    // Запись в Billing History
                    $billing_history = new BillingHistory();
                    $billing_history->user_id = $user->id;
                    $billing_history->created_at = time();
                    $billing_history->summ = $amount/100;
                    $billing_history->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                    $billing_history->state = Payment::PAYMENT_TYPE_UZCARD;
                    $billing_history->status = 1;
                    $billing_history->apelsin_trans = null;
                    if(!$billing_history->save()){
                        return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving Billing_history')]);
                    }

                    $user->summ += $amount/100;
                    if(!$user->save(false)){
                        return json_encode(['status' => 0,  'info' => Yii::t('app', 'error saving User')]);
                    }
                    $info = 'Пополнение прошло успешно!';
                    return json_encode(['status' => 1, 'info' => $info], JSON_UNESCAPED_UNICODE);
                }
                // если нет суммы списания просто показать баланс
            } else {

                if($user->auto_discard_type == 1) {
                    $uzcard = new Uzcard;
                    $balance = $uzcard->cardsGet($user);
                    $balance = $balance['result'][0]['balance'];
                }
                if($user->auto_discard_type == 2) {
                    $scoring = Scoring::find()->where(['user_id' => $user->id])->one();
                    $card = '9860' . $scoring->bank_c . $scoring->card_h;
                    $balance = HumoHelper::humoBalance($card);
                }
                return json_encode(['status' => 1, 'balance' => $balance], JSON_UNESCAPED_UNICODE);
            }

        }

    }

    // закрепление карты через кабинет клиента
    public function actionCheckCard()
    {
        $user_id = Yii::$app->user->identity->id;  // убрать на боевом
        if ($user = User::find()->where(['id' => $user_id])->one()) {
            Yii::$app->session->set('user_id', $user_id);
        }

        $id = (int)Yii::$app->request->get('id');

        // если клиент не найден
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                // Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                // return $this->redirect('/settings');
                //return ['status'=>0,'result'=>$result];
            }
        }

        $post = Yii::$app->request->post();
        if (isset($post['card']) && isset($post['exp'])) {

            $card = preg_replace('/[^0-9]/', '', $post['card']);
            $exp = preg_replace('/[^0-9]/', '', $post['exp']);

            $bank_c = mb_substr($card, 4, 2);
            $card_h = mb_substr($card, 6, 10);
            $exp_m = mb_substr($exp, 0, 2);
            $exp_y = mb_substr($exp, 2, 2);
            $exp = $exp_y . $exp_m;

            Yii::$app->session->set('bank_c', $bank_c);
            Yii::$app->session->set('card_h', $card_h);
            Yii::$app->session->set('card', $card); // используется в скоринге
            Yii::$app->session->set('exp', $exp);

            $user->uzcard = mb_substr($card, 10, 6);
            $user->exp = $exp;
            $user->status_client_complete = 2;

            if (preg_match('[^8600]', $card)) {
                $user->auto_discard_type = 1;
            } else if (preg_match('[^9860]', $card)) {
                $user->auto_discard_type = 2;
            } else {
                $user->auto_discard_type = 0;
            }

            $type = $user->auto_discard_type;
            if ($user_id) {
                $user->save(false);
            } else {
                return json_encode(['status' => 0, 'info' => 'Не удалось сохранить данные. Пожалуйста, авторизуйтесь на сайте!']);
            }

            Yii::$app->session->set('type', $type);

            if ($user->auto_discard_type == 1) {
                $result = Scoring::sendOtp($user->id);

                if ((int)$result['status'] == 1) {
                    $info = Yii::t('app', 'Вам отправлен смс код для подтверждения карты');
                    return json_encode(['status' => 1, 'result' => $result['info'], 'info' => $info]);
                } else {
                    $info = Yii::t('app', 'Что-то пошло не так! Вероятно, вы вели неправильные данные. Попробуйте еще раз');
                    return json_encode(['status' => 0, 'result' => $result['info'], 'info' => $info]);
                }
            }

            if ($user->auto_discard_type == 2) {
                // проверка подключено ли смс информирование
                $card = Yii::$app->session->get('card');;
                $bank_c = Yii::$app->session->get('bank_c');
                $card_h = Yii::$app->session->get('card_h');
                $exp = Yii::$app->session->get('exp');
                $user_id = Yii::$app->session->get('user_id');

                $bank_c = mb_substr($card, 4, 2);
                $exp_m = mb_substr($exp, 0, 2);
                $exp_y = mb_substr($exp, 2, 2);
                $exp_humo = $exp_y . $exp_m;

                $inform_humo = HumoHelper::humoSmsBanking($card, $bank_c);  // телефон мобиль банкинга
                $phone_humo = $inform_humo[0];
                $fio = $inform_humo[1];
                Yii::$app->session->set('phone_humo', $phone_humo);
                Yii::$app->session->set('fio', $fio);
                if ($inform_humo[2] != $exp_humo) {
                    return json_encode(['status' => 0, 'exp' => 1, 'get' => $inform_humo[2], 'send' => $exp_humo, 'info' => 'Указан неправильный срок карты!']);
                }

                // var_dump($phone_humo); exit;
                if ($phone_humo) {
                    $code_humo = SmsHelper::generateCode(4);
                    UtilsHelper::debug('Add-credir.send-user-sms. sms-phone:' . $phone_humo);
                    UtilsHelper::debug(' sms-phone:' . $phone_humo . '. sms-code:' . $code_humo);
                    $text = Yii::t('app', '_code_ - kod podtverzhdeniya dlya dobavleniya karti Humo k partneru ZMARKET. Nikomu ne soobshayte danniy kod.');
                    $text = str_replace('_code_', $code_humo, $text);

                    // смс оповещение клиента с кодом подтверждения автосписания хумо
                    SmsHelper::sendSms($phone_humo, Yii::t('app', $text));
                    Yii::$app->session->set('code_humo', $code_humo);
                    return json_encode(['status' => 1, 'info' => 'На ваш номер отправлен смс код подтверждения!']);

                } else {
                    return json_encode(['status' => 0, 'info' => 'Возникла ошибка Humo: подключите смс банкинг!']);
                }
            }

            return json_encode(['status' => 0, 'info' => 'Нет данных!']);
        }


        return $this->render('check-card', [
            'model' => $this->user,
        ]);

    }

    public function actionAutodiscard()
    {
        $post = Yii::$app->request->post();
        $type = Yii::$app->session->get('type');
        $code_humo = Yii::$app->session->get('code_humo');
        // $card = Yii::$app->session->get('card');;
        $bank_c = Yii::$app->session->get('bank_c');
        $card_h = Yii::$app->session->get('card_h');
        $exp = Yii::$app->session->get('exp');
        $phone_humo = Yii::$app->session->get('phone_humo');
        $fio = Yii::$app->session->get('fio');
        $user_id = Yii::$app->session->get('user_id');

        if (isset($post['code'])) {
            if ($type == 1) {
                $result = Scoring::checkOtp();
                Yii::$app->session->setFlash('info', $result['info']);
                if ((int)$result['status'] == 1) {
                    if ($user = User::find()->where(['id' => $user_id])->One()) {
                        $user->auto_discard_type = 1;
                        $user->save();
                    }
                    if (!$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                        $scoring = new Scoring();
                    }
                    $scoring->bank_c = null;// обнуляем предыдущую humo
                    $scoring->card_h = null;
                    if (!$scoring->save()) {
                        $info = Yii::t('app', 'Ошибка при сохранении данных Uzcard!') . ' ' . json_encode($scoring->getErrors());
                        return json_encode(['status' => 0, 'info' => $info]);
                    }
                    return json_encode(['status' => 1, 'info' => 'Карта успешно подтверждена!']);
                }
            }
            if ($type == 2) {
                if ($code_humo == $post['code']) {
                    if ($user = User::find()->where(['id' => $user_id])->One()) {
                        $user->auto_discard_type = 2;
                        $user->save();
                    }
                    if (!$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                        $scoring = new Scoring();
                    }
                    $scoring->user_id = $user_id;
                    $scoring->token = null; // обнуляем предыдущую uzcard
                    $scoring->pan = null;
                    $scoring->bank_c = $bank_c;
                    $scoring->card_h = $card_h;
                    $scoring->exp = $exp;
                    $scoring->phone = $phone_humo;
                    $scoring->fullname = $fio;
                    $scoring->sms = 1;
                    $scoring->created_at = time();
                    if (!$scoring->save()) {
                        $info = Yii::t('app', 'Ошибка при сохранении данных Humo!') . ' ' . json_encode($scoring->getErrors());
                        return json_encode(['status' => 0, 'info' => $info]);
                    }
                    return json_encode(['status' => 1, 'info' => 'Карта успешно подтверждена!']);
                } else {
                    return json_encode(['status' => 0, 'info' => 'Возникла ошибка Humo: не правильный номер смс! ']);
                }
            }
        }
    }


    // план график оплаты
    public function actionCreditPlan()
    {

        $credit_id = Yii::$app->request->get('id');

        if (!$credit = Credits::find()->with(['creditItems', 'paymentsAsc', 'supplier'])->where(['id' => $credit_id, 'user_id' => $this->user->id])->one()) {
            $credit = false;
            //return $this->redirect('/clients/credits');
        }


        if (!$kyc = Kyc::find()->where(['client_id' => $this->user->id])->one()) {
            $kyc = false;
        }

        $credit_limit_year = 100000;

        return $this->render('credit-plan', [
            'credit' => $credit,
            //'user' => $this->user,
            'model' => Yii::$app->user->identity,
            'model_kyc' => $kyc,
            'credit_limit_year' => $credit_limit_year,

        ]);


    }


    // ежемесячная оплата списание средств со счет баланса клиента - БИЛЛИНГ
    public function actionCreditPayment()
    {

        $get = Yii::$app->request->get();

        $id = (int)$get['id'];
        $cid = (int)$get['credit_id'];


        // оплачиваемый ежемесячный кредит
        if (!$credit_history = CreditHistory::find()->with(['credit', 'supplier'])->where(['id' => $id, 'credit_id' => $cid])->one()) { //->one() ){

        }
        if ($this->user->summ < $credit_history->price) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Недостаточно средств для списания!'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($credit_history->payment_status == 1) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата за текущую дату уже произведена!'));
            return $this->redirect('/clients/credit-history');
        }

        //echo '<pre>';print_r($credit); exit;
        /*if( !$settings = SuppliersSettings::find()->where(['supplier_id'=>$credit_history->supplier->id])->one()){
             $settings = new SuppliersSettings();
         }*/

        // for payment
        if (!$payment = Payment::find()->where(['user_id' => $this->user->id, 'status' => 0])->one()) {
            $payment = new Payment();
            $payment->user_id = $this->user->id;
            $payment->price = $credit_history->price;
            $payment->supplier_id = $credit_history->supplier->id;
            $payment->credit_id = $credit_history->credit_id;
            $payment->credit_item_id = $credit_history->id;
            $payment->type = Payment::TYPE_PAY;
            $payment->status = 0;
            $payment->state = 0;
            $payment->payment_type = 0;
            $payment->created_at = 0;
            $payment->save();
        }

        return $this->render('credit-payment', [
            'credit_items' => $credit_history,
            'order_id' => $payment->id,
            'lang' => $this->lang,
        ]);

    }

    public function actionPayment()
    {

        $post = Yii::$app->request->post();
        $id = (int)$post['order_id'];
        $sum = $post['sum'];
        $sign = $post['sign'];


        if ($payment = Payment::find()->with(['user', 'contract'])->where(['id' => $id, 'status' => 0])->one()) {

            if (!isset($payment->contract)) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате. Не найден договор.'));
                return $this->redirect('/clients/checkout');
            }

            $_sign = md5($payment->price . \common\models\Payment::SECRET . $payment->id);
            if ($sign == $_sign) {


                if ($credit_item = CreditHistory::find()->with('credit')->where(['id' => $payment->credit_item_id, 'payment_status' => 0])->one()) {
                    $credit_item->payment_date = time();
                    $credit_item->payment_type = Payment::PAYMENT_TYPE_BILLING;
                    $credit_item->payment_status = 1;
                    $credit_item->save();

                } else {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате!') . json_encode($payment->getErrors(), JSON_UNESCAPED_UNICODE));
                    return $this->redirect('/clients/checkout');

                }


                $billing_payments = new BillingPayments();
                $billing_payments->credit_item_id = $payment->credit_item_id;
                $billing_payments->contract_id = $payment->contract->id;
                $billing_payments->user_id = $payment->user_id;
                $billing_payments->created_at = time();
                $billing_payments->summ = $payment->price;
                $billing_payments->status = 1;
                if (!$billing_payments->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате!') . json_encode($billing_payments->getErrors(), JSON_UNESCAPED_UNICODE));
                    return $this->redirect('/clients/checkout');
                }

                $payment->status = 1;
                $payment->state = Payment::PAYMENT_STATE_SUCCESS;
                $payment->payment_type = Payment::PAYMENT_TYPE_BILLING;
                $payment->created_at = time();
                if (!$payment->save()) {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате!') . json_encode($payment->getErrors(), JSON_UNESCAPED_UNICODE));
                    return $this->redirect('/clients/checkout');
                }
                $payment->user->summ -= $payment->price;
                if (!$payment->user->save()) {
                    print_r($payment->user->getErrors());
                    exit;
                }

                if ($credit_item->credit->getPaymentSum() < 0.5) { // 50 тийин
                    $credit_item->credit->status = Payment::PAYMENT_STATE_SUCCESS;
                    $credit_item->credit->save();
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Ваш кредит полностью оформлен!'));
                } else {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата успешно произведена!'));
                }
            } else {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате. Переданы неверные данные.'));
            }

        } else {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при оплате'));
        }

        return $this->redirect('/clients/checkout');

    }


    // пополнение НЕ РАБОТАЕТ
    public function actionPayments()
    {

        // $get = Yii::$app->request->get();

        $user = $this->user;
        // for payment
        if (!$payment = Payment::find()->where(['user_id' => $this->user->id, 'status' => 0])->one()) {
            $payment = new Payment();
            $payment->user_id = $this->user->id;
            $payment->price = 0;
            $payment->supplier_id = 0;
            $payment->credit_id = 0;
            $payment->credit_item_id = 0;
            $payment->status = 0;
            $payment->state = 0;
            $payment->payment_type = 0;
            $payment->created_at = 0;
            $payment->save();
        }

        $order_id = $payment->id;

        return $this->render('payments', [
            'model' => $user,
            'order_id' => $order_id,
            'lang' => $this->lang,
        ]);

    }

    public function actionCheckout()
    {

        $get = Yii::$app->request->get();

        if (isset($get['payment_status']) && $get['payment_status'] == -10000) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата была отменена.'));
        }

        return $this->render('checkout', [
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout(true);
        return $this->redirect('/');
    }


}
