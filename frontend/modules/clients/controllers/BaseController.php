<?php

namespace app\modules\clients\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public $lang = '';
    public $user = null;

    public function beforeAction($action)
    {
        $this->layout = '@frontend/views/layouts/cabinet.php';

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

        if( $this->user->role == User::ROLE_SUPPLIER ) return $this->redirect('/suppliers');
        if( $this->user->role == User::ROLE_KYC ) return $this->redirect('/kyc');
        if( $this->user->role != User::ROLE_CLIENT ) return $this->redirect('/');

        /*if($this->user->auto_discard==0 && $action->id != 'confirm-auto-discard' ){
            return $this->redirect('/clients/confirm-auto-discard');
        }*/
        return parent::beforeAction($action);

    }


}
