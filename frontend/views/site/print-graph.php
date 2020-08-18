<?php

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

        @page{
            margin:50px;
        }

    </style>

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

        if($credit->payments) {
            $npp = 0;
            foreach ($credit->payments as $payment) {
                $npp++;
                ?>
                <tr>
                    <td><?= $npp ?></td>
                    <td><?= date('d.m.Y', $payment->credit_date) ?></td>
                    <td><?= number_format($payment->price, 2, '.', ' ') ?> <?= Yii::t('app', 'сум') ?></td>
                    <td><?= $payment->payment_date != '' ? date('d.m.Y', $payment->payment_date) : '' ?></td>
                    <td class="yellow"><?= $payment->payment_status ? Yii::t('app', 'Оплачено') : Yii::t('app', 'Не оплачено') ?></td>

                </tr>
                <?php
            }
        } ?>

        </tbody>
    </table>

<?php

$script = " 
$('document').ready(function(){
     window.print();
   window.close();
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
