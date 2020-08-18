<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

?>
<style>
	.fix_numb {
		font-size: 24px;
		float: left;
		width: 42% !important;
		padding-top: 10px;
		color: #009f80;
		font-family: Roboto, sans-serif;
		font-weight: 400;
	}
</style>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(57533263, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/57533263" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    <div class="container" style="text-align: center">
        <a href="/"><img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
    </div>
    <div class="container">
        <div class="update__settings-container" style="margin: 0px 0px 60px;">

            <?php $form = ActiveForm::begin(
                [
                    'id' => 'register-form',
                    'options' => [
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    ]

                ]);

            ?>




            <div class="flex-parent">
                <div class="input-flex-container">
                    <div class="input active">
                        <span class="hook-fl-mob" data-year="Платежная информация"></span>
                    </div>
                    <div class="input active">
                        <span class="hook-fl-pass" data-year="Паспортные данные"></span>
                    </div>
                    <div class="input">
                        <span data-year="Завершение регистрации"></span>
                    </div>
                </div>
            </div>

            <div class="flex-parent">
                <div class="input-flex-container" style="height:100px;">
                    <div class="form-group col-sm-12 ">
                        <input type="text" class="form-control  required" name="User[username]" required placeholder="* Имя">
                    </div>
                </div>
                <div class="input-flex-container" style="height:100px;">
                    <div class="form-group col-sm-12 ">
                        <input type="text" class="form-control  required" name="User[lastname]" required placeholder="* Фамилия">
                    </div>
                </div>
                <div class="input-flex-container" style="height:100px;">
                    <div class="form-group col-sm-12 ">
                        <input type="text" class="form-control  required" name="User[patronymic]" required placeholder="* Отчество">
                    </div>
                </div>
                <div class="input-flex-container" style="height:100px;">
                    <div class="form-group col-sm-12 ">
                        <input type="text" class="form-control  required" name="User[work_place]"  required placeholder="* Место работы/учебы">
                    </div>
                </div>
                <div class="input-flex-container" style="height:100px;">
                    <div class="form-group col-sm-12 ">
                        <input type="text" class="form-control  required" name="User[permanent_address]"  required placeholder="* Адрес постоянного места жительства">
                    </div>
                </div>
				<div class="input-flex-container" style="height:100px;">
					<div class="form-group col-sm-12 ">
						<input type="text" class="form-control reg-client__input" name="User[phone_home]" value placeholder="* Домашний номер телефона" required id="phone_home">
					</div>
				</div>
            </div><br><br>

            <!--<div class="form-group mb-50px">
                <input type="text" class="form-control hook-st-form required" name="User[username]" required placeholder="ФИО">
            </div>-->
            <div class="update__settings-container-hook">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="update__settings-item">
                                <img id="update__preview1" class="update__preview-img" src="/images/update__pass.png" alt="">
                                <div class="update-container-bottom">
                                    <input type="file" class="form-control hidden image" id="passport_self" onchange="readPassportPhoto1(this)"  name="User[passport_self]">
                                    <label for="file" class="file-type load-image" data-img="passport_self"><span> <i class="fa fa-plus" aria-hidden="true"></i></span></label>
                                    <span class="update__settings-preview__label">
                            Селфи с паспортом
                        </span>
                                </div>
                                <?php //<input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview"> ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="update__settings-item">
                                <img id="update__preview2" class="update__preview-img" src="/images/update__pass2.png" alt="">
                                <div class="update-container-bottom">
                                    <input type="file" class="form-control hidden image" id="passport_main" onchange="readPassportPhoto2(this)" name="User[passport_main]">
                                    <label for="file" class="file-type load-image" data-img="passport_main"><span><i class="fa fa-plus" aria-hidden="true"></i></span></label>
                                    <span class="update__settings-preview__label">
                            Лицевая сторона паспорта
                        </span>
                                </div>
                                <?php //<input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview"> ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="update__settings-item">
                                <img id="update__preview3" class="update__preview-img" src="/images/update__pass3.png" alt="">
                                <div class="update-container-bottom">
                                    <input type="file" class="form-control hidden image" id="passport_address" onchange="readPassportPhoto3(this)" name="User[passport_address]">
                                    <label for="file" class="file-type load-image" data-img="passport_address"><span><i class="fa fa-plus" aria-hidden="true"></i></span></label>
                                    <span class="update__settings-preview__label">
                            Прописка на паспорте
                        </span>
                                </div>
                                <?php //<input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview"> ?>
                            </div>

                        </div>
                </div>




            </div>

            <?php ActiveForm::end() ?>

        </div>

        <span class="stage__subline">
        Что бы пройти верификацию вам нужно
        загрузить все фотографии документов как показано на примере.
    </span>
        <button type="submit" class="btn btn-default m-40 update__settings-btn hook-stage btn-reg-cont">
            Далее
        </button>

    </div>


<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');

$msg_photo_passport = Yii::t('app','Загрузите фото паспорта!');
$msg_photo_address = Yii::t('app','Загрузите фото прописки!');
$msg_photo_self = Yii::t('app','Загрузите фото селфи с паспортом!');

$msg_sms = Yii::t('app','Введите смс код');
$msg_sms_error = Yii::t('app','Введен неверный смс код');
$msg_autodiscard = Yii::t('app','Необходимо подтвердить автосписание Uzcard');


$script = " 
$('document').ready(function(){
    
    var caption = '';
    var pay_type = 1;
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
        
        $.ajax({
            type: 'post',
            url: '/site/send-otp',
            data: 'card='+card+'&exp='+exp+'&type='+pay_type+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   // $('#user_sms_code').fadeIn();
                    $('#user_sms_code').focus();
                    $('.uzcard-ad.hidden').removeClass('hidden');
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });   
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
            data: 'code='+code+'&type='+pay_type+'&_csrf=' + yii.getCsrfToken(),
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
     
     $('#auto_discard_type').change(function(){
        pay_type = $(this).val();
        if(pay_type==1){
            $('.uzcard-ad').fadeIn();
        }else{
            $('.uzcard-ad').fadeOut();
        }
   });  
	 
	 $('.btn-reg-cont').click(function(){
	 	 
	 	
	
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
	 
    //$('#uzcard_month').mask('99');
   // $('#uzcard_year').mask('99');
    $('#exp').mask('99 99');
    $('#uzcard').mask('9999 9999 9999 9999');
    $('#phone_home').mask('99 999-99-99');

});";

$passportPhoto1 = "function readPassportPhoto1(input) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview1').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$passportPhoto2 = "function readPassportPhoto2(input) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview2').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$passportPhoto3 = "function readPassportPhoto3(input) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview3').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$this->registerJs($script, yii\web\View::POS_END);
$this->registerJs($passportPhoto1, yii\web\View::POS_END);
$this->registerJs($passportPhoto2, yii\web\View::POS_END);
$this->registerJs($passportPhoto3, yii\web\View::POS_END);
