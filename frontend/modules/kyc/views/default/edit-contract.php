<?php

use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);

//echo '<pre>';
//print_r($model_order); exit;

?>
    <style>
        label{
            color:#000;
        }
        .hidden{
            display: none !important;
        }
        textarea.form-control{
            height: auto !important;
        }
    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'contracts']) ?>

        <div class="title-with-border"><?=Yii::t('app','Договора') ?></div>


        <?php $form = ActiveForm::begin(
            [
                'id' => 'register-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>


    <div id="table_block">
        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','Дата на сегодня') ?></th>
                <th><?=Yii::t('app','ID договора') ?></th>
                <th><?=Yii::t('app','ID клиента') ?></th>
                <th><?=Yii::t('app','Клиент') ?></th>
                <th><?=Yii::t('app','Телефон') ?></th>
                <th><?=Yii::t('app','Дата займа') ?></th>
                <th><?=Yii::t('app','Сумма договора') ?></th>
                <th><?=Yii::t('app','Дата начала') ?></th>
                <th><?=Yii::t('app','Дата окончания') ?></th>
                <th><?=Yii::t('app','Период займа') ?></th>
                <th><?=Yii::t('app','Погашено месяцев') ?></th>
                <th><?=Yii::t('app','Сумма погашения') ?></th>
                <th><?=Yii::t('app','Дата следующей оплаты') ?></th>
                <th><?=Yii::t('app','Поставщик') ?></th>
            </tr>
            </thead>
            <tbody>

            <?php if($model_order) {

                $item = $model_order;
                    ?>
                    <tr>
                        <td><?=date('d.m.Y')?></td>
                        <td><?=$item->id ?></td>
                        <td><?=$item->client->id ?></td>
                        <td><a href="/kyc/edit?id=<?=@$item->kyc->id ?>"><?=@$item->client->username  . ' '. @$item->client->lastname ?></a></td>
                        <td><?=$item->client->phone ?></td>
                        <td><?=date('d.m.Y',$item->created_at)?></td>
                        <td><?=number_format($item->credit->price,2,'.', ' ') ?></td>
                        <td><?=date('d.m.Y',$item->date_start)?></td>
                        <td><?=date('d.m.Y',$item->date_end)?></td>
                        <td><?=$item->credit->credit_limit ?></td>
                        <td>
                            <?php
                            if(isset($item->credit))
                                echo $item->credit->getPaymentMonth();
                            ?>
                        </td>
                        <td><?=$item->credit->price - $item->credit->credit - $item->credit->deposit_first ?></td>
                        <td>
                            <?php
                            if(isset($item->credit))
                                echo $item->credit->getNextPayment();
                            ?>
                        </td>
                        <td><?=@$item->supplier->company ?></td>
                        <?php /*<td>
                         if($item->status){ ?>
                            <div class="row">
                                <div class="col-4"><a href="/print-act?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Акт') ?></a></div>
                                <div class="col-4"><a href="/print-invoice?id=<?=$item->credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Счет-фактура') ?></a></div>
                                <div class="col-4"><a href="/print-graph?id=<?=$item->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','План-график') ?></a></div>
                            </div>
                            <?php }
                        </td> */ ?>
                    </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>



    <div class="pad-container">
    <div class="row mb-60px">
    <div class="offset-sm-1 col-sm-10">
        <div class="row">

        <div class="col-sm-6">
            <div class="black-container">
                <div class="row">



                    <div class="col-sm-12">
                        <label><?=Yii::t('app','Скан Акта')?></label>
                        <input type="file" class="form-control hidden image" id="act" name="Contracts[act]">
                        <label for="file" class="file-type load-image" data-img="act"><span><?=Yii::t('app','Загрузить')?> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                        <?php if($model_order->act!=''){ ?>
                            <div><a href="/uploads/contracts/<?=$model_order->id .'/'. $model_order->act ?>" download=""><?=Yii::t('app','Скачать Акт') ?></a></div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12">
                        <label><?=Yii::t('app','Скан Счет фактуры')?></label>
                        <input type="file" class="form-control hidden image" id="invoice" name="Contracts[invoice]">
                        <label for="file" class="file-type load-image" data-img="invoice"><span><?=Yii::t('app','Загрузить')?> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                        <?php if($model_order->invoice!=''){ ?>
                            <div><a href="/uploads/contracts/<?=$model_order->id .'/'. $model_order->invoice ?>" download=""><?=Yii::t('app','Скачать Счет фактуру') ?></a></div>
                        <?php } ?>
                    </div>




                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=Yii::t('app','Статус Договора')?></label>
                            <select name="Contracts[status]" class="form-control">
                                <option value="0" <?=$model_order->status == 0 ? 'selected' : '' ?>><?=Yii::t('app','Не подтвержден')?></option>
                                <option value="1" <?=$model_order->status == 1 ? 'selected' : '' ?>><?=Yii::t('app','Подтвержден')?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=Yii::t('app','Статус Счет фактуры')?></label>
                            <select name="Contracts[status_invoice]" class="form-control">
                                <option value="0" <?=$model_order->status_invoice == 0 ? 'selected' : '' ?>><?=Yii::t('app','Не подтвержден')?></option>
                                <option value="1" <?=$model_order->status_invoice == 1 ? 'selected' : '' ?>><?=Yii::t('app','Подтвержден')?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=Yii::t('app','Комментарии к договору')?></label>

                            <textarea class="form-control" name="Contracts[comments]" rows="5"><?=$model_order->comments ?></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=Yii::t('app','Сообщение смс клиенту')?></label>
                            <textarea class="form-control" id="msg" rows="5"></textarea>

                        </div>
                        <div class="btn btn-default send-sms" data-id="<?=$model_order->credit->user_id ?>"><?=Yii::t('app','Отправить смс сообщение клиенту') ?></div>
                    </div>





                </div><!--row-->
            </div>
        </div><!--col-sm-6-->

            <div class="col-sm-6">
                <div class="black-container">
                    <div class="row">
                        <h4 class="title"><?=Yii::t('app','Транзакции оплат')?></h4>
                        <div class="table-container" style="overflow-y: auto;overflow-x: hidden; max-height: 450px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><?=Yii::t('app','Дата') ?></th>
                                    <th><?=Yii::t('app','№ транзакции') ?></th>
                                    <th><?=Yii::t('app','Платежная система') ?></th>
                                    <th><?=Yii::t('app','Сумма оплаты') ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if(isset($payments)) {


                                    foreach ($payments as $payment) {

                                        ?>

                                        <tr>
                                            <td><?=date('d.m.Y',$payment->created_at ) ?></td>
                                            <td><?=$payment->id ?></td>
                                            <td><?=\common\models\Payment::getPaymentType($payment->payment_type) ?></td>
                                            <td><?=number_format($payment->price,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>

                                        </tr>

                                    <?php } ?>


                                <?php } ?>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
    </div> <!--pad-container -->




    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','Период просрочки') ?></th>
                <th><?=Yii::t('app','Сумма просрочки') ?></th>
                <th><?=Yii::t('app','Остаток по договору') ?></th>
                <th><?=Yii::t('app','Номер страхового полиса') ?></th>
                <th><?=Yii::t('app','Отправить в страховую') ?></th>
                <th><?=Yii::t('app','Передано в суд') ?></th>
                <th><?=Yii::t('app','Статус дела') ?></th>
                <th><?=Yii::t('app','Статус клиента') ?></th>

            </tr>
            </thead>
            <tbody>

            <?php if($model_order) {

                $item = $model_order;
                //foreach ($model_order as $item) {

                if (isset($credit))
                    $polis = $credit->getPolis()->one();

                ?>
                <tr>
                    <td>
                        <?php
                        if(isset($item->credit))
                            echo $item->credit->getPaymentDelay();
                        ?>
                    </td>
                    <td>
                        <?php
                        if(isset($item->credit))
                            echo $item->credit->getPaymentDelaySum();
                        ?>
                    </td>
                    <td>
                        <?php
                        if(isset($item->credit))
                            echo $item->credit->getPaymentSum();
                        ?>
                    </td>
                    <td><?=@$polis->polisSeries . ' ' . @$polis->polisNumber ?></td>

                    <td><?=$model_order->send_insurance ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Отправлен'): '<div class="btn-default btn-small send-insurance" data-id="'.$item->id .'" title="'.Yii::t('app','Отправить договор в страховую').'">' . Yii::t('app','Отправить') .'</div>' ?></td>
                    <td><?=$model_order->send_jud ?  '<i class="fa fa-check"></i> ' . Yii::t('app','Отправлен'): '<div class="btn-default btn-small send-jud" data-id="'.$item->id .'" title="'.Yii::t('app','Договор передан в суд').'">' . Yii::t('app','Отправлен') .'</div>' ?></td>
                    <td>
                        <select name="Contracts[status_jud]" class="form-control">
                            <option value="0" <?=$model_order->status_jud == 0 ? 'selected' : '' ?>><?=Yii::t('app','Нет дела')?></option>
                            <option value="1" <?=$model_order->status_jud == 1 ? 'selected' : '' ?>><?=Yii::t('app','В процессе')?></option>
                            <option value="2" <?=$model_order->status_jud == 2 ? 'selected' : '' ?>><?=Yii::t('app','Ожидание ответа от страховой')?></option>
                            <option value="3" <?=$model_order->status_jud == 3 ? 'selected' : '' ?>><?=Yii::t('app','Возмещено страховой компанией')?></option>
                            <option value="4" <?=$model_order->status_jud == 4 ? 'selected' : '' ?>><?=Yii::t('app','Дело проиграно')?></option>
                        </select>
                    </td>
                    <td><?=$item->status==2 ? Yii::t('app','Блокирован') : Yii::t('app','Активен') ?></td>

                </tr>
                <?php // } ?>

            <?php } ?>
            </tbody>
        </table>



    </div>

    <div class="row">


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

                $supplier = $credit->getSupplier()->one();
                //print_r($supplier); exit;
                $nds_state = @$supplier->nds_state;
                $nds = @$supplier->nds;

                //exit;

                $npp = 0;
                $cnt = 0;
                $sum = 0;
                foreach ($credit->creditItems as $credit_item) {
                    $npp++;
                    $cnt += $credit_item->quantity;
                    $s = $credit_item->price * $credit_item->quantity;
                    $sum += $s;
                    ?>

                    <tr>
                        <td><?=$npp ?></td>
                        <td><?=$credit_item->title ?></td>
                        <td><?=number_format($credit_item->price,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                        <td><?=$credit_item->quantity ?></td>
                        <td><?=number_format($s,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>

                    </tr>

                <?php } ?>
                <tr>
                    <td colspan="3"><?=Yii::t('app','Итого')?>:</td>
                    <td><?=$cnt ?></td>
                    <td><?=number_format($sum,2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                </tr>
                <?php if($nds_state){ ?>
                    <tr>
                        <td colspan="3"><?=Yii::t('app','НДС')?>:</td>
                        <td></td>
                        <td><?=($credit->price/$sum-1)*100 // $nds ?> % (<?=$credit->price - $sum ?> сум)</td>
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

    </div>


        <button type="submit" class="btn btn-default"><?=Yii::t('app','Сохранить')?></button>
        <?php ActiveForm::end() ?>

    </div>


<?php
$msg_confirm_order = Yii::t('app','Подтвердить договор?');
$msg_confirmed_order = Yii::t('app','Договор подтвержден!');
$msg_send_insurance = Yii::t('app','Отправить договор в страховую?');
$msg_sended_insurance = Yii::t('app','Договор отправлен в страховую!');
$msg_server_error =  Yii::t('app','Ошибка сервера');
$msg_send_sms = Yii::t('app','Подтвердите отправку сообщения!');
$msg_text = Yii::t('app','Введите текст сообщения!');

$script = " 
$('document').ready(function(){
   
   var caption = '';

     $(document).on('change','.image',function(){
	  var input = $(this)[0];
	  var obj = $(this);
	  if ( input.files && input.files[0] ) {
		if ( input.files[0].type.match('image.*') ) {
		  var reader = new FileReader();
		  reader.readAsDataURL(input.files[0]);	  		  
		  caption.text(input.files[0].name);	   
		} else console.log('is not image mime type');
	  } else console.log('not isset files data or files API not support');  
	});  
	
	$('.load-image').click(function(e){ 
	    $( '#' + $(this).data('img') ).click(); 
	    caption = $(this);
    });   

    $('.send-sms').click(function(){
	    if($('#msg').val().length==0){
	      alert('{$msg_text}');
	      $('#msg').focus();
	      return false;
	    }  
	    if(!confirm('{$msg_send_sms}')) return false;
	    id = $(this).data('id');
	    msg = $('#msg').val();	  
	    $.ajax({
            type: 'post',
            url: '/kyc/send-sms',
            data: 'id='+id+'&msg='+msg+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert(data.info);
                } 
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	
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
   
   $('.send-insurance').click(function(){        
        if(!confirm('{$msg_send_insurance}')) return false;
        id = $(this).data('id');
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/kyc/send-insurance',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert('{$msg_sended_insurance}')            
                }else{
                    alert(data.info)
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
   }); 
   
    $('#filter_contracts').change(function(){        
        type = $(this).val();
        $.ajax({
            type: 'post',
            url: '/kyc/contracts',
            data: 'type='+type+'&&_csrf=' + yii.getCsrfToken(),
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