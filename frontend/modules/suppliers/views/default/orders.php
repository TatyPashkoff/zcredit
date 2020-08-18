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
	
		<?= $this->render('_menu',['active'=>'orders']) ?>

        <div class="title-with-border"><?=Yii::t('app','Договора')?></div>

        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','Дата заявки') ?></th>
                <th><?=Yii::t('app','ID договора') ?></th>
                <th><?=Yii::t('app','Поставщик') ?></th>
                <th><?=Yii::t('app','Клиент') ?></th>
                <th><?=Yii::t('app','Сумма') ?></th>
                <th><?=Yii::t('app','Статус') ?></th>
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
                        <td><?=$item->supplier->company ?></td>
                        <td><?=$item->client->username  . ' '. $item->client->lastname ?></td>
                        <td><?=number_format($item->credit->price,2,'.', ' ') ?></td>
                        <td><?=$item->status ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Подтвержден'): Yii::t('app','Не подтвержден') ?></td>
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
            <?= LinkPager::widget([
                'pagination' => $pagination,
            ]);  ?>
        </div>

    </div>




<?php
$msg_confirm_order = Yii::t('app','Подтвердить договор?');
$msg_server_error =  Yii::t('app','Ошибка сервера');

$script = " 
$('document').ready(function(){
   $('.order-confirm').click(function(){
        
        if(!confirm('{$msg_confirm_order}')) return false;
        order_id = $(this).data('id');
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/kyc/order-confirm',
            data: 'id='+order_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   obj.html(data.info);             
                }                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
   });
	 
});";
$this->registerJs($script, yii\web\View::POS_END);