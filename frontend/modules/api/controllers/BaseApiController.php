<?php

namespace app\modules\api\controllers;

use common\helpers\UtilsHelper;
use common\models\ApiUser;
use Exception;
use RuntimeException;
use Yii;
use yii\web\Controller;

class BaseApiController extends Controller
{

    public $user = null;

    public function beforeAction($action)
    {

        $this->enableCsrfValidation = false;
        if(Yii::$app->user->isGuest) {
            echo $this->response(['error'=>'API. '  . Yii::t('app','Пользователь не найден!')], 404);
            exit;
        }
        $this->user = Yii::$app->user->identity;

        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");


        return parent::beforeAction($action);

    }

    protected function response($data, $status = 500) {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));

        UtilsHelper::debug($data);

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    private function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }


}
