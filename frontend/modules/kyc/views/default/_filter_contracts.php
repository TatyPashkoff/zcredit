<?php

use yii\widgets\LinkPager;


?>

<table class="table">
    <thead>
    <tr>
        <th><?=Yii::t('app','Дата заявки') ?></th>
        <th><?=Yii::t('app','ID договора') ?></th>
        <th><?=Yii::t('app','Поставщик') ?></th>
        <th><?=Yii::t('app','Клиент') ?></th>
        <th><?=Yii::t('app','Телефон') ?></th>
        <th><?=Yii::t('app','Сумма') ?></th>
        <th><?=Yii::t('app','Дата начала') ?></th>
        <th><?=Yii::t('app','Дата окончания') ?></th>
        <th><?=Yii::t('app','Период займа') ?></th>
        <th><?=Yii::t('app','Погашенных месяцев') ?></th>
        <th><?=Yii::t('app','Погашено') ?></th>
        <?php /*<th><?=Yii::t('app','Дата след. оплаты') ?></th>
                <th><?=Yii::t('app','Полис') ?></th> */ ?>
        <th><?=Yii::t('app','Договор') ?></th>
        <th><?=Yii::t('app','Документы') ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <?php if($model_order) {

        foreach ($model_order as $item) {
            ?>
            <tr>
                <td><?=date('d.m.Y',$item->created_at)?></td>
                <td><?=$item->id ?></td>
                <td><?=@$item->supplier->company ?></td>
                <td><a href="/kyc/edit?id=<?=@$item->kyc->id ?>"><?=@$item->client->username  . ' '. @$item->client->lastname ?></a></td>
                <td><?=@$item->client->phone ?></td>
                <td><?=number_format(@$item->credit->price,2,'.', ' ') ?></td>
                <td><?=date('d.m.Y',$item->date_start)?></td>
                <td><?=date('d.m.Y',$item->date_end)?></td>
                <td><?=$item->credit->credit_limit ?></td>
                <td><?=$item->credit->getPaymentMonth() ?></td>
                <td><?=$item->credit->price - $item->credit->credit - $item->credit->deposit_first ?></td>
                <?php /*<td><?=$item->credit->getNextPayment() ?></td>

                         <td><?=$item->status_polis ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Получен'): Yii::t('app','Не получен') /*'<div class="btn-default btn-small send-polis" data-id="'.$item->id .'" title="'.Yii::t('app','Отправить договор на страхование').'">' . Yii::t('app','Отправить') .'</div>' * /?></td> */ ?>
                <td><?=$item->status ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Подтвержден'): '<div class="btn-default btn-small confirm-contract" data-id="'.$item->id .'">' . Yii::t('app','Подтвердить') .'</div>' ?></td>
                <td>
                    <?php if($item->status){ ?>
                        <div class="row">
                            <div class="col-4"><a href="/print-act?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Акт') ?></a></div>
                            <div class="col-4"><a href="/print-invoice?id=<?=$item->credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Счет-фактура') ?></a></div>
                            <div class="col-4"><a href="/print-graph?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','План-график') ?></a></div>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

    <?php } ?>
    </tbody>
</table>

<div class="pagination">
    <?php if($pagination) { ?>
        <?= LinkPager::widget([
            'pagination' => $pagination,
        ]);
    } ?>
</div>

