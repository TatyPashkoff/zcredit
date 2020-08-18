<div class="offer-container container">
    <div class="offer__head">
        <div class="offer__head-content">
            <h1 class="offer__headline">
                Оферта N <?=$credit->contract->id ?>
            </h1>
            <span class="offer__subline">
            На покупку товара
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
                Юр.лицо:
                <span class="offer__content__item-value"><?=$credit->supplier->company ?></span>
            </div>
            <div class="offer__content__item">
                ИНН:
                <span class="offer__content__item-value"><?=$credit->supplier->inn ?></span>
            </div>
            <div class="offer__content__item">
                Продавец:
                <span class="offer__content__item-value"><?=$credit->supplier->username ?></span>
            </div>

            <div class="offer__content__item">
                Адрес:
                <span class="offer__content__item-value"><?=$credit->supplier->address ?></span>
            </div>

            <div class="offer__content__item">
                ID продавца:
                <span class="offer__content__item-value">
                    <?=$credit->supplier->id ?>
                </span>
            </div>
        </div>

        <div class="offer__content-list">
            <div class="offer__content__item">
                Покупатель:
                <span class="offer__content__item-value">ООО ZAAMIN MARKET</span>
            </div>

            <div class="offer__content__item">
                Адрес:
                <span class="offer__content__item-value">г. Ташкент, Юнус-Абадский р-н, ул. Корхожиота д.3 </span>
            </div>

            <div class="offer__content__item">
                Реквизиты компании:
                <span class="offer__content__item-value">
                    ИНН: 306771342
                </span>
            </div>
        </div>


    </div>

    <p class="offer__license">
        В соответствии с Общими условиями купли-продажи товаров между ООО «ZAAMIN MARKET» и Партнерами, текст которых размещен в Информационной системе «ZMARKET» (www.zmarket.uz), Покупатель просит Продавца продать нижеследующий товар согласно:
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
            <tbody>

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
                    //$discount = Yii::$app->user->identity->discount ? Yii::$app->user->identity->discount : 0;
                   // $discount_s = $credit_item->amount * $discount / 100;
                   // $discount_sum = $credit_item->amount - $discount_s; // стоимость товара со скидкой

                    if (!$credit->nds) {
                        $nds_percent = 1.15;
                        $reverse_nds_percent = 1;
                        $nds_title = "(без ндс)";
                    }
                    // вычисление процента кредита
                    $percent = ($credit_item->price) / ($credit_item->discount_sum * $credit_item->quantity * $nds_percent);

                    // Сумма товара с процентами но без НДС если таковой имеется
                    $item_price = $credit_item->discount_sum / $reverse_nds_percent /* * $percent*/;
                    $sum_price += $credit_item->discount_sum  * $credit_item->quantity;
                    $clear_price += $credit_item->discount_sum * $credit_item->quantity;
                    // сумма ндс
                    $nds_sum = $credit_item->discount_sum - $item_price;
                    //var_dump($percent);
                    //$one_item_price =
                    ?>

                    <tr class="offer__table__tr">
                        <td><?= $npp ?></td>
                        <td><?= $credit_item->title ?></td>
                        <td>шт</td>
                        <td><?= $credit_item->quantity ?></td>
                        <td><?= number_format($item_price, 2, '.', ' ') ?></td>
                        <td><?= number_format($item_price * $credit_item->quantity, 2, '.', ' ') ?></td>
                        <td><?= $nds_title ?> </td>
                        <td><?= number_format($nds_sum* $credit_item->quantity, 2, '.', ' ') ?> </td>
                        <td><?= number_format(($item_price + $nds_sum) * $credit_item->quantity, 2, '.', ' ') ?></td>
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

      <? /* ?>
        <div class="offer__table-item">
            <span class="offer__table-res">
                Условия рассрочки:
            </span>
            <span class="offer__table-subline">
                <?=number_format($sum_price,2,'.',' ') ?> сум подлежат выплате в течении <?=$credit->credit_limit ?> месяцев
            </span>
        </div>
    <? */ ?>
    </div>
<? /* ?>
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
            <tbody> 
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
<? */ ?>
    <div class="offer__subcontent">

        <h4 class="offer__subcontent__headline">
            Покупатель гарантирует, что ознакомлен с Общими условиями купли-продажи товаров между ООО «ZAAMIN MARKET» и Партнерами, текст которых размещен в Информационной системе «ZMARKET» (www.zmarket.uz), и обязуется соблюдать их при выполнении электронного договора купли-продажи, который будет заключен на основании настоящей оферты.
        </h4>
        <h2 class="offer__subcontent__headline">
            Настоящая оферта получена продавцом:
        </h2>
        <span class="offer__subcontent__subline">
            <?=date('d-m-Y',$credit->created_at) ?>
        </span>
    </div>
</div>