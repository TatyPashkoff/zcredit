<?php

use yii\widgets\LinkPager;

?>
<table class="table">
    <thead>
    <tr>
        <th><?=Yii::t('app','Дата запроса') ?></th>
        <th><?=Yii::t('app','Статус') ?></th>
        <th><?=Yii::t('app','Описание') ?></th>

    </tr>
    </thead>
    <tbody>

    <?php if($model_scoring) {

        foreach ($model_scoring as $item) {
            ?>
            <tr>
                <td><?=date('d.m.Y / H:i',$item->created_at)?></td>
                <td><?=$item->status ? Yii::t('app','Успешно') : Yii::t('app','Не успешно') ?></td>
                <td><?=$item->status ? 'OK' : $item->info ?></td>
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

