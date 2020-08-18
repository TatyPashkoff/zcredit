<?php

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }


    </style>
	
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'credit_history']) ?>

        <div class="title-with-border"><?=Yii::t('app','Информация кредита')?></div>
        
        <?php if($credit) {
                ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?=Yii::t('app','Дата выдачи') ?></th>
                        <th><?=Yii::t('app','Дата рассрочки') ?></th>
                        <th><?=Yii::t('app','Заемщик') ?></th>
                        <th><?=Yii::t('app','Срок рассрочки') ?></th>
                        <th><?=Yii::t('app','Сумма') ?></th>
                        <th><?=Yii::t('app','Предоплата') ?></th>
                        <th><?=Yii::t('app','Рассрочка') ?></th>
                        <th><?=Yii::t('app','НДС') ?></th>
                        <?php /*<th><?=Yii::t('app','Номер телефона') ?></th>        */ ?>
                        <th><?=Yii::t('app','Статус') ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td><?=date('d.m.Y',$credit->created_at)?></td>
                        <td><?=date('d.m.Y',$credit->credit_date)?></td>
                        <td><?=$credit->client->username . ' ' . $credit->client->lastname ?></td>
                        <td><?=$credit->credit_limit ?> <?=Yii::t('app','мес.')?></td>
                        <td><?=number_format($credit->price,2,'.',' ')?> <?=Yii::t('app','сум')?></td>
                        <td><?=number_format($credit->deposit_first,2,'.',' ')?> <?=Yii::t('app','сум')?></td>
                        <td><?=number_format($credit->price - $credit->deposit_first,2,'.',' ')?> <?=Yii::t('app','сум')?></td>
<!--                        <td>--><?//=$credit->supplier->nds_state ? $credit->supplier->nds : 0 ?><!-- %</td>-->
                        <td><?=$credit->nds ? 0 : 15 ?> %</td>
                        <?php /*<td><a href="tel:+<?=$credit->client->phone ?>">+<?=$credit->client->phone ?></a></td>*/ ?>

                        <td class="yellow"><?=\common\models\Credits::PAYMENT_STATUS[$credit->status]?></td>

                    </tr>
                    </tbody>
                </table>
            <?php
        } ?>

        <div class="title-with-border"><?=Yii::t('app','Список товаров в кредит')?></div>

        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','№пп') ?></th>
                <th><?=Yii::t('app','Наименование') ?></th>
                <th><?=Yii::t('app','Цена') ?></th>
                <th><?=Yii::t('app','Количество') ?></th>
                <th><?=Yii::t('app','Сумма') ?></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($credit->creditItems)) {
                $npp = 0;
                $cnt = 0;
                $sum = 0;
                foreach ($credit->creditItems as $credit_item) {
                    $npp++;
                    $cnt += $credit_item->quantity;
                    $s = $credit_item->amount * $credit_item->quantity;
                    $sum += $s;
                    ?>

                    <tr>
                        <td><?=$npp ?></td>
                        <td><?=$credit_item->title ?></td>
                        <td><?=number_format($credit_item->amount,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                        <td><?=$credit_item->quantity ?></td>
                        <td><?=number_format($s,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>

                    </tr>

                <?php } ?>
                <tr>
                    <td colspan="3"><?=Yii::t('app','Итого')?>:</td>
                    <td><?=$cnt ?></td>
                    <td><?=number_format($sum,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                </tr>
                <?php //if($credit->supplier->nds_state){ ?>
                <?php if($credit->nds){ ?>
                    <tr>
                        <td colspan="3"><?=Yii::t('app','НДС')?>:</td>
                        <td></td>
                        <td>15 % (<?=$credit->price - $sum ?> сум)</td>
                    </tr>
                    <tr>
                        <td colspan="3"><?=Yii::t('app','Итого с НДС')?>:</td>
                        <td></td>
                        <td><?=number_format($credit->price,2,'.',' ') ?> сум</td>
                    </tr>
                <?php } ?>
            <?php } ?>




            </tbody>
        </table>

        <div class="title-with-border"><?=Yii::t('app','График оплаты')?></div>

        <table class="table">
                <thead>
                <tr>
                  <th><?=Yii::t('app','№пп') ?></th>
                  <th><?=Yii::t('app','Дата') ?></th>
                  <th><?=Yii::t('app','Сумма') ?></th>
                  <th><?=Yii::t('app','Дата оплаты') ?></th>
                  <th><?=Yii::t('app','Статус') ?></th>
                </tr>
                </thead>
                <tbody>

                <?php

                $npp = 0;
                foreach ($credit->payments as $payment){
                    $npp++;
                    ?>
                        <tr>
                            <td><?=$npp ?></td>
                            <td><?= date('d.m.Y', $payment->credit_date) ?></td>
                            <td><?=number_format($payment->price,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                            <td><?= $payment->payment_date!='' ? date('d.m.Y', $payment->payment_date) : '' ?></td>
                            <td class="yellow"><?= $payment->payment_status ? Yii::t('app','Оплачено') : Yii::t('app','Не оплачено') ?></td>

                        </tr>
                    <?php
                }  ?>

                <tr>
                    <td colspan="2"><?=Yii::t('app','Итого') ?></td>
                    <td><?=number_format($credit->price - $credit->deposit_first,2,'.',' ') ?> сум</td>
                    <td colspan="2"></td>
                </tr>
                </tbody>
              </table>

    </div>




<?php

$script = " 
$('document').ready(function(){
   
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
