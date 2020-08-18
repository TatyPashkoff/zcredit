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
            <input type="search" class="contract-search__input" id="client_id_phone" placeholder="Поиск клиента">
            <span id="button-search"><i class="fa fa-search"></i></span>
        </div>

        <div class="update__client-container contract__head">

            <h3 class="update__main__headline update__main-client__headline contract__head__headline" id="username">
                Клиент <i class="fa fa-check"></i>
            </h3>
            <p>
                <img id="passport_self" class="update__preview-img" >
            </p>
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


            <!-- old: col-4 -->
            <div class="update__main__item">
                <!-- old: info-group mb-30px-->
                <div class="update__main__item-container">
                    <!-- old: title-info -->
                    <h3 class="update__main__item-title">Дата верификации</h3>
                    <!-- old: circle -->
                    <div class="update__main__item-count" id="_verify_date">0</div>
                </div>
            </div>
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
            <div class="update__main__item">
                <!-- old: info-group mb-30px-->
                <div class="update__main__item-container">
                    <!-- old: title-info -->
                    <h3 class="update__main__item-title">Лицевой счет</h3>
                    <!-- old: circle -->
                    <div class="update__main__item-count" id="balance">0</div>
                </div>
            </div>
            <div class="update__main__item">
                <!-- old: info-group mb-30px-->
                <div class="update__main__item-container">
                    <!-- old: title-info -->
                    <h3 class="update__main__item-title">Годовой лимит рассрочки</h3>
                    <!-- old: circle -->
                    <div class="update__main__item-count" id="zmarket_sum">0</div>
                </div>
            </div>

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


        <div class="products-block">

            <div class="offer-inputs__item product-row">
                <input class="offer-inputs__item-input hook__offer-input product-item required" data-name="product" type="text" placeholder="Наименование товара" name="product[]">
                <input class="offer-inputs__item-input product-item required" data-name="quantity" type="number" placeholder="Количество" name="quantity[]">
                <input class="offer-inputs__item-input product-item required" data-name="amount" type="number" placeholder="Стоимость" name="amount[]">
                <input class="offer-inputs__item-input" data-name="price" type="number" placeholder="Стоимость ZMARKET" name="price[]" readonly>
            </div>

        </div>

        <div class="offer-inputs__item hook__last-off">
            <!-- <label for="" class="update__main__data nds_margin ">Ндс плательщик</label> -->
            <!-- <input type="checkbox" id="nds_change"  name="Credits[nds]" class="nds_margin product-item" value="1" checked > -->

            <?php if($model->service_type == 0) : ?>
                <input type="hidden" name="Credits[service_type]" value="0">
            <?php endif ?>
            <?php if($model->service_type == 1) : ?>
                <input type="hidden" name="Credits[service_type]" value="1">
            <?php endif ?>
            <?php if($model->service_type == 2) : ?>
            <select placeholder="Товары или Услуги" name="Credits[service_type]" id="service_type"  class="offer-inputs__item-input hook__l-it">
                <option value="">Выберите вид услуг</option>
                <option value="0">Товары </option>
                <option value="1">Услуги </option>
            </select>
            <?php endif ?>
            <select placeholder="Общий срок рассрочки" name="Credits[credit_limit]" id="credit_limit"  class="offer-inputs__item-input hook__l-it">
                <!--                        <option value="1">1 --><?//=Yii::t('app','мес')?><!--</option>-->
                <option value="3">3 <?=Yii::t('app','мес')?></option>
                <option value="6">6 <?=Yii::t('app','мес')?></option>
                <option value="9">9 <?=Yii::t('app','мес')?></option>
                <!--                        <option value="12">12 --><?//=Yii::t('app','мес')?><!--</option>-->
                <!--                        <option value="15">1 год 3 --><?//=Yii::t('app','мес')?><!-- (15 --><?//=Yii::t('app','мес')?><!--)</option>-->
                <!--                        <option value="18">1 год 6 --><?//=Yii::t('app','мес')?><!-- (18 --><?//=Yii::t('app','мес')?><!--)</option>-->
                <!--                        <option value="21">1 год 9 --><?//=Yii::t('app','мес')?><!-- (21 --><?//=Yii::t('app','мес')?><!--)</option>-->
                <!--                        <option value="24">2 года (24 --><?//=Yii::t('app','мес')?><!--)</option>-->
            </select>
            <input class="offer-inputs__item-input hook__l-it " id="deposit_first" name="Credits[deposit_first]" value="0" type="hidden" placeholder="Первоначальный взнос">
            <input class="offer-inputs__item-input hook__l-it" id="deposit_month" name="Credits[deposit_month]" type="text" placeholder="Ежемесячный взнос" value="0">

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
                <div id="stock-info-block"></div>
            </div>
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
            <input type="hidden" name="Credits[stock_id]" value="0" id="stock_id">
            <input type="hidden" name="Credits[stock_sum]" value="0" id="stock_sum">
            <input type="hidden" name="Credits[stock_current_sum]" value="0" id="stock_current_sum">
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
$msg_month = Yii::t('app','mes');
$msg_sum = Yii::t('app','sum. ');
$msg_credit_sum = Yii::t('app','Summa dogovora');
$msg_count = Yii::t('app','Kolichestvo tovarov');
$msg_credit_limit = Yii::t('app','Srok pogasheniya');
$msg_deposit_first = Yii::t('app','Первоначальный взнос');
$msg_deposit_month = Yii::t('app','Ejemesyachnyi vznos');
$msg_print_act = Yii::t('app','Распечатать АКТ');
$msg_nds = Yii::t('app','NDS');
$msg_sms_error = Yii::t('app','Введен неверный смс код');
$msg_sms = Yii::t('app','Введите смс код');
$msg_stock = Yii::t('app','Поздравляем! Вы попали под условия акции ');
$msg_client_sum = Yii::t('app','Недостаточно средств на балансе клиента!');
$msg_client_zmarket_sum = Yii::t('app','Сумма превышает Годовой лимит рассрочки!');
$msg_client_delay = Yii::t('app','У клиента имеется задолженность!');
$msg_nouser = Yii::t('app','Пользователь не найден!');

$nds = 1;
$nds_value = '0 %';
if($model->nds_state==1) {
    $nds =  $model->nds ;
    $nds_value = $nds .' %';
    $nds = 1 + ($nds / 100);
}
$discount =   $model->discount ?  $model->discount :  0 ;
$nds_state =   $model->nds_state ?  $model->nds_state : 0 ;
$margin_three =  $model->margin_three ?  $model->margin_three : 0 ;
$margin_six =  $model->margin_six ? $model->margin_six : 0 ;
$margin_nine =  $model->margin_nine ? $model->margin_nine : 0 ;

// условия для участия в акции
$akcia = 0;

$stock_sum = $stock->sum ? $stock->sum : 0; // сумма акции  - если нет процентов по акции, то на эту сумму считать без процентов
$stock_percent  = $stock->percent ? $stock->percent : 0; // скидка в процентах (если есть сумма, то скидка на эту сумму)
if($stock_id){
// если вендор учавствует в акции
    if (in_array($model->id, $stock_company)) {
        $akcia = 1;
    }
}else{
    $stock_id = 0;
}

$print_act_url = '/suppliers/print-act';
$confirm_order_url = '/suppliers/credit-confirm';

$script = " 
$('document').ready(function(){
    var payment = false;
    var info = '';
    var nds = {$nds};
    var nds_state = {$nds_state};
    var discount = {$discount};
    var margin_three = {$margin_three};
    var margin_nine = {$margin_nine};
    var margin_six = {$margin_six};
    var stock_sum = {$stock_sum};    
    var stock_percent = {$stock_percent};
    var sum = 0;
    var clear_price = 0;
    var sum_month = 0;
    var credit_limit = 0;
    var code = '...';
    var balance = 0;
    var zmarket_sum = 0;
    var delay = 0; 
     var balance_stock = 0;       
    var akcia = {$akcia}; 
    if(akcia){
        var stock_id = {$stock_id}; 
    }  
        
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
                  //console.log(data.status);
	                // подтверждение кредита поставщиком
                    credit_id = $('.print-act').data('credit_id');	    
                    $.ajax({
                        type: 'post',
                        url: '/suppliers/credit-confirm',
                        data: 'id='+credit_id+'&_csrf=' + yii.getCsrfToken(),
                        dataType: 'json',
                        success: function(data){
                            alert(data.info);
                            if(data.status){
                            $('.print-act').fadeIn();
                            $('#confirm_sms').fadeOut();
                            $('.send-order').fadeOut();                                     
                            }                
                        },
                        error: function(data){
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
  	   // if(hasFilled(false)) calcPrice();
        calcPrice();
	 });
	 
	 function itemIsFilled(item){
	     $('.required', item).each(function(){
	        if($(this).val().length==0){
	            return false;
	        }
	     })
	     
	     return true;
	 }
	
	 function calcPrice(){
	 /* $('.required').each(function(){
	        if($(this).val().length==0){	 */
	    deposit_first = $('#deposit_first').val(); // первый взнос
	    credit_limit = $('#credit_limit').val(); // срок кредита
	   
	    count = 0;  //кол-во товаров
	    sum = 0;    //сумма товаров
	   	 
	   	 // Блок расчета НДС и маржм
	   	 /*if(credit_limit == 3){
	   	    nds_default = (margin_three != 0) ? margin_three : 25;
	   	 }else{
	   	    nds_default = (margin_six != 0) ? margin_six : 35; }	*/ 
	   	    
	   	 if(credit_limit == 3){
	   	    nds_default = (margin_three != 0) ? margin_three : 10;
	   	 }
	   	 if(credit_limit == 6){
	   	    nds_default = (margin_six != 0) ? margin_six : 25; }
	   	 if(credit_limit == 9){
	   	    nds_default = (margin_nine != 0) ? margin_nine : 35; }  	 
	   	
	   	//console.log(nds_default);
         nds=0;
	   
	     if(nds_state == 0){
	        nds=15;
	     } 
	     
	     // получаем остаточную сумму кредита по акции, если есть	
	     stock_sum_ = balance_stock ? balance_stock : stock_sum;  
	     //console.log('balance_stock : ' +balance_stock);
	     //console.log('stock_sum_ : ' +stock_sum_);
	      
	     //расчитываем чистую сумму все товаров   
	     let clearTotal = 0; 
	     $('.products-block .product-row').each(function(){
	        let currentAmount = parseInt($('[data-name=\"amount\"]', $(this)).val()),
	            currentQuantity = parseInt($('[data-name=\"quantity\"]', $(this)).val());
	        clearTotal +=  currentAmount*currentQuantity; 
	     })
	    
	    //определяем, действует ли акция
	    let saleActive = false;
	    if(akcia && clearTotal <= stock_sum_)
	        saleActive = true;
	    
	    clear_price = 0; //???
	    c_price = 0;    // cумма если учавствует в акции
	    let s = false;
    
	    $('.products-block .product-row').each(function(){
                        
            // Сумма со скидкой, если не акция
            discount_s=0;
            discount_sum=0;	
            
            if(!saleActive){
                discount_s += discount * $(this).find('[data-name=\"amount\"]').val() / 100;	    
                discount_sum += $(this).find('[data-name=\"amount\"]').val() - discount_s;	    
            }
            
            clear_price += parseInt($(this).find('[data-name=\"amount\"]').val());		//???     
            c_price += parseInt($(this).find('[data-name=\"amount\"]').val()) * parseInt($(this).find('[data-name=\"quantity\"]').val());		     
            let item=0;
            count+= parseInt($(this).find('[data-name=\"quantity\"]').val());  
            if(saleActive) {            
                item = $(this).find('[data-name=\"quantity\"]').val() * $(this).find('[data-name=\"amount\"]').val();
            }else{
                item = $(this).find('[data-name=\"quantity\"]').val() * discount_sum;
                item *= nds!=0 ? (100+nds_default)*(100+nds)/10000 : (100+nds_default)/100;     
            }
            
            sum += item;                           
                                               
            $(this).find('[data-name=\"price\"]').val(item);
	    });	    
	    
	    
	    // если сумма не превышает сумму по кредиту, считать без процентов
	    end_stock = 0;  // если клиент уже учавствовал в акции, то 1
	   
	    
	  /*
	    if(saleActive && sum <= stock_sum_ ){
	    
	        s = false;		      
	     
	    }else{
	        s = true;
	    }	*/
	 
	    
	    // учет НДС - 15% к стоимости
	    /*if(!s) {
	        sum *= nds!=0 ? (100+nds_default)*(100+nds)/10000 : (100+nds_default)/100;
	    }else{
	        sum = clearTotal;	        
	    }*/
	   
	    
	    // расчет ежемесячного взноса с вычетом депозита
	    sum_month = parseInt( ( (sum - deposit_first ) / credit_limit) * 100 ) / 100;	    
	  	$('#deposit_month').val(sum_month);	  	
	  	info_stock = saleActive ? '<div><b>{$msg_stock}</b></div> <br>' :  ' ';
	    info = '<div><b>{$msg_credit_sum}</b>: ' + sum + ' {$msg_sum}.</div> <br>';	   
	    info += '<div><b>{$msg_count}</b>: ' + count + '.</div> <br>';
	    info += '<div><b>{$msg_credit_limit}</b>: ' + credit_limit + ' {$msg_month}.</div> <br>';
	    info += '<div><b>{$msg_deposit_month}</b>: ' + sum_month + ' {$msg_sum}</div>';
	    if(!saleActive){
	        info += '<div><b>{$msg_nds}</b>: 15%</div>';
	    }	    
	    $('#credit_info').val(info);
        $('#credit_quantity').val(count);
        if(saleActive){
            $('#stock_id').val(stock_id);       
            $('#stock_sum').val(sum);       
            $('#stock_current_sum').val(stock_sum_);       
        }else{
            $('#stock_id').val(0);
            $('#stock_sum').val(0);
            $('#stock_current_sum').val(0);
        }        
	    $('#stock-info-block').html(info_stock);
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
	    
	    //console.log(clear_price);
	     	
	    //console.log(balance + ' ' + sum);
	     	
	    //if(balance < sum){
        //  alert('{$msg_client_sum}');
        //   return false;
        //	 }
	    	    
	    if(delay != 'Да'){
            if(zmarket_sum - clear_price < 0){
             console.log(zmarket_sum);   
            console.log(clear_price);
            console.log(balance);
	            console.log(clear_price - zmarket_sum);
	            if(clear_price - zmarket_sum > balance){	       
                    alert('{$msg_client_zmarket_sum}');
                    return false; 
                }  
                let deposit = clear_price - zmarket_sum;  
                console.log(deposit);                      
	        }
	    }else{
            alert('{$msg_client_delay}');
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
	    /*$.ajax({
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
                //console.log(data);               

            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });  */  
                
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
                 //console.log(data.credit_id);
                 // отправка смс /////////
                 credit_id = data.credit_id;
                 $.ajax({
                    type: 'post',
                    url: '/suppliers/send-user-sms',
                    data: 'id='+id+'&credit_id='+credit_id+'&phone='+phone+'&info='+info+'&_csrf=' + yii.getCsrfToken(),
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
                 
                 ///////////////////////
                    $('.print-act').data('credit_id',data.credit_id);
                   // $('.print-act').fadeIn();
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
	    sendAjaxGetUser(); 
	 }) 
	 
	 $('#client_id_phone').keydown(function(e) {
         if(e.keyCode === 13) {             
             sendAjaxGetUser();             
            }
       });
       
       function sendAjaxGetUser(){ 
             phone = $('#client_id_phone').val();
            //console.log(phone);
             $.ajax({
            type: 'post',
            url: '/suppliers/get-user',
            data: 'phone='+phone+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){  
               // console.log(data);    
                   let path = '';
                   if(!data.passport_self){
                   path = '/images/update__pass.png';
                   }else{
                   path = '/uploads/users/' + data.user_id + '/' + data.passport_self;
                   }   
                   $('#verify_date').text(data.client_date_verify);
                   $('#_verify_date').text(data.client_date_verify);
                  $('#passport_self').attr('src', path);
                   $('#username').html(data.username + ' ' + data.lastname + ' ' + data.patronymic + ' ' +data.client_verify );
                   $('#delay').text(data.client_delay);
                   $('#balance').text(data.client_summ);
                   $('#user_id').val(data.user_id);    
                   $('#_phone').text(data.client_phone);                   
                   //$('#_phone').val(data.client_phone);                   
                   $('#client_id').text(data.user_id);                   
                   $('#zmarket_sum').text(data.zmarket_sum);
                   balance = data.client_summ.replace(/ /g, '');                  
                   zmarket_sum = data.zmarket_sum.replace(/ /g, '');                  
                   delay = data.client_delay.replace(/ /g, '');                  
                   end_stock = data.end_stock;
                   akcia = end_stock ? 0 : akcia;   
                   balance_stock = data.balance_stock ? data.balance_stock : stock_sum;                    
                   //console.log(' data.balance_stock ' +data.balance_stock);                  
                   //console.log('stock_sum ' +stock_sum);                   
                   //console.log('balance_stock ' + balance_stock);  
                   //console.log('akcia ' + akcia);  
                   calcPrice();
                   
                }else{                        
                    alert(data.info);  //('{ $ msg_client_not_found}');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
         });
       }
	 
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
