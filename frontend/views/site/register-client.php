<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

?>
<style>

label{
	color:#000;
}
.hidden{
	display: none !important;
	opacity: 0;
}
.load-image{
	cursor:pointer;
}


</style>

	



    <div class="logo logo-in-form"><a href="/"><img src="/images/logo-back@2x.png" alt="" class="img-fluid"></a></div>
    <div class="container">

        <div class="row">
            <div class="offset-sm-4 col-sm-4">
                <div class="reg-container black-bg w700 mb-30px">
                    <h2><?=Yii::t('app','Регистрация / Вход')?></h2>


                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'register-form',
                            'options' => [
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                            ]

                        ]);

                    ?>

                    <div class="reg-container black-bg w700 mb-30px">


                        <div class="form-group mb-30px">
                            <input type="text" class="form-control required" name="User[username]" required placeholder="<?=Yii::t('app','Ваше имя')?>">
                        </div>
                        <div class="form-group mb-30px">
                            <input type="text" class="form-control required" name="User[lastname]" required placeholder="<?=Yii::t('app','Ваша фамилия')?>">
                        </div>

                        <div class="form-group mb-30px">
                            <label><?=Yii::t('app','Фото паспорта')?></label>
                            <input type="file" class="form-control hidden image" id="passport_main" name="User[passport_main]">
                            <label for="file" class="file-type load-image" data-img="passport_main"><span><?=Yii::t('app','Загрузить')?> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                        </div>

                        <div class="form-group mb-30px">
                            <label><?=Yii::t('app','Фото прописки')?></label>
                            <input type="file" class="form-control hidden image" id="passport_address" name="User[passport_address]">
                            <label for="file" class="file-type load-image" data-img="passport_address"><span><?=Yii::t('app','Загрузить')?> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                        </div>

                        <div class="form-group mb-30px">
                            <label><?=Yii::t('app','Фото селфи с паспортом')?></label>
                            <input type="file" class="form-control hidden image" id="passport_self" name="User[passport_self]">
                            <label for="file" class="file-type load-image" data-img="passport_self"><span><?=Yii::t('app','Загрузить')?> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                        </div>

                        <?php /* <div class="form-group mb-30px">
                            <label class="control-label"><?=Yii::t('app','Платежная система')?></label>
                            <select name="User[auto_discard_type]" id="auto_discard_type" class="form-control">
                                <option value="1" selected><?=Yii::t('app','Uzcard')?></option>
                                <option value="2"><?=Yii::t('app','Paymo')?></option>
                            </select>
                        </div> */ ?>

                        <div class="form-group mb-30px">
                            <label><?=Yii::t('app','UZCARD - номер карты')?></label>
                            <input type="text" class="form-control required" name="User[uzcard]" id="uzcard" required>
                        </div>
                        <div class="form-group mb-30px">
                            <label><?=Yii::t('app','UZCARD - срок годности карты, ммгг')?></label>
                            <input type="text" class="form-control required" name="User[exp]" id="exp" required>
                        </div>


                        <div class="form-group uzcard-ad">
                            <div class="btn btn-default m-40 send-otp"><?=Yii::t('app','Подключить автосписание Uzcard') ?></div>
                        </div>

                        <div class="form-group uzcard-ad">
                            <label><?=Yii::t('app','Введите смс код подтверждения автосписания')?></label>
                            <input type="text" class="form-control" id="user_sms_code">
                        </div>

                        <div class="form-group uzcard-ad">
                            <div class="btn btn-default m-40 check-otp"><?=Yii::t('app','Проверить смс код') ?></div>
                        </div>

                        <div class="reg-check mb-30px">
                            <label class="check checked"> <?=Yii::t('app','Политика конфиденциальности')?> <span class="checkbox"><input type="checkbox" name="User[policy]"></span></label>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-default btn-reg-cont"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>


                    <?php ActiveForm::end() ?>

                </div>
            </div>
        </div>
    </div>




<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');

$msg_photo_passport = Yii::t('app','Загрузите фото паспорта!');
$msg_photo_address = Yii::t('app','Загрузите фото прописки!');
$msg_photo_self = Yii::t('app','Загрузите фото селфи с паспортом!');

$msg_sms = Yii::t('app','Введите смс код');
$msg_sms_error = Yii::t('app','Введен неверный смс код');
$msg_policy = Yii::t('app','Необходимо подтвердить Политику конфиденциальности');
$msg_autodiscard = Yii::t('app','Необходимо подтвердить автосписание Uzcard');
$msg_card_type = Yii::t('app','Указан неверный номер карты!');


$script = " 
$('document').ready(function(){
    
    var caption = '';
    var pan_type = 0;
    var sms_check=false;
   
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
     
    $('.send-otp').click(function(e){
   
        e.preventDefault();
        //if( !checkFields() ) return false;
       
        /* phone = $('#phone').val();
        if(phone.indexOf('_')>0) {
            alert('{ $ msg_phone_not_fill}');
            return false;
        } */
        if($('#uzcard').val().indexOf('_')>0 || $('#uzcard').val()=='' ) {
            alert('{$msg_required_field}');
            $('#uzcard').focus(); 
            return false;
        }    
        if($('#exp').val().indexOf('_')>0 || $('#exp').val() =='') {
            alert('{$msg_required_field}');
            $('#exp').focus(); 
            return false;
        }
        card = $('#uzcard').val();
        exp = $('#exp').val();
        
        pan_type=0
        if( card.indexOf('8600')===0 )  pan_type=1;
        if( card.indexOf('6262')===0 )  pan_type=2;
        
        if(pan_type==0){
            alert('{$msg_card_type}');
            return false;
        }            
        
        $.ajax({
            type: 'post',
            url: '/site/send-otp',
            data: 'card='+card+'&exp='+exp+'&type='+pan_type+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   // $('#user_sms_code').fadeIn();
                    $('#user_sms_code').focus();
                    $('.uzcard-ad.hidden').removeClass('hidden');
                }
                alert(data.info);

            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });   
    });
    
    $(document).on('keydown input blur','#uzcard', function(){
        card = $('#uzcard').val();
        if( card.indexOf('8600')===0 ){
          pan_type=1;
          $('.uzcard-ad').fadeIn();
          return true;
        } 
        if( card.indexOf('6262')===0 )  {
            pan_type=2;
            $('.uzcard-ad').fadeOut();
            return true;
        }    
        pan_type=0;
        $('.uzcard-ad').fadeOut();

    });
    
     
   $('.check-otp').click(function(){	 
	 
	    code = $('#user_sms_code').val();
	    if(code.length==0){
	        alert('{$msg_sms}');
	        $('#user_sms_code').focus();
	        return false;
	    }
	    //if( !checkFields() ) return false;
	   
        $.ajax({
            type: 'post',
            url: '/site/check-otp',
            data: 'code='+code+'&type='+pan_type+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                sms_check=false;
                if(data.status){ 
                    $('.uzcard-ad').fadeOut();  	                             
	                $('#user_sms_code').fadeIn();
	                $('.btn-reg-cont').fadeIn();
   	                sms_check=true;
   	                
                }else{
                    alert('{$msg_sms_error}');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        }); 
	     
	 })     
     
     /* $('#auto_discard_type').change(function(){
        pan_type = $(this).val();
        if(pan_type==1){
            $('.uzcard-ad').fadeIn();
        }else{
            $('.uzcard-ad').fadeOut();
        }
    }); */ 
	 
	 $('.btn-reg-cont').click(function(){
	 	 
	 	if( !$('.check').hasClass('checked') ){
	 	    alert('{$msg_policy}');
	 	    return false;
	 	}
	 	
	 	if(pan_type==1 && !sms_check){
	 	    alert('{$msg_autodiscard}');
	 	    return false;
	 	}
	 	 
	    var submit = true;
	    $('.required').each(function(){
	        if($(this).val().length==0){
	            $(this).focus();
	            alert('{$msg_required_field}');
	            submit = false;
	            return false;
	        }
	    })  
	    if(!submit) return false;
        if($('#passport_main').val()=='') {
            alert('{$msg_photo_passport}');
            return false;
        }
        if($('#passport_address').val()=='') {
            alert('{$msg_photo_address}');
            return false;
        }
        if($('#passport_self').val()=='') {
            alert('{$msg_photo_self}');
            return false;
        }        
        if(submit) $('form#register-form').submit();
	 })
	 
    $('#exp').mask('99 99');
    $('#uzcard').mask('9999 9999 9999 9999');

});";
$this->registerJs($script, yii\web\View::POS_END);
