<?php

namespace common\models;
use common\models\User;
use common\models\KatmOrder;

use Yii;


class Katm extends \yii\db\ActiveRecord
{

    const APIURL = 'http://192.168.1.143:8001/katm-api/v1/claim/registration';
	const APIURL_MANUAL = 'http://192.168.1.143:8001/katm-api/v1/claim/registration/ext';
	
	const APIURL2 = 'http://192.168.1.143:8001/katm-api/v1/credit/report';
	const APIURL3 = 'http://192.168.1.143:8001/katm-api/v1/credit/report/status';
    const LOGIN = 'ret_zaamin';
    const PASSWORD = 'koQbhVYe4WKGRXFP';
    const PCODE = '20037';
    const PHEAD = 'RET';
    
    
        public static function tableName()
    {
        return 'katm';
    }
    
        public static function registerKatm($clientId,$region,$street)
    {

		$user = User::find()->where(['id'=>$clientId])->one();
      
        $claim_id = rand(5,999999999);
        $agree_id = rand(3,999999999);
		$report_id = rand(1,2555);
		
		$t = microtime(true);
		$micro = sprintf("%03d",($t - floor($t)) * 1000);
		$utc = gmdate('Y-m-d\TH:i:s.', $t).$micro.'Z';
			
			// РЕГИСТРАЦИЯ ЗАЯВКИ АВТОМАТИЧЕСКОЙ KATM
		$send = json_encode([
			'data' => [
				'pAddress' => $user['address'], //Адрес клиента
				'pAgreementDate' => $utc, //Дата согласия клиента(yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pAgreementId' => $agree_id, //Уникальный код согласия
				'pClaimDate' => $utc, //Дата заявки(yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pClaimId' => $claim_id, //Уникальный ID заявки
				'pCode' => self::PCODE, //Код организации
				'pCreditAmount' => 0,
				'pCreditEndDate' => $utc, //Дата завершения кредита (yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pCurrency' => '860',     //Код валюты
				'pDocNumber' => $user['passport_id'], //Номер паспорта клиента
				'pDocSeries' => $user['passport_serial'], //Серия паспорта клиента
				'pIsUpdate' => 1,     //Флаг обновления данных (0-по умолчанию,1-обновление)
				'pLocalRegion' => $street, //Код района
				'pPhone' => $user['phone'],       //Телефон клиента
				'pPinfl' => $user['pnfl'], //ПИНФЛ код клиента
				'pRegion' => $region,    //Код региона
			],
			'security' => [
				'pLogin' => self::LOGIN, // Логин, предоставляется кредитным бюро
				'pPassword' => self::PASSWORD, //Пароль, предоставляется кредитным бюро
			],

		]);
		
		
		
			
            $curl = curl_init(self::APIURL);
            
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $send);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$curl_result = curl_exec($curl);
			self::debug('Ответ результата подачи заявки');
			self::debug($curl_result);
			curl_close($curl);
            
            self::debug('ID ЗАЯВКИ');
			self::debug($claim_id);
			
						
			//ПОДАЧА ЗАЯВКИ НА ОТЧЕТ 
		$send_otchet = json_encode([
			'data' => [
				'pCode' => self::PCODE, //Код организации,
				'pHead' => self::PHEAD,
				'pLegal' => 1,
				'pClaimId' => $claim_id, //Уникальный ID заявки
				'pQuarter' => 0,
				'pReportFormat' => 1,
				'pReportId' => 23,
				'pYear' => 0

			],
			'security' => [
				'pLogin' => self::LOGIN, // Логин, предоставляется кредитным бюро
				'pPassword' => self::PASSWORD //Пароль, предоставляется кредитным бюро
			],

		]);
		
		
			$curl = curl_init(self::APIURL2);
            
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $send_otchet);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$curl_result = curl_exec($curl);
			self::debug('Ответ результата на подачу отчета');
			self::debug($curl_result);
			curl_close($curl);
			
			$curl_json = json_decode($curl_result, true);


			//СОХРАНЕНИЕ ОТЧЕТА В БАЗУ
			if($curl_json['data']['result'] == '05000') {
				
				$arr2 = base64_decode($curl_json['data']['reportBase64']);
				$otchet = json_decode($arr2,true);
				
			
				if ($katm = Katm::find()->where(['id'=>$clientId])->one() ) {
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				
				return 05000;
			}else {
				$katm = new Katm();
				$katm->id = $clientId;
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				}
				
				return 05000;
				
			}else if($curl_json['data']['result'] == '05050') {
				
				
			//СОХРАНЕНИЕ ТОКЕНА ДЛЯ ПРОВЕРКИ ОТЧЕТА
			if ($katm_order = KatmOrder::find()->where(['user_id'=>$clientId])->one() ) {

			$katm_order->claim_id = $claim_id;
			$katm_order->user_id = $clientId;
			$katm_order->token = $curl_json['data']['token'];
			$katm_order->save();
			}else{
				$katm_order = new KatmOrder();
				$katm_order->user_id = $clientId;
				$katm_order->claim_id = $claim_id;
				$katm_order->token = $curl_json['data']['token'];
				$katm_order->save();
			}
			
			return 05050;
			
		
		}else if($curl_json['data']['result'] == '05002')
		{
			return 05002;
		}
		else{
			return 0;
		}
	}
	
	
	
	
	
	
	public static function registerManualKatm($clientId,$region,$street)
    {

		$user = User::find()->where(['id'=>$clientId])->one();
      
        $claim_id = rand(5,999999999);
        $agree_id = rand(3,999999999);
		$report_id = rand(1,2555);
		
		$t = microtime(true);
		$micro = sprintf("%03d",($t - floor($t)) * 1000);
		$utc = gmdate('Y-m-d\TH:i:s.', $t).$micro.'Z';
			
			// РЕГИСТРАЦИЯ ЗАЯВКИ В РУЧНУЮ KATM
		$send = json_encode([
			'data' => [
				'pFirstName' => $user['username'],
				'pLastName' => $user['lastname'],
				'pMiddleName' => $user['patronymic'],
				'pMale' => 1,
				'pAddress' => $user['address'], //Адрес клиента
				'pAgreementDate' => $utc, //Дата согласия клиента(yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pAgreementId' => $agree_id, //Уникальный код согласия
				'pClaimDate' => $utc, //Дата заявки(yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pClaimId' => $claim_id, //Уникальный ID заявки
				'pCode' => self::PCODE, //Код организации
				'pCreditAmount' => 0,
				'pCreditEndDate' => $utc, //Дата завершения кредита (yyyy-MM-dd'T'HH:mm:ss.SSSZ)
				'pCurrency' => '860',     //Код валюты
				'pDocNumber' => $user['passport_id'], //Номер паспорта клиента
				'pDocSeries' => $user['passport_serial'], //Серия паспорта клиента
				'pIsUpdate' => 1,     //Флаг обновления данных (0-по умолчанию,1-обновление)
				'pLocalRegion' => $street, //Код района
				'pPhone' => $user['phone'],       //Телефон клиента
				'pPinfl' => $user['pnfl'], //ПИНФЛ код клиента
				'pRegion' => $region,    //Код региона
				'pInn' => $user['inn'],
				'pBirthDate' => $user['birthday'], //День рождения клиента
				'pIssueDocDate' => $user['passport_date'], //Дата выдачи пасспорта
				'pExpiredDocDate' => $user['passport_date_end'], //Дата сдачи пасспорта
			],
			'security' => [
				'pLogin' => self::LOGIN, // Логин, предоставляется кредитным бюро
				'pPassword' => self::PASSWORD, //Пароль, предоставляется кредитным бюро
			],

		]);
		


			
            $curl = curl_init(self::APIURL_MANUAL);
            
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $send);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$curl_result = curl_exec($curl);
			self::debug('Ответ результата подачи заявки');
			self::debug($curl_result);
			curl_close($curl);
            
            self::debug('ID ЗАЯВКИ');
			self::debug($claim_id);
			
						
			//ПОДАЧА ЗАЯВКИ НА ОТЧЕТ 
		$send_otchet = json_encode([
			'data' => [
				'pCode' => self::PCODE, //Код организации,
				'pHead' => self::PHEAD,
				'pLegal' => 1,
				'pClaimId' => $claim_id, //Уникальный ID заявки
				'pQuarter' => 0,
				'pReportFormat' => 1,
				'pReportId' => 23,
				'pYear' => 0

			],
			'security' => [
				'pLogin' => self::LOGIN, // Логин, предоставляется кредитным бюро
				'pPassword' => self::PASSWORD //Пароль, предоставляется кредитным бюро
			],

		]);
		
		
			$curl = curl_init(self::APIURL2);
            
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $send_otchet);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$curl_result = curl_exec($curl);
			self::debug('Ответ результата на подачу отчета');
			self::debug($curl_result);
			curl_close($curl);
			
			$curl_json = json_decode($curl_result, true);


			//СОХРАНЕНИЕ ОТЧЕТА В БАЗУ
			if($curl_json['data']['result'] == '05000') {
				
				$arr2 = base64_decode($curl_json['data']['reportBase64']);
				$otchet = json_decode($arr2,true);
				
			
				if ($katm = Katm::find()->where(['id'=>$clientId])->one() ) {
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				
				return 05000;
			}else {
				$katm = new Katm();
				$katm->id = $clientId;
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				}
				
				return 05000;
				
			}else if($curl_json['data']['result'] == '05050') {
				
				
			//СОХРАНЕНИЕ ТОКЕНА ДЛЯ ПРОВЕРКИ ОТЧЕТА
			if ($katm_order = KatmOrder::find()->where(['user_id'=>$clientId])->one() ) {

			$katm_order->claim_id = $claim_id;
			$katm_order->user_id = $clientId;
			$katm_order->token = $curl_json['data']['token'];
			$katm_order->save();
			}else{
				$katm_order = new KatmOrder();
				$katm_order->user_id = $clientId;
				$katm_order->claim_id = $claim_id;
				$katm_order->token = $curl_json['data']['token'];
				$katm_order->save();
			}
			
			return 05050;
			
		
		}else if($curl_json['data']['result'] == '05002')
		{
			return 05002;
		}
		else{
			return 0;
		}
	}
	
	
	public static function getKatm($token,$claimId,$clientId) {

			// ПРОВЕРКА И ПОЛУЧЕНИЕ ОТЧЕТА
			$get_otchet = json_encode([
				'data' => [
					'pHead' => self::PHEAD, //Код банка
					'pCode' => self::PCODE, //МФО БАНКА
					'pToken' => $token,
					'pClaimId' => $claimId,
					'pReportFormat' => 1,
				],
				'security' => [
					'pLogin' => self::LOGIN, // Логин, предоставляется кредитным бюро
					'pPassword' => self::PASSWORD //Пароль, предоставляется кредитным бюро
				],
			]);
			
			$curl = curl_init(self::APIURL3);
            
            curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $get_otchet);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			$curl_ex = curl_exec($curl);
			self::debug('Ответ результата на получение отчета');
			self::debug($curl_ex);
			curl_close($curl);
			$arr = json_decode($curl_ex,true);
			$arr2 = base64_decode($arr['data']['reportBase64']);
			self::debug_katm(json_encode($arr2));
			$otchet = json_decode($arr2,true);
			
			if($arr['data']['result'] == '05000') {
				//СОХРАНЕНИЕ ОТЧЕТА В БАЗУ
				if ($katm = Katm::find()->where(['id'=>$clientId])->one() ) {
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				
				return 05000;

			}else {
				$katm = new Katm();
				$katm->id = $clientId;
				$katm->katm_sir = $otchet['report']['client']['katm_sir'];
				$katm->duplicates = $otchet['report']['client']['duplicates'];
				$katm->old_name = $otchet['report']['client']['old_name'];
				$katm->subject = $otchet['report']['client']['subject'];
				$katm->client_type = $otchet['report']['client']['client_type'];
				$katm->inn = $otchet['report']['client']['inn'];
				$katm->birth_date = $otchet['report']['client']['birth_date'];
				$katm->document_type = $otchet['report']['client']['document_type'];
				$katm->document_serial = $otchet['report']['client']['document_serial'];
				$katm->document_number = $otchet['report']['client']['document_number'];
				$katm->document_date = $otchet['report']['client']['document_date'];
				$katm->gender = $otchet['report']['client']['gender'];
				$katm->nibbd = $otchet['report']['client']['nibbd'];
				$katm->region = $otchet['report']['client']['region'];
				$katm->local_region = $otchet['report']['client']['local_region'];
				$katm->address = $otchet['report']['client']['address'];
				$katm->phone = $otchet['report']['client']['phone'];
				//Сохранение данных по ломбардам
				$katm->org_type_lombard = $otchet['report']['subject_claims']['lombard_claims']['org_type'];
				$katm->claims_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['claims_qty'];
				$katm->rejected_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['rejected_qty'];
				$katm->granted_qty_lombard = $otchet['report']['subject_claims']['lombard_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по коммерческим банкам
				$katm->org_type_bank = $otchet['report']['subject_claims']['bank_claims']['org_type'];
				$katm->claims_qty_bank = $otchet['report']['subject_claims']['bank_claims']['claims_qty'];
				$katm->rejected_qty_bank = $otchet['report']['subject_claims']['bank_claims']['rejected_qty'];
				$katm->granted_qty_bank = $otchet['report']['subject_claims']['bank_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по ритейлам
				$katm->org_type_retail = $otchet['report']['subject_claims']['retail_claims']['org_type'];
				$katm->claims_qty_retail = $otchet['report']['subject_claims']['retail_claims']['claims_qty'];
				$katm->rejected_qty_retail = $otchet['report']['subject_claims']['retail_claims']['rejected_qty'];
				$katm->granted_qty_retail = $otchet['report']['subject_claims']['retail_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по МКО
				$katm->org_type_mko = $otchet['report']['subject_claims']['mko_claims']['org_type'];
				$katm->claims_qty_mko = $otchet['report']['subject_claims']['mko_claims']['claims_qty'];
				$katm->rejected_qty_mko = $otchet['report']['subject_claims']['mko_claims']['rejected_qty'];
				$katm->granted_qty_mko = $otchet['report']['subject_claims']['mko_claims']['granted_qty'];
				$katm->save();
				//Сохранение данных по лизингам
				$katm->org_type_leasing = $otchet['report']['subject_claims']['leasing_claims']['org_type'];
				$katm->claims_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['claims_qty'];
				$katm->rejected_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['rejected_qty'];
				$katm->granted_qty_leasing = $otchet['report']['subject_claims']['leasing_claims']['granted_qty'];
				
				//Сохранение отчета в JSON
				$katm->json_data = $arr2;
				
				//Дата сохранения отчета
				$katm->created_at = time();
				
				$katm->save();
				
				return 05000;
			}
			
		}else if ($arr['data']['result'] == '05004') {
			return 05004;
		}else if ($arr['data']['result'] == '05002') {
			return 05002;
		}else if ($arr['data']['result'] == '05050') {
			return 05050;
		}else {
			return 0;
		}
    }
    

    
        public static function debug( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_katm.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_katm.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }
	
	        public static function debug_katm( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_katm.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/katm_otchet.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . $data . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }





}
