<?php


namespace frontend\controllers;


use common\models\User;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{

    public $lang = 'ru';
    public $user = null;


    public function beforeAction($action)
    {

        $lang = Yii::$app->session->get('lang');
        if( $lang =='' ) $lang = 'ru';
        if($this->lang != $lang){
            $this->lang = $lang;
            Yii::$app->language = $lang;
        }

        if ($action->id == 'perform' || $action->id == 'complete' ) {
            Yii::$app->controller->enableCsrfValidation = false;
        }
           
        $this->user =  Yii::$app->user->identity;

        return parent::beforeAction($action); 
    }

}