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
        <th><?=Yii::t('app','ИНН') ?></th>
        <th><?=Yii::t('app','Статус') ?></th>
        <th><?=Yii::t('app','Заполнение документов') ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <?php if($model_kyc) {

        foreach ($model_kyc as $item) {

            switch ($item->client->status_client_complete) {
                case 1:
                    $complete = "Тел подтвержден";
                    $class_text_complete = "text-danger";
                    break;
                case 2:
                    $complete = "Карта";
                    $class_text_complete = "text-success";
                    break;
                case 3:
                    $complete = "Паспорт";
                    $class_text_complete = "text-danger";
                    break;
                case 4:
                    $complete = "Завершено";
                    $class_text_complete = "text-success";
                    break;
                default:
                    $complete = 'Не завершено';
                    $class_text_complete = "text-danger";
            }

            ?>
            <tr>
                <td><?=date('d.m.Y',$item->created_at)?></td>
                <td><?=@$item->client->id ?></td>
                <td><?=@$item->client->username  . ' '. @$item->client->lastname ?></td>
                <td><?=@$item->client->passport_serial . ' ' . @$item->client->passport_id ?></td>
                <?php /*<td><a href="/get-documents?id=<?=$item->client_id ?>"><?=Yii::t('app','Скачать документы клиента')?></a></td> */ ?>
                <td><?=$item->status_verify ? Yii::t('app','Да'): Yii::t('app','Нет') ?></td>
                <td><?=@$item->client->phone ?></td>
                <td><?=$item->delay ? Yii::t('app','Есть') : Yii::t('app','Нет') ?></td>
                <td><?=number_format($item->credit_month,2,'.',' ')?></td>
                <td><?=number_format($item->credit_year,2,'.',' ')?></td>
                <td><?=number_format($item->salary,2,'.',' ')?></td>
                <td><?=$item->client->inn ?></td>
                <td><?=$item->status ? Yii::t('app','Подтвержден'): Yii::t('app','Не подтвержден') ?></td>
                <td class="<?=$class_text_complete ?>"><?=$complete ?></td>
                <td><a href="/kyc/edit?id=<?=$item->id ?>" title="<?=Yii::t('app','Изменить')?>"><i class="fa fa-address-card-o"></i></a></td>
            </tr>
        <?php } ?>

    <?php } ?>
    </tbody>
</table>

<div class="pagination">
    <?= LinkPager::widget([
        'pagination' => $pagination,
    ]);  ?>
</div>

