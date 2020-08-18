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
            color: #000;
        }

        .readonly{
            background-color: #53535342 !important;
            color: #9b9b9b !important;
        }
        .nds_margin{
            margin-top: 15px;
        }

    </style>
 	<?= $this->render('_header') ?>

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
        <div class="contract-container container">
            <div class="contract-search">
                <div class="cs-container">
                    <input type="search" class="contract-search__input" id="client_id_phone" placeholder="Поиск клиента">
                     <span id="button-search"><i class="fa fa-search"></i></span>
                </div>
            </div>

            <div class="update__client-container contract__head">

                <h3 class="update__main__headline update__main-client__headline contract__head__headline" id="username">
                    Клиент <i class="fa fa-check"></i>
                </h3>
                <div class="contract__head-container">
                    <span class="update__main__id">
                        ID: <span id="client_id"></span>
                    </span>
                    <span class="update__main__data">
                        Дата верификации: <span id="verify_date"></span>
                    </span>
                </div>
            </div>

            <!-- old: row mb-40 products-block -->
            <div class="offer-cont">
                <div class="row">
                <!-- old: col-4 -->
                    <div class="col">
                        <div class="update__main__item">
                            <!-- old: info-group mb-30px-->
                            <div class="update__main__item-container">
                                <!-- old: title-info -->
                                <h3 class="update__main__item-title">Дата верификации</h3>
                                <!-- old: circle -->
                                <div class="update__main__item-count" id="_verify_date">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="update__main__item">
                            <!-- old: info-group mb-30px-->
                            <div class="update__main__item-container">
                                <!-- old: title-info -->
                                <h3 class="update__main__item-title">Телефон</h3>
                                <!-- old: circle -->
                                <div class="update__main__item-count" id="_phone">
                                    0
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="update__main__item">
                            <!-- old: info-group mb-30px-->
                            <div class="update__main__item-container">
                                <!-- old: title-info -->
                                <h3 class="update__main__item-title">Баланс</h3>
                                <!-- old: circle -->
                                <div class="update__main__item-count" id="balance">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="update__main__item">
                            <!-- old: info-group mb-30px-->
                            <div class="update__main__item-container">
                                <!-- old: title-info -->
                                <h3 class="update__main__item-title">Годовой лимит рассрочки</h3>
                                <!-- old: circle -->
                                <div class="update__main__item-count" id="zmarket_sum">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="update__main__item update__hook-last">
                            <!-- old: info-group mb-30px-->
                            <div class="update__main__item-container">
                                <!-- old: title-info -->
                                <h3 class="update__main__item-title">Просрочка</h3>
                                <!-- old: circle -->
                                <div class="update__main__item-count" id="delay">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="products-block">
                    <div class="offer-inputs__item product-row">
                        <div class="row mb-40px">
                            <div class="col-sm-8">
                                <input class="offer-inputs__item-input hook__offer-input product-item required" data-name="product" type="text" placeholder="Наименование товара" name="product[]">
                            </div>
                            <div class="col-sm-2">
                                <input class="offer-inputs__item-input product-item required" data-name="quantity" type="number" placeholder="Количество" name="quantity[]">
                            </div>
                            <div class="col-sm-2">
                                <input class="offer-inputs__item-input product-item required" data-name="amount" type="number" placeholder="Стоимость" name="amount[]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="offer-inputs__item hook__last-off">
                   <!-- <label for="" class="update__main__data nds_margin ">Ндс плательщик</label> -->
                   <!-- <input type="checkbox" id="nds_change"  name="Credits[nds]" class="nds_margin product-item" value="1" checked > -->
                    <div class="row">
                        <div class="col-sm-3">
                            <select placeholder="Общий срок рассрочки" name="Credits[credit_limit]" id="credit_limit"  class="offer-inputs__item-input hook__l-it">
                                <!--                        <option value="1">1 --><?//=Yii::t('app','мес')?><!--</option>-->
                                <option value="3">3 <?=Yii::t('app','мес')?></option>
                                <option value="6">6 <?=Yii::t('app','мес')?></option>
                                <!--                        <option value="9">9 --><?//=Yii::t('app','мес')?><!--</option>-->
                                <!--                        <option value="12">12 --><?//=Yii::t('app','мес')?><!--</option>-->
                                <!--                        <option value="15">1 год 3 --><?//=Yii::t('app','мес')?><!-- (15 --><?//=Yii::t('app','мес')?><!--)</option>-->
                                <!--                        <option value="18">1 год 6 --><?//=Yii::t('app','мес')?><!-- (18 --><?//=Yii::t('app','мес')?><!--)</option>-->
                                <!--                        <option value="21">1 год 9 --><?//=Yii::t('app','мес')?><!-- (21 --><?//=Yii::t('app','мес')?><!--)</option>-->
                                <!--                        <option value="24">2 года (24 --><?//=Yii::t('app','мес')?><!--)</option>-->
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input class="offer-inputs__item-input hook__l-it " id="deposit_first" name="Credits[deposit_first]" value="0" type="hidden" placeholder="Первоначальный взнос">
                        </div>
                        <div class="col-sm-3">
                            <input class="offer-inputs__item-input hook__l-it" id="deposit_month" name="Credits[deposit_month]" type="text" placeholder="Ежемесячный взнос" value="0">
                        </div>
                        <div class="col-sm-3">
                            <input class="offer-inputs__item-input" data-name="price" type="number" placeholder="Стоимость ZMARKET" name="price[]" readonly>
                        </div>
                    </div>

                </div>

                <div class="offer__file-container">

                    <div class="offer__file-cnt">
                        <label class="offer__file-label add-product">
                            +
                        </label>
                        <label for="" class="offer__file-clr">
                            <?=Yii::t('app','Добавить еще товар')?>
                        </label>
                    </div>

                   <div class="btn btn-default mb-40 print-act" data-credit_id="0" style="display: none"><?=Yii::t('app','Печать документов')?></div>

                    <div class="row">
                        <div id="credit-info-block"></div>
                    </div>

                    <div class="row" id="confirm_sms" style="display: none;">
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <label class="offer__file-clr"><?=Yii::t('app','Код подтверждения из смс') ?></label>
                            <input type="text" class="form-control" name="user_sms_code" id="user_sms_code"  placeholder="Введите код смс">
                        </div>
                    </div>
                    <br><br>
                    <input type="hidden" name="Credits[supplier_id]" value="<?=$model->id ?>">
<!--                    <input type="hidden" name="Credits[price]" value="0" id="credit_price">-->
                    <input type="hidden" name="Credits[quantity]" value="0" id="credit_quantity">
                    <input type="hidden" name="Credits[user_id]" id="user_id" value="<?php /*=$user_id ? $user_id : ''*/ ?>">
                    <input type="hidden" name="credit_info" id="credit_info">

                    <div class="btn btn-default calc-price">
                        <?=Yii::t('app','Оформить/Принять')?>
                    </div>

                    <div class="btn btn-default send-order " style="display: none;"><?=Yii::t('app','Отправить заявку') ?></div>

                </div>
            <br><br>

        </div>


    <?php ActiveForm::end() ?>


<div id="product-template" style="display: none">
	

    <div class="offer-inputs__item product-row">
        <input class="offer-inputs__item-input hook__offer-input product-item" data-name="product" type="text" placeholder="Наименование товара" name="product[]">
        <input class="offer-inputs__item-input product-item" data-name="quantity" type="number" placeholder="Количество" name="quantity[]">
        <input class="offer-inputs__item-input product-item" data-name="amount" type="number" placeholder="Стоимость" name="amount[]">
        <input class="offer-inputs__item-input" data-name="price" type="number" placeholder="Стоимость ZMARKET" name="price[]" readonly>
        <span class="offer-inputs__item-input remove-item"><i class="fa fa-remove"></i></span>

    </div>
	

</div>

<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');
$msg_client_not_found = Yii::t('app','Клиент не найден!');
$msg_delete_product = Yii::t('app','Удалить товар?');

$msg_month = Yii::t('app','мес.');
$msg_sum = Yii::t('app','сум');
$msg_credit_sum = Yii::t('app','Сумма договора');
$msg_count = Yii::t('app','Количество товаров');
$msg_credit_limit = Yii::t('app','Срок погашения');
$msg_deposit_first = Yii::t('app','Первоначальный взнос');
$msg_deposit_month = Yii::t('app','Ежемесячный взнос');
$msg_print_act = Yii::t('app','Распечатать АКТ');
$msg_nds = Yii::t('app','НДС');
$msg_sms_error = Yii::t('app','Введен неверный смс код');
$msg_sms = Yii::t('app','Введите смс код');
$msg_client_sum = Yii::t('app','Недостаточно средств на балансе клиента!');
$msg_client_zmarket_sum = Yii::t('app','Сумма превышает Годовой лимит рассрочки!');

$msg_nouser = Yii::t('app','Пользователь не найден!');

$nds = 1;
$nds_value = '0 %';
if(Yii::$app->user->identity->nds_state==1) {
    $nds =  Yii::$app->user->identity->nds ;
    $nds_value = $nds .' %';
    $nds = 1 + ($nds / 100);
}
$discount =  Yii::$app->user->identity->discount ? Yii::$app->user->identity->discount :  0 ;
$nds_state =  Yii::$app->user->identity->nds_state ? Yii::$app->user->identity->nds_state : 0 ;

$print_act_url = '/suppliers/print-act';
$confirm_order_url = '/suppliers/credit-confirm';

$script = " 
$('document').ready(function(){
    var payment = false;
    var info = '';
    var nds = {$nds};
    var nds_state = {$nds_state};
    var discount = {$discount};
    var sum = 0;
    var clear_price = 0;
    var sum_month = 0;
    var credit_limit = 0;
    var code = '...';
    var balance = 0;
    var zmarket_sum = 0;    
    
	 $('.add-product').click(function(){
	    $($('#product-template').html()).appendTo('.products-block');	 
	 })
	 
	 $('.send-order').click(function(){	 
	 
	    code = $('#user_sms_code').val();
	    if(code.length==0){
	        alert('{$msg_sms}');
	        $('#user_sms_code').focus();
	        return false;
	    }
	   
        $.ajax({
            type: 'post',
            url: '/suppliers/check-user-sms',
            data: 'code='+code+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){ 
                  console.log(data.status);
	                // подтверждение кредита поставщиком
                    credit_id = $('.print-act').data('credit_id');	    
                    $.ajax({
                        type: 'post',
                        url: '/suppliers/credit-confirm',
                        data: 'id='+credit_id+'&_csrf=' + yii.getCsrfToken(),
                        dataType: 'json',
                        success: function(data){
						console.log(data);
                            alert(data.info);
                            if(data.status){
                              setTimeout(function() { 
                                window.location.href = '/suppliers/credit-history';
                              }, 2000)            
                            }                
                        },
                        error: function(data){
							console.log(data);
                           alert('{$msg_server_error}')
                        }
                
                     });                
	                	                
                }else{
                    alert('{$msg_sms_error}');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        }); 
	     
	 })
	  
	 $(document).on('input change keyup','.product-item, #deposit_first,#credit_limit', function(){
  	    if(hasFilled(false)) calcPrice();

	 });
	 

	
	 function calcPrice(){
	 
	    deposit_first = $('#deposit_first').val(); // первый взнос
	    credit_limit = $('#credit_limit').val(); // срок кредита
	   
	    count = 0;
	    sum = 0;	    
	     if(credit_limit==3){
            nds_default=25;
         }else{
            nds_default=35;
         }
            nds=0;
	   // if(!$('#nds_change').is(':checked')){
	      // nds=15;	       
	    //}
	     if(nds_state == 0){
	        nds=15;
	     } 
	     
	    clear_price = 0;
    
    
	    $('.products-block .product-row').each(function(){
	    discount_s=0;
	    discount_sum=0;	 
	    discount_s += discount * $(this).find('[data-name=\"amount\"]').val() / 100;	    
	    discount_sum += $(this).find('[data-name=\"amount\"]').val() - discount_s;
	    
	    clear_price += parseInt($(this).find('[data-name=\"amount\"]').val());
	    
	    console.log('-');
	    console.log($(this).find('[data-name=\"amount\"]').val());
	   
	    console.log(clear_price);
	    console.log('----');
	    
	    
	    let item=0;
	        count+= parseInt($(this).find('[data-name=\"quantity\"]').val());
	        //item+=$(this).find('[data-name=\"quantity\"]').val() * $(this).find('[data-name=\"amount\"]').val();
	        item+=$(this).find('[data-name=\"quantity\"]').val() * discount_sum;
	        sum += item;
	        item *= nds!=0 ? (100+nds_default)*(100+nds)/10000 : (100+nds_default)/100;
	        
	        $(this).find('[data-name=\"price\"]').val(item);
	        
	    });
	    
	    
	       
	    // учет НДС - 15% к стоимости
	    sum *= nds!=0 ? (100+nds_default)*(100+nds)/10000 : (100+nds_default)/100;
	    
	   
	    
	    // расчет ежемесячного взноса с вычетом депозита
	    sum_month = parseInt( ( (sum - deposit_first ) / credit_limit) * 100 ) / 100;
	  	       $('#deposit_month').val(sum_month);
	    info = '<div><b>{$msg_credit_sum}</b>: ' + sum + ' {$msg_sum}.</div> <br>';
	    info += '<div><b>{$msg_count}</b>: ' + count + '.</div> <br>';
	    info += '<div><b>{$msg_credit_limit}</b>: ' + credit_limit + ' {$msg_month}.</div> <br>';
	    info += '<div><b>{$msg_deposit_month}</b>: ' + sum_month + ' {$msg_sum}</div>';
	    info += '<div><b>{$msg_nds}</b>: 15%</div>';
	    $('#credit_info').val(info);
        $('#credit_quantity').val(count);
//        $('#credit_price').val(sum);
	    $('#credit-info-block').html(info);
	    	    
	    return info;
	 }
	 
	 $('.calc-price').click(function(){
	    
	    if($('#user_id').val().length==0){
	        alert('{$msg_client_not_found}');
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
	    
	    info = calcPrice();
	    
	    console.log(clear_price);
	     	
	    console.log(balance + ' ' + sum);
	     	
	    //if(balance < sum){
//            alert('{$msg_client_sum}');
//            return false;
//	    }
	    	    
	    if(zmarket_sum - clear_price < 0){
            alert('{$msg_client_zmarket_sum}');
            return false;
	    }
	     	        
	    if($('#_phone').text().length==0){
            alert('{$msg_client_not_found}');
            return false;
	    }
	    
	    $('.calc-price').fadeOut();
        $('.add-product').fadeOut();
	    	    
	    phone = $('#_phone').text();
	    id=$('#user_id').val();
	    $.ajax({
            type: 'post',
            url: '/suppliers/send-user-sms',
            data: 'id='+id+'&phone='+phone+'&info='+info+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){                  
                    $('#confirm_sms').fadeIn();
                    $('.send-order').fadeIn();
                }
                alert(data.info);

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
                    console.log(data);
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
                   $('#verify_date').text(data.client_date_verify);
                   $('#_verify_date').text(data.client_date_verify);
                   $('#username').html(data.username + ' ' + data.lastname + ' ' +data.client_verify );
                   $('#delay').text(data.client_delay);
                   $('#balance').text(data.client_summ);
                   $('#user_id').val(data.user_id);    
                   $('#_phone').text(data.client_phone);                   
                   $('#phone').val(data.client_phone);                   
                   $('#client_id').text(data.user_id);                   
                   $('#zmarket_sum').text(data.zmarket_sum);
                   balance = data.client_summ.replace(/ /g, '');
                   zmarket_sum = data.zmarket_sum.replace(/ /g, '');
                }else{                        
                    alert(data.info);  //('{ $ msg_client_not_found}');
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
			$(this).parent().fadeOut('slow');
			$(this).parent().remove();
			if(hasFilled(false)) calcPrice(); 
		}		
	})
	
	
});";
$this->registerJs($script, yii\web\View::POS_END);
