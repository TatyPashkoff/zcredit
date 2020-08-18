<?php

namespace app\modules\api;

use common\helpers\UtilsHelper;
use yii;
use common\models\User;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init()
    {
        parent::init();

        $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
        //$phone = $_SERVER['PHP_AUTH_USER'] ?? null;  - test
        //if( $username && $user = User::findByPhone($username) ){ // - phone
        //if( $username && $user = User::findByUsername($username) ){ // логин - username



        if( $username && $user = User::findIdentity($username) ){ // логин - id
            $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null; // isset($get['password']) ? $get['password'] : '';

            if( $password && $user->validatePassword($password)){
                if( Yii::$app->user->login($user,86400) ){ // пробуем войти пользователем
                    UtilsHelper::debug('вход в систему api_user:'.$username);
                    UtilsHelper::debug('GET ' . var_export($_GET, true));
                    UtilsHelper::debug('POST ' . var_export($_POST, true));
                    return true;
                }
            }else{
                UtilsHelper::debug('неправильный логин или пароль api_user:'.$username);
                echo json_encode(['status'=>0,'error'=>['code'=>91,'info'=>'Incorrect login or password!']]);
                echo json_encode(['status'=>0,'error'=>['code'=>91,'info'=>'err login!']]);
                exit;

            }

        }

        UtilsHelper::debug('API пользователь не найден -  api_user:'.$username);
        echo json_encode(['status'=>0,'error'=>['code'=>90,'info'=>'API User not found!']]);
        exit;


    }




}
