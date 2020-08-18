<?php

namespace app\modules\kyc\controllers;

use common\helpers\PolisHelper;
use common\helpers\HumoHelper;
use common\helpers\SmsHelper;
use common\helpers\SmsAllHelper;
use common\helpers\UtilsHelper;
use common\models\Contracts;
use common\models\CreditHistory;
use common\models\CreditItems;
use common\models\Credits;
use common\models\Insurance;
use common\models\Kyc;
use common\models\Notify;
use common\models\Orders;
use common\models\Humo;
use common\models\Polises;
use common\models\Scoring;
use common\models\Katm;
use common\models\ScoringHistory;
use common\models\SmsMailing;
use common\models\SuppliersSettings;
use common\models\User;
use common\models\Uzcard;
use function GuzzleHttp\Psr7\str;
use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class DefaultController extends BaseController
{


    public $TEST_MODE = true;

public function actionIndex()
    {

        $post = Yii::$app->request->post();

        if(isset($post['type'])) {
            $type = (int)$post['type'];
            Yii::$app->session->set('filter_type', $type);
        }elseif(Yii::$app->session->has('filter_type')) {
            $type = Yii::$app->session->get('filter_type');
        }else {
            $type = 0;
        }

        if($is_ajax = Yii::$app->request->isAjax) $this->layout = false;

        switch($type){

            case 1:
                $where = ['status'=>1];
                break;
            case 2:
                $where = ['status'=>0];
                break;

            case 3:
            $where = ['status'=>0];
            break;

            default:
                $where = ['>','id',0];
        }


        // Подготовка запроса поиск по ID
        if(!is_null($post['user_id'])){
            $where = ['client_id'=>$post['user_id']];
            $type=2;
            Yii::$app->session->set('filter_type', $type);
        }       


        $kycQuery = Kyc::find()->where($where);
        // Вывод статичной страницы
        if (!$is_ajax and $kycQuery) {

            $pagination = new Pagination([
                'totalCount' => $kycQuery->count(),
                'pageSize' => Kyc::ITEMS_COUNT,
            ]);

            if (!$model_kyc = $kycQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all()) {
                $model_kyc = false;
            }

            $html = $this->render('index', [
                'user' => $this->user,
                'model_kyc' => $model_kyc,
                'pagination' => $pagination,
                'filter_type' => Yii::$app->session->has('filter_type') ? Yii::$app->session->get('filter_type') : 2,

            ]);
            return $html;
        }


        //Вывод аякс запроса с type == 0
        if ($is_ajax and $type == 0) {

            $kycQuery = Kyc::find()->where($where);

            $pagination = new Pagination([
                'totalCount' => $kycQuery->count(),
                'pageSize' => Kyc::ITEMS_COUNT,
            ]);

            if (!$model_kyc = $kycQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all()) {
                $model_kyc = false;
            }

            $html = $this->renderPartial('_filter_clients', [
                'user' => $this->user,
                'model_kyc' => $model_kyc,
                'pagination' => $pagination,
                'filter_type' => Yii::$app->session->has('filter_type') ? Yii::$app->session->get('filter_type') : 2,
            ]);

            return json_encode(['status'=>1,'html'=>$html]);
        }




        //Вывод аякс запроса с type == 1 или 2
        if($type == 1 or $type == 2){
            $kycQuery = Kyc::find()->where($where);

            $pagination = new Pagination([
                'totalCount' => $kycQuery->count(),
                'pageSize' => Kyc::ITEMS_COUNT,
            ]);

            if (!$model_kyc = $kycQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all()) {
                $model_kyc = false;
            }

            $html = $this->renderPartial('_filter_clients',[
                'user' => $this->user,
                'model_kyc' => $model_kyc,
                'pagination' => $pagination,

            ]);

            return json_encode(['status'=>1,'html'=>$html]);

        }

        //Вывод аякс запроса с type == 3
        if ($type == 3) {

            $kycQueryUpdate = User::find();

            $pagination = new Pagination([
                'totalCount' => $kycQueryUpdate->count(),
                'pageSize' => Kyc::ITEMS_COUNT,
            ]);


            $model_kyc_update = $kycQueryUpdate->orderBy('updated_at DESC')->all();


            $html = $this->renderPartial('_filter_clients_update', [
                'user' => $this->user,
                'model_kyc_update' => $model_kyc_update,
            ]);

            return json_encode(['status' => 1, 'html' => $html]);

        }
    }


    public function actionEdit()
    {

        $id =(int)Yii::$app->request->get('id');
        if( !$model_kyc = Kyc::find()->with(['client','supplier'])->where(['id'=>$id])->one() ){
            return $this->redirect('/kyc');
        }
        if(!$user = User::findOne($model_kyc->client_id)) {
            Yii::$app->session->setFlash('info','Клиент не найден!');
            return $this->redirect('/kyc');
        }
        if($model_kyc->updateModel()  ){
            $check_pnfl = $user->updateModel();
			if(!$check_pnfl) {
				Yii::$app->session->setFlash('info','Такой ПНФЛ уже есть в базе!');
			}
			
            return $this->redirect('/kyc/edit?id=' . $model_kyc->id);
        }

        if( !$model_scoring = Scoring::find()->where(['user_id'=>$model_kyc->client_id])->one() ) $model_scoring = new Scoring();

        /*for($i=0;$i<10;$i++){
            $sh = new ScoringHistory();
            $sh->scoring_id = 0;
            $sh->created_at = time()+$i;
            $sh->date=strtotime(date('d.m.Y 00:00:00',time()));
            $sh->user_id = $this->user->id;
            $res = rand(0,1) > 0.5 ? 'OK' :'error';
            $sh->status = $res=='OK' ? 1 : 0;
            $sh->info = json_encode(['info'=>$res]);
            if(!$sh->save()){
                print_r($sh->getErrors()); exit;
            }
        }*/

        //$scoring = ScoringHistory::find()->select('COUNT(id) as count,date,status')->groupBy('date,status')->orderBy('status')->asArray()->all();
        $scoring = ScoringHistory::find()->select('COUNT(id) as count,status')->groupBy('status')->orderBy('status')->asArray()->all();
        $scoring_fail = 0;
        $scoring_success = 0;

        if(count($scoring)==2){
            $scoring_fail = isset($scoring[0]['count'])?$scoring[0]['count']:0;
            $scoring_success = isset($scoring[1]['count'])?$scoring[1]['count']:0;
        }elseif(isset($scoring[0])){
            if( $scoring[0]['status']==1){
                $scoring_success = isset($scoring[1]['count'])?$scoring[1]['count']:0;
            }else{
                $scoring_fail = isset($scoring[0]['count'])?$scoring[0]['count']:0;
            }
        }

        return $this->render('edit',[
            'user' => $user,
            'model' => $model_kyc,
            'model_scoring' => $model_scoring,
            'scoring_success' => $scoring_success,
            'scoring_fail' => $scoring_fail,

        ]);
    }

    public function actionGetBalance(){

        $user_id = Yii::$app->request->post('id');

        if( !$user = User::find()->where(['id'=>$user_id])->one() ){
            return json_encode(['$user' => "user not found"]);
        }

        if($user->auto_discard_type == 1) {
            $balance = new Uzcard;
            $balance = $balance->cardsGet($user);
            //$balance = $balance['result'][0]['balance'];
            $balance = $balance['result'][0]['balance'] ? $balance['result'][0]['balance'] : $balance;
        }
        if($user->auto_discard_type == 2) {
            if( !$scoring = Scoring::find()->where(['user_id'=>$user_id])->one() ){
                return json_encode(['$user' => "scoring - user not found"]);
            }
            $card = '9860' . $scoring->bank_c . $scoring->card_h;
            $balance = HumoHelper::humoBalance($card);
        }
        return json_encode(['balance' => $balance]);
    }


    public function actionSaveComments ()
    {
        $post = Yii::$app->request->post();
        $id = (int)$post['id'];
        if ($model = Kyc::find()->where(['client_id'=>$id])->one() ) {
            $model->comments = $post['msgComments'];
            $model->save();
            return json_encode(['status' =>1, 'info' => Yii::t('app', 'Заметка успешно сохранена!')]);
        }
        return json_encode(['status' => 0, 'info' => Yii::t('app', 'Заметка не сохранена!')]);

    }
	
	//Отправка автоматической заявки KATM
	public function actionSendKatmData () {
		$post = Yii::$app->request->post();
		$katm = Katm::registerKatm($post['clientId'],$post['regionKatm'], $post['streetKatm']);
		if($katm == 05000) {
			return json_encode(['status' =>1, 'info' => Yii::t('app', 'Заявка успешно прошла, отчет сохранен проверка не нужна!')]);
		}else if($katm == 05050){
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Отчет ожидает подтверждения от оператора, попробуйте через 30-60 секунд')]);	
		}else if ($katm == 05002) {
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Произошла системная ошибка 05002 - Не передано одно либо несколько значений район/пнфл/номер паспорта/серия/телефон. Проверьте поля и их формат! ')]);
		}else{
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Неизвестная ошибка проверьте логи в debug_katm')]);
		}
	}
	
		//Отправка ручной заявки KATM
	public function actionSendManualKatmData () {
		$post = Yii::$app->request->post();
		$katm = Katm::registerManualKatm($post['clientId'],$post['regionKatm'], $post['streetKatm']);
		if($katm == 05000) {
			return json_encode(['status' =>1, 'info' => Yii::t('app', 'Заявка успешно прошла, отчет сохранен проверка не нужна!')]);
		}else if($katm == 05050){
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Отчет ожидает подтверждения от оператора, попробуйте через 30-60 секунд')]);	
		}else if ($katm == 05002) {
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Произошла системная ошибка 05002 - Не передано одно либо несколько значений район/пнфл/номер паспорта/серия/телефон. Проверьте поля и их формат! ')]);
		}else{
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Неизвестная ошибка проверьте логи в debug_katm')]);
		}
		
	}
	
	
	//Проверка заявки KATM
	public function actionCheckKatmData() {
		$post = Yii::$app->request->post();
		$katm = Katm::getKatm($post['token'], $post['claimId'], $post['clientId']);
		if($katm == 05000) {
			return json_encode(['status' =>1, 'info' => Yii::t('app', 'Проверка успешно прошла, отчет сохранен в базу можете смотреть!')]);
		}else if($katm == 05004) {
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Отчет ожидает подтверждения от оператора, попробуйте через 30-60 секунд')]);
		}else if($katm == 05050){
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Отчет ожидает подтверждения от оператора, попробуйте через 30-60 секунд')]);	
		}else if ($katm == 05002) {
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Токен не найден, подайте еще раз заявку пожалуйста')]);
		} else {
			return json_encode(['status' =>0, 'info' => Yii::t('app', 'Неизвестная ошибка, попробуйте позже проверить скорее всего у Вас получится, если через 15-20 мин постоянных проверок у Вас ничего не выйдет то пишите админу!')]);
		}
	}
	
	//Вывод Отчета KATM
	public function actionKatmReport($id) {
	   $katm_report = (new\yii\db\Query())->select('*')->from('katm')->where('id=:id', [':id' => $id])->one();
		$user = (new\yii\db\Query())->select('*')->from('user')->where('id=:id', [':id' => $id])->one();
        return $this->render('_katm-report', [
            'katm_report' => $katm_report,
			'user' => $user
        ]);	
	}

    public function actionSendSms(){
        $post = Yii::$app->request->post();
        $id = (int)$post['id'];
        $msg = htmlspecialchars($post['msg']);
        if($user = User::find()->where(['id'=>$id,'role'=>User::ROLE_CLIENT])->one() ) {
            SmsHelper::sendSms($user->phone, $msg);
            return json_encode(['status' => 1, 'info' => Yii::t('app', 'Сообщение успешно отправлено!')]);
        }
        return json_encode(['status' => 0, 'info' => Yii::t('app', 'Клиент не найден!')]);

    }

    public function actionViewAllSms() {
        $model = new Smsmailing();
        return $this->render ('_send_all_sms', [
            'model' => $model,
        ]);
    }

    public function actionSendAllSms() {
        $is_ajax = Yii::$app->request->isAjax;
        $post = Yii::$app->request->post();
        $prepare_date_start = strtotime(htmlspecialchars ($post['SmsMailing']['datestart']));
        $prepare_date_end = strtotime(htmlspecialchars ($post['SmsMailing']['dateend']));

        switch($post['typesmsView'] or $post['typesmsInsert']){
            case 0:
                $where = ['status_client_complete'=>1];
                break;
            case 1:
                $where = ['status_client_complete'=>2];
                break;

            case 2:
                $where = ['status_client_complete'=>3];
                break;
        }

        if($is_ajax){
            $model_kyc = User::find()->where($where)->andWhere(['between', 'created_at', $prepare_date_start, $prepare_date_end])->all();

            $html = $this->renderPartial('_filter_clients_sms',[
                'model_kyc' => $model_kyc,
            ]);

            return json_encode(['status'=>1,'html'=>$html]);

        } else {
            $kycQuery = User::find('phone')
                ->where($where)
                ->andWhere(['between', 'created_at', $prepare_date_start, $prepare_date_end]);

            $time = $post['SmsMailing']['sendday'].' '.$post['SmsMailing']['sendhour'].':00';

            $model_kyc = $kycQuery->orderBy('created_at DESC')->asArray()->all();

            foreach($model_kyc as $item) {
            	$phone = preg_replace("/[^0-9]/", '', $item['phone']);
                //echo $phone.' '.$post['SmsMailing']['msg'].' '. $time.'<br/>';
                SmsAllHelper::sendAllSms($phone, $post['SmsMailing']['msg'], $time);
                UtilsHelper::debugSms($phone.' '.$post['SmsMailing']['msg'].' '. $time.'<br>');
            }
        }

        return $this->redirect('/kyc/');
    }

    public function actionContracts()
    {

        $post = Yii::$app->request->post();

        if(isset($post['type'])) {
            $type = (int)$post['type'];
            Yii::$app->session->set('filter_type', $type);
        }elseif(Yii::$app->session->has('filter_type')) {
            $type = Yii::$app->session->get('filter_type');
        }else {
            $type = 0;
        }

        if(isset($post['search'])) {
            $search = (int)$post['search'];
            Yii::$app->session->set('search', $type);
        }elseif(Yii::$app->session->has('search')) {
            $search = Yii::$app->session->get('search');
        }else {
            $search = '';
        }

        if(isset($post['search_type'])) {
            $search_type = (int)$post['search_type'];
            Yii::$app->session->set('search_type', $search_type);
        }elseif(Yii::$app->session->has('search_type')) {
            $search_type = Yii::$app->session->get('search_type');
        }else {
            $search_type = 0;
        }

        if($is_ajax = Yii::$app->request->isAjax) $this->layout = false;

        $ds = 86400;
        $time = time(); //+$ds*31;

        switch($type){
            case 1:
                $day = 1;
                $day2= 7;
                $where = ['and',['<=','credit_date',$time-$ds*$day],['>=','credit_date',$time-$ds*$day2]];
                break;
            case 2:
                $day = 8;
                $day2= 15;
                $where = ['and',['<=','credit_date',$time-$ds*$day],['>=','credit_date',$time-$ds*$day2]];
                break;
            case 3:
                $day = 16;
                $day2= 30;
                $where = ['and',['<=','credit_date',$time-$ds*$day],['>=','credit_date',$time-$ds*$day2]];
                break;
            case 4:
                $day = 31;
                $day2= 45;
                $where = ['and',['<=','credit_date',$time-$ds*$day],['>=','credit_date',$time-$ds*$day2]];
                break;
            default:
                $where = false;
        }

        switch ($search_type){
            case 1:
                //$where = ['and',['like','credit_date',$search_type]];

                break;

        }


       $sql = '';
        if($where) {
            // только активные
            $credit_history = CreditHistory::find()->select('credit_id')->where(['payment_status' => 0])->andWhere($where)->orderBy('credit_date')->groupBy('credit_id, credit_date')->asArray()->all();
            $where_credit = ['credit_id' => $credit_history];


            $cid = [];
            if($search!='') {
                $contracts = Contracts::find()->select('id,credit_id')->with(['client'])->where($where_credit)->andWhere(['status'=>1])->all();
                foreach ($contracts as $contract) {
                    if (!isset($contract->client->username)) continue;
                    if (strpos($contract->client->username, $search) >= 0) {
                        $cid[] = $contract->id;
                    }
                }

                $where_cid = ['id' => $cid];
            }

            if($cid) {
                $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc'])->where($where_cid)->andWhere(['status'=>1]);//->andWhere(['id' => $cid]);
            }else{
               $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc'])->where($where_credit)->andWhere(['status'=>1]);

            }


            $sql = implode(', ', $cid); //.$s;

        }else{


            //-------
            $cid = [];
            if($search) { // если задан поиск по имени

                $contracts = Contracts::find()->select('id,credit_id')->with(['client'])->all();
                foreach ($contracts as $contract) {
                    if(!isset($contract->client)) continue;
                    if (mb_strrpos($contract->client->username, $search) > 0) {
                        $cid[] = $contract->id;
                    }
                }

            }

            if($cid) {
                $where_cid = ['id'=>$cid];
                $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc'])->where($where_cid);//->andWhere(['id' => $cid]);
            }else{
                $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc']);//->where($where_credit);

            }

        }

        if($orderQuery){
            $pagination = new Pagination( [
                'totalCount' => $orderQuery->count(),
                'pageSize' => Contracts::ITEMS_COUNT,
            ]);

            if( !$model_order = $orderQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all() ) {
                $model_order = false;
            }

        }else{
            $model_order = false;
            $pagination = false;

        }

        if($is_ajax){
            $html = $this->renderPartial('_filter_contracts',[
                'user' => $this->user,
                'model_order' => $model_order,
                'pagination' => $pagination,
            ]);
            return json_encode(['status'=>1,'html'=>$html, 'sql'=>$sql]);

        }else{
            $html = $this->render('contracts',[
                'user' => $this->user,
                'model_order' => $model_order,
                'pagination' => $pagination,
                'filter_type' => Yii::$app->session->has('filter_type')?Yii::$app->session->get('filter_type'):0,
                'search_type' => Yii::$app->session->has('search_type')?Yii::$app->session->get('search_type'):0,

            ]);
        }

        return $html;

    }

    public function actionContractEdit($id){

        if(!$model_order = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc','payments'])->where(['id'=>$id])->one()){
            Yii::$app->session->setFlash('info','Договор не найден!');
            return $this->redirect('/kyc/contracts');
        }

        if($model_order->updateModel()){

            return $this->refresh();
        }

        return $this->render('edit-contract',[
            'user' => $this->user,
            'model_order' => $model_order,
            'credit' => $model_order->credit,
            'payments' => $model_order->payments,
            'filter_type' => Yii::$app->session->has('filter_type')?Yii::$app->session->get('filter_type'):0,
            'search_type' => Yii::$app->session->has('search_type')?Yii::$app->session->get('search_type'):0,

        ]);
    }

    // ajax
    public function actionSearchContracts()
    {
        $this->layout = false;
        $q = Yii::$app->request->post('q');
        if(is_numeric($q)){
            $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc'])->where(['id'=>$q]);
        }else{
            $cid = [];
            $contracts = Contracts::find()->with(['clientName'])->all();
            foreach ($contracts as $_contract) {
                if( mb_stripos($_contract->clientName->username,$q)!==false || mb_stripos($_contract->clientName->lastname,$q)!==false ){
                    $cid[] = $_contract->id;
                }
            }
            if($cid) {
                $orderQuery = Contracts::find()->with(['client', 'supplier', 'credit', 'kyc'])->where(['id'=>$cid]);

            }else{
                $orderQuery = false;
            }
        }

        if($orderQuery){
            $pagination = new Pagination([
                'totalCount' => $orderQuery->count(),
                'pageSize' => Contracts::ITEMS_COUNT,
            ]);
            if( !$model_order = $orderQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all() ) {
                $model_order = false;
            }

        }else{
            $model_order = false;
            $pagination = false;
        }

        $html = $this->renderPartial('_filter_contracts', [
            'model_order' => $model_order,
            'pagination' => $pagination
        ]);

        return json_encode(['status'=>1,'html'=>$html]);
    }


    public function actionScoring()
    {

        $post = Yii::$app->request->post();

        if(isset($post['type'])) {
            $type = (int)$post['type'];
            Yii::$app->session->set('filter_type', $type);
        }elseif(Yii::$app->session->has('filter_type')) {
            $type = Yii::$app->session->get('filter_type');
        }else {
            $type = 0;
        }

        if($is_ajax = Yii::$app->request->isAjax) $this->layout = false;

        switch($type){
            case 1:
                $where = ['status'=>1];
                break;
            case 2:
                $where = ['status'=>0];
                break;
            default:
                $where = ['>','id',0];
        }

        $scoreQuery = ScoringHistory::find()->where($where);

        if($scoreQuery){
            $pagination = new Pagination( [
                'totalCount' => $scoreQuery->count(),
                'pageSize' => ScoringHistory::ITEMS_COUNT,
                'pageSizeParam' => false
            ]);

            if( !$model_scoring = $scoreQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all() ) {
                $model_scoring = false;
            }

        }else{
            $model_scoring = false;
            $pagination = false;

        }


        if($is_ajax){
            $html = $this->renderPartial('_filter_scoring',[
                'user' => $this->user,
                'model_scoring' => $model_scoring,
                'pagination' => $pagination,
                'filter_type' => $type

            ]);
            return json_encode(['status'=>1,'html'=>$html,'type'=>$type]);

        }else{
            $html = $this->render('scoring',[
                'user' => $this->user,
                'model_scoring' => $model_scoring,
                'pagination' => $pagination,
                'filter_type' => $type

            ]);
        }


        return $html;

    }

    public function actionPolises()
    {


        if($polisesQuery = Polises::find()->with(['client','supplier'])){
            $pagination = new Pagination( [
                'totalCount' => $polisesQuery->count(),
                'pageSize' => Polises::ITEMS_COUNT,
            ]);

            if( !$model_polises = $polisesQuery->orderBy('created_at DESC')->offset($pagination->offset)->limit($pagination->limit)->all() ) {
                $model_polises = false;
            }

        }else{
            $model_polises = false;
            $pagination = false;

        }



            return $this->render('polises',[
                'user' => $this->user,
                'model_polises' => $model_polises,
                'pagination' => $pagination,

            ]);


    }
    // вывод уведомлений
    public function actionNotify(){

        return $this->render('notify',[
            'model' => $this->user,
        ]);

    }

	// получение уведомлений от пользователей
	public function actionGetNotify(){

        $post = Yii::$app->request->post();
        $id = isset($post['id'])?(int)$post['id']:0;
        if($notify = Notify::find()->where(['id'=>$id])->one()){
            return json_encode(['status'=>1,'count'=>rand(0,12)]);
        }
        return json_encode(['status'=>1,'count'=> rand(0,10)]);
    }

    // получение скоринга для пользователя
	public function actionGetScoring(){

        $post = Yii::$app->request->post();
        $id = isset($post['id'])?(int)$post['id']:0;
        $sum = (float)$post['ss'];
        $date_start = date('dmY',strtotime($post['ds']));
        $date_end = date('dmY',strtotime($post['de']));

        // самая последняя запись
        if($user = User::find()->with('scoring')->where(['id'=>$id,'role'=>User::ROLE_CLIENT])->orderBy(['id' => SORT_DESC])->one()){

           /* if($user->auto_discard_type == 0){
                $scoring_data = Yii::t('app','Нет данных для тестового клиента!');
            }else{
                $scoring_data = Uzcard::scoring($user, $sum, $date_start, $date_end);
            }*/


            switch ($user->auto_discard_type){
                case 1:
                    // получение скоринга за выбранный период на сумму
                    $scoring_data = Uzcard::scoring($user, $sum, $date_start, $date_end);
                    break;
                case 2:
                    $sum = $sum*100; // в тиинах
                    $card = '9860'. $user->scoring->bank_c . $user->scoring->card_h;
                    $scoring_data_humo = HumoHelper::humoScoring($card, $user->scoring->bank_c,$sum);
                    $balance_humo = HumoHelper::humoBalance($card);
                    break;
                default:
                    $scoring_data = Yii::t('app','Нет данных для тестового клиента!');

            }

            if($scoring_data_humo){
                if ($scoring = Scoring::find()->where(['user_id' => $user->id])->one()) {
                    $scoring->created_at = time();
                    $scoring->updated_at = time();
                    $scoring->data = $scoring_data_humo;
                    $scoring->balance = $balance_humo;
                    if(!$scoring->save()){
                         return json_encode(['status' => 0, 'info' => json_encode($scoring->getErrors(), JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE);
                    }
                    return json_encode($scoring_data_humo, JSON_UNESCAPED_UNICODE);
                }
            }

            $scoring_history = new ScoringHistory();
            $scoring_history->created_at = time();
            $scoring_history->date = strtotime(date('d.m.Y 00:00:00',time())); // для группировки по месяцам
            $scoring_history->user_id = $this->user->id;

            if(isset($scoring_data['error'])){ // учет ошибок скоринга
                $scoring_history->scoring_id = 0;
                $scoring_history->status = 0;
                $scoring_history->info = json_encode($scoring_data['error'],JSON_UNESCAPED_UNICODE);
                $scoring_history->save();

            }else{


                $data = isset($scoring_data['Scoring']['data']) ? $scoring_data['Scoring']['data'] : json_encode($scoring_data);

                if (!$scoring = Scoring::find()->where(['user_id' => $user->id])->one()) {
                    $scoring = new Scoring();
                    $scoring->created_at = time();
                    $scoring->user_id = $user->id;

                } else {
                    $scoring_data['Scoring'] = [
                        'token' => $scoring->token,
                        'pan' => $scoring->pan,
                        'exp' => $scoring->exp,
                        'phone' => $scoring->phone,
                        'fullname' => $scoring->fullname,
                        'balance' => $scoring->balance,
                        'sms' => $scoring->sms,
                        'data' => $data,
                    ];
                }

                if ($scoring->load($scoring_data)) {
                    $scoring->updated_at = time();
                    $scoring->date_start = strtotime($post['ds']);
                    $scoring->date_end = strtotime($post['de']);
                    $scoring->summ = (string)$sum;

                    if (isset($scoring_data['Scoring']['sms'])) {
                        $scoring_data['Scoring']['sms'] = $scoring_data['Scoring']['sms'] == 1 ? Yii::t('app', 'Подключен') : Yii::t('app', 'Не подключен');
                    }
                    if (!$scoring->save()) {
                        return json_encode(['status' => 0, 'info' => json_encode($scoring->getErrors(), JSON_UNESCAPED_UNICODE)], JSON_UNESCAPED_UNICODE); // . ' ' . json_encode($scoring_data,JSON_UNESCAPED_UNICODE)]);
                    }
                    $scoring_data['status'] = 1;

                    $scoring_history->scoring_id = $scoring->id;
                    $scoring_history->status = 1;
                    $scoring_history->info = json_encode(['error'=>0],JSON_UNESCAPED_UNICODE);
                    $scoring_history->save();


                    return json_encode($scoring_data, JSON_UNESCAPED_UNICODE);

                }
            }

            return json_encode(['status'=>0,'info'=> json_encode($scoring_data,JSON_UNESCAPED_UNICODE)]);
        }
        return json_encode(['status'=>0,'info'=>Yii::t('app','Клиент не найден!')]);
    }

	// настройка
	public function actionSettings(){

        if( $this->user->updateModel()){

            Yii::$app->session->setFlash('info','Сохранение успешно!');

            return $this->refresh();

        }
		
		return $this->render('settings',[
            'model' => $this->user,
        ]);

    }

    /*
    // ajax - отправка в страховую компанию шаг 15
    public function actionSendPolis()
    {
        $id = (int) Yii::$app->request->post('id');

        if( $contract = Contracts::find()->where(['id'=>$id,'status'=>0])->one() ){

            // после подтверждения договора в кабинете поставщика (кнопка подтвердить)
            $credit = Credits::find()->with(['contract','client'])->where(['id'=>$contract->credit_id])->one(); //($contract->credit_id);

            // отправка на страхование
            $result = PolisHelper::getPolisForCredit('zMarket_' . $contract->id, $credit);
            $result = PolisHelper::сheckTransaction('zMarket_' . $contract->id, $credit);

            if( isset($result['original']) && isset($result['original']['contractRegistrationID']) ) {
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
                $polis->contract_id = $contract->id;

                if (!$polis->save()) {
                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($polis->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);
                }

                $contract->status_polis = 1;
                $contract->save();

                return json_encode(['status' => 1, 'info' => '<i class="fa fa-check"></i> ' . Yii::t('app', 'Отправлен')],JSON_UNESCAPED_UNICODE);

            }else{

                return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.') . json_encode($result,JSON_UNESCAPED_UNICODE)],JSON_UNESCAPED_UNICODE);

            }

        }

        return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка, договор не найден!')],JSON_UNESCAPED_UNICODE);
    } */


    // ajax - отправка в страховую компанию шаг 15
    public function actionSendInsurance()
    {
        $id = (int) Yii::$app->request->post('id');

        if( $contract = Contracts::find()->with('polis')->where(['id'=>$id,'status'=>0])->one() ){

            $request_id = 'zMarket_' . $contract->id . '-' . $contract->polis->id;
            if( PolisHelper::sendCustomerList($request_id,$client)) {

                $insurance = new Insurance();
                $insurance->created_at = time();
                $insurance->contract_id = $contract->id;
                $insurance->polis_id = $contract->polis->id;
                $insurance->request_id = $request_id;
                if(!$insurance->save()){
                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($insurance->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);

                }

                $contract->send_insurance_date = time();
                $contract->send_insurance = 1;
                $contract->save(false);

                return json_encode(['status'=>1,'info'=>Yii::t('app','Данные договора успешно отправлены в страховую компанию.')]);

            }

        }

        return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.')],JSON_UNESCAPED_UNICODE);
    }

    // ajax - отправка в суд компанию шаг 15
    public function actionSendJud()
    {
        $id = (int) Yii::$app->request->post('id');

        if( $contract = Contracts::find()->with('client')->where(['id'=>$id,'status'=>0])->one() ){

            $request_id = 'zMarket_' . $contract->id . '-' . $contract->polis->id;
            if( PolisHelper::sendCustomerList($request_id,$client)) {

                $insurance = new Insurance();
                $insurance->created_at = time();
                $insurance->contract_id = $contract->id;
                $insurance->polis_id = $contract->polis->id;
                $insurance->request_id = $request_id;
                if(!$insurance->save()){
                    return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при сохранении данных от страховой компании. ' . json_encode($insurance->getErrors(),JSON_UNESCAPED_UNICODE)) ]); //. json_encode($result,JSON_UNESCAPED_UNICODE)]);

                }

                $contract->send_jud_date = time();
                $contract->send_jud = 1;
                $contract->save(false);

                if($user = User::findOne($contract->client->id)){
                    $user->status = 2; // блокирование клиента
                    $user->save(false);
                }

                return json_encode(['status'=>1,'info'=>Yii::t('app','Данные договора успешно отправлены в страховую компанию. Клиент заблокирован.')]);

            }

        }

        return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка при отправке договора в страховую компанию.')],JSON_UNESCAPED_UNICODE);
    }

    // ajax - подтверждение договора KYC шаг 17
    public function actionConfirmContract()
    {
        $id = (int) Yii::$app->request->post('id');

        if($contract = Contracts::find()->where(['id'=>$id,'status'=>0])->one() ){
            $contract->status = 1;
            $contract->confirm_date = time();
            if($contract->save()){

                return json_encode(['status'=>1,'info'=> '<i class="fa fa-check"></i> '. Yii::t('app','Подтвержден')]);

            }

            return json_encode(['status'=>0,'info'=> $contract->getErrors()]);

        }

        return json_encode(['status'=>0]);
    }


    /* public function actionOrderConfirm(){

        $post = Yii::$app->request->post();
        $id = isset($post['id'])?(int)$post['id']:0;
        if($order = Orders::find()->where(['id'=>$id])->one()){
            $order->status = 1;
            if($order->save()){
                return json_encode(['status'=>1,'info'=> '<i class="fa fa-check"></i> '. Yii::t('app','Подтвержден')]);
            }
            return json_encode(['status'=>0,'info'=> $order->getErrors()]);

        }
        return json_encode(['status'=>0]);
    } */



}
