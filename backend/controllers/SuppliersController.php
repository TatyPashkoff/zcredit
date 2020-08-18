<?php

namespace backend\controllers;

use common\models\Stock;
use common\models\User;
use common\models\UserSearch;
use common\models\VendorItems;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * CreditsController implements the CRUD actions for Credits model.
 */
class SuppliersController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchSuppliers(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = User::find()->where(['id'=>$id])->one();


        if ($this->update($model)){

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,

        ]);

    }

    private function update(&$model, $new=false){

        $post = Yii::$app->request->post();

        if( $model->load($post) ) {


            if(isset($post['User']['password'])) {
                $pw = @$post['User']['password'];
                //$pwc = @$post['User']['password_confirm'];

                //if ($pw == $pwc) {
                // смена пароля пользователя
                $user = User::findIdentity($model->id);
                $user->setPassword($pw);
                $user->password = $pw;
                $user->generateAuthKey();
                $user->save();

                //}
            }


            if(!$model->save()) {
                Yii::$app->session->setFlash('info','Ошибка при сохранении!');
                return false;
            }

            Yii::$app->session->setFlash('info','Сохранение успешно!');
            return true;
        }
        return false;
    }


    public function actionPrint(){
        $id =(int)Yii::$app->request->get('id');

        if(!$user = User::findOne($id)) {
            Yii::$app->session->setFlash('info','Клиент не найден!');
            return $this->redirect(['index']);
        }
        return $this->render('print',[
            'user' => $user,
        ]);

    }

    public function actionCreateFilial(){
        $id =(int)Yii::$app->request->get('id');

        if(!$old = User::findOne($id)) {
            Yii::$app->session->setFlash('info','Пользователь не найден!');
            return $this->redirect(['index']);
        }

        $password_login = User::createLoginPassword();

        $model =  new User;
        $model->setAttributes($old->getAttributes(), false);
        $model->id = null;
        $model->password_login = $password_login;  // create new password
        $model->address_filial = null;
        $model->nds_state = null;
        $model->nds = null;
        $model->phone = null;
        $model->discount = null;
        $model->margin_three = null;
        $model->margin_six = null;
        $model->margin_six = null;
        $model->seal_number = null;
        $model->seal_number = null;
        $model->printer_number = null;
        $model->filial = 1;
        $model->status_client_complete = 4;

        if($model->save(false)){
            Yii::$app->session->setFlash('info','Создан филиал магазина! ' );
            return $this->redirect(['update', 'id' => $model->id]);
        }

    }
    /**
     * Deletes an existing Suppliers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       if($this->findModel($id)->delete()){

           Yii::$app->session->setFlash('info','пользователь  ID: ' . $id . '  успешно удален!');
       }
        return $this->redirect(['index']);


    }

    /**
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Markers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
