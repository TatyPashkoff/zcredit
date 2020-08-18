<?php

namespace backend\controllers;

use common\helpers\PolisHelper;
use common\models\CreditItems;
use Yii;
use common\models\Credits;
use common\models\CreditsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CreditsController implements the CRUD actions for Credits model.
 */
class CreditsController extends BaseController
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

   /* public function beforeAction($action){

        if(Yii::$app->user->isGuest){

            header('location:/login');
            exit;
        }

        return parent::beforeAction($action);
    }*/

    /**
     * Lists all Credits models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CreditsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionItems(){

        //$id = Yii::$app->request->get('id');

        //$model = CreditItems::find()->where(['credit_id'=>$id])->all();

        $searchModel = new CreditsSearch();
        $dataProvider = $searchModel->searchItems(Yii::$app->request->queryParams);

        return $this->render('items', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionDetails(){

        //$this->layout = 'print';
        $get = Yii::$app->request->get();

        $credit_id = isset($get['credit_id']) ?(int)$get['credit_id']:0;

        if( !$credit = Credits::find()->with(['client','supplier','creditItems','paymentsAsc'])->where(['id'=>$credit_id])->one() ){
            //$credit = false;
            exit;
        }
        return $this->render('print-act',[
            'credit'=>$credit
        ]);

    }

    public function actionDetailsOffer(){

        $get = Yii::$app->request->get();
        $credit_id = isset($get['credit_id']) ?(int)$get['credit_id']:0;

        // поиск только своих кредитов
        if( !$credit = Credits::find()->with(['client','supplier','creditItems','paymentsAsc'])->where(['id'=>$credit_id])->one() ){
            exit;
        }

        return $this->render('_offer',[
            'credit' => $credit
        ]);

    }

    /**
     * sendindg Polis.
     * If sending is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSendpolis($id)
    {

        if ($credit = Credits::find()->where(['id' => $id])->one()) {

            // отправка договора на страхование
            $result = PolisHelper::getPolisForCredit('zMarket_' . $credit->contract_id, $credit);
            $result = PolisHelper::сheckTransaction('zMarket_' . $credit->contract_id, $credit);

            if (isset($result['original']) && isset($result['original']['contractRegistrationID'])) {
                // создание полиса
                $polis = new Polises();
                $polis->status = 1;
                $polis->created_at = time();
                $polis->contractRegistrationID = $result['original']['contractRegistrationID'];
                $polis->polisSeries = $result['original']['polisSeries'];
                $polis->polisNumber = (string)$result['original']['polisNumber'];
                $polis->client_id = $credit->user_id;
                $polis->credit_id = $credit->id;
                $polis->supplier_id = $credit->supplier_id;
                $polis->contract_id = $credit->contract_id;

                if (!$polis->save()) {
                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(), JSON_UNESCAPED_UNICODE))]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
                }

                if ($contract = Contracts::find()->where(['credit_id' => $credit->id])->one()) {
                    $contract->status_polis = 1;
                    $contract->save();
                }

                Yii::$app->session->setFlash('info','Договор успешно отправлен в страховую компанию!');
                return $this->redirect(['update', 'id' => $id]);

            } else {
                return json_encode(['status' => 0, 'info' => Yii::t('app', 'Ошибка при отправке договора в страховую компанию.') . json_encode($result, JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE);

            }
        }else{
            Yii::$app->session->setFlash('info','Кредит '.  $id  .' не найден!');
            return $this->redirect(['update', 'id' => $id]);
        }
    }


    /**
     * Creates a new Credits model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Credits();

        if ($model->updateModel(true)) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Credits model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->updateModel()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {

        
            return $this->render('update', [
                'model' => $model,
                            ]);
        }
    }

    /**
     * Deletes an existing Credits model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Credits model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Credits the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Credits::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    


}
