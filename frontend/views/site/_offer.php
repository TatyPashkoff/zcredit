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
            font-size: 14px;
            margin-bottom: 5px;
            line-height: 1.3;
            color: #000;

        }
    </style>

   <div class="offer-container container">
    <div class="offer__head">
        <div class="offer__head-content">
            <h1 class="offer__headline">
                Оферта N <?=$credit->contract->id ?>
            </h1>
            <span class="offer__subline">
            По продаже в рассрочку
        </span>
        </div>

        <?php /*<div class="offer-head-date">
            <a class="zcoin-container__z" href="">
                Распечатать
            </a>
            <span class="offer-header-date__content">
                Дата 28.02.2019
            </span>
        </div> */ ?>
    </div>

    <div class="offer__content">
        <div class="offer__content-list">
            <div class="offer__content__item">
                Продавец:
                <span class="offer__content__item-value">ООО «ZAAMIN-MARKET»<!--<?=$credit->supplier->username ?>--></span>
            </div>

            <div class="offer__content__item">
                Адрес:
                <span class="offer__content__item-value"> г. Ташкент, Юнус-Абадский р-н, ул. Корхожиота д.3<!--<?=$credit->supplier->address ?>--></span>
            </div>

            <div class="offer__content__item">
                ИНН:
                <span class="offer__content__item-value">
				306771342
                    <!--<?=$credit->supplier->id ?>-->
                </span>
            </div>
			 <div class="offer__content__item">
                ОКЭД:
                <span class="offer__content__item-value">
				47190
                    <!--<?=$credit->supplier->id ?>-->
                </span>
            </div>
			
			 <div class="offer__content__item">
                МФО:
                <span class="offer__content__item-value">
				01095
                    <!--<?=$credit->supplier->id ?>-->
                </span>
            </div>
			
			 <div class="offer__content__item">
                Р/С:
                <span class="offer__content__item-value">
				20208000905136549001
                    <!--<?=$credit->supplier->id ?>-->
                </span>
            </div>
        </div>

        <div class="offer__content-list">
            <div class="offer__content__item">
                Покупатель:
                <span class="offer__content__item-value"><?=$credit->client->lastname . ' ' . $credit->client->username . ' ' . $credit->client->patronymic ?></span>
            </div>

            <div class="offer__content__item">
                Адрес:
                <span class="offer__content__item-value"><?=$credit->client->address ?></span>
            </div>

            <div class="offer__content__item">
                ID клиента:
                <span class="offer__content__item-value">
                    <?=$credit->client->id ?>
                </span>
            </div>
			
        </div>


    </div>

    <p class="offer__license">
        Настоящим Продавец, руководствуясь Общими условиями купли-продажи товаров в рассрочку между ООО  «ZAAMIN MARKET» и клиентом на электронной площадке «ZMARKET», текст которых размещен в Информационной системе «ZMARKET» (zmarket.uz), предлагает Покупателю купить следущие товары в рассрочку по цене, графику платежей и условий, указанных ниже:
    </p>


    <div class="offer__table-container">
        <table class="offer__table">
            <thead>
            <tr class="offer__table__tr">
                <th class="offer__table__th">№</th>
                <th class="offer__table__th">Наименование товара</th>
                <th class="offer__table__th">Единица</th>
                <th class="offer__table__th">Количество</th>
                <th class="offer__table__th">Цена</th>
                <th class="offer__table__th">Стоимость поставки</th>
                <th class="offer__table__th">Ставка НДС, %</th>
                <th class="offer__table__th">Сумма НДС</th>
                <th class="offer__table__th">Стоимость с НДС</th>

            </tr>
            </thead>
            <tbody style="border-bottom: 4px solid #009F80">

            <?php

            if(isset($credit->creditItems)) {
                $npp = 0;
                $cnt = 0;
                $sum = 0;
                $sum_price = 0;
                $s = 0;
                $nds_sum = 0;
                $clear_price = 0;

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
                if (!$credit->nds) {
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
                    $reverse_nds_percent = 1.15;
                    if (!$credit->nds) {
                        $nds_percent = 1.15;
                        $reverse_nds_percent = 1;
                    }
                    // вычисление процента кредита
                    $percent = ($credit_item->price) / ($credit_item->amount * $credit_item->quantity * $nds_percent);

                    // Сумма товара с процентами но без НДС если таковой имеется
                    $item_price = $credit_item->amount / $reverse_nds_percent * $percent;
                    //$sum_price += $credit_item->price * $credit_item->quantity;
                    $sum_price += $credit_item->price ;
                    $clear_price += $credit_item->amount * $credit_item->quantity;
                    // сумма ндс
                    $nds_sum = $credit_item->price - $item_price;
                    //var_dump($percent);
                    //$one_item_price =
                    ?>

                    <tr class="offer__table__tr">
                        <td><?= $npp?></td>
                        <td><?= $credit_item->title ?></td>
                        <td>шт</td>
                        <td><?= $credit_item->quantity ?></td>
                        <td><?= number_format($item_price, 2, '.', ' ') ?></td>
                        <td><?= number_format($item_price * $credit_item->quantity, 2, '.', ' ') ?></td>
                        <td><?= $nds_title ?> </td>
                        <td><?= number_format($nds_sum, 2, '.', ' ') ?> </td>
                        <td><?= number_format($credit_item->price, 2, '.', ' ') ?></td>
                    </tr>




                <?php }

            }
            // $nds_sum = $credit->credit - $sum_price;

            ?>

            </tbody>
        </table>
    </div>

    <div class="offer__table__summ">

        <div class="offer__table-item">
            <span class="offer__table-res">
                Итого на общую сумму
            </span>
            <span class="offer__table-count">
                <?=number_format($sum_price,2,'.',' ') ?>
            </span>
        </div>


        <div class="offer__table-item">
            <span class="offer__table-res">
                Условия рассрочки:
            </span>
            <span class="offer__table-subline" style="margin-top: 13px;">
                <?=number_format($sum_price,2,'.',' ') ?> сум подлежат выплате в течении <?=$credit->credit_limit ?> месяцев
            </span>
        </div>

    </div>

    <div class="offer__table-container">
        <table class="offer__table">
            <thead>
            <tr class="offer__table__tr">
                <th class="offer__table__th">№</th>
                <th class="offer__table__th">Дата</th>
                <th class="offer__table__th">Платеж</th>
                <th class="offer__table__th">Остаток</th>

            </tr>
            </thead>
            <tbody style="border-bottom: 4px solid #009F80;">
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
                    <th style="text-align: center;" scope="row"><?=$npp ?></th>
                    <td class="offer__tr-td"><?=$d .'-' . $m .'-' .$y ?></td>
                    <td class="offer__tr-td"><?=number_format($credit->deposit_month,2,'.',' ') ?></td>
                    <td class="offer__tr-td"> <?=number_format($credit_sum,2,'.',' ') ?></td>
                </tr>

                <?php

            } // for  ?>

            </tbody>
        </table>
    </div>

    <div class="offer__subcontent">
        <h2 class="offer__subcontent__headline">
            Настоящая оферта получена продавцом:
        </h2>
        <span class="offer__subcontent__subline">
            <?=date('d-m-Y',$credit->created_at) ?>
        </span>

        <h4 class="offer__subcontent__headline">
            Покупатель гарантирует, что ознакомлен с Общими условиями купли-продажи товаров между ООО "ZAAMIN MARKET" и Партнерами, текст которых размещен в Информационной системе "ZMARKET" (<a href="//zmarket.uz">www.zmarket.uz</a>), и обязуется соблюдать их при выполнении электронного договора купли-продажи, который будет заключен на основании настоящей оферты.
        </h4>
    </div>
</div>
