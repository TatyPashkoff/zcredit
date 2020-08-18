<?php
namespace backend\controllers;

use common\models\CreditHistory;
use common\models\CreditHistorySearch;
use common\models\CreditItems;
use common\models\CreditItemsSearch;
use common\models\Credits;
use common\models\CreditsSearch;
use common\models\User;
use common\models\UserSearch;
use Yii;


/**
 * Site controller
 */
class ClientsController extends BaseController
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
        $dataProvider = $searchModel->searchClients(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCredits()
    {
        $searchModel = new CreditsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(isset(Yii::$app->request->queryParams['user_id'])){
            Yii::$app->session->set('user_id',Yii::$app->request->queryParams['user_id']);
           // $user_id = Yii::$app->request->queryParams['user_id'];
        //}else{
           // $user_id = 0;
        }

        return $this->render('credits', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'user_id' => $user_id,
        ]);
    }

    public function actionCreditItems()
    {
        $searchModel = new CreditItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $user_id = Yii::$app->session->has('user_id') ?  Yii::$app->session->get('user_id') : 0;

        //print_r($dataProvider); exit;

        return $this->render('credit-items', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_id' => $user_id,
        ]);
    }

    /**
     * @return string
     */
    public function actionPayments()
    {
        $searchModel = new CreditHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $user_id = Yii::$app->session->has('user_id') ?  Yii::$app->session->get('user_id') : 0;

        return $this->render('payments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_id' => $user_id,
        ]);
    }

    public function actionUpdatePayment($id)
    {
        $model = CreditHistory::find()->where(['id'=>$id])->one();

        $post = Yii::$app->request->post();
        if ( $model->load($post)  ){
            $credit_date = strtotime($post['CreditHistory']['credit_date'] . ' 00:00:00');
            if ($credit_date < 0) $credit_date = 0;
            $model->credit_date = $credit_date;

            $payment_date = strtotime($post['CreditHistory']['payment_date'] . ' 00:00:00');
            if ($payment_date < 0) $payment_date = 0;
            $model->payment_date = $payment_date;
            $model->save();
          return $this->redirect(['update-payment', 'id' => $model->id]);
        }

        $user_id = Yii::$app->session->has('user_id') ? Yii::$app->session->get('user_id') : 0;

        return $this->render('_payment', [
            'model' => $model,
            'user_id' => $user_id,
        ]);

    }
    public function actionUpdateItem($id)
    {
        $model = CreditItems::find()->where(['id'=>$id])->one();

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() ){

          return $this->redirect(['update-item', 'id' => $model->id]);
        }

        $user_id = Yii::$app->session->has('user_id') ? Yii::$app->session->get('user_id') : 0;

        return $this->render('_items', [
            'model' => $model,
            'user_id' => $user_id,
        ]);

    }

    public function actionUpdateCredit($id)
    {
        $model = Credits::find()->where(['id'=>$id])->one();

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save() ){

          return $this->redirect(['update-credit', 'id' => $model->id]);
        }

        $user_id = Yii::$app->session->has('user_id') ? Yii::$app->session->get('user_id') : 0;

        return $this->render('_credit', [
            'model' => $model,
            'user_id' => $user_id,
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

    public function actionDelete($id)
    {
        if($user = User::findOne($id) )  $user->delete();

        return $this->redirect(['index']);
    }
}
