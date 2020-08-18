<?php

namespace app\modules\suppliers;

use yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\suppliers\controllers';
	

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
