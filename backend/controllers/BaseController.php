<?php


namespace backend\controllers;


use common\models\User;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{


    public function beforeAction($action)
    {


        if( $action->id !='login' && Yii::$app->user->isGuest  ) {

            return $this->redirect('/login'); // admin/login

        }


        if( $user = Yii::$app->user->identity ) {

            if ($user->role == User::ROLE_KYC) return $this->redirect('/kyc');
            if ($user->role == User::ROLE_CLIENT) return $this->redirect('/clients');
            if ($user->role == User::ROLE_SUPPLIER) return $this->redirect('/suppliers');
            if ($user->role == User::ROLE_ADMIN) return $this->redirect('/admin');

        }

        return parent::beforeAction($action);
    }

}