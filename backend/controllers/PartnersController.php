<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use common\models\Partners;
use common\models\PartnersCats;
use common\models\PartnersSearch;
use common\models\PartnersShares;
use common\models\PartnersFilials;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnersController implements the CRUD actions for Partners model.
 */
class PartnersController extends Controller
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
        $searchModel = new PartnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Partners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partners();

        $model2 = new PartnersCats();

        $modelsShares = new PartnersShares();

        $data_model2 = $model2->selectPartnersCats();


        if ($model->updateModel(true)) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'model2' => $model2,
            'modelsShares' => $modelsShares,
            'data_model2' => $data_model2,
        ]);
    }

    /**
     * Updates an existing Partners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionViewShare() {
        return $this->render('create-share');
    }

    public function actionViewFilial() {
        return $this->render('create-filial');
    }

    public function actionCreateShare () {
        $modelsShares = new PartnersShares();
        $modelsShares->updateModel();
        $modelsShares->partner_id = $_POST['PartnersShares']['partner_id'];
        $modelsShares->save();

        $redirect_url = '/partners/update?id='.$_POST['PartnersShares']['partner_id'];
        return $this->redirect([$redirect_url]);
    }

    public function actionCreateFilial () {
        $modelFilials = new PartnersFilials();
        $modelFilials->updateModel();
        $modelFilials->partner_id = $_POST['PartnersFilials']['partner_id'];
        $modelFilials->save();

        $redirect_url = '/partners/update?id='.$_POST['PartnersFilials']['partner_id'];
        return $this->redirect([$redirect_url]);
    }

    public function actionViewUpdateShare($id) {
        $model = $this->findModelShare($id);
        return $this->render('update-share',['model' => $model]);
    }

    public function actionViewUpdateFilial($id) {
        $model = $this->findModelFilial($id);
        return $this->render('update-filial',['model' => $model]);
    }

    public function actionUpdateShare () {
        $id = $_POST['PartnersShares']['partner_id'];
        $modelShares = $this->findModelShare($id);
        $modelShares->updateModel();
        $modelShares->title = $_POST['PartnersShares']['title'];
        $modelShares->description = $_POST['PartnersShares']['description'];
        $modelShares->save();
        return $this->redirect(['index']);
    }

    public function actionUpdateFilial () {
        $id = $_POST['PartnersFilials']['partner_id'];
        $modelFilials = $this->findModelFilial($id);
        $modelFilials->updateModel();
        $modelFilials->title = $_POST['PartnersFilials']['title'];
        $modelFilials->description = $_POST['PartnersFilials']['description'];
        $modelFilials->phone = $_POST['PartnersFilials']['phone'];
        $modelFilials->address = $_POST['PartnersFilials']['address'];
		$modelFilials->workhour = $_POST['PartnersFilials']['workhour'];
        $modelFilials->cards = $_POST['PartnersFilials']['cards'];
        $modelFilials->save();
      return $this->goBack((
        !empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null
		));
    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        $model_partners = new Partners();

        $model2 = new PartnersCats();

        $data_model2 = $model2->selectPartnersCats();


        $shares = new PartnersShares();
        $filials = new PartnersFilials;

        $partners_filials = (new\yii\db\Query())->select('*')->from('partners_filials')->where('partner_id=:partner_id', [':partner_id' => $model->id])->all();
        $partners_shares = (new\yii\db\Query())->select('*')->from('partners_shares')->where('partner_id=:partner_id', [':partner_id' => $model->id])->all();

        if ($filials->load(Yii::$app->request->post())) {
            $filials->updateModel();
            $filials->partner_id = $model->id;
            $filials->save();
        }

        if ($shares->load(Yii::$app->request->post())) {
            $model_partners->updateModel();
            $shares->partner_id = $model->id;
            $shares->save();
        }

        if ($model->updateModel()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model2' => $model2,
            'data_model2' => $data_model2,
            'partners_filials' => $partners_filials,
            'partners_shares' => $partners_shares,
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
        if ($model = $this->findModel($id)) {

            $path = Yii::getAlias("@frontend/web/uploads/partners/");

            // удаляем фото
            @unlink($path . $model->id . '/' . $model->image);
            @unlink($path . $model->id . '/thumb/' . $model->image);

            $model->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionDeleteImage()
    {
        $id = (int)Yii::$app->request->get('id');
        if ($model = $this->findModel($id)) {

            $path = Yii::getAlias("@frontend/web/uploads/partners/");

            // удаляем фото
            @unlink($path . $model->id . '/' . $model->image);
            @unlink($path . $model->id . '/thumb/' . $model->image);

            $model->image = '';
            $model->save();
            return json_encode(['status' => 1]);
        }
        return json_encode(['status' => 0]);
    }

    public function actionDeleteBanerImage()
    {
        $id = (int)Yii::$app->request->get('id');
        if ($model = $this->findModel($id)) {

            $path = Yii::getAlias("@frontend/web/uploads/partners/");

            // удаляем фото
            @unlink($path . $model->id . '/' . $model->imagebaner);
            @unlink($path . $model->id . '/thumb/' . $model->imagebaner);

            $model->imagebaner = '';
            $model->save();
            return json_encode(['status' => 1]);
        }
        return json_encode(['status' => 0]);
    }



    public function actionDeleteFilial($id)
    {
        if ($model = $this->findModelFilial($id)) {
            $model->delete();
            $redirect_url = '/partners/update?id='.$model['partner_id'];
            return $this->redirect([$redirect_url]);
        }
    }

    public function actionDeleteShare($id)
    {
        if ($model = $this->findModelShare($id)) {
            $model->delete();
            $redirect_url = '/partners/update?id='.$model['partner_id'];
            return $this->redirect([$redirect_url]);
        }
    }

    public function actionDeleteShares($id)
    {
        if ($model = $this->findModelShare($id)) {
            $model->delete();
            $redirect_url = '/partners/update?id='.$model['partner_id'];
            return $this->redirect([$redirect_url]);
        }
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
        if (($model = Partners::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelFilial($id)
    {
        if (($model = PartnersFilials::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelShare($id)
    {
        if (($model = PartnersShares::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

