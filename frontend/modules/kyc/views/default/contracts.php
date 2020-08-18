<?php

use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }
    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'contracts']) ?>

        <div class="title-with-border"><?=Yii::t('app','Договора') ?></div>

    <div class="row">
        <div class="col-4">
            <label><?=Yii::t('app','Фильтр просрочки')?> </label>
            <select id="filter_contracts" class="form-control">
                <option value="0" <?=$filter_type==0 ? 'selected':'' ?>><?=Yii::t('app','Все')?></option>
                <option value="1" <?=$filter_type==1 ? 'selected':'' ?>><?=Yii::t('app','от 1 до 7 дн.')?></option>
                <option value="2" <?=$filter_type==2 ? 'selected':'' ?>><?=Yii::t('app','от 8 до 15 дн.')?></option>
                <option value="3" <?=$filter_type==3 ? 'selected':'' ?>><?=Yii::t('app','от 16 до 30 дн.')?></option>
                <option value="4" <?=$filter_type==4 ? 'selected':'' ?>><?=Yii::t('app','от 30 до 45 дн.')?></option>
            </select>
        </div>
        <?php /*
        <div class="col-4">
            <label><?=Yii::t('app','Поиск')?> </label>
            <select id="filter_contracts" class="form-control">
                <option value="0" <?=$search_type==0 ? 'selected':'' ?>><?=Yii::t('app','Все')?></option>
                <option value="1" <?=$search_type==1 ? 'selected':'' ?>><?=Yii::t('app','Клиенты')?></option>
                <option value="2" <?=$search_type==2 ? 'selected':'' ?>><?=Yii::t('app','Договора')?></option>
            </select>
        </div> */ ?>
        <div class="col-4">
            <label><?=Yii::t('app','Поиск') ?> </label>
            <input type="text" id="search_contracts" class="form-control">
        </div>


    </div>

    <div id="table_block">


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
                <th><?=Yii::t('app','Просрочка') ?></th>
                <?php /*<th><?=Yii::t('app','Дата след. оплаты') ?></th>
                <th><?=Yii::t('app','Полис') ?></th> */ ?>
                <th><?=Yii::t('app','Договор') ?></th>
                <th><?//=Yii::t('app','Действия') ?></th>
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
                        <td><?=number_format($item->credit->price,2,'.', ' ') ?></td>
                        <td><?=date('d.m.Y',$item->date_start)?></td>
                        <td><?=date('d.m.Y',$item->date_end)?></td>
                        <td><?=$item->credit->credit_limit ?></td>
                        <td><?php
                             if(isset($item->credit))
                                 echo $item->credit->getPaymentMonth();
                            ?>
                        <td><?=$item->credit->price - $item->credit->credit - $item->credit->deposit_first ?>

                        </td>
                        <td>
                            <?php
                            if(isset($item->credit))
                                echo $item->credit->getPaymentDelay();
                            ?>
                        </td>
                        <?php $item->credit->user_confirm == 1 ? $class_text_complete = "text-success" : $class_text_complete = "text-danger" ?>
                        <?php /*<td><?=$item->credit->getNextPayment() ?></td>

                         <td><?=$item->status_polis ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Получен'): Yii::t('app','Не получен') /*'<div class="btn-default btn-small send-polis" data-id="'.$item->id .'" title="'.Yii::t('app','Отправить договор на страхование').'">' . Yii::t('app','Отправить') .'</div>' * /?></td> */ ?>
                        <td class = "<?=$class_text_complete?>"><?//=$item->status ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Подтвержден'): '<div class="btn-default btn-small confirm-contract" data-id="'.$item->id .'">' . Yii::t('app','Подтвердить') .'</div>' ?>
                            <?=$item->credit->user_confirm == 1 ? 'подтвержден' : 'не подтвержден' ?>
                        </td>
                        <td>
                            <?php if($item->status){ /* ?>
                            <div class="row">
                                <div class="col-4"><a href="/print-act?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Акт') ?></a></div>
                                <div class="col-4"><a href="/print-invoice?id=<?=$item->credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Счет-фактура') ?></a></div>
                                <div class="col-4"><a href="/print-graph?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','План-график') ?></a></div>
                            </div>
                            <?php */ } ?>
                            <a href="/kyc/contract-edit?id=<?=$item->id ?>"><?=Yii::t('app','Детали')?></a>
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

    </div>

<?php
$msg_confirm_order = Yii::t('app','Подтвердить договор?');
$msg_confirmed_order = Yii::t('app','Договор подтвержден!');
$msg_send_polis = Yii::t('app','Отправить договор на страховку?');
$msg_sended_polis = Yii::t('app','Договор отправлен на страховку!');
$msg_server_error =  Yii::t('app','Ошибка сервера');

$script = " 
$('document').ready(function(){
   $('.confirm-contract').click(function(){        
        if(!confirm('{$msg_confirm_order}')) return false;
        id = $(this).data('id');
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/kyc/confirm-contract',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   obj.html(data.info); 
                   alert('{$msg_confirmed_order}')            
                }                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
   });
   /*
   $('.send-polis').click(function(){        
        if(!confirm('{ $ sg_send_polis}')) return false;
        id = $(this).data('id');
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/kyc/send-polis',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   obj.html(data.info); 
                   alert('{ $ msg_sended_polis}')            
                }else{
                    alert(data.info)
                }
            },
            error: function(data){
               alert('{ $ msg_server_error}')
            }
    
         });
   }); */
   
   $(document).on('keydown input','#search_contracts',function(e){
        if($(this).val().length>0 && e.keyCode==13){
            q = $(this).val();
            $.ajax({
            type: 'post',
            url: '/kyc/search-contracts',
            data: 'q='+q+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   $('#table_block').html(data.html);
                } 
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
        
        }
   })
   
    $('#filter_contracts').change(function(){        
        type = $(this).val();
        $.ajax({
            type: 'post',
            url: '/kyc/contracts',
            data: 'type='+type+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   $('#table_block').html(data.html);
                } 
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
   })
	 
});";
$this->registerJs($script, yii\web\View::POS_END);