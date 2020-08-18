<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#000;
        }
        #credit-info-block{

            margin:20px;
            padding:20px;

        }
        #credit-info-block {
            color: #fff;
        }

        .readonly{
            background-color: #53535342 !important;
            color: #9b9b9b !important;
        }


    </style>
 	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'add_credit']) ?>
		
        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'credit-form',
                //'action' =>'/',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <div class="title-with-border"><?=Yii::t('app','Регистрация новой сделки')?></div>


        <div class="row mb-40">

            <div class="col-4">
               <label><?=Yii::t('app','Поиск клиента') ?></label>
                <div class="input-group">
                    <input type="number" id="client_id_phone" class="form-control required" aria-describedby="button-search" placeholder="<?=Yii::t('app','Введите номер телефона или ID')?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-search"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="col-4">
               <label><?=Yii::t('app','Имя клиента') ?></label>
                <input type="text" id="username" class="form-control readonly" readonly>
            </div>
            <div class="col-4">
               <label><?=Yii::t('app','Фамилия клиента') ?></label>
                <input type="text" id="lastname" class="form-control readonly" readonly>
            </div>



        </div>


        <div class="row mb-40">
            <div class="col-4">
                <label><?=Yii::t('app','ID клиента') ?></label>
                <input type="text" class="form-control readonly" id="client_id" readonly>
            </div>
            <div class="col-4">
                <label><?=Yii::t('app','Телефон клиента') ?></label>
                <input type="text" class="form-control readonly" id="client_phone" readonly>
            </div>
            <div class="col-4">
                <label><?=Yii::t('app','Баланс клиента') ?></label>
                <input type="text" class="form-control readonly" id="client_summ" readonly>
            </div>
        </div>


        <div class="row mb-40">
            <div class="col-4">
               <label><?=Yii::t('app','Верифицирован') ?></label>
                <input type="text" class="form-control readonly" id="client_verify" readonly>
            </div>
            <div class="col-4">
               <label><?=Yii::t('app','Дата верификации') ?></label>
                <input type="text" class="form-control readonly" id="client_date_verify" readonly>
            </div>
            <div class="col-4">
               <label><?=Yii::t('app','Просрочки платежей') ?></label>
                <input type="text" class="form-control readonly" id="client_delay" readonly>
            </div>
        </div>


        <div class="row mb-40">

            <div class="col-4">
               <label><?=Yii::t('app','Срок кредита') ?></label>
                <select name="Credits[credit_limit]" id="credit_limit" class="form-control">
                    <option value="1">1 <?=Yii::t('app','мес')?></option>
                    <option value="3">3 <?=Yii::t('app','мес')?></option>
                    <option value="6">6 <?=Yii::t('app','мес')?></option>
                    <option value="9">9 <?=Yii::t('app','мес')?></option>
                    <option value="12">12 <?=Yii::t('app','мес')?></option>
                    <option value="15">1 год 3 <?=Yii::t('app','мес')?> (15 <?=Yii::t('app','мес')?>)</option>
                    <option value="18">1 год 6 <?=Yii::t('app','мес')?> (18 <?=Yii::t('app','мес')?>)</option>
                    <option value="21">1 год 9 <?=Yii::t('app','мес')?> (21 <?=Yii::t('app','мес')?>)</option>
                    <option value="24">2 года (24 <?=Yii::t('app','мес')?>)</option>
                </select>
            </div>
            <div class="col-4">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Первоначальный взнос') ?></label>
                    <input type="number" name="Credits[deposit_first]" id="deposit_first" value="<?=$settings->deposit_first ?>" class="form-control required" required>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Ежемесячный взнос') ?></label>
                    <input type="number" name="Credits[deposit_month]" id="deposit_month" value="<?php /*=$settings->deposit_month */ ?>" class="form-control" required>
                </div>
            </div>


        </div>
        <div class="row mb-40 products-block">

            <div class="row product-row">
            <div class="col-3">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Наименование товара') ?></label>
                    <input type="text" name="product[]" class="form-control product-item" data-name="title">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Количество') ?></label>
                    <input type="number" name="quantity[]" class="form-control product-item" data-name="quantity">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Стоимость товара') ?></label>
                    <input type="number" name="amount[]" class="form-control product-item" data-name="amount">
                </div>
            </div>

            <div class="col-3">
                <div class="form-group mb-30px">
                   <label><?=Yii::t('app','Цена для клиента') ?></label>
                    <input type="number" name="price[]" class="form-control product-item" data-name="price">
                </div>
            </div>
            </div>


        </div>

        <div class="-row">
            <div class="btn btn-default mb-40 add-product"><?=Yii::t('app','Добавить еще товар')?></div>
        </div>



        <div class="-row">
            <div class="btn btn-default mb-40 calc-price"><?=Yii::t('app','Оформить/Принять')?></div>
        </div>

        <div class="-row">
            <div class="btn btn-default mb-40 print-act" data-credit_id="0" style="display: none"><?=Yii::t('app','Печать документов')?></div>
        </div>

        <input type="hidden" name="Credits[supplier_id]" value="<?=$model->id ?>">
        <input type="hidden" name="Credits[user_id]" id="user_id" value="<?php /*=$user_id ? $user_id : ''*/ ?>">
        <input type="hidden" name="credit_info" id="credit_info">

        <div class="row">
             <div id="credit-info-block"></div>
        </div>

        <div class="row" id="confirm_sms" style="display: none;">
            <div class="col-4">
            </div>
            <div class="col-4">
                <label><?=Yii::t('app','Код подтверждения из смс клиента') ?></label>
                <input type="text" class="form-control" name="user_sms_code" id="user_sms_code" required>
            </div>


        </div>

        <div class="btn btn-default mb-40 check-code" style="display: none;"><?=Yii::t('app','Проверить') ?></div>

        <div class="btn btn-default mb-40 send-order" style="display: none;"><?=Yii::t('app','Отправить заявку') ?></div>

        <?php ActiveForm::end() ?>
        </div>


<div id="product-template" style="display: none">
	
	<div class="row product-row">

		<div class="col-3 ">
			<div class="form-group mb-30px">
				<label><?=Yii::t('app','Наименование товара') ?></label>
				<input type="text" name="product[]" class="form-control product-item" data-name="title">
			</div>
		</div>
		<div class="col-3">
			<div class="form-group mb-30px">
				<label><?=Yii::t('app','Количество') ?></label>
				<input type="text" name="quantity[]" class="form-control product-item"  data-name="quantity">
			</div>
		</div>
		<div class="col-3">
			<div class="form-group mb-30px">
				<label><?=Yii::t('app','Стоимость товара') ?></label>
				<input type="text" name="amount[]" class="form-control product-item" data-name="amount">
			</div>
		</div>
		<div class="col-3">
			<div class="form-group mb-30px">
				<label><?=Yii::t('app','Цена для клиента') ?></label>
				<input type="text" name="price[]" class="form-control product-item" data-name="price">
			</div>
			<div class="remove-item"><i class="fa fa-remove"></i></div>
		</div>
	
	</div>

</div>

<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');
$msg_client_not_found = Yii::t('app','Клиент не найден!');
$msg_delete_product = Yii::t('app','Удалить товар?');

$msg_month = Yii::t('app','мес.');
$msg_sum = Yii::t('app','сум');
$msg_credit_sum = Yii::t('app','Сумма кредита');
$msg_count = Yii::t('app','Количество товаров');
$msg_credit_limit = Yii::t('app','Срок погашения');
$msg_deposit_first = Yii::t('app','Первоначальный взнос');
$msg_deposit_month = Yii::t('app','Ежемесячный взнос');
$msg_print_act = Yii::t('app','Распечатать АКТ');
$msg_nds = Yii::t('app','НДС');

$msg_card = Yii::t('app','Укажите номер карты');
$msg_exp = Yii::t('app','Укажите срок карты');
$msg_autodiscard_send = Yii::t('app','Клиенту отправлен смс с кодом подтверждения безакцептного списания');

$msg_nouser = Yii::t('app','Пользователь не найден!');

$nds = 1;
$nds_value = '0 %';
if(Yii::$app->user->identity->nds_state==1) {
    $nds =  Yii::$app->user->identity->nds ;
    $nds_value = $nds .' %';
    $nds = 1 + ($nds / 100);
}

$print_act_url = '/suppliers/print-act';
$confirm_order_url = '/suppliers/credit-confirm';

$script = " 
$('document').ready(function(){
    var payment = false;
    var info = '';
    var nds = {$nds};
    var sum = 0;
    var sum_month = 0;
    var credit_limit = 0;
    var code = '...';
    
	 $('.add-product').click(function(){
	    $($('#product-template').html()).appendTo('.products-block');	 
	 })
	 
	 $('.send-order').click(function(){	 
	    // подтверждение кредита поставщиком
	    credit_id = $('.print-act').data('credit_id');	    
	    $.ajax({
            type: 'post',
            url: '/suppliers/credit-confirm',
            data: 'id='+credit_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                  alert(data.info);
                  setTimeout(function() { 
                    window.location.href = '/suppliers/credit-history';
                  }, 2000)

                }                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
	     
	 })
	 
	 $(document).on('input change keyup','.product-item, #deposit_first', function(){
	    if(payment && hasFilled(false)) $('.calc-price').click();
	 })
	 $('select#credit_limit').change(function(){
	    if(payment && hasFilled(false)) $('.calc-price').click();
	 })
	 $('.calc-price').click(function(){
	    
	    if(!confirm('{$msg_autodiscard}')){
	        return false;    
	    }
	    
	    if( $('#deposit_first').val().length == 0 ){
	        alert('{$msg_required_field}');
	        $('#deposit_first').focus();
	        payment = false;
	        return false;
	    }  
	    
	    if(!hasFilled(true)) return false;
	    payment = true;
	    
	    deposit_first = $('#deposit_first').val(); // первый взнос
	    credit_limit = $('#credit_limit').val(); // срок кредита
	    count = 0;
	    sum = 0;
	    $('.products-block .product-row').each(function(){
	        count+= parseInt($(this).find('[data-name=\"quantity\"]').val());
	        sum += $(this).find('[data-name=\"quantity\"]').val() * $(this).find('[data-name=\"price\"]').val();
	    });
	       
	    // учет НДС - 15% к стоимости
	    sum *= nds > 0 ? nds : 1;
	    
	    sum = parseInt(sum*100)/100;
	    
	    // расчет ежемесячного взноса с вычетом депозита
	    sum_month = parseInt( ( (sum - deposit_first ) / credit_limit) * 100 ) / 100;
	     	    	  	    
	    info = '<div><b>{$msg_credit_sum}</b>: ' + sum + ' {$msg_sum}.</div> <br>';
	    info += '<div><b>{$msg_count}</b>: ' + count + '.</div> <br>';
	    info += '<div><b>{$msg_credit_limit}</b>: ' + credit_limit + ' {$msg_month}.</div> <br>';
	    info += '<div><b>{$msg_deposit_first}</b>: ' + deposit_first + ' {$msg_sum}.</div> <br>';
	    info += '<div><b>{$msg_deposit_month}</b>: ' + sum_month + ' {$msg_sum}</div>';
	    info += '<div><b>{$msg_nds}</b>: {$nds_value}</div>';
	    $('#credit_info').val(info);
	    $('#deposit_month').val(sum_month);
	    $('#credit-info-block').html(info);
	    
	    if($('#client_phone').val().length==0){
            alert('{$msg_client_not_found}');
            return false;
	    }
	    
	    $('.calc-price').fadeOut();
        $('.add-product').fadeOut();
	    	    
	    phone = $('#client_phone').val();
	    card = $('#card').val();
	    exp = $('#exp').val();
	    
	    if($('#card').val().length==0){
            alert('{$msg_card}');
            return false;
	    }	
	    if($('#exp').val().length==0){
            alert('{$msg_exp}');
            return false;
	    }
	    
	    
	    $.ajax({
            type: 'post',
            url: '/suppliers/send-user-sms',
            data: 'phone='+phone+'&card='+card+'&exp='+exp+'&info='+info+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){                  
                    $('#confirm_sms').fadeIn();
                    $('.check-code').fadeIn();
                    alert('{$msg_autodiscard_send}');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });        
                
        // создание кредита
        var formData = new FormData($('form#credit-form')[0]);
        formData.append('_csrf', yii.getCsrfToken());
        $.ajax({
            url: '/suppliers/send-order',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'JSON',
            success: function(data) { 
                 if(data.status){
                    $('.print-act').data('credit_id',data.credit_id);
                    $('.print-act').fadeIn();
                 }else{
                    alert(data.error);
                 }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });     
         
	 })
	 function hasFilled(showAlert){
	    var submit = true;
	    $('.required').each(function(){
	        if($(this).val().length==0){	           
	            if(showAlert){
	                $(this).focus();
	                alert('{$msg_required_field}');	               
	            } 
	            submit = false;
	            payment = false;
	            return false;
	        }
	    })  
	    
	    if(!submit) return false;
	    
	    $('.products-block .product-item').each(function(){
	        if($(this).val().length==0){	            
	            if(showAlert){
	                $(this).focus();
	                alert('{$msg_required_field}');	               
	            }   
	            submit = false;
	            payment = false;
	            return false;
	        }
	    })
	    return submit;
	 }
	 $('#button-search').click(function(){    
	    phone = $('#client_id_phone').val();	    
	    if( phone.length==0 ) {
	        $('#client_id_phone').focus();
	        return false;
	    }	    	    
	    $.ajax({
            type: 'post',
            url: '/suppliers/get-user',
            data: 'phone='+phone+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   $('#client_verify').val(data.client_verify);
                   $('#client_date_verify').val(data.client_date_verify);
                   $('#username').val(data.username);
                   $('#lastname').val(data.lastname);
                   $('#client_delay').val(data.client_delay);
                   $('#client_id').val(data.client_id);
                   $('#client_phone').val(data.client_phone);
                   $('#client_summ').val(data.client_summ);
                   $('#user_id').val(data.user_id);                   
                }else{
                    alert('{$msg_client_not_found}');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
         });	    
	 }) 
	 
	 $('.print-act').click(function(){
    	   	    
	    var formData = new FormData();
	    formData.append('_csrf' , yii.getCsrfToken());
	    formData.append('_csrf' , yii.getCsrfToken());
	   
	    var params = 'menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes'
        window.open('{$print_act_url}'+'?credit_id=' + $(this).data('credit_id'), '{$msg_print_act}', params)
	  	  	    
	    return false;	       
	 })
	 
	$(document).on('click','.remove-item',function(){
		if( confirm('{$msg_delete_product}') ){
			$(this).parent().parent().fadeOut('slow');
			$(this).parent().parent().remove();
			if(hasFilled(false)) $('.calc-price').click();
		}		
	})
	
    $('.check-code').click(function(){
        code = $('#user_sms_code').val();
        id = $('#client_id').val();
        $.ajax({
            type: 'post',
            url: '/suppliers/check-user-sms',
            data: 'id='+id+'&code='+code+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            console.log(data.status)
                if(data.status){                  
	                $('.send-order').fadeIn();
	                $('#confirm_sms').fadeOut();
                    $('.check-code').fadeOut();
                }else{
              	    $('.send-order').fadeOut();
              	    $('#confirm_sms').fadeIn();
                    $('.check-code').fadeIn();
              	    alert(data.info);
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });
    });
    $('#card').mask('9999 9999 9999 9999');
    $('#exp').mask('99 99');
    `    
	
});";
$this->registerJs($script, yii\web\View::POS_END);
