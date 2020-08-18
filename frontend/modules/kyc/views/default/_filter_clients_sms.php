<?php

use yii\widgets\LinkPager;

//\frontend\assets\MainAsset::register($this);


?>

<table class="table">
    <thead>
    <tr>
        <th><?=Yii::t('app','Дата заявки') ?></th>
        <th><?=Yii::t('app','ID клиента') ?></th>
        <th><?=Yii::t('app','ФИО Клиента') ?></th>
        <th><?=Yii::t('app','Номер паспорта клиента') ?></th>
        <?php /* <th><?=Yii::t('app','Документы клиента') ?></th> */ ?>
        <th><?=Yii::t('app','Верификация uzcard') ?></th>
        <th><?=Yii::t('app','Телефон') ?></th>
        <th><?=Yii::t('app','Просрочки платежей') ?></th>
        <th><?=Yii::t('app','Кредитный лимит в месяц, сум') ?></th>
        <th><?=Yii::t('app','Кредитный лимит в год, сум') ?></th>
        <th><?=Yii::t('app','Заработная плата, сум') ?></th>
        <th><?=Yii::t('app','Кредитный рейтинг') ?></th>
        <th><?=Yii::t('app','Статус') ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <?php if($model_kyc) {
        foreach ($model_kyc as $item) {
            ?>
            <tr>
                <td><?=date('d.m.Y',$item->created_at)?></td>
                <td><?=@$item->id ?></td>
                <td><?=@$item->username  . ' '. @$item->lastname ?></td>
                <td><?=@$item->passport_serial . ' ' . @$item->passport_id ?></td>
                <?php /*<td><a href="/get-documents?id=<?=$item->client_id ?>"><?=Yii::t('app','Скачать документы клиента')?></a></td> */ ?>
                <td><?=$item->contract->status_verify ? Yii::t('app','Да'): Yii::t('app','Нет') ?></td>
                <td><?=@$item->phone ?></td>
                <td><?=$item->contract->delay ? Yii::t('app','Есть') : Yii::t('app','Нет') ?></td>
                <td><?=number_format($item->contract->credit_month,2,'.',' ')?></td>
                <td><?=number_format($item->contract->credit_year,2,'.',' ')?></td>
                <td><?=number_format($item->contract->salary,2,'.',' ')?></td>
                <td><?=$item->contract->credit_rating ?></td>
                <td><?=$item->contract->status ? Yii::t('app','Подтвержден'): Yii::t('app','Не подтвержден') ?></td>
            </tr>
        <?php } ?>

    <?php } ?>
    </tbody>
</table>

