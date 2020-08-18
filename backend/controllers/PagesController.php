<?php

namespace backend\controllers;

use Yii;
use common\models\Pages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class PagesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    // главная страница
    public function actionMain(){

        if( $pages = Pages::find()->where(['page'=>'main'])->one() ){
            $data = json_decode($pages->data,true);

        }else{
            $pages = new Pages();
            $data=[];

        }

        $post = Yii::$app->request->post();
        $pages->load($post);
        if( isset($post['Pages']['page']) /*&& $post['Pages']['page']=='main'*/ ){

            $pages->data = json_encode($post['info'],JSON_UNESCAPED_UNICODE);

            $this->loadImage( $pages,'img1' ,'tmp_image');
           


            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            if(!$pages->save()){
                print_r($pages->getErrors());
                exit;
            }

            return $this->redirect('/admin/pages/main' );

        }

        return $this->render('union', [
            'model'=>$pages,
            'data' => $data,
            'type' => 'main',
            'title' => 'Главная страница'
        ]);

    }


	
    public function actionAbout(){

        if($pages = Pages::find()->where(['page'=>'about'])->one()) {
            $data = json_decode($pages->data, true);
        }else{
            $pages = new Pages();
            $pages->page = 'about';
            $data=[];
        }

        $post = Yii::$app->request->post();
        $pages->load($post);

        if( isset($post['Pages']['page']) && $post['Pages']['page'] == 'about' ){

            $pages->data = json_encode($post['info'],JSON_UNESCAPED_UNICODE);

            $this->loadBanner($pages,'about');

            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            if(!$pages->save()){
                print_r($pages->getErrors());
                exit;
            }
            return $this->redirect('/admin/pages/about');

        }

        return $this->render('union', [
            'data' => $data,
            'type' => 'about',
            'title' => 'О компании'
        ]);
    }


    public function actionPartners(){

        if($pages = Pages::find()->where(['page'=>'partners'])->one()) {
            $data = json_decode($pages->data, true);
        }else{
            $pages = new Pages();
            $data=[];
        }

        $post = Yii::$app->request->post();
        $pages->load($post);

        if( isset($post['Pages']['page']) ){

            $pages->data = json_encode($post['info'],JSON_UNESCAPED_UNICODE);

            $this->loadBanner($pages,'partners');

            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            if(!$pages->save()){
                print_r($pages->getErrors());
                exit;
            }
            return $this->redirect('/admin/pages/partners');

        }

        return $this->render('union', [
            'data' => $data,
            'type' => 'partners',
            'title' => 'Партнерство'
        ]);
    }

    // страница о нас
    public function actionContacts() {

        if ($pages = Pages::find()->where(['page' => 'contacts'])->one()) {
            $data = json_decode($pages->data, true);

        } else {
            $pages = new Pages();
            $pages->page = 'contacts';
            $data = [];

        } // страница контакты

        $post = Yii::$app->request->post();
        $pages->load($post);
        if( isset($post['Pages']['page']) && $post['Pages']['page'] == 'contacts' ){

            $pages->data = json_encode($post['pages'],JSON_UNESCAPED_UNICODE);
            if( ! $pages->save() ){
                print_r($pages->getErrors());
                exit;
            }
            return $this->redirect('/admin/pages/contacts');

        }

        return $this->render('contacts', [
            'data' => $data,
        ]);

    }

   

    private function update(&$model, $page){

        $post = Yii::$app->request->post();


        if($model->load($post) ) {

            $model->data = json_encode($post['pages'],JSON_UNESCAPED_UNICODE);


            if( !$model->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении документа!');
                print_r($model->getErrors());
                exit;

                return true;
            }

           // print_r([$post,$_FILES]); exit;

            Yii::$app->session->setFlash('info-success','Страница успешно сохранена!');

            return true;
        }
        return false;
    }


}
