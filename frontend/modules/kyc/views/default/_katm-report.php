<?php
error_reporting(E_ALL);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=cyrillic" rel="stylesheet">
    
	<style>

    </style>

<div style="max-width: 80%; margin: auto;margin-top:50px;">
	<h2 align="center" class="container__headline" style="margin: 0px!important;">
			ИДЕНТИФИКАЦИЯ СУБЪЕКТА КРЕДИТНОЙ ИНФОРМАЦИИ

	</h1>

	<table class="table table-striped table-hover table-borderless">
	  <thead>
		<tr>
		  <th scope="col">№</th>
		  <th scope="col">ТИП ИНФОРМАЦИИ</th>
		  <th scope="col">ИНФОРМАЦИЯ</th>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <th scope="row">1</th>
		  <td>Наименование заемщика</td>
		  <td><?=$user['lastname'].' '.$user['username']?></td>
		</tr>
		<tr>
		  <th scope="row">2</th>
		  <td>Дата рождения</td>
		  <td><?=$katm_report['birth_date']?></td>
		</tr>
		<tr>
		  <th scope="row">3</th>
		  <td>Резидентность</td>
		  <td><?='Резидент'?></td>
		</tr>
		<tr>
		  <th scope="row">4</th>
		  <td>Пол</td>
		  <td><?='М/Ж'?></td>
		</tr>
		<tr>
		  <th scope="row">5</th>
		  <td>Адрес по прописке</td>
		  <td><?=$katm_report['address']?></td>
		</tr>
		<tr>
		  <th scope="row">6</th>
		  <td>ПИНФЛ</td>
		  <td><?=$user['pnfl']?></td>
		</tr>
		<tr>
		  <th scope="row">6</th>
		  <td>ИНН</td>
		  <td><?=$katm_report['inn']?></td>
		</tr>
		<tr>
		  <th scope="row">7</th>
		  <td>Тип документа</td>
		  <td><?='Биометрический паспорт гражданина Республики Узбекистан'?></td>
	  </tr>
	  <tr>
		  <th scope="row">8</th>
		  <td>Данные документа</td>
		  <td><?=$katm_report['document_serial']. ' '.$katm_report['document_number'].' от '.$katm_report['document_date']?></td>
	  </tr>
	  <tr>
		  <th scope="row">9</th>
		  <td>Пользователь кредитного отчета</td>
		  <td><?='ZMARKET'?></td>
	  </tr>
	  <tr>
		  <th scope="row">10</th>
		  <td>Кредитная ставка</td>
		  <td><?='Нет данных'?></td>
	  </tr>
	  <tr>
		  <th scope="row">11</th>
		  <td>№ запроса пользователя</td>
		  <td><?=$katm_report['katm_sir']?></td>
	  </tr>
	  </tbody>
	</table>
	<h2 align="center" class="container__headline" style="margin: 0px!important;">
	ИНФОРМАЦИЯ ОБ ОБРАЩЕНИЯХ СУБЪЕКТА
	</h2>
		<table class="table table-striped table-hover table-borderless">
	  <thead>
		<tr>
		  <th scope="col">№</th>
		  <th scope="col">ТИП ОРГАНИЗАЦИИ</th>
		  <th scope="col">КОЛИЧЕСТВО ЗАЯВОК</th>
		  <th scope="col">КОЛИЧЕСТВО ОТКЛОНЕНИЙ</th>
		  <th scope="col">КОЛИЧЕСТВО ДОГОВОРОВ</th>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <th scope="row">1</th>
		  <td><?=$katm_report['org_type_bank']?></td>
		  <td><?=$katm_report['claims_qty_bank']?></td>
		  <td><?=$katm_report['rejected_qty_bank']?></td>
		  <td><?=$katm_report['granted_qty_bank']?></td>
		</tr>
		<tr>
		  <th scope="row">2</th>
		  <td><?=$katm_report['org_type_leasing']?></td>
		  <td><?=$katm_report['claims_qty_leasing']?></td>
		  <td><?=$katm_report['rejected_qty_leasing']?></td>
		  <td><?=$katm_report['granted_qty_leasing']?></td>
		</tr>
		<tr>
		  <th scope="row">3</th>
		  <td><?=$katm_report['org_type_lombard']?></td>
		  <td><?=$katm_report['claims_qty_lombard']?></td>
		  <td><?=$katm_report['rejected_qty_lombard']?></td>
		  <td><?=$katm_report['granted_qty_lombard']?></td>
		</tr>
		<tr>
		  <th scope="row">4</th>
		  <td><?=$katm_report['org_type_mko']?></td>
		  <td><?=$katm_report['claims_qty_mko']?></td>
		  <td><?=$katm_report['rejected_qty_mko']?></td>
		  <td><?=$katm_report['granted_qty_mko']?></td>
		</tr>
		<tr>
		  <th scope="row">5</th>
		  <td><?=$katm_report['org_type_retail']?></td>
		  <td><?=$katm_report['claims_qty_retail']?></td>
		  <td><?=$katm_report['rejected_qty_retail']?></td>
		  <td><?=$katm_report['granted_qty_retail']?></td>
		</tr>
		<tr>
		  <th scope="row"></th>
		  <td><b>Итого</b></td>
		  <td><?= $total_claims = $katm_report['claims_qty_retail'] + $katm_report['claims_qty_mko'] + $katm_report['claims_qty_bank'] + $katm_report['claims_qty_leasing'] + $katm_report['claims_qty_lombard']; ?></td>
		  <td><?= $total_rejected = $katm_report['rejected_qty_retail'] + $katm_report['rejected_qty_bank'] + $katm_report['rejected_qty_mko'] + $katm_report['rejected_qty_lombard'] + $katm_report['rejected_qty_leasing'];?></td>
		  <td><?=$total_granted = $katm_report['granted_qty_retail'] + $katm_report['granted_qty_bank'] + $katm_report['granted_qty_leasing'] + $katm_report['granted_qty_mko'] + $katm_report['granted_qty_lombard'];?></td>
		</tr>
	  </tbody>
	</table>
	<h2 align="center" class="container__headline" style="margin: 0px!important;">
	ИНФОРМАЦИЯ О НАЛИЧИЯХ ЗАДОЛЖЕННОСТИ СУБЪЕКТА
	</h2>
	<table class="table table-striped table-hover table-borderless">
	  <thead>
		<tr>
		  <th scope="col">№</th>
		  <th scope="col">НАИМЕНОВАНИЕ ОРГАНИЗАЦИИ </th>
		  <th scope="col">ОСТАТОК ВСЕЙ ЗАДОЛЖЕННОСТИ </th>
		  <th scope="col">ОСТАТОК ПРОСРОЧЕННОЙ ЗАДОЛЖЕННОСТИ</th>
		  <th scope="col">ДАТА ОБНОВЛЕНИЯ</th>
		</tr>
	  </thead>
	  <tbody>
	  <? 
		
		$arr = stripslashes($katm_report['json_data']);
		$str = substr($arr, 1);
		$str2 = substr($str,0,-1);
		$str3 = json_decode($str2,true);

		//var_dump($str3['report']['subject_debts']['debts']);die();

		if(isset($str3['report']['subject_debts']['debts'][0])) {
	  foreach($str3['report']['subject_debts']['debts'] as $key=>$debt) {?>
		<tr>
		  <th scope="row"><?=$key?></th>
		  <td><?=$debt['org_name'];?></td>
		  <td><?=round($debt['all_debts']) / 100; ?></td>
		  <td><?=round($debt['curr_debts']) / 100; ?></td>
		  <td><?=$debt['last_update']; ?></td>
		</tr>
		<?php } } else if(isset($str3['report']['subject_debts']['debts'])) { ?>
			<tr>
		  <th scope="row"><?='1'?></th>
		  <td><?=$str3['report']['subject_debts']['debts']['org_name'];?></td>
		  <td><?=round($str3['report']['subject_debts']['debts']['all_debts']) / 100; ?></td>
		  <td><?=round($str3['report']['subject_debts']['debts']['curr_debts']) / 100; ?></td>
		  <td><?=$str3['report']['subject_debts']['debts']['last_update']; ?></td>
		</tr>
		<?php  } ?>

	  </tbody>
	</table>
</div>


<?php

$script = " 
$('document').ready(function(){
   window.print();
   //window.close();
});";
$this->registerJs($script, yii\web\View::POS_END);

