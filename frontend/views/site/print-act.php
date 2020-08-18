<?php
error_reporting(E_ALL);
\frontend\assets\MainAsset::register($this);


$months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
$month = $months[ date('m',time())-1];

$company = explode(' ',@$credit->supplier->company);
$company_type = $company[0];
unset($company[0]);
$company_name = implode(' ',$company);
if(!isset($credit->client)){
    echo 'Клиент не найден!';
    exit;
}
$client = $credit->client;

$offer_prefix = str_repeat('0', 8-mb_strlen($credit->id));
$prefix_act = $credit->prefix_act ? '№ ' . $credit->prefix_act  : '';

?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=cyrillic" rel="stylesheet">
    
        <style>
        @page {
            /*        size: 58mm 297mm;*/
            margin: 0;
        }

        body {
            max-width: 100%;
        }

        @media print {
            * {
                max-width: 100%;
            }

            h1 {
                break-before: always;
            }

            table,
            img,
            svg {
                break-inside: avoid;
            }

            body {
                margin: 0;
                background-color: #fff;
                color: #000;
                min-width: unset !important;
            }
        }

        * {
            /*font-family: "Open Sans Condensed", sans-serif;*/
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-size: 10px;
            margin-bottom: 5px;
            line-height: 1.3;
            color: #000;

        }
        ul li{
            font-size: 10px;
        }
    </style>

    <div style="max-width: 80%; margin: auto;margin-top:50px;">
        <h1 class="container__headline" style="margin: 0px!important;">
            СЧЕТ-ФАКТУРА/АКТ ПРИЕМА ПЕРЕДАЧ
            <br/>

            <?= $prefix_act?> ОТ <?=date('d.m.Y',$credit->created_at) ?>
        </h1>
        <ul class="facture-list">
            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    Продавец:
                </span>
                <span class="feature-list__item-content">
                    ООО «ZAAMIN-MARKET»
                </span>
            </li>
            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    Адрес:
                </span>
                <span class="feature-list__item-content">
                     г. Ташкент, Юнус-Абадский р-н, ул. Корхожиота д.3
                </span>
            </li>
            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    ИНН:
                </span>
                <span class="feature-list__item-content">
                    306771342
                </span>
            </li>
            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    ОКЭД:
                </span>
                <span class="feature-list__item-content">
                    47190
                </span>
            </li>

            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    МФО:
                </span>
                <span class="feature-list__item-content">
                    01095
                </span>
            </li>
            <li class="facture-list__item">
                <span class="feature-list__item-head">
                    Р/С:
                </span>
                <span class="feature-list__item-content">
                    20208000905136549001
                </span>
            </li>
        </ul>

        <div style="" class="feature-cred">
            <ul class="feature-cred__list">
                <li class="feature-cred__list-item">
                    <span class="feature-cred__list-item-head">
                        Покупатель:
                    </span>
                    <span class="feature-cred__list-item-content">
                       <?=$client->lastname . ' ' .$client->username . ' ' .$client->patronymic  ?>
                    </span>
                </li>
                <li class="feature-cred__list-item">
                    <span class="feature-cred__list-item-head">
                        Адрес:
                    </span>
                    <span class="feature-cred__list-item-content">
                       <?=$client->address ?>
                    </span>
                </li>
            </ul>
            <div class="id-container">
                <h1 style="margin: 0px!important;" class="id-container__headline">
                    ID покупателя
                </h1>
                <span class="id-container__content">
                       <h1><?=$client->id ?></h1>
                </span>
            </div>

            <div class="text-cont">
                <u><?=$company_type ?> "<?=$company_name ?>"</u>, действующее по поручению продавца – ООО «ZAAMIN MARKET» (далее – «Продавец»), и
                <u><?=$client->lastname . ' ' .$client->username . ' ' .$client->patronymic  ?></u>, именуемый(-ая) в дальнейшем «Покупатель», с другой Стороны, вместе именуемые как «Стороны», а по отдельности – «Сторона», составили настоящий акт о том, что на основании электронного договора купли-продажи товара, заключенного путем направления со стороны Продавца оферты №<?=$offer_prefix . $credit->id ?>-К, и его акцепта со стороны Покупателя, Продавец передал, а Покупатель получил нижеуказанный товар:

            </div>

            <span class="split-line">
                ----------------------------------
            </span>



            <?php if(isset($credit->creditItems)) {
                $npp = 0;
                $cnt = 0;
                $sum = 0;
                $sum_price = 0;
                $s = 0;
                $nds_sum = 0;
                $clear_price = 0;
//                    if( $credit->supplier->nds_state){
//                        $nds = 1+$credit->supplier->nds/100 ;
//                        $nds_title = $credit->supplier->nds;
//                    }else{
//                        $nds = 1.15;
//                        $nds_title = '15';
//                    }
                $date_change = 1590978663; // 1/06/2020  ввели другие процентные ставки

                if($credit->created_at < $date_change){
                    $nds = $credit->credit_limit==3 ? 1.25 : 1.35;
                }else{

                    if($credit->credit_limit==3){
                        $nds = 1.10;
                    }
                    if($credit->credit_limit==6){
                        $nds = 1.25;
                    }
                    if($credit->credit_limit==9){
                        $nds = 1.35;
                    }
                }
                if( !$credit->nds){
                    $nds += 1.15;
                }
                $nds_title = '15';
                foreach ($credit->creditItems as $credit_item) {
                    $npp++;
                    $cnt += $credit_item->quantity;

                    //$sum_price += $credit_item->amount* $credit_item->quantity;

                    //  $s = $credit_item->amount * $credit_item->quantity;
//
//                        $price_nds = $nds * $s;
//                        $sum += $price_nds;


                    $nds_percent = 1;
                    $reverse_nds_percent =  1.15;
                    if(!$credit->nds) {
                        $nds_percent = 1.15;
                        $reverse_nds_percent = 1;
                    }
                    // вычисление процента кредита
                    $percent = ($credit_item->price ) / ($credit_item->amount * $credit_item->quantity * $nds_percent);

                    // Сумма товара с процентами но без НДС если таковой имеется
                    $item_price = $credit_item->amount / $reverse_nds_percent  * $percent;
                    $sum_price += $item_price * $credit_item->quantity;
                    $clear_price += $credit_item->amount * $credit_item->quantity;
                    //var_dump($percent);
                    //$one_item_price =
                    ?>

                    <div class="check-container__item">
                        <span>
                            <?=$npp ?>
                        </span>
                        <?= $credit_item->title ?>…………………….<?=$credit_item->quantity .'*'.number_format($item_price,0,'.',' ') ?><br>
                        включая налоги ндс <br>
                        ндс <?=$nds_title ?>% ……………………………………………………<?=number_format($credit_item->price,2,'.',' ') ?>
                    </div>


                <?php }


                    $nds_sum = $credit->price - $sum_price;

                ?>

                <div class="check-container__summary">
                <div class="check-container__summary-head">
                        <span>
                            Сумма
                        </span>
                    <span>
                            НДС <?=$nds_title ?> %
                        </span>
                    <span>
                            ИТОГО
                        </span>
                </div>
                <ul class="check-container__summary-list">
                    <li>
                        <?=number_format($sum_price,1,'.',' ') ?>
                    </li>
                    <li>
                        <?=number_format($nds_sum,1,'.',' ') ?>
                    </li>
                    <li>
                        <?=number_format($credit->price,2,'.',' ') ?>
                    </li>
                </ul>
                </div>

            <?php } ?>


            <span class="split-line">
                ----------------------------------
                </span>




            <h1 style="text-align: center;margin-bottom: 20px!important;">
                График платежей
            </h1>

            <div class="check-container__transactions">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">дата</th>
                        <th scope="col">платеж</th>
                        <th scope="col">остаток</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $d = date('d',$credit->date_start);
                    $m = date('m',$credit->date_start);
                    $y = date('Y',$credit->date_start);
                    $npp = 0;
                    $credit_sum = $credit->price;
                    for ($i=0; $i<$credit->credit_limit; $i++){
                        $npp++;
                        $credit_sum -= $credit->deposit_month;
                        if($credit_sum<=0.9) $credit_sum = 0.0;
                        $m++;
                        if($m>12){
                            $m = $m-12;
                            $y++;
                        }
                        ?>

                        <tr>
                            <th scope="row"><?=$npp ?></th>
                            <td><?=$d .'-' . $m .'-' .$y ?></td>
                            <td><?=number_format($credit->deposit_month,2,'.',' ') ?></td>
                            <td> <?=number_format($credit_sum,2,'.',' ') ?></td>
                        </tr>

                        <?php

                    } // for  ?>


                    </tbody>
                </table>
            </div>

            <span class="split-line">
                ----------------------------------
                </span>

            <div>
                <div>
                    1.	Товар передан Покупателю в согласованном количестве и надлежащего качества.
                </div>

                <div>
                    2. Покупатель подтверждает свои обязательства по оплате стоимости товара в рассрочку согласно нижеследующему графику:
                </div>

                <div>
                    3.	Покупатель заверяет Продавца, что не имеет каких-либо претензий по исполнению электронного договора купли-продажи товара в рассрочку.
                </div>

                <!--<div>
                    4.  Покупатель заверяет Продавца, что он ознакомлен и принимает все условия  «Общих условий купли-продажи товаров в рассрочку между ООО «ZAAMIN-MARKET» и Клиентами на электронной торговой площадке «ZMARKET», в том числе с разделом «страхование», что является офертой АО «UNIVERSAL SUG”URTA» по заключению договора страхования риска невозврата суммы рассрочки  в качестве обеспечения исполнения Покупателя своих обязательств по договору купли продажи.
                </div>-->

                <div>
                    4. Настоящий Акт сформирован в Информационной системе «ZMARKET» (www.zmarket.uz), а также распечатан и подписан Сторонами в 2 (двух) экземплярах, один из которых находится у Продавца, второй - у Покупателя.
                </div>
            </div>


            <span class="split-line">
                ----------------------------------
                </span>

            <div style="margin-bottom: 0px;">
                <div>

                    ТОВАР ПЕРЕДАЛ:
                    от имени и по поручению ООО «ZAAMIN MARKET»
                </div>

                <div>
                    <u><?=$company_type ?> «<?=$company_name?>»</u>
                    на основании доверенности от (<?=date('d.m.Y',$credit->supplier->created_at) ?>)
                    Ответственное лицо
                </div>

                <div>
                    ФИО СОТРУДНИКА:

                    <div>
                        --------------------------------
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    ПОДПИСЬ:

                    <div>
                        --------------------------------
                    </div>
                </div>
                м.п.
            </div>


            <div>
                <h1 style="margin:0px!important;">
                    ТОВАР ПОЛУЧИЛ:
                </h1>

                <div>
                    <u><?=$client->lastname . ' ' .$client->username . ' ' .$client->patronymic  ?></u>
                </div>
                <div style="margin: 0px!important;">
                    ИНН:  <u><?=$client->inn ?></u>
                </div>

                <div style="margin: 0px!important;">
                    ID Покупателя:  <u><?=$client->id ?></u>
                </div>

                <div>
                    Паспорт (серия и номер): <u><?=$client->passport_serial . ' ' . $client->passport_id  ?></u>
                </div>

                <div>
                    Адрес проживания: <u><?=$client->address ?></u>
                </div>

                <div style="margin-bottom: 0px;">
                    Телефон:<u><?=$client->phone ?></u>
                </div>
				<p>





                <div style="margin-bottom: 80px;"> 
                   <div>
                    <u><?=$client->lastname . ' ' .$client->username . ' ' .$client->patronymic  ?></u>
                </div>
				<p>
                    подпись
                    -------------------------------
					</p>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table thead th,.table td,.table td, .table th{
            line-height: 1.4;
            font-size: 10px;
            padding: 0 !important;
            vertical-align: middle;
            border:1px solid #000;
            text-align: center;
        }
        h1{
            font-size: 20px;
        }
        .facture-list{
            padding: 0px;
            margin: 0px;
        }
        .facture-list__item{
            list-style: none;
        }

        .feature-cred__list{
            padding: 0px;
            margin: 0px;
        }

        .feature-cred__list-item-head{
            padding: 0px;
            margin: 0px;
        }
        .feature-cred__list-item{
            list-style: none;
        }
        .id-container{
        }
        .check-container__summary-list{
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-between;
        }
        .check-container__summary-list > li {
            list-style: none;
        }
        .check-container__summary-head{
            display: flex;
            justify-content: space-between;
        }
        .id-container__headline,.id-container__content{
            display: inline-block;
        }
    </style>

<?php
$script = " 
$('document').ready(function(){
   window.print();
   //window.close();
});";
$this->registerJs($script, yii\web\View::POS_END);
