<?php

use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }
        .circle{
            border-radius: 50%;
            border: 4px solid #fff; ;
            background: #0acb94;
            width:50px;
            height: 50px;
            padding: 5px 0 0 0;
            font-size: 22px;
            color: #fff;
        }
        .title-info{
            text-align: center;
            color: #0acb94;
        }


    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'polises']) ?>


        <div class="title-with-border"><?=Yii::t('app','Страховые полисы')?></div>

        <?php /*<div class="col-md-12">
            <div class="col-sm-6">
                    <label><?=Yii::t('app','Фильтр')?> </label>
                    <select id="filter_clients" class="form-control">
                        <option value="0" <?=$filter_type==0 ? 'selected':'' ?>><?=Yii::t('app','Все')?></option>
                        <option value="1" <?=$filter_type==1 ? 'selected':'' ?>><?=Yii::t('app','Подтвержденные')?></option>
                        <option value="2" <?=$filter_type==2 ? 'selected':'' ?>><?=Yii::t('app','Не подтвержденные')?></option>
                    </select>
            </div>


        </div> */ ?>

        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','Дата создания') ?></th>
                <th><?=Yii::t('app','ID договора') ?></th>
                <th><?=Yii::t('app','Клиент') ?></th>
                <th><?=Yii::t('app','Поставщик') ?></th>
                <?php /* <th><?=Yii::t('app','Документы клиента') ?></th> */ ?>
                <th><?=Yii::t('app','ID полиса') ?></th>
                <th><?=Yii::t('app','Серия полиса') ?></th>
                <th><?=Yii::t('app','Номер полиса') ?></th>
                <?php /*<th><?=Yii::t('app','Статус') ?></th>
                <th></th> */ ?>
            </tr>
            </thead>
            <tbody>

            <?php if($model_polises) {

                foreach ($model_polises as $item) {
                    ?>
                    <tr>
                        <td><?=date('d.m.Y',$item->created_at)?></td>
                        <td><?=@$item->contract_id ?></td>
                        <td><?=@$item->client->username  . ' '. @$item->client->lastname ?></td>
                        <td><?=@$item->supplier->company ?></td>
                        <?php /*<td><a href="/get-documents?id=<?=$item->client_id ?>"><?=Yii::t('app','Скачать документы клиента')?></a></td> */ ?>
                        <td><?=$item->contractRegistrationID ?></td>
                        <td><?=@$item->polisSeries ?></td>
                        <td><?=$item->polisNumber ?></td>
                        <?php /* <td><?=$item->status ? Yii::t('app','Подтвержден'): Yii::t('app','Не подтвержден') ?></td>
                        <td><a href="/kyc/edit-polis?id=<?=$item->id ?>" title="<?=Yii::t('app','Изменить')?>"><i class="fa fa-address-card-o"></i></a></td> */ ?>
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


    </div>

<?php
/*$msg_server_error = Yii::t('app','Ошибка сервера!');

$script = " 
$('document').ready(function(){

});";
$this->registerJs($script, yii\web\View::POS_END); */