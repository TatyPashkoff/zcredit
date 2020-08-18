<?php

namespace backend\controllers;

use Yii;
use common\models\PartnersCats;
use common\models\PartnersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnersController implements the CRUD actions for Partners model.
 */
class CatsPartnersController extends Controller
{

//echo Url::to(['post/index'], true);


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action){

        if(Yii::$app->user->isGuest){

            header('location:/login');
            exit;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Partners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new PartnersCats();

        $dataProvider = $model->search(Yii::$app->request->queryParams);


        if ($model->updateModel(true)) {
            return $this->redirect(['/']);
        }

        return $this->render('list_categories', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateCat()
    {
        $model = new PartnersCats();

        if ($model->updateModel(true)) {
            return $this->redirect(['/cats-partners']);
        }

        return $this->render('create_cat', [
            'model' => $model,
        ]);
    }


        /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->updateModel()) {
            return $this->redirect(['/cats-partners', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,


        ]);
    }

    /**
     * Deletes an existing Partners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if( $model = $this->findModel($id) ) {

            $model->delete();
        }
        return $this->redirect(['/cats-partners']);
    }


    /**
     * Finds the Partners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartnersCats::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
