<?php

namespace app\modules\api\controllers;

use common\helpers\HumoHelper;
use common\helpers\PolisHelper;
use common\helpers\SmsHelper;
use common\helpers\UtilsHelper;
use common\models\Asko;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Humo;
use common\models\Kyc;
use common\models\Polises;
use common\models\Scoring;
use common\models\User;
use common\models\Contracts;
use common\models\Uzcard;
use Yii;

/*

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

        //return $this->response(['error' => 'API. ' . 'Data not found'], 404);

        $params = Yii::$app->request->get();
        // получать только своих клинтов
        $usersQuery = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_CLIENT, 'supplier_id' => $this->user->id]);

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
		//$credit_limit = number_format($userQuery->kyc->credit_year - Credits::getPaymentSumAll($id),2,'.',' ') < 0 ? number_format(0,2,'.',' ') : number_format($userQuery->kyc->credit_year - Credits::getPaymentSumAll($id),2,'.',' ');

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

            return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app', 'Клиент с номером телефона ' . $phone . ' не найден!')], 404);
        }
        return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app', 'Клиент не найден!')], 404);
    }

    /**
     * Поиск полиса по кредиту
     */
    public function actionCheckTransaction()
    {
        $params = Yii::$app->request->get();

        $credit_id = isset($params['contractNumber']) ? (int)$params['contractNumber'] : null;

        if ($polis = Polises::find()->where(['credit_id' => $credit_id])->one()) {
            return $this->response(['code' => 0, 'message' => ' ', 'contractRegistrationID' => $polis->contractRegistrationID, 'client_id' => $polis->client_id, 'polisSeries' => $polis->polisSeries, 'polisNumber' => $polis->polisNumber], 200);

        }

        return $this->response(['code' => 1, 'message' => 'API. ' . Yii::t('app', 'Полис не найден!')], 404);


    }


    /**
     * Создание нового клиента
     *
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        /*print_r($post);
        print_r($_FILES);
        exit;*/

        // проверка на обязательные поля
        if (!isset($post['User']['phone'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Телефон [phone] не указан!')], 404);
        }
        if (!isset($post['User']['username'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Имя [username] не указано!')], 404);
        }
        if (!isset($post['User']['lastname'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Фамилия [lastname] не указана!')], 404);
        }
        if (!isset($post['User']['patronymic'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Отчество [patronymic] не указано!')], 404);
        }
         if (!isset($post['User']['work_place'])) {
             return $this->response(['error' => 'API. ' . Yii::t('app', 'Место учебы/работы [work_place] не указано!')], 404);
         }
         if (!isset($post['User']['permanent_address'])) {
             return $this->response(['error' => 'API. ' . Yii::t('app', 'Адрес постоянного места жительства [permanent_address] не указан!')], 404);
         }
         if (!isset($post['User']['phone_home'])) {
             return $this->response(['error' => 'API. ' . Yii::t('app', 'Номер домашнего телефона [phone_home] не указан!')], 404);
         }

        $phone = User::correctPhone($post['User']['phone']);

        if ($user = User::find()->where(['phone' => $phone])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент с данным номером телефона уже существует!')]);
        }

        $user = new User();
        $user->status_client_complete = 3;
        $user->role = User::ROLE_CLIENT;

        if (!$user->updateModel(true)) { // здесь сохраняются сканы паспорта
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Не удалось зарегистрировать клиента!')], 404);
        }

        $user->save();

        $kyc = Kyc::addUser($user->id, $supplier_id = 0);
        $kyc->status_verify = 0;
        $kyc->date_verify = time();
        if (!$kyc->save()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Ошибка при сохранении данных клиента!')]);
        }
        UtilsHelper::debug('kyc saved OK');
        // отправить смс
        //$sms_code = SmsHelper::generateCode(4);
        //SmsHelper::sendSms($phone, 'Zdravstvuyte Uv. Polzovatel! Vas privetstvuet platforma zMarket. Publichnaya oferta https://zmarket.uz/publicoffer.pdf Vash kod podtverzhdeniya nomera talefona ' . $sms_code);
        //$user->code = $sms_code;

        return $this->response(['status' => 1, 'user_id' => $user->id, 'info' => 'API. ' . Yii::t('app', 'Регистрация прошла успешно!')], 200);

    }

    /**
     * проверка смс сообщения телефона клиента при регистрации
     * если ОК то подтвердить телефон
     * и создать kyc
     */
   /* public function actionCheckClientSms()
    {
        $post = Yii::$app->request->post();
        //print_r($post);exit;
        $code = $post['User']['code'];
        $user_id = $post['User']['user_id'];
        UtilsHelper::debug('Api проверка смс кода ' . $code . ' user id ' . $user_id);

        if (!$user = User::find()->where(['id' => $user_id])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент с данным ID не найден!')]);
        }
        if ($user->code == $code) {
            UtilsHelper::debug('Телефон успешно подтвержден');
            $user->phone_confirm = 1;
            $user->status_client_complete = 3;
            $user->save();

            $kyc = Kyc::addUser($user->id, $supplier_id = 0);
            $kyc->status_verify = 0;
            $kyc->date_verify = time();
            if (!$kyc->save()) {
                return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Ошибка при сохранении данных клиента!')]);
            }
            UtilsHelper::debug('kyc saved OK');
            return $this->response(['status' => 1, 'info' => 'API. ' . Yii::t('app', 'Телефон успешно подтвержден!')]);
        }

        UtilsHelper::debug('Не верный смс код');
        return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Не верный смс код!')]);

    }*/

    /**
     * Регистрация карты
     *
     */
    public function actionRegisterClientPayment()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['User']['card'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Номер карты [card] не указан!')], 404);
        }
        if (!isset($post['User']['exp'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Срок карты [exp] не указан!')], 404);
        }
        if (!isset($post['User']['user_id'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Срок карты [user_id] не указан!')], 404);
        }

        $user_id = $post['User']['user_id'];

        if (isset($post['User']['card']) && isset($post['User']['exp'])) {

            $card = preg_replace('/[^0-9]/', '', $post['User']['card']);
            $exp = preg_replace('/[^0-9]/', '', $post['User']['exp']);

            $bank_c = mb_substr($card, 4, 2);
            $card_h = mb_substr($card, 6, 10);
            $exp_m = mb_substr($exp, 0, 2);
            $exp_y = mb_substr($exp, 2, 2);
            $exp = $exp_y . $exp_m;

            $uzcard = mb_substr($card, 10, 6);

            if (preg_match('[^8600]', $card)) {
                $type = 1;
            } else if (preg_match('[^9860]', $card)) {
                $type = 2;
            } else {
                $type = 0;
            }

        }

        if (!$user = User::find()->where(['id' => $user_id])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент не найден!')]);
        }
        if (Scoring::find()->where(['user_id' => $user_id])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Данный клиент уже регистрировал карту!')]);
        }

        $user->uzcard = $uzcard;
        $user->exp = $exp;
        $user->auto_discard_type = $type;
        if (!$user->save()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'ошибка сохранения user!')]);
        }

        // scoring
        if ($scoring = $this->scoring($type, $card, $exp, $user->id)) {
            if ($scoring) {
                return $this->response($scoring);
            }
        }
    }

    /**
     * scoring
     */
    public function scoring($type, $card, $exp, $user_id)
    {
        // смс оповещение клиента с кодом подтверждения
        if (!$user = User::find()->where(['id' => $user_id])->one()) {
            return ['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент не найден')];
        }
        switch ($type) {
            case 1: // Uzcard - отправка смс
                // вызов otp

                $scoring_data = Uzcard::cardOtp($card, $exp);
                if ($scoring_data['status']) {
                    UtilsHelper::debug('есть данные скоринга!');
                    UtilsHelper::debug(json_encode($scoring_data));
                    if (!$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                        $scoring = new Scoring();
                    }
                    //$scoring = new Scoring();
                    $scoring->load($scoring_data);
                    $scoring->user_id = $user_id;
                    $scoring->created_at = time();
                    if (!$scoring->save()) {
                        UtilsHelper::debug('Ошибка при сохранении данных скоринга!');
                        UtilsHelper::debug(json_encode($scoring_data));
                        UtilsHelper::debug(json_encode($scoring->getErrors()));
                        $info = 'API. ' . Yii::t('app', 'Ошибка при сохранении данных скоринга!') . ' ' . json_encode($scoring->getErrors());
                        return ['status' => 0, 'info' => $info];
                    }
                    UtilsHelper::debug('сохранились ОК данные скоринга!');
                    UtilsHelper::debug(json_encode($scoring_data));
                    $user->status_client_complete = 2;
                    $user->save();
                    $info = 'API. ' . Yii::t('app', 'Клиент успешно подлючен к системе Uzcard!');
                    return ['status' => 0, 'info' => $info];

                } else {
                    UtilsHelper::debug('Ошибка при получении токена UZCARD!');
                    UtilsHelper::debug(json_encode(json_encode($scoring_data)));
                    $info = 'API. ' . Yii::t('app', 'Ошибка при получении токена UZCARD!') . ' ' . json_encode($scoring_data);
                    return ['status' => 0, 'info' => $info];
                }

                break;
            case 2: // humo -
                // проверка подключено ли смс информирование
                $bank_c = mb_substr($card, 4, 2);
                $exp_m = mb_substr($exp, 0, 2);
                $exp_y = mb_substr($exp, 2, 2);
                $exp_humo =  $exp_y . $exp_m;
                $inform_humo = HumoHelper::humoSmsBanking($card, $bank_c);  // телефон мобиль банкинга

                $phone_humo = $inform_humo[0];
                if($inform_humo[2] != $exp_humo){
                    return ['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Неправильный срок карты User[exp]!')];
                }

                if ($phone_humo) {
                    $code_humo = SmsHelper::generateCode(4);
                    UtilsHelper::debug('send-user-sms. sms-phone-humo:' . $phone_humo);
                    UtilsHelper::debug(' sms-phone:' . $phone_humo . '. sms-code:' . $code_humo);
                    $text = 'API. ' . Yii::t('app', '_code_ - kod podtverzhdeniya dlya dobavleniya karti Humo k partneru ZMARKET. Nikomu ne soobshayte danniy kod.');
                    $text = str_replace('_code_', $code_humo, $text);

                    // смс оповещение клиента с кодом подтверждения автосписания хумо
                    SmsHelper::sendSms($phone_humo, Yii::t('app', $text));
                    UtilsHelper::debug($text);
                    $user->code = $code_humo; // сохраняется туда же куда и код телефона
                    $user->status_client_complete = 2;
                    $user->save();
                    $info = 'API. ' . Yii::t('app', 'Клиент успешно подлючен к системе Humo!');
                    return ['status' => 0, 'info' => $info];

                } else {
                    UtilsHelper::debug($user_id . '- Возникла ошибка Humo: не подключен смс банкинг!');
                    return ['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Возникла ошибка Humo: не подключен смс банкинг!')];
                }
                break;

            default:
                return false;
        }

    }

    /**
     * scoring sms
     * проверка смс карты автосписания
     */
    public function actionRegisterClientAutodiscard()
    {
        $post = Yii::$app->request->post();

        $code = $post['User']['code'];
        $card = $post['User']['card'];
        $user_id = $post['User']['user_id'];

        UtilsHelper::debug('api-verify');
        UtilsHelper::debug($post);

        if (!$user = User::find()->with('scoring')->where(['id' => $user_id])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент не найден!')]);
        }
        if (!$kyc = Kyc::find()->where(['client_id' => $user->id])->one()) {
            return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Клиент KYC не найден!')]);
        }

        switch ($user->auto_discard_type) {
            case 1: // Uzcard
                if (!isset($user->scoring)) {
                    return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Токен карты для клиента не найден!')]);
                }
                $token = $user->scoring->token;
                if ($token == '' || is_null($token)) return ['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Токен не найден')];
                UtilsHelper::debug($token);
                $result = Uzcard::cardVerify($token, $code);
                if (isset($result['status']) && $result['status'] == 1) { //} || isset($result['result']) ) {
                    UtilsHelper::debug('Api Uzcard Autodiscard OK!');
                    $user->status_client_complete = 4;
                    $user->save();
                    $kyc->updated_at = time();
                    $kyc->save();
                    return $this->response(['status' => 1, 'info' => 'Привязка карты Uzcard успешно подтверждена!']);
                } else {
                    UtilsHelper::debug($result);
                    return $this->response(['status' => 0, 'info' => 'API. ' . Yii::t('app', 'Ошибка при подключении Uzcard!') . ' ' . json_encode($result)]);
                }
                break;
            case 2: // Humo

                $bank_c = mb_substr($card, 4, 2);
                $card_h = mb_substr($card, 6, 10);
                $humo = new Humo();
                $inform_humo = $humo->humoSmsBanking($card, $bank_c);  // телефон мобиль банкинга
                $phone_humo = $inform_humo[0];
                $fio = $inform_humo[1];

                if ($user->code == $code) {

                    if (!$scoring = Scoring::find()->where(['user_id' => $user_id])->One()) {
                        $scoring = new Scoring();
                    }
                    //$scoring = new Scoring();
                    $scoring->user_id = $user_id; // после сохранения клиента внесем
                    $scoring->token = null;
                    $scoring->pan = null;
                    $scoring->bank_c = $bank_c;
                    $scoring->card_h = $card_h;
                    $scoring->exp = $user->exp;
                    $scoring->phone = $phone_humo;
                    $scoring->fullname = $fio;
                    $scoring->sms = 1;
                    $scoring->created_at = time();
                    if (!$scoring->save()) {
                        $info = 'API. ' . Yii::t('app', 'Ошибка при сохранении данных Humo!') . ' ' . json_encode($scoring->getErrors());
                        return $this->response(['status' => 0, 'info' => $info]);
                    }

                } else {
                    $info = 'API. ' . Yii::t('app', 'Возникла ошибка Humo: не правильный номер смс!');
                    return $this->response(['status' => 0, 'info' => $info]);
                }
                $user->status_client_complete = 4;
                $user->save();
                $kyc->updated_at = time();
                $kyc->save();
                return $this->response(['status' => 1, 'info' => 'API. ' . 'Привязка карты Humo успешно подтверждена!']);
                break;
        }

        return $this->response(['status' => 0, 'info' => 'API. ' . 'Платежная система не указана']);
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
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Срок кредита должен быть 3, 6 или 9 мес  [credit_limit]!')], 404);
        }
        /*if(!isset($get['deposit_first']) ){
            return $this->response(['error'=>'API. ' . Yii::t('app','Сумма первоначального взноса кредита [deposit_first] не указана!')],404);
        }*/
        if (!isset($get['user_id'])) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'ID пользователя не задан [user_id]!')], 404);
        }
        if (!isset($get['phone'])) {
            if (!isset($get['vendor_id']))
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
        } else {
            if ($vendor_id && !$vendor = User::findIdentity($vendor_id)) {
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


        /*if ($get['credit_limit'] == 3) {
            $nds_default = $vendor->margin_three ? $vendor->margin_three : 25;
        } else {
            $nds_default = $vendor->margin_six ? $vendor->margin_six : 35;
        }*/

        if ($get['credit_limit'] == 3) {
            $nds_default = $vendor->margin_three ? $vendor->margin_three : 10;
        }
        if ($get['credit_limit'] == 6) {
            $nds_default = $vendor->margin_six ? $vendor->margin_six : 25;
        }
        if ($get['credit_limit'] == 9) {
            $nds_default = $vendor->margin_nine ? $vendor->margin_nine : 35;
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

        if ($credit_amount = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user_id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
            $res = $credit_amount->payment_sum ? $credit_amount->payment_sum : 0;
            $rest_credit_year = $user->kyc->credit_year - $res;
        }

        if ($rest_credit_year - $sum < 0) {
            return $this->response(['status' => 0, 'error' => Yii::t('app', 'Сумма товаров превышает допустимые ' . $rest_credit_year . ' сумов !')]);
        }

        // учет НДС - 15% к стоимости
        $sum *= $nds; // общая сумма кредита (всех товаров)

        // расчет ежемесячного взноса с вычетом депозита
        $sum_month = (int)((($sum - $deposit_first) / $get['credit_limit']) * 100) / 100; // ежемесячный взнос

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // получаем последний prefix_act
            $credits = Credits::find()->where(['not',['prefix_act'=>null]])->orderBy(['id' => SORT_DESC])->one();
            $prefix_act_last = $credits->prefix_act ? $credits->prefix_act : 24;
            $prefix_act_last = $prefix_act_last + 1;

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
        $credit->prefix_act = $prefix_act_last; // нумерация актов начиная с 25 (нужно для внутренней отчетности)
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
        UtilsHelper::debug(' sms-phone:' . $phone . '. sms-code:' . $code);
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
            $text = Yii::t('app', 'Здравствуйте Ув. Пользователь! Вас приветствует платформа zMarket. 
            Публичная оферта ' . $link . ' . Ваш код подтверждения ' . $code . '. ' . $credit_info . ' Платформа zMarket благодарит Вас за покупку!');


            if ($phone) {
                //Uzcard::sendOrder($credit, $user); // не надо?
                SmsHelper::sendSms($phone, Yii::t('app', $text));

            }
            UtilsHelper::debug('create-credit'); // оформление договора
            UtilsHelper::debug('Add-credit.send-user-sms. sms-phone:' . $phone);
            UtilsHelper::debug($credit_info);


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
            if ($credit_amount = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user->id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                $res = $credit_amount->payment_sum;
            }

            $zmarket_sum_credit = $user->kyc->credit_year - $res; // сумма оставшегося кредита

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

            //$credit->price;  - сумма кредита

            if ($user = User::findIdentity($user_id)) {
                if ($credit_amount = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user_id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                    $res = $credit_amount->payment_sum ? $credit_amount->payment_sum : 0;
                    $rest_credit_year = $user->kyc->credit_year - $res;

                }


                if ($rest_credit_year - $credit->price < 0) {
                    return $this->response(['status' => 0, 'error' => Yii::t('app', 'Недостаточный годовой лимит( ' . $rest_credit_year . ' сумов ) для оформления договора! Общая сумма взятых кредитов: ' . $res . ' сумов')]);

                }
            } else {
                return $this->response(['status' => 0, 'user_id' => $user_id, 'error' => Yii::t('app', 'Клиент не найден! ')]);
            }

            $credit->user_confirm = 1;
            $credit->confirm = 1;
            $credit->save();


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
            // отправка договора на страхование
            /* $result = PolisHelper::getPolisForCredit('zMarket_' . $contract->id, $credit);
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
     * калькулятор суммы для асахий
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
        } elseif ($sum > 8000000) {
            return $this->response(['error' => 'API. ' . Yii::t('app', 'Сумма не может превышать 8000000 [sum]!')], 404);
        }

        if ($credit_limit != 3 && $credit_limit != 6 && $credit_limit != 9) {
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Период должен быть 3, 6 или 9 месяцев [credit_limit]!')], 404);
        }

        // находим вендора
        if ($phone && $vendor = User::findVendorByPhone($phone)) {
        } else {
            if ($vendor_id && !$vendor = User::findIdentity($vendor_id)) {
                return $this->response(['error' => 'API. ' . Yii::t('app', 'Партнер не найден!')], 404);

            }
        }

        if ($sum) {
            /*if ($get['credit_limit'] == 3) {
                $nds_default = $vendor->margin_three ? $vendor->margin_three : 25;
            } else {
                $nds_default = $vendor->margin_six ? $vendor->margin_six : 35;
            }*/


            if ($get['credit_limit'] == 3) {
                $nds_default = $vendor->margin_three ? $vendor->margin_three : 10;
            }
            if ($get['credit_limit'] == 6) {
                $nds_default = $vendor->margin_six ? $vendor->margin_six : 25;
            }
            if ($get['credit_limit'] == 9) {
                $nds_default = $vendor->margin_nine ? $vendor->margin_nine : 35;
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
	
	//Оплата с платежных систем
	public function actionAddPay () 
	{
		 $request = Yii::$app->request->post();
		 
		 if (isset($request)) {
			  return $this->response(['status' => 'success' . Yii::t('app', ' Оплата успешно проведена')], 200);
		 }
	}
	
	    public function actionVerificationWithCreditLimit()
    {
        $params = Yii::$app->request->get();

        $id = isset($params['id']) ? (int)$params['id'] : null;
        $phone = isset($params['phone']) ? (int)$params['phone'] : null;

        $userQuery = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_CLIENT]);
		

        if ($id) {
            if ($user = $userQuery->andWhere(['id' => $id])->one()) {
                if ($user->kyc->status == 1 && $user->kyc->delay == 0) {
					
				   $res = 0;
					if ($credit_amount = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user->id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
						$res = $credit_amount->payment_sum;
					}

					$zmarket_sum_credit = $user->kyc->credit_year - $res; // сумма оставшегося кредита
                    return $this->response(['status' => 1, 'result' => 'Клиент ' . $user->username . ' ' . $user->lastname . ' верифицирован!', 'ID' => $user->id, 'CREDIT_LIMIT' => $zmarket_sum_credit, 'error' => null], 200); // возвращает айди клиента в нашей системе
                } else {
                    return $this->response(['status' => 2, 'error' => 'API. ' . Yii::t('app', 'Клиент ' . $user->username . ' ' . $user->lastname . ' не верифицирован!')], 404);
                }
            }
        }
        if ($phone) {
            if ($user = $userQuery->andWhere(['phone' => $phone])->one()) {
                if ($user->kyc->status == 1 && $user->kyc->delay == 0) {
				   $res = 0;
					if ($credit_amount = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $user->id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
						$res = $credit_amount->payment_sum;
					}

					$zmarket_sum_credit = $user->kyc->credit_year - $res; // сумма оставшегося кредита
                    return $this->response(['status' => 1, 'result' => 'Клиент ' . $user->username . ' ' . $user->lastname . ' верифицирован!', 'ID' => $user->id, 'CREDIT_LIMIT' => $zmarket_sum_credit, 'error' => null], 200); // возвращает айди клиента в нашей системе
                } else {
                    return $this->response(['status' => 2, 'error' => 'API. ' . Yii::t('app', 'Клиент ' . $user->username . ' ' . $user->lastname . ' не верифицирован!')], 404);
                }

            }

            return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app', 'Клиент с номером телефона ' . $phone . ' не найден!')], 404);
        }
        return $this->response(['status' => 0, 'error' => 'API. ' . Yii::t('app', 'Клиент не найден!')], 404);
    }


}
