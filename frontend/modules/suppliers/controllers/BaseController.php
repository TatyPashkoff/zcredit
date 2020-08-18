<?php

namespace app\modules\suppliers\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public $lang = '';
    public $user = null;

    public function beforeAction($action)
    {

        $lang = Yii::$app->session->get('lang');
        if( $lang =='' ) $lang = 'ru';
        if($this->lang != $lang){
            $this->lang = $lang;
            Yii::$app->language = $lang;
        }

        if( $action->id !='login' && Yii::$app->user->isGuest ) {
            header('location: /login');
            exit;
            //return $this->redirect('/login' );
        }

        $this->user = User::findOne(Yii::$app->user->id);

        if( $this->user->role == User::ROLE_KYC ) return $this->redirect('/kyc');
        if( $this->user->role == User::ROLE_CLIENT ) return $this->redirect('/clients');
        if( $this->user->role != User::ROLE_SUPPLIER ) return $this->redirect('/');

        $this->layout = '@frontend/views/layouts/cabinet.php';



        return parent::beforeAction($action);

    }


}
