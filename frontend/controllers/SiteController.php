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

        if ($_SERVER['SERVER_NAME'] == 'crm1.loc') {
            Yii::$app->session->set('sms_code', '1234');
        } else {
            Yii::$app->session->set('sms_code', $sms_code);
        }

        $phone = Yii::$app->request->post('phone');

        $phone = User::correctPhone($phone);

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



    // скачивание документов
    public function actionGetDocuments()
    {
        $id = (int)Yii::$app->request->get('id');
        User::getDocuments($id);
        exit;
    }



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

}






