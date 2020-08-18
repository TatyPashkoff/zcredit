<?php

namespace app\modules\clients;

use yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\clients\controllers';
	

    public function init()
    {
        parent::init();


        if( Yii::$app->user->isGuest ) {

            $test = false;

			if($test){
				
				//return $this->redirect('/login');
				header('location: /login');
				exit;
			}
        }
        

    }



}
