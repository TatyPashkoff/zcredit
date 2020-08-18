<?php

namespace frontend\controllers;

use common\helpers\CryptHelper;
use common\helpers\FileHelper;
use common\helpers\PolisHelper;
use common\helpers\HumoHelper;
use common\helpers\SmsHelper;
use common\helpers\UpayHelper;
use common\helpers\UtilsHelper;
use common\models\Asko;
use common\models\Contracts;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\HumoPayments;
use common\models\Kyc;
use common\models\Partners;
use common\models\PartnersCats;
use common\models\Payment;
use common\models\Paymo;
use common\models\Humo;

use common\models\Polises;
use common\models\Scoring;
use common\models\User;
use common\models\Katm;

use common\models\Uzcard;
use common\models\UzcardPayments;
use common\models\UzcardTranspay;
use common\models\BillingHistory;
use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\HttpException;
use SoapClient;
use SimpleXMLElement;
use SoapHeader;


/**
 * Site controller
 */
class SiteController extends BaseController
{

    public $TEST_MODE = true;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    /* public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    } */

    // установка языка
    public function actionLang($lang = 'ru')
    {
        $ref = Yii::$app->request->referrer;
        $_lang = ['ru', 'uz', 'en']; // допустимые языки
        if (!in_array($lang, $_lang)) $lang = 'ru';
        Yii::$app->session->set('lang', $lang);

        $this->lang = $lang;
        Yii::$app->language = $lang; // установка языка на сайте

        return $this->redirect($ref);
    }

    /**
     * главная страница
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'lang' => $this->lang,
        ]);
    }


    public function actionRegisterClient()
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';

        $post = Yii::$app->request->post();

        if (isset($post['User']['phone'])) {
            if ($post['offer'] == 1) {
                $phone = '998' . $post['User']['phone'];
                $phone = User::correctPhone($phone);
                //Yii::$app->session->set('phone',$phone);
                
				if (isset($post['User']['promocode'])) {
					Yii::$app->session->set('promocode', $post['User']['promocode']);
				}

                if ($user = User::find()->where(['phone' => $phone])->one()) {
                    Yii::$app->session->setFlash('info', 'Клиент с данным номером телефона уже существует, войдите в личный кабинет!');
                    //return $this->refresh();
                    return $this->redirect('/login');
                }
                // создаем сессию после проверки в бд
                Yii::$app->session->set('phone', $phone);

                $sms_code = SmsHelper::generateCode(4);

                if ($_SERVER['SERVER_NAME'] == 'crm1.loc') {
                    Yii::$app->session->set('sms_code', '1234');
                } else {
                    Yii::$app->session->set('sms_code', $sms_code);
                }
				$user = User::find()->where(['phone' => $phone])->one(); // ???
				
			SmsHelper::sendSms($phone, 'Zdravstvuyte Uv. Polzovatel! Vas privetstvuet platforma zMarket. Publichnaya oferta https://zmarket.uz/publicoffer.pdf Vash kod podtverzhdeniya ' . $sms_code);

                Yii::$app->session->setFlash('info', 'На ваш номер телефона отправлен смс код для подтверждения!');

                return $this->redirect('/register-client-check-sms');

            }
        }
        


        return $this->render('register-client/step-1-phone', [
            'lang' => $this->lang,
        ]);

    }

    public function actionRegisterClientCheckSms()
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';
        $post = Yii::$app->request->post();

        $sms_code = Yii::$app->session->has('sms_code') ? Yii::$app->session->get('sms_code') : $this->uniqueId;

        if (isset($post['sms'])) {

            if ($post['sms'] == $sms_code) { //  проверка кода из смс
                // удаляем сессию после проверки смс
                Yii::$app->session->remove('sms_code');

                if (!Yii::$app->user->isGuest) Yii::$app->user->logout();

                if ($user = User::create(User::ROLE_CLIENT)) {
                    $phone = Yii::$app->session->get('phone');
                    $promocode = Yii::$app->session->get('promocode');
                    $user->phone = $phone;
                    $user->phone_confirm = 1;
                    $user->promocode = $promocode;
                    $user->status_client_complete = 1;
                    $user->save(false);

                    Yii::$app->session->set('user_id', $user->id);
                    UtilsHelper::debug('user_id');
                    UtilsHelper::debug('send-user-sms. sms-phone:' . $phone . ' user_id: ' . $user->id);

                    // return $this->redirect('/register-client-passport');
                    return $this->redirect('/register-client-payment');

                }

            } else {
                Yii::$app->session->setFlash('info', 'Неверный код из СМС !');
            }

        }

        return $this->render('register-client/step-1-check-sms', [
            'lang' => $this->lang,
        ]);


    }

    public function actionRegisterClientPassport()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if ($user = User::find()->where(['id' => $user_id])->one()) {
                $user->status_client_complete = 3;
                if ($user->updateModel())
                    //return $this->redirect('/register-client-payment');
                    return $this->redirect('/register-client-complete');

            } else {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                return $this->redirect('/register-client');
            }
        }

        return $this->render('register-client/step-2-passport', [
            'lang' => $this->lang,
        ]);

    }

    public function actionRegisterClientPayment()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';

        $post = Yii::$app->request->post();

        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                return $this->redirect('/register-client');
            }
        }
        /*if( isset($post['type']) ){
            Yii::$app->session->set('card',$post['User']['uzcard']);
            Yii::$app->session->set('exp',$post['User']['exp']);
        }*/

        if (isset($post['User']['uzcard']) && isset($post['User']['exp'])) {

            $card = preg_replace('/[^0-9]/', '', $post['User']['uzcard']);
            $exp = preg_replace('/[^0-9]/', '', $post['User']['exp']);

            $bank_c = mb_substr($card, 4, 2);
            $card_h = mb_substr($card, 6, 10);
            $exp_m = mb_substr($exp, 0, 2);
            $exp_y = mb_substr($exp, 2, 2);
            $exp = $exp_y . $exp_m;

            //$exp = date('ym',strtotime($post['User']['exp']));

            Yii::$app->session->set('bank_c',$bank_c);
            Yii::$app->session->set('card_h',$card_h);
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

            if (Yii::$app->session->has('user_id')){
                $user->save(false);
            }else{
                Yii::$app->session->setFlash('info', 'Невозможно сохранить данные!');
                return $this->refresh();
            }

            Yii::$app->session->set('type',$type);

            if ($user->auto_discard_type == 1) {
                $result = Scoring::sendOtp($user->id);
                Yii::$app->session->setFlash('info', @$result['info']);
                if ((int)$result['status'] == 1) {
                    Yii::$app->session->setFlash('info', 'На ваш номер телефона отправлен смс код для подтверждения привязки карты. ');
                    return $this->redirect('/register-client-autodiscard');
                } else {
                    Yii::$app->session->setFlash('info', 'Возникла ошибка Uzcard: ' . @$result['info']);
                    return $this->refresh();
                }
            }
            if( $user->auto_discard_type == 2){
                // проверка подключено ли смс информирование
                $card = Yii::$app->session->get('card');;
                $bank_c = Yii::$app->session->get('bank_c');
                $card_h = Yii::$app->session->get('card_h');
                $exp = Yii::$app->session->get('exp');
                $user_id = Yii::$app->session->get('user_id');

                $bank_c = mb_substr($card, 4, 2);
                $exp_m = mb_substr($exp, 0, 2);
                $exp_y = mb_substr($exp, 2, 2);
                $exp_humo =  $exp_y . $exp_m;

                $inform_humo = HumoHelper::humoSmsBanking($card, $bank_c);  // телефон мобиль банкинга
                $phone_humo = $inform_humo[0];
                $fio = $inform_humo[1];
                Yii::$app->session->set('phone_humo',$phone_humo);
                Yii::$app->session->set('fio',$fio);
                if($inform_humo[2] != $exp_humo){
                    Yii::$app->session->setFlash('info', 'Указан неправильный срок карты!');
                    return $this->refresh();
                }

                if($phone_humo) {
                    $code_humo = SmsHelper::generateCode(4);
                    UtilsHelper::debug('send-user-sms. sms-phone-humo:' . $phone_humo);
                    UtilsHelper::debug(' sms-phone:'.$phone_humo.'. sms-code:'.$code_humo);
                    $text = Yii::t('app','_code_ - kod podtverzhdeniya dlya dobavleniya karti Humo k partneru ZMARKET. Nikomu ne soobshayte danniy kod.');
                    $text = str_replace('_code_',$code_humo,$text);

                    // смс оповещение клиента с кодом подтверждения автосписания хумо
                    SmsHelper::sendSms($phone_humo,Yii::t('app',$text ));
                    Yii::$app->session->set('code_humo',$code_humo);

                    $info = Yii::t('app', 'На ваш номер отправлен смс код для подтверждения автосписания.');
                    return $this->redirect('/register-client-autodiscard');

                }else{
                    Yii::$app->session->setFlash('info', 'Возникла ошибка Humo: подключите смс банкинг! ' );
                    return $this->refresh();
                }
            }
            //return $this->redirect('/register-client-complete');
            return $this->redirect('/register-client-passport');
        }

        return $this->render('register-client/step-3-payment', [
            'lang' => $this->lang,
        ]);

    }

    public function actionRegisterClientAutodiscard()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';
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

        // if($_SERVER['SERVER_NAME']=='crm1.loc') return  $this->redirect('/register-client-complete');
        if (isset($post['code'])) {
            if($type == 1) {
                $result = Scoring::checkOtp();
                Yii::$app->session->setFlash('info', $result['info']);
                if ((int)$result['status'] == 1) {
                    Yii::$app->session->setFlash('info', 'Карта успешно подтверждена!');
                    // return  $this->redirect('/register-client-complete');
                    return $this->redirect('/register-client-passport');
                }
            }
            if($type == 2){
                if($code_humo == $post['code']){
                    Yii::$app->session->setFlash('info','Карта успешно подтверждена!');
                    $scoring = new Scoring();
                    $scoring->user_id = $user_id; // после сохранения клиента внесем
                    $scoring->bank_c = $bank_c;
                    $scoring->card_h = $card_h;
                    $scoring->exp = $exp;
                    $scoring->phone = $phone_humo;
                    $scoring->fullname = $fio;
                    $scoring->sms = 1;
                    $scoring->created_at = time();
                    if(!$scoring->save()){
                        $info = Yii::t('app','Ошибка при сохранении данных Humo!') . ' ' . json_encode($scoring->getErrors());
                        return ['status'=>0,'info'=>$info];
                    }
                    return $this->redirect('/register-client-passport');
                }else{
                    Yii::$app->session->setFlash('info', 'Возникла ошибка Humo: не правильный номер смс! ' );
                    return $this->refresh();
                }
            }
        }


        return $this->render('register-client/step-3-autodiscard', [
            'lang' => $this->lang,
        ]);
    }


    public function actionRegisterClientComplete()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';
        $post = Yii::$app->request->post();
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                return $this->redirect('/register-client');
            }
            $user->status_client_complete = 4;
            $user->save(false);
            $kyc = Kyc::find()->where(['client_id' => $user->id])->one();
            $kyc->updated_at = time(); // в кус фильтр по дате создания заявки
            $kyc->save();
        }
        if (isset($post['complete']) && $user) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('info', 'Вы успешно зарегистрировались на сайте! ');
            return $this->redirect('/clients');

        }
        return $this->render('register-client/step-4-complete', [
            'lang' => $this->lang,
        ]);

    }


    // проверка по смс
    public function actionCheckSms()
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';


        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');

        } else {
            return $this->redirect('/');

        }

        // echo $user_id; exit;
        $sms_code = Yii::$app->session->has('sms_code') ? Yii::$app->session->get('sms_code') : $this->uniqueId;

        $post = Yii::$app->request->post();
        if (isset($post['sms'])) {

            if ($post['sms'] == $sms_code) {
                // поиск пользователя и проверка кода из смс

                if ($user = User::find()->where(['id' => $user_id])->one()) {

                    if ($user->phone == '' || is_null($user->phone)) {
                        $user->phone = User::correctPhone($post['User']['phone']);
                    }
                    $user->phone_confirm = 1;
                    $user->save();

                    Yii::$app->user->login($user);
                    Yii::$app->session->setFlash('info', 'Вы успешно зарегистрировались на сайте!');

                    if ($user->role == User::ROLE_CLIENT) {
                        Yii::$app->session->set('phone', $user->phone);
                        return $this->redirect('/clients');

                    } elseif ($user->role == User::ROLE_SUPPLIER) {
                        return $this->redirect('/suppliers');

                    } elseif ($user->role == User::ROLE_KYC) {
                        return $this->redirect('/kyc');

                    }

                    Yii::$app->session->setFlash('info', 'Ошибка регистрации пользователя!');

                    return $this->redirect('/check-sms');

                } else {
                    Yii::$app->session->setFlash('info', 'Ошибка 1! Неверный код из СМС !');
                }

            } else {
                Yii::$app->session->setFlash('info', 'Ошибка 2! Неверный код из СМС !');
            }
        }


        return $this->render('check-sms', [
            'user_id' => $user_id
        ]);

    }

    // отправка смс кода
    public function actionSendSms()
    {

        $sms_code = SmsHelper::generateCode(4);

        //echo $_SERVER['DOCUMENT_ROOT'];

        if ($_SERVER['SERVER_NAME'] == 'crm1.loc') {
            Yii::$app->session->set('sms_code', '1234');
        } else {
            Yii::$app->session->set('sms_code', $sms_code);
        }

        $phone = Yii::$app->request->post('phone');

        $phone = User::correctPhone($phone);
        //return json_encode(['status'=>$phone,  /*'info'=>  $sms_code */ ]);
        //$user = User::findByPhone($phone);
//        return json_encode(['status'=>$user,  /*'info'=>  $sms_code */ ]);
//        return $user;

        // получаем созданного пользователя
        $user_id = Yii::$app->session->has('user_id') ? Yii::$app->session->get('user_id') : 0;

        // отправка смс на указанный номер
        if (($user_id && $user = User::find()->where(['id' => $user_id])->one())
            ||
            ($phone && $user = User::find()->where(['phone' => $phone])->one())) {

            $user->phone = $phone;
            if (!$user->save()) {
                $error = $user->getErrors();
                if (isset($error['phone'])) {
                    return json_encode(['status' => 0, 'info' => $phone . Yii::t('app', ' Данный номер телефона уже существует, укажите другой!')]);
                }
                return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при создании пользователя.') . ' ' . json_encode($error, JSON_UNESCAPED_UNICODE)]);
            }

            Yii::$app->session->set('user_id', $user->id);

            $user_id = $user->id;

            $text = Yii::t('app', '_code_ - Vash kod podtverzhdeniya dlya vhoda na platformu.  Priyatnih Vam pokupok. S Uvazheniem zMarket.');
            $text = str_replace('_id_', $user_id, $text);
            $text = str_replace('_code_', $sms_code, $text);

            SmsHelper::sendSms($phone, $text);

            return json_encode(['status' => 1,  /*'info'=>  $sms_code */]); // смс для теста УБРАТЬ!!!!

        } elseif (isset($phone)) {

            if ($user = User::find()->where(['phone' => $phone])->one()) {

                $text = Yii::t('app', 'Код подтверждения: _code_.');
                $text = str_replace('_code_', $sms_code, $text);

                SmsHelper::sendSms($phone, $text);

                return json_encode(['status' => 1,  /*'info'=>  $sms_code */]); // смс для теста УБРАТЬ!!!!
            }

        }

        return json_encode(['status' => 0, 'info' => Yii::t('app', 'Пользователь не найден:') . ' ' . $phone]);

    }


    public function actionCheckPhone()
    {

        $phone = Yii::$app->request->post('phone');
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if ($user = User::find()->where(['phone' => $phone])->one()) {
            return json_encode(['status' => 1]);
        }
        return json_encode(['status' => 0]);
    }

    // регистрация поставщика

    public function actionRegisterSupplier()
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';

        $post = Yii::$app->request->post();

        if (isset($post['User']['phone'])) {
            if ($post['offer'] == 1) {
                $phone = '998' . $post['User']['phone'];

                $phone = User::correctPhone($phone);
                // var_dump($phone);die;

                Yii::$app->session->set('phone', $phone);

                if ($user = User::find()->where(['phone' => $phone])->one()) {
                    Yii::$app->session->setFlash('info', 'Пользователь с данным номером телефона уже существует, укажите другой!');
                    return $this->refresh();
                }

                $sms_code = SmsHelper::generateCode(4);

                if ($_SERVER['SERVER_NAME'] == 'crm1.loc') {
                    Yii::$app->session->set('sms_code', '1234');
                } else {
                    Yii::$app->session->set('sms_code', $sms_code);
                }

                SmsHelper::sendSms($phone, $sms_code);

                Yii::$app->session->setFlash('info', 'На ваш номер телефона отправлен смс код для подтверждения!');

                return $this->redirect('/register-supplier-check-sms');

            }
        }

        return $this->render('register-supplier/step-1-phone', [
            'lang' => $this->lang,
        ]);

    }

    public function actionRegisterSupplierCheckSms()
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';
        $post = Yii::$app->request->post();
        $password_login = User::createLoginPassword();

        $sms_code = Yii::$app->session->has('sms_code') ? Yii::$app->session->get('sms_code') : $this->uniqueId;

        if (isset($post['sms'])) {

            if ($post['sms'] == $sms_code) { //  проверка кода из смс

                if ($user = User::create(User::ROLE_SUPPLIER)) {

                    $phone = Yii::$app->session->get('phone');
                    // var_dump($phone);die;
                    $user->phone = $phone;
                    $user->phone_confirm = 1;
                    $user->status = 0;
                    $user->password_login = $password_login;
                    $user->save(false);

                    Yii::$app->session->set('user_id', $user->id);

                    return $this->redirect('/register-supplier-passport');

                }

            } else {
                Yii::$app->session->setFlash('info', 'Неверный код из СМС !');
            }

        }

        return $this->render('register-supplier/step-1-check-sms', [
            'lang' => $this->lang,
        ]);

    }


    public function actionRegisterSupplierPassport()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if ($user = User::find()->where(['id' => $user_id])->one()) {
                if ($user->updateModel()) return $this->redirect('/register-supplier-complete');

            } else {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                return $this->redirect('/register-supplier');
            }
        }

        return $this->render('register-supplier/step-2-passport', [
            'lang' => $this->lang,
        ]);

    }


    public function actionRegisterSupplierComplete()
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';
        $post = Yii::$app->request->post();
        if (Yii::$app->session->has('user_id')) {
            $user_id = Yii::$app->session->get('user_id');
            if (!$user = User::find()->where(['id' => $user_id])->one()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Клиент не найден!'));
                return $this->redirect('/register-supplier');
            }
        }
        if (isset($post['complete']) && $user) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('info', 'Вы успешно зарегистрировались на сайте!');

            return $this->redirect('/suppliers');

        }
        return $this->render('register-supplier/step-4-complete', [
            'lang' => $this->lang,
        ]);

    }


    /*public function actionRegisterSupplier2(){


        $post = Yii::$app->request->post();

        if( isset($post['User']['username']) ) {

            if( $user = User::create(User::ROLE_SUPPLIER) ) {

                Yii::$app->session->set('user_id', $user->id);

                return $this->redirect('check-sms');
            }

        }

        return $this->render('register-supplier',[

        ]);

    } */

    // скачивание документов
    public function actionGetDocuments()
    {
        $id = (int)Yii::$app->request->get('id');
        User::getDocuments($id);
        exit;
    }


    public function actionApiTest()
    {


        $phone = "998901234500";
        $password = "1";
        $host_api = "http://{$_SERVER['SERVER_NAME']}/api/clients";
        //$param = 123;

        // авторизация
        $curl = curl_init($host_api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $phone . ":" . $password);
        // get запрос
        curl_setopt($curl, CURLOPT_URL, $host_api); //' ?param=$param");  если есть параметры
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        // вывести результат
        print_r($result);
        exit;


    }


    // ------------------------------


    public function actionAbout()
    {

        return $this->render('about', [

        ]);
    }

    public function actionPolitics()
    {
        return $this->render('politics', [

        ]);
    }

    public function actionOffer()
    {

        return $this->render('_offer', [

        ]);

    }

    public function actionFacture()
    {
        return $this->render('facture');
    }

    public function actionZcoin()
    {

        return $this->render('_zcoin', [

        ]);

    }

    public function actionStagetwo()
    {
        return $this->render('stage2');
    }

    public function actionContract()
    {

        return $this->render('_contract', [

        ]);

    }


    public function actionClist()
    {
        return $this->render('contract-list');
    }

    public function actionStageone()
    {
        return $this->render('stage1');
    }


    public function actionLogin()
    {

        $this->layout = '@frontend/views/layouts/cabinet-login.php';
        // вход для магазинов

        $post = Yii::$app->request->post();

        if (isset($post['password'])) {
            $password = @$post['password'];
            $id = @$post['login'];

            if ($user = User::findIdentity($id)) {
                if ($user->password_hash) {
                    if ($password && $user->validatePassword($password)) {
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash('info', 'Добро пожаловать в личный кабинет!');
                        return $this->redirect('/suppliers');
                    } else {
                        Yii::$app->session->setFlash('info', Yii::t('app', 'Неверный логин или пароль!'));
                        return $this->redirect('/suppliers');
                    }
                } else {
                    if ($user->password_login === $password) {
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash('info', 'Добро пожаловать в личный кабинет! Вы используете временный пароль, это может быть небезопасно для ваших данных.  Пожалуйста, смените свой пароль во вкладке "настройки" как можно скорее.');
                        return $this->redirect('/suppliers');
                    } else {
                        Yii::$app->session->setFlash('info', Yii::t('app', 'Неверный логин илипароль!'));
                        return $this->redirect('/suppliers');
                    }
                }
            }


            Yii::$app->session->setFlash('info', Yii::t('app', 'Пользователь не найден!'));
            return $this->redirect('/login');

        }

        if (!Yii::$app->user->isGuest) {
            // User::checkRole();
            switch (Yii::$app->user->identity->role) {
                case User::ROLE_CLIENT:
                    return $this->redirect('/clients');
                case User::ROLE_SUPPLIER:
                    return $this->redirect('/suppliers');
                case User::ROLE_KYC:
                    return $this->redirect('/kyc');
            }

            return $this->redirect('/login');
        }

        if (Yii::$app->session->has('user_id')) $this->actionCheckSms();

        return $this->render('login', [

        ]);

    }


    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect('/profile');
                }
            }
        }
        return $this->redirect('/');

    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('/'); // goHome();
    }


    public function actionZetLoginx()
    {
        $id = Yii::$app->request->get('id');
        if ($user = User::findOne($id)) {
            Yii::$app->user->login($user);
            return $this->redirect('/login');
        }

    }

    public function actionPrintAct($id)
    {

        $this->layout = 'print';
        $ref = Yii::$app->request->referrer;

        // поиск только своих кредитов
        if (!$credit = Credits::find()->with(['client', 'supplier', 'creditItems', 'paymentsAsc'])->where(['id' => $id])->one()) {

            return $this->redirect($ref);
        }
        //die(123);

        return $this->render('print-act', [
            'credit' => $credit
        ]);


    }

    public function actionGetOffer($id)
    {

        $this->layout = '@frontend/views/layouts/cabinet.php';
        $ref = Yii::$app->request->referrer;

        // поиск только своих кредитов
        if (!$credit = Credits::find()->with(['client', 'supplier', 'creditItems', 'paymentsAsc'])->where(['id' => $id])->one()) {

            return $this->redirect($ref);
        }

        return $this->render('_offer', [
            'credit' => $credit
        ]);


    }

    public function actionPrintInvoice($id)
    {

        $this->layout = 'print';
        $ref = Yii::$app->request->referrer;

        // поиск только своих кредитов
        if (!$credit = Credits::find()->with(['client', 'supplier', 'creditItems', 'paymentsAsc'])->where(['id' => $id])->one()) {
            return $this->redirect($ref);
        }

        return $this->render('print-invoice', [
            'credit' => $credit
        ]);


    }
	
	public function actionKatmFaktura() {
        return $this->render ('_katm_faktura');
    }
	
    public function actionGetFaktura() {
        $is_ajax = Yii::$app->request->isAjax;
        $post = Yii::$app->request->post();
        $prepare_date_start = strtotime(htmlspecialchars ($post['datestart']));
        $prepare_date_end = strtotime(htmlspecialchars ($post['dateend']));


        if($is_ajax){
            $model_katm = Katm::find()->where(['between', 'created_at', $prepare_date_start, $prepare_date_end])->count();
			
            return json_encode(['status'=>1,'info'=>$model_katm]);
		}else{
			return $this->redirect('/katm-faktura');
		}
	}


    public function actionPrintGraph($id)
    {

        $this->layout = 'print';
        $ref = Yii::$app->request->referrer;

        // поиск только своих кредитов
        if (!$credit = Credits::find()->with(['client', 'payments', 'supplier', 'creditItems', 'paymentsAsc'])->where(['id' => $id])->one()) {

            return $this->redirect($ref);
        }

        return $this->render('print-graph', [
            'credit' => $credit
        ]);


    }

    // оплата выбранного месяца кредита с помощью uzcard из кабинете клиента
    public function actionUzcardPayment()
    {

        $post = Yii::$app->request->post();

        /*
         *
         * [_csrf-frontend] => B-Rx_gF-IMjSYzNtB_CVZMN3c5WvZxVlvdgOQq3Dx5VjkEadTBMNn-Y3chtDnf8v8gdF0-UiJQrl6GEI5pGs7A==
            [MERCHANT_TRANS_AMOUNT] => 4600000.00
            [MERCHANT_ID] => 90050043806
            [MERCHANT_TERMINAL_ID] => 9146734
            [MERCHANT_TRANS_ID] => 1
            [SIGN_TIME] => 2019-12-04 05:38:17
            [SIGN_STRING] => a04f19a639f9784a8679c058c7d88e3e
            [RETURN_URL] => http://crm1.loc/clients/checkout
        */


        if (isset($post['SIGN_STRING'])) {

            $order_id = (int)$post['MERCHANT_TRANS_ID'];

            if ($payment = Payment::find()->with('user')->where(['id' => $order_id])->one()) {

                $signString = md5($post['SIGN_TIME'] . Payment::UZCARD_SECRET . $post['MERCHANT_TERMINAL_ID'] . $post['MERCHANT_TRANS_ID'] . $post['MERCHANT_TRANS_AMOUNT']);

                if ($post['SIGN_STRING'] == $signString) {
                    $user = $payment->user;
                    $sum = $payment->price * 100; // в тийинах

                    if (!$this->TEST_MODE) {
                        $result = Uzcard::discard($user, $sum);
                    } else { // имитация uzcard
                        $result = [
                            'id' => '61082322',
                            'username' => 'zmarket',
                            'refNum' => '007850949191',
                            'ext' => 'zmarket_1575896987',
                            'pan' => '860012******5202',
                            'pan2' => '',
                            'expiry' => '2408',
                            'tranType' => '',
                            'date7' => '1209180937',
                            'date12' => '191209180937',
                            'amount' => '1000',
                            'currency' => '860',
                            'stan' => '141220',
                            'field38' => '949191',
                            'field48' => '',
                            'field91' => '',
                            'merchantId' => '90485570',
                            'terminalId' => '92404056',
                            'resp' => '0',
                            'respText' => 'Successful transaction',
                            'respSV' => '00',
                            'status' => 'OK',
                        ];
                    }

                    if (isset($result['status']) && $result['status'] == 'OK') {

                        $data['UzcardPayments'] = $result['result'];
                        $trans_id = $result['result']['id'];
                        unset($result['result']['id']);

                        $up = new UzcardPayments(); // учет снятия средств
                        if ($up->load($data)) {
                            $up->user_id = $payment->user->id;
                            $up->payment_id = $payment->id;
                            $up->credit_item_id = 0; // test
                            $up->created_at = time();
                            $up->trans_id = $trans_id;
                            $up->save();
                        }

                        $payment->status = 1; // 'SUCCESS';
                        $payment->state = 1;

                        if (!$payment->save()) {
                            Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка при сохранении результатов оплаты!'));

                        } else {
                            // сохранение оплаты в общей таблице по всем платежным системам
                            Payment::payment($order_id, Payment::PAYMENT_TYPE_UZCARD);
                            Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата прошла успешно!'));
                            return $this->redirect($post['RETURN_URL']);

                        }
                    }

                } else {
                    Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка. Подпись не подтверждена.'));

                }

            } else {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка. Заказ не найден.'));

            }

        } else {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Ошибка. Подпись не подтверждена.'));
        }
        return $this->redirect($post['RETURN_URL']);


    }

    // отправка на регистрацию автосписания к uzcard или paymo
    public function actionSendOtp()
    {

        if ($_SERVER['SERVER_NAME'] == 'crm1.loc') return json_encode(['status' => 1, 'info' => 'local otp']);
        $user_id = Yii::$app->user->isGuest ? 0 : $this->user->id;
        return Scoring::sendOtp($user_id);

    }

    public function actionCheckOtp()
    {
        if ($_SERVER['SERVER_NAME'] == 'crm1.loc') return json_encode(['status' => 1, 'info' => 'local check otp']);

        return Scoring::checkOtp();

    }


    public function actionError()
    {
        if (preg_match('(\/api\/)', $_SERVER['REQUEST_URI'])) {
            echo json_encode(['error' => 'Request not found!'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        return $this->render('error');
    }

    public function actionBonus()
    {

        return $this->render('_bonus');
    }

    public function actionVendors()
    {

        $partners = Partners::find()->where(['status'=>1])->asArray()->all();
        foreach ($partners as $partner) {
            $url = Url::to(['vendors/vendors-page', 'id' => $partner['id']]);
        }

        //$partnersCats = PartnersCats::find()->asArray()->all();


        return $this->render('_vendors', [
            'partners' => $partners,
            'partnersCats' => $partnersCats,
        ]);
    }

    //Cтраница партнера
    public function actionVendorsPage()
    {
        $get_request = Yii::$app->request->get();
        $partner = (new\yii\db\Query())->select('*')->from('partners')->where('id=:id', [':id' => $get_request['id']])->one();
        return $this->render('_vendors_page', ['partner' => $partner]);
    }

    public function actionHowItWorks()
    {

        return $this->render('_how-is-work');
    }

    public function actionPartnership()
    {

        return $this->render('_partnership');
    }

    public function actionFaq()
    {

        return $this->render('_faq');
    }

    public function actionDict()
    {


        FileHelper::createDictionary();
        exit;
    }

    public function actionUzcard()
    {

        //$user = User::findOne(101);
        //$card = '8600120473225202';
        //$exp = '2408';

        $card = '8600120431212326';
        $exp = '2409';

        //$data = Uzcard::cardOtp($card,$exp);
        //print_r($data);

        //exit;
        $code = '202668';

        $token = '9A342D7F5C27AC7BE0530100007F9149';
        $data = Uzcard::cardVerify($token, $code);
        print_r($data);

        exit;

    }


    public function actionTest()
    {


        $clients = [1, 2, 3];
        Yii::$app->session->setFlash('autodiscard', '1');
        User::updateAll(['nds' => 0], ['role' => $clients]);

        exit;

        $user = User::findOne(271);

        echo 'cards.get';
        $res = Uzcard::cardsGet($user);

        print_r($res);

        exit;

        //$data = Uzcard::discard($user,1000);
        //print_r($data);
        //exit;

        $token = '9A342D7F5C27AC7BE0530100007F9149';
        //exit;
        //$data = Uzcard::scoring($token,$user,1000000,'01012019','01122022');
        //print_r($data);
        //exit;

        // echo 'scoringGetToken';

        $card = '8600120473225202';
        $exp = '2408';

        $card = '8600492964185694';
        $exp = '2405';

        $card = '8600112919958884';
        $exp = '2211';

        $card = '8600312974859072';
        $exp = '2309';

        $card = '8600140251821014';
        $exp = '2212';

        $card = '8600530402727054';
        $exp = '2311';


        $card = '8600120473225202';
        $exp = '2408';

        $data = Uzcard::cardOtp($card, $exp);
        print_r($data);
        exit;


        $token = '99F69BE058AF0424E0530100007F2228'; //$data['Scoring']['token'];
        $code = '631164';

        $token = '987C1DD91B07658CE0530100007FCD6A';
        //$data = Uzcard::cardVerify($token,$code);
        //print_r($data);

        //$data = Uzcard::scoringGetToken($card,$exp);
        //print_r($data);

        $token = '9A338DDB8EB187F4E0530100007FB0B2';
        //exit;
        $data = Uzcard::scoring($token, $user, 1000000, '01012019', '01112019');
        print_r($data);
        exit;


        //$data = Uzcard::scoringGetToken_cardsnew($card,$exp);
        //print_r($data);
        //exit;
        //echo $token;
        //exit;


        //exit;

        //$data = Uzcard::scoringGetToken_cardnsew($card,$exp);
        //print_r($data);
        //exit;
        echo 'scoring';

        $data = Uzcard::discard($user, 1000);
        print_r($data);


        //$data = Uzcard::scoring($user,1000000,'01012019','01112019');
        //print_r($data);


        exit;

        /*

        При получении access token необходимо использовать grant_type = client_credentials.
        Адрес для получения токена: https://api.pays.uz:8243/token
        Consumer key: CyTRhrbG8E4sv2pz6jiiUkj98p4a
        Consumer Secret: 9Vf5Pali74Wyf9I3KnMOb_54Issa

        Описание веб-сервиса
        Сервис по переданной карте анализирует движения средств по карте за последние 12 месяцев и возвращает способность клиента выплатить указанную в запросе сумму.
        Адрес веб-сервиса: https://api.pays.uz:8243/scoring/get-monthly
        Входящие параметры (методом POST):


        */
        /*
        POST /oauth/token HTTP/1.1
        > Host: connect.mail.ru
        > Content-Type: application/x-www-form-urlencoded
        >
        > grant_type=authorization_code&client_id=464119&client_secret=deadbeef&code=DoRieb0y&
          redirect_uri=http%3A%2F%2Fexample.com%2Fcb%2F123
          */

        $url_token = 'https://api.pays.uz:8243/token';
        $url_scoring = 'https://api.pays.uz:8243/scoring/get-monthly';
        $host = 'api.pays.uz';


        $data = 'grant_type=client_credentials';
        $key = 'CyTRhrbG8E4sv2pz6jiiUkj98p4a';
        $secret = '9Vf5Pali74Wyf9I3KnMOb_54Issa';

        $ch = curl_init($url_token);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $key . ':' . $secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        print_r($result);

        $token = $result['access_token'];


        $data = json_encode([
            'card_number' => '8600312905897001',
            'card_expiry' => '2302',
            'amount' => '10000000',
            'percent' => '50',
        ]);

        $ch = curl_init($url_scoring);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_USERPWD, 'Authorization: Bearer ' . $token);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $result = curl_exec($ch);

        curl_close($ch);

        print_r($result);

        /* header("POST $url_scoring HTTP/1.1\r\n");
         header("Host: $host\r\n");
         header('Authorization: Bearer ' . $token."\r\n");
         header("Content-type: application/json\r\n");
         header("Content-length: " . strlen($data) . "\r\n");
         header("Connection: close\r\n\r\n");
          header($data);
         //$result = json_decode($result,true);

         */
        //print_r($result);


        exit;


        // $user = User::find()->where(['id'=>110])->one();
        /*$user = User::find()->where(['id'=>101])->one();
         $sum = 1000;

        Uzcard::discard($user,$sum);
        exit; */


        //$credit = Credits::findOne(29);
        //Uzcard::scoring($credit);

    }

    public function actionTestApi()
    {

        //delete from credit_history where credit_id IN (select id from credits where status=9);
        //delete from credit where status=9;
        //delete from contracts where status=9;


        //echo time(); exit;

        echo '205000  20500<br>';

        // тестовый кредит
        $credit = new Credits();
        $credit->user_id = 101;
        $credit->supplier_id = 0;
        $credit->deposit_first = 24000;
        $credit->credit_limit = 10;
        $credit->contract_id = 0;
        $credit->created_at = time();
        $credit->price = 205000;
        $credit->credit = 205000;
        $credit->quantity = 1;
        $credit->credit_date = strtotime('01.05.2020 00:00:00');
        $credit->user_confirm = 1; // подтвержден клиентом
        $credit->confirm = 1; //  подтвержден поставщиком!
        $credit->status = 9; // тестовый
        if (!$credit->save()) {
            return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при создании кредита! ' . json_encode($credit->getErrors(), 256))]);
        }

        $m2 = 4;
        // тестовый план график оплат
        for ($m = 5; $m < 15; $m++) {  // 10 месяцев
            $ch = new CreditHistory();
            $ch->price = 20500; //  в сум
            $ch->credit_id = $credit->id;
            $ch->delay = 0;
            $ch->payment_date = 0;
            if ($m > 12) {
                $m2 = 1;
            } else {
                $m2++;
            }
            $ch->credit_date = strtotime('01.' . $m2 . '.2019'); // дата ожидаемой оплаты
            $ch->payment_type = 0;
            $ch->payment_status = 0;
            $ch->save();
        }


        // тестовый договор
        $contract = new Contracts();
        $contract->created_at = time();
        $contract->credit_id = $credit->id;
        $contract->user_id = $credit->user_id;
        $contract->supplier_id = 0;
        $contract->date_start = strtotime('01.04.2019'); // дл теста с апреля 2019 года
        $contract->date_end = strtotime('01.04.2020');
        $contract->status = 9; // тестовый
        if (!$contract->save()) {
            return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при создании договора! ' . json_encode($contract->getErrors(), 256))]);

        }
        echo 'contract_id: ' . $contract->id . '<br>';

        $credit->contract_id = $contract->id;
        $credit->save(false);

        $user = User::find()->with('scoring')->where(['id' => 101])->one();

        $card_id = $user->scoring->token;
        echo $card_id;
        exit;
        // Paymo::paySheduleCreate($contract->id,$user->phone,$card_id,$contract->date_start,$contract->date_end);


    }

    // проверка баланса карты вручную - боевой
    public function actionTestCard()
    {

        $user = User::findOne(152295);
       // $res = Uzcard::cardsGet($user);

        $request_id = 'zmarket_' . time();
        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.get',
            'params' => [
                'ids' => [
                    //'A108DC3DDF6350AEE0530100007FC6F2',
                    $user->scoring->token
                ],
            ],
        ]);


        $login = 'zmarket';
        $pw = 'H#x%kfte[Bk}xxVT{Market<rY?(n';
        $url = 'https://172.16.249.52:47007/api/jsonrpc';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pw);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);
        $result = $_result;

        echo '<pre>' . print_r($result, 1) . '</pre>';
        //echo '<pre>' . print_r($res, 1) . '</pre>';
        //echo '<pre>' . print_r($res['result'][0]['balance'], 1) . '</pre>';

    }

// оплата вперед по желанию клиента
    public function actionPay()
    {

        if(!Yii::$app->session->has('user_id')){

            header('location:/');
            exit;
        }

        $user_phone = Yii::$app->session->has('phone') ? Yii::$app->session->get('phone') : 0;
        //return $this->response(['error' => 'API. ' . 'Data not found'], 404);

        $this->layout = '@frontend/views/layouts/cabinet.php';
        $post = Yii::$app->request->post();
        //$phone = '998' . $post['User']['phone'];
        $sum_tiin = $post['User']['amount'] * 100;
        //$phone = User::correctPhone($phone);

        if (isset($post['User']['amount'])) {
            $user = User::find()->where(['phone' => $user_phone])->one();
            $token = $user->scoring->token;
            $uzcard = new Uzcard();

            $res = json_decode($uzcard->discard($user, $sum_tiin),true);
            if ($res['result']['status'] == 'OK') {
               //$flash =  Yii::$app->session->setFlash('info-success','Списание успешно завершено!');
                $info = ' ' . Yii::t('app', 'Списание прошло успешно!');

                SmsHelper::sendSms($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $sum_tiin / 100 );
                // log cvc
                Uzcard::debug_pay('sms');
                Uzcard::debug_pay($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $sum_tiin / 100 . $info);
                 // log результат от узкарда
                    Uzcard::debug_pay('trans.pay');
                    Uzcard::debug_pay($res);

                // запись транзакции по всем платежкам
                $payment = new Payment();
                $payment->created_at = time();
                $payment->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                $payment->state = Payment::PAYMENT_STATE_SUCCESS;
                $payment->price = $sum_tiin/100; //$credit_item->price; // тут что за сумма??
                $payment->user_id = $user->id;
                $payment->supplier_id = '';
                $payment->credit_id = '';
                $payment->credit_item_id = '';
                $payment->status =  'COMPLETE';
                $payment->save();

                // Запись в Billing History
                $billing_history = new BillingHistory();
                $billing_history->user_id = $user->id;
                $billing_history->created_at = time();
                $billing_history->summ = $sum_tiin/100;
                $billing_history->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                $billing_history->state = Payment::PAYMENT_TYPE_UZCARD;
                $billing_history->status = 1;
                $billing_history->save();

                $user->summ += $billing_history->summ; // увеличение баланса клиента
                $user->save();

                $trans_id = $res['result']['id'];
                unset($res['result']['id']);


                $utp = new UzcardPayments(); // учет снятия средств
                //if( $utp->load($data) ){
                foreach ($res["result"] as $k => $v)
                    $utp->$k = strval($v);

                $utp->user_id = $user->id;
                $utp->payment_id = $payment->id;
                $utp->created_at = time();
                $utp->trans_id = $trans_id;
                if (!$utp->save()) {
                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving UzcardPayments. ' )]);
                }

                return $this->redirect('/pay-complete');

            }
            if(!$res['result']['status'] == 'OK'){


                //SmsHelper::sendSms($phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $sum_tiin / 100 . $info);
                // log cvc
                Uzcard::debug_pay('sms');
                Uzcard::debug_pay($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $sum_tiin / 100 );
                // log результат от узкарда
                Uzcard::debug_pay('trans.pay');
                Uzcard::debug_pay($res);
                Yii::$app->session->setFlash('info', $res['error']['message']);

            }

        }
        //print_r($user);

        return $this->render('pay', [

        ]);

    }

    //  вручную - боевой
    public function actionReverse()
    {
        $tranId = '**'; // refnum
        $uzcard = new Uzcard();
        echo 'boyevoy reverse '.$uzcard->cardsReverse($tranId);

    }



    // автосписание cron - боевой
    public function actionCronDiscard()
    {
        $uzcard = new Uzcard();
        $uzcard->autoDiscard();

    }

    // cron Reco Humo
    public function actionHumoReco(){
        HumoHelper::HumoReco();
    }


    // правка договора вручную на страхование - новый
    public function actionTestAsko(){
        // отправка договора на страхование Asko
        /*$user_id = 166759;
        $amount = 182621900; // tiin
        $term = 9;
        $credit = 1298;*/

        if (!$user = User::find()->where(['id' => $user_id])->one()) {
            return json_encode(['status' => 1, 'info' => Yii::t('app', 'Клиент не найден!')], JSON_UNESCAPED_UNICODE);
        }

        $asko = new Asko();
        $result = Asko::askoInfo($user, $amount, $term, $credit);

        print_r($result);
    }

    // правка договора вручную на страхование - старый
    public function actionTestPolis(){

        $id = 810; // credit_id
        $supplier_id = 150103;

        if($credit = Credits::find()->where(['id'=>$id,'supplier_id'=>$supplier_id])->one() ){


        }
        // отправка договора на страхование
        $result = PolisHelper::getPolisForCredit('zMarket_' . $credit->contract_id, $credit);
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

            if (!$polis->save()) {
                return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
            }

            if ($contract = Contracts::find()->where(['credit_id' => $credit->id])->one()) {
                $contract->status_polis = 1;
                $contract->save();
            }

        } else {

            return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.') . json_encode($result,JSON_UNESCAPED_UNICODE)],JSON_UNESCAPED_UNICODE);

        }

    }




    public function actionBalance(){
        $token = 'A1EC6871E2F2429AE0530100007FEA3E';
        //$balance = new Uzcard();
       // echo 'hi - boyevoy'. $balance->getBalance($token);

        $request_id = 'zmarket_' . uniqid(rand(),1) ;
        $url = 'https://172.16.249.52:47007/api/jsonrpc';
        $login = 'zmarket';
        $pw = 'H#x%kfte[Bk}xxVT{Market<rY?(n';

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.get',
            'params' => [
                'ids' => [
                    $token
                ],
            ],
        ]);


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pw);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);
        print_r($result);
    }

    public function actionGetHumoBalance()
    {
        //$humo = new Humo();
        //$card = '9860020101338315';
        $card = '9860330101025055';
        //$balance = $humo->humoBalance($card);
        $balance = HumoHelper::humoBalance($card);
        return $balance;
    }

    public function actionGetHumoPhone(){
        //$card = '9860090101328467';
        //$card = '9860020101069563'; // sardor
        $card =  '9860270101913498'; // sardor сестра
        //$card = '9860020101338315'; // братишка
        //$card =  '9860200101773014'; // sardor брат
        //$card = '9860082402501603'; // бабуля
        //$card = '9860010130134496'; // sardor
        $bank_c = '01';
        $inform = HumoHelper::humoSmsBanking($card, $bank_c);
        var_dump($inform);
    }

    public function actionHumoScoring(){
        $card =  '9860330101025055'; // sardor сестра
        //$card = '9860020101338315'; // sardor братишка
        //$card =  '9860200101773014'; // sardor брат
        //$card = '9860020101069563'; // sardor мама
        //$card = '9860090101697622';  // test humo
        //$card = '9860090101328467'; // мой

        $bank_c = '33';
        $limit = 100000000; // в тиинах
        $scoring = HumoHelper::humoScoring($card,$bank_c,$limit);
        return $scoring;
    }

    public function actionGetHumoExp(){
        $card =  '9860010130134496';
        $bank_c = '01';
        $exp = HumoHelper::humoGetExp($card,$bank_c);
        return $exp;
    }

    public function actionHumoDiscard(){
        $card_h = '0101913498';
        $bank_c = '27';
        $exp = '2204';
        $amount = 100;
        /*$humo = new Humo();
        $result = $humo->HumoDiscard($card_h,$bank_c,$exp,$amount);*/
        $result = HumoHelper::HumoDiscard($card_h,$bank_c,$exp,$amount);
        //return $result;
        $result = json_decode($result, true);
        //print_r($result);
        if(isset($result['payment_id'])){
            $humo_payments = new HumoPayments();
            $humo_payments->user_id = 163734;
            $humo_payments->credit_item_id = 3333;
            $humo_payments->created_at = time();
            foreach($result as $k => $v){
                var_dump($k . ' - ' .$v); echo '<br>';
                $humo_payments->$k = $v;
                if(!$humo_payments->save()){
                    return 'error' . json_encode($humo_payments->getErrors(), JSON_UNESCAPED_UNICODE);
                }
            }
        }else{
            echo 'error ';
            return $result;
        }
    }
    public function actionHumoReverse(){
        $centre_id = 'OFB';
        $payment_id = '26112009';
        $humo = new Humo();
        $result = $humo->HumoReverse($centre_id,$payment_id);
        return $result;
    }

    // upay получить сумму штрафа
    public function actionGetSum(){
        $service_id = 1; // сервис
        //$account = '220242'; // логин
        $account = 'he2620323'; // номер штрафа или тел номер*/
        $result = UpayHelper::getSum($account, $service_id);
        //print_r($result);
        print_r($result->return->Result->code);  // ФИО аккаунта провайдеров
    }
    // оплатить
    public function actionUpayBank(){
        $service_id = 1;
        $amount_with_tiyin = 100000;
        $account = 'he2620323'; // номер штрафа или тел номер
        $result = UpayHelper::BankPayment($service_id, $account, $amount_with_tiyin);
        print_r($result);
    }

    // upay сгенерить токен для getServiceList
    public function actionToken(){
        $login = 'zmarket';
        $password = '3M@rk3t!';
        $service_id = 1;
        //$amount_with_tiyin = 200000;
        //$account = 'KV18082914394'; // номер штрафа или тел номер*/
        $account = 'he2620323'; // номер штрафа или тел номер*/
        //$partner_trans_id = time();
        // md5('zmarket4090319463420000015929104553M@rk3t!'); - пример генерации для мобильной
        //md5(Username + serviceId + acсount + AmountWithTiyin + PartnerTransId + password)
        //$token = md5(UserName + serviceId + regionCode + subRegionCode + account + Password);
        $token = md5($login .  $service_id . $account  /*. $amount_with_tiyin . $partner_trans_id*/ . $password);
        return $token ;
    }
    // getServiceList
    public function actionUpayList(){
        $category_id = 'OFB';
        $result = UpayHelper::getServiceList($category_id);
        print_r($result);
    }






    // отправлка вручную на страхование Asco
    public function actionSendList(){

        $clients = [
            [
                'clientId' => '150991',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '16',
                'sum' => 743666.66,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151153',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '33',
                'sum' => 915750.00,
                'date' => '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151153',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '35',
                'sum' => 432450.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151285',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '37',
                'sum' => 355275.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151153',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '39',
                'sum' => 152550.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151551',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '43',
                'sum' => 1015006.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151545',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '45',
                'sum' => 610200.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151610',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '47',
                'sum' => 582187.50,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151285',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '50',
                'sum' => 130000.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '150680',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '52',
                'sum' => 1009493.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151635',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '55',
                'sum' => 1018205.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '150991',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '58',
                'sum' => 438115.04,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151789',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '68',
                'sum' => 1042824.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '151262',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '69',
                'sum' => 126604.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '152345',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '74',
                'sum' => 561920.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '152124',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '82',
                'sum' => 36459.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '152515',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '87',
                'sum' => 627642.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '152531',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '91',
                'sum' => 107787.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '152579',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '95',
                'sum' => 142629.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '153976',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '114',
                'sum' => 638777.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '154528',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '115',
                'sum' => 551919.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
            [
                'clientId' => '156090',
                'polisSeries' => 'E-ZMK',
                'polisNumber' => '121',
                'sum' => 628480.00,
                'date' =>  '22-06-2020'// (01-01-2019 dd-MM-yyyy) задолженность на дату
            ],
        ];


        $request_id = 'zMarket_' .time();
        $bordero_id = 3;
        $maxPackageNum = 1;
        $curPackageNum = 1;

        $result = PolisHelper::sendCustomerList($request_id,$bordero_id, $maxPackageNum, $curPackageNum, $clients);

        print_r($result);
        //var_dump($clients) ;

        //$responseId = $result['responseId'];
        //$status = $result['error']['code'];
        //print_r($responseId);
        /*if ($responseId) {
            echo 1;
            $polis_cust_list = new PolisCustomerList();
            $polis_cust_list->created_at = time();
            $polis_cust_list->credit_id = $credit->id;
            $polis_cust_list->client_id = $polis->client_id;
            $polis_cust_list->responseId = $responseId;
            $polis_cust_list->polisSeries = $polis->polisSeries;
            $polis_cust_list->polisNumber = $polis->polisNumber;
            $polis_cust_list->status = $status;
            if (!$polis_cust_list->save()) {
                Yii::$app->session->setFlash('info', 'Ошибка при сохранении данных от страховой компании ');

                //return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]);
            } else {
                Yii::$app->session->setFlash('info', 'сохранение данных от страховой компании прошло успешно! ');

            }

        }*/
    }


    public function actionTest3(){
    // удалить по таблицам кредиты оптом
        $credits = Credits::find()->where(['user_id' => 150780])->all();

        foreach ($credits as $credit) {

            $credits_ids = [];
            $credits_ids[] = $credit->id;

            foreach($credits_ids as $credits_id){
                if($credits_history = CreditHistory::find()->where(['credit_id' => $credits_id])->all()){
                    foreach($credits_history as $credit_history){
                        echo '<pre>'.print_r ($credit_history,1).'</pre>';
                       // $credit_history->delete();
                    }
                }

                if($credits_items = CreditItems::find()->where(['credit_id' => $credits_id])->all()){
                    foreach($credits_items as $credits_item){
                        echo '<pre>'.print_r ($credits_item,1).'</pre>';
                        //$credits_item->delete();
                    }
                }
                if($contract = Contracts::find()->where(['credit_id' => $credits_id])->one()){
                    echo '<pre>'.print_r ($contract,1).'</pre>';
                    //$contract->delete();

                }
            }

            //$credit->delete();

        }



    }







}






