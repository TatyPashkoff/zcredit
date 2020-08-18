<?php
namespace common\helpers;

use Yii;

class LangHelper {

    public static function t( $ru, $uz='', $en='' ){ // перевод

        $lang = Yii::$app->session->get('lang');

        switch($lang){
            case 'ru':
                return $ru;
                break;
            case 'uz':
                return $uz !='' ? $uz : $ru;
                break;
            case 'en':
                return $en !='' ? $en : $ru;
                break;
        }
        return $ru;
    }

    public static function getLang(){

        $lang = Yii::$app->session->get('lang');

        $params = Yii::$app->request->getQueryParams();
        if(isset($params['lang']) && $lang ==$params['lang']){
            $lang = $params['lang'];

            Yii::$app->language = $lang;
            Yii::$app->session->set( 'lang', $lang );
        }

        return $lang;
    }


	
}