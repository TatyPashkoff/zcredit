<?php
error_reporting(E_ALL);
\frontend\assets\MainAsset::register($this);

$months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
$month = $months[ date('m',time())-1];

$company = explode(' ',@$credit->supplier->company);
$company_type = $company[0];
unset($company[0]);
$company_name = implode(' ',$company);

?>
    <p align="right">
        <strong><em>Пункт 2.5 Концепции</em></strong>
    </p>
    <p align="right">
        <strong><em>Документ к разработке №1</em></strong>
    </p>
    <p align="right">
        <strong><em>(если ZAAMIN MARKET плательщик НДС)</em></strong>
    </p>
    <p>
        <strong></strong>
    </p>
    <p align="center">
        <strong>А К Т </strong>
        <strong>№_____-</strong>
        <strong>K</strong>
        <strong></strong>
    </p>
    <p align="center">
        <strong>приема-передачи товара </strong>
    </p>
    <p align="center">
        <strong></strong>
    </p>
    <p align="center">
        <strong></strong>
    </p>
    <div align="center">
        <p>
            Дата: ______ <?=$month . ' ' . date('Y') ?> г.
        </p>
        <p>
            _____
            <em>
                <u>
                    <?=$company_type //автоматическая вставка организационно-правовой формы (ООО, АО) и ?>
                </u>
            </em>
            <u>«</u>
            <em><u><?=$company_name /* фирменное наименование организации*/ ?></u></em>
            <u>»</u>
            ______, действующее по поручению продавца – <strong>ООО «</strong><strong>ZAAMIN</strong><strong> </strong><strong>MARKET</strong>    <strong>»</strong> (далее – «Продавец»), и
        </p>
        <p>
            ___    <em><u><?=@$credit->client->username . ' ' . @$credit->client->lastname //автоматическая вставка Ф.И.О. и иных данных лица-Покупателя ?></u></em>
            __, именуемый(-ая) в дальнейшем «Покупатель», с другой Стороны, вместе
            именуемые как «Стороны», а по отдельности – «Сторона», составили настоящий
            акт о том, что на основании электронного договора купли-продажи товара,
            заключенного путем направления со стороны Продавца оферты №_____-К, и его
            акцепта со стороны Покупателя, Продавец передал, а Покупатель получил
            нижеуказанный товар:
        </p>
    </div>
    <div align="center">
        <table border="1" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="33">
                    <p>
                        <a name="_Hlk16517644"><strong><em>№</em></strong></a>
                    </p>
                </td>
                <td width="211">
                    <p align="center">
                        <strong>
                            <em>Наименование и описание Товара</em>
                        </strong>
                    </p>
                </td>
                <td width="124">
                    <p align="center">
                        <strong><em>Единица измерения </em></strong>
                    </p>
                    <p align="center">
                        <strong><em>(штука, единица или комплект)</em></strong>
                    </p>
                </td>
                <td width="54">
                    <p align="center">
                        <strong><em>Кол-во</em></strong>
                    </p>
                </td>
                <td width="103">
                    <p align="center">
                        <strong><em>Цена с учетом НДС, сум </em></strong>
                    </p>
                </td>
                <td width="98">
                    <p align="center">
                        <strong><em>Сумма НДС, сум</em></strong>
                    </p>
                </td>
            </tr>
            <?php if(isset($credit->creditItems)) {
                $npp = 0;
                $cnt = 0;
                $sum = 0;
                $sum_price = 0;
                foreach ($credit->creditItems as $credit_item) {
                    $npp++;
                    $cnt += $credit_item->quantity;
                    $s = $credit_item->price * $credit_item->quantity;
                    $sum += $s;
                    $sum_price += $credit_item->price;
                    ?>

                    <tr>
                        <td width="33">
                            <p>
                                <?=$npp ?>
                            </p>
                        </td>
                        <td width="211">
                            <p align="center">
                                <strong><?=$credit_item->title ?></strong>
                            </p>
                        </td>
                        <td width="124">
                            <p align="center">
                                <strong></strong>
                            </p>
                        </td>
                        <td width="54">
                            <p align="center">
                                <?=$credit_item->quantity ?>
                            </p>
                        </td>
                        <td width="103">
                            <p align="center">
                                <strong><?=number_format($credit_item->price,2,'.',' ') ?></strong>
                            </p>
                        </td>
                        <td width="98" valign="top">
                            <p align="center">
                                <strong><?=number_format($s,2,'.',' ') ?></strong>
                            </p>
                        </td>
                    </tr>

                <?php } ?>

                <tr>
                    <td width="33">
                    </td>
                    <td width="389" colspan="3">
                        <p align="center">
                            <strong>Итого на общую сумму:</strong>
                        </p>
                    </td>
                    <td width="103"><?=number_format($sum_price,2,'.',' ') ?>
                    </td>
                    <td width="98" valign="top"><?=number_format($sum,2,'.',' ') ?>
                    </td>
                </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
    <div align="center">
        <p>
            1. Товар передан Покупателю в согласованном количестве и надлежащего
            качества.
        </p>
        <p>
            2. Покупатель подтверждает свои обязательства по оплате стоимости товара в
            рассрочку согласно нижеследующему графику:
        </p>
    </div>

    <div align="center">
        <table border="1" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="154" valign="top">
                    <p align="center">
                        <strong><em>№ платежа</em></strong>
                    </p>
                </td>
                <td width="154" valign="top">
                    <p align="center">
                        <strong><em>Дата платежа</em></strong>
                    </p>
                </td>
                <td width="154" valign="top">
                    <p align="center">
                        <strong><em>Сумма платежа, сум</em></strong>
                    </p>
                </td>
                <td width="155" valign="top">
                    <p align="center">
                        <strong><em>Остаток к погашению, сум</em></strong>
                    </p>
                </td>
            </tr>
            <?php
            $d = date('d',$credit->date_start);
            $m = date('m',$credit->date_start);
            $y = date('Y',$credit->date_start);
            $npp = 0;
            $credit_sum = $credit->credit;
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
                    <td width="154" valign="top">
                        <p align="center">
                            Платеж №<?=$npp ?>
                        </p>
                    </td>
                    <td width="154" valign="top">
                        <p align="center">
                            <?=$d .'-' . $m .'-' .$y ?>
                        </p>
                    </td>
                    <td width="154" valign="top">
                        <p align="center">
                            <?=number_format($credit->deposit_month,2,'.',' ')?>
                        </p>
                    </td>
                    <td width="155" valign="top">
                        <p align="center">
                            <?=number_format($credit_sum,2,'.',' ')?>
                        </p>
                    </td>
                </tr>

            <?php

            } // for  ?>


            </tbody>
        </table>
    </div>

    <div align="center">
        <p>
            3. Покупатель заверяет Продавца, что не имеет каких-либо претензий по
            исполнению электронного договора купли-продажи товара в рассрочку.
        </p>
        <p>
            4. Настоящий Акт сформирован в Информационной системе «ZMARKET» (    <a href="http://www.zmarket.uz/">www.zmarket.uz</a>), а также распечатан и
            подписан Сторонами в 2 (двух) экземплярах, один из которых находится у
            Продавца, второй - у Покупателя.
        </p>
        <table border="1" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="309" valign="top">
                    <p align="center">
                        <strong>ТОВАР ПЕРЕДАЛ: </strong>
                    </p>
                    <p align="center">
                        <strong>от имени и по поручению ООО «</strong>
                        <strong>ZAAMIN</strong>
                        <strong> </strong>
                        <strong>MARKET</strong>
                        <strong>»</strong>
                        <strong></strong>
                    </p>
                    <p align="center">
                        <strong></strong>
                    </p>
                    <p align="center">
                        <strong>_</strong>
                        <em>
                           <?=$company_type // организационно-правовая форма компании Партнера (ООО, АО,…) ?>
                        </em>
                        <strong>_ «_</strong>
                        <em><?=$company_name //фирменное наименование комании Партнера ?></em>
                        <strong>_»</strong>
                    </p>
                    <p align="center">
                        на основании доверенности от 01.11.2019 г.
                    </p>
                </td>
                <td width="314" valign="top">
                    <p align="center">
                        <strong>ТОВАР ПОЛУЧИЛ:</strong>
                    </p>
                    <p>
                        <strong></strong>
                    </p>
                    <p align="center">
                        <em>
                            ___
                            <u>
                                <?=@$credit->client->username . ' ' . @$credit->client->lastname  //автоматическая вставка Ф.И.О. лица-Покупателя из его Персонального кабинета ?>
                            </u>
                            ___
                        </em>
                        <strong></strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="309" valign="top">
                    <p>
                        Ответственное лицо
                    </p>
                    <p>
                        <em>
                            <?=@$credit->supplier->username . ' ' . @$credit->supplier->lastname /*
                            автоматическая вставка Ф.И.О. лица-сотрудника компании
                            Партнера, ответственного за отпуск товара Клиенту - из
                            данных Персонального кабинета Продавца */ ?>
                        </em>
                    </p>
                    <p>
                        ______________
                    </p>
                    <p>
                        м.п.
                    </p>
                </td>
                <td width="314" valign="top">
                    <p>
                        ID Покупателя:
                        <em>
                            ___
                            <u>
                                <?=@$credit->client->id //автоматическая вставка данных лица-Покупателя из его Персонального кабинета ?>
                            </u>
                            ___
                        </em>
                    </p>
                    <p>
                        Паспорт (серия и номер):
                        <em>
                            ___
                            <u>
                                <?=@$credit->client->passport_serial . ' ' . @$credit->client->passport_id // //автоматическая вставка данных лица-Покупателя из его Персонального кабинета ?>
                            </u>
                            ___
                        </em>
                    </p>
                    <p>
                        Адрес проживания:
                        <em>
                            ___
                            <u>
                                <?=@$credit->client->address // автоматическая вставка данных лица-Покупателя из его Персонального кабинета ?>
                            </u>
                        </em>
                    </p>
                    <p>
                        Телефон:
                        <em>
                            __
                            <u>
                                <?=@$credit->client->phone //автоматическая вставка данных лица-Покупателя из его Персонального кабинета ?>
                            </u>
                        </em>
                    </p>
                </td>
            </tr>
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
