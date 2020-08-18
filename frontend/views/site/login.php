<?php


use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Mobile_Detect;

\frontend\assets\LoginAsset::register($this);

$this->title = Yii::t('app', 'Авторизация');

$detect = new Mobile_Detect;

?>
    <style>
        #timer {
            color: #009F80;
        }

        .fix_numb {
            font-size: 15px;
            position: absolute;
            padding-top: 18px;
            color: #009f80;
            font-family: Roboto,sans-serif;
            font-weight: 400;
			margin-left:1%;
			left:60px;
			top:190px;
        }
		
		#basic-addon1 {
			margin-right:50px;
		}

        #phone {
			margin: 0 auto 20px auto;
			font-size:15px;
			padding-left:70px;
			width:80% !important;
        }

        #tabs {
            margin: 10px 0;
        }
        .tabs-nav {
            display: table;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .tabs-nav li {
            display: table-cell;
            float: none;
            margin: 0;
            padding: 0;
        }
        .tabs-nav a {
            display: block;
            padding: 10px 20px;
            background: #fbfbfb;
            text-decoration: none;
            text-align: center;
            color: #999;
            font-size: 24px;
        }

        .tabs-nav a.active {
            background: #e2e2e2;
            color: #02614C;
			font-weight:bold;
        }
        .tabs-items {
            background: #fff;
        }
        .tabs-item {
            padding: 15px;
        }
		
		
		    html {
        background-color: #56baed;
    }

    body {
        height: 100vh;
		color:#707070;
    }
	
	.first_container {
		width:360px; 
		margin:0  auto 50px auto;
	}
	
	.second_container {
		width:262px; 
		margin:0  auto 50px auto;
	}

    .overlay:before {
        content: "";
        position: fixed;
        top: -35px;
        left: -35px;
        right: -35px;
        bottom: -35px;
        width: calc(100vw + 70px);
        height: calc(100vh + 70px);
        -webkit-filter: blur(15px);
        filter: blur(15px);
        background-size: cover;
        background-position: 50%;
        background-repeat: repeat-y;
        background-image: url(/images/bg-login-zauto.jpg);
		
    }

    .overlay:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,.4);
    }

    a {
        color: #92badd;
        display:inline-block;
        text-decoration: none;
        font-weight: 400;
    }

    h2 {
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        display:inline-block;
        margin: 40px 8px 10px 8px;
    }



    /* STRUCTURE */

    .wrapper {
        display: flex;
        flex-direction: row;
        justify-content: center;
        width: 100%;
        min-height: 100%;
        padding: 20px;
    }

    #formContent {
        -webkit-border-radius: 10px 10px 10px 10px;
        border-radius: 10px 10px 10px 10px;
        background: #fff;
        padding: 30px;
        width: 90%;
        max-width: 450px;
        position: relative;
        padding: 0px;
        -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
        box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
        text-align: center;
        padding-top:30px;
		margin-right:5%;
    }

    #formContent img {
        margin-bottom:20px;
    }

    #formFooter {
        background-color: #f6f6f6;
        border-top: 1px solid #dce8f1;
        padding: 25px;
        text-align: center;
        -webkit-border-radius: 0 0 10px 10px;
        border-radius: 0 0 10px 10px;
    }



    /* TABS */

    h2.inactive {
        color: #cccccc;
    }

    h2.active {
        color: #0d0d0d;
        border-bottom: 2px solid #5fbae9;
    }



    /* FORM TYPOGRAPHY*/

    input[type=button], input[type=submit], input[type=reset], button[type=submit]  {
        background-color: #00997B;
        border: none;
        color: white;
        padding: 15px 80px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        font-size: 13px;
        -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
        box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
        margin: 5px 20px 40px 20px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
        background-color: #00997B;
    }

    input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
        -moz-transform: scale(0.95);
        -webkit-transform: scale(0.95);
        -o-transform: scale(0.95);
        -ms-transform: scale(0.95);
        transform: scale(0.95);
    }

    input[type=text], input[type=password] {
        background-color: #f6f6f6;
        border: none;
        color: #0d0d0d;
        padding: 15px 32px;
        text-decoration: none;
        font-size: 15px;
        width: 80%;
		margin-bottom:20px;
        border: 2px solid #f6f6f6;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #fff;
        border-bottom: 2px solid #00997b;
    }

    input[type=text]:placeholder {
        color: #cccccc;
    }



    /* ANIMATIONS */

    /* Simple CSS3 Fade-in-down Animation */
    .fadeInDown {
        -webkit-animation-name: fadeInDown;
        animation-name: fadeInDown;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    @-webkit-keyframes fadeInDown {
        0% {
            opacity: 0;
            -webkit-transform: translate3d(0, -100%, 0);
            transform: translate3d(0, -100%, 0);
        }
        100% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    @keyframes fadeInDown {
        0% {
            opacity: 0;
            -webkit-transform: translate3d(0, -100%, 0);
            transform: translate3d(0, -100%, 0);
        }
        100% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    /* Simple CSS3 Fade-in Animation */
    @-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    @-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

    .fadeIn {
        opacity:0;
        -webkit-animation:fadeIn ease-in 1;
        -moz-animation:fadeIn ease-in 1;
        animation:fadeIn ease-in 1;

        -webkit-animation-fill-mode:forwards;
        -moz-animation-fill-mode:forwards;
        animation-fill-mode:forwards;

        -webkit-animation-duration:1s;
        -moz-animation-duration:1s;
        animation-duration:1s;
    }

    .fadeIn.first {
        -webkit-animation-delay: 0.4s;
        -moz-animation-delay: 0.4s;
        animation-delay: 0.4s;
    }

    .fadeIn.second {
        -webkit-animation-delay: 0.6s;
        -moz-animation-delay: 0.6s;
        animation-delay: 0.6s;
    }

    .fadeIn.third {
        -webkit-animation-delay: 0.8s;
        -moz-animation-delay: 0.8s;
        animation-delay: 0.8s;
    }

    .fadeIn.fourth {
        -webkit-animation-delay: 1s;
        -moz-animation-delay: 1s;
        animation-delay: 1s;
    }

    /* Simple CSS3 Fade-in Animation */
    .underlineHover:after {
        display: block;
        left: 0;
        bottom: -10px;
        width: 0;
        height: 2px;
        background-color: #00997B;
        content: "";
        transition: width 0.2s;
    }

    .underlineHover:hover {
        color: #0d0d0d;
    }

    .underlineHover:hover:after{
        width: 100%;
    }



    /* OTHERS */

    *:focus {
        outline: none;
    }

    #icon {
        width:60%;
    }
	
	.reg-client__headline {
		width:90%;
		padding-top:20px;
		margin-top:20px;
		display:inherit;
		margin-left:30px;
		color:#707070;
		font-weight:normal;
	}
	
	label {
		width: 95%;
		margin-right: 5%;
		font-size: 18px;
	}
	
	.language-list ul li a {
		color:#707070 !important;
		border:1.5px solid #707070 !important;
	}

	
	#header span {
		color:#707070 !important;
	}
	
	
	@media only screen and (max-width: 720px){
		#phone {
			width:100% !important;
			padding-left:75px !important;
		}
		.fix_numb {
			margin-left:-10%;
			padding-top:0;
			top:38px;
        }
		
		#basic-addon1 {
			margin:0;
		}
		input[type=text], input[type=password] {
			width: 100%;
			margin:0;
		}
		
		input[type=button], button[type=submit] {
			margin:0;
		}
		
		#header {
			position:relative;
			margin-bottom:5px;
		}
		
		h2{
			margin:0;
		}
		.col-sm-6 {
			width:50%;
		}
		
		.language-list {
			margin:0;
		}
		
		#tabs {
			width:100%;
			margin:10px auto;
		}
		
		.tabs-nav {
			display:inline;
			margin:0 auto;
		}
		
		@media(min-height:812px) and (max-height:850px) {
		   #header{
			 margin-top:110px;  
			}
			.user-block {
				margin-right:10px;
			}
		}
	}
		
		
    </style>
	
	<? if ($detect->isMobile()) { ?>
<div class="overlay"></div>
	
	<header id="header" style="color:#000; font-size:18px;; background:#fff; border:1px solid #000;">
		<div class="container">
		  <div class="row align-items-center">
			<div class="col-sm-6">
			  <span style="font-size:15px;font-weight:bold;"><a style="color:#000;" href="tel:+998954790770">+998 95 479 0770</a></span>
			  </div>
		  <div class="col-sm-6">
			<a href="https://telegram.im/@zmarketsupports" class="user-block">
				<img src="/images/headphone.jpg" alt="">
			</a>
			  <div class="language-list">
				<ul class="list-unstyled">
					<li><a href="/lang/ru">РУ</a></li>
					<li><a href="/lang/uz">UZ</a></li>
				</ul>
			  </div>
			  </div>
		  </div>
		</div><!--container-->
	  </header>
	
    <div class="reg-container reg-client__container" style="width:91%; margin:0 auto;">
		<div class="row fadeInDown" style="background:#fff;">
			<div class="container">
				<a href="/"><img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 20px;margin-bottom: 10px;"></a>
			</div>
			<div class="container">
				<h2><?= Yii::t('app', 'ВОЙТИ КАК')    ?></h2>
			</div>

			<!-- табы ---------------  -->
			<div id="tabs">

				<ul class="tabs-nav">
					<li><a href="#tab-1"><?= Yii::t('app', 'Клиент ') ?></a></li>
					<li><a href="#tab-2"><?= Yii::t('app', 'Партнер') ?></a></li>
				</ul>

				<div class="tabs-items">
					<div class="tabs-item" id="tab-1">

						<?php $form = ActiveForm::begin(
							[
								'id' => 'check-form',
								'options' => [
									'class' => 'form-horizontal',
									//'enctype' => 'multipart/form-data',
								]
							]);
						?>
						<div class="reg-container black-bg w700">
							<div class="form-group text-left">
								<span class="fix_numb">+(998)</span>
								<input type="text" class="form-control uniq-phone" name="User[phone]" id="phone" value="" required
									   placeholder="<?= Yii::t('app', 'Ваш номер телефона') ?>">
							</div>
							<div class="form-group">
								<input type="text" placeholder="Введите код из СМС" required class="form-control reg-client__input mob_" name="sms">
							</div>
						</div>
							<input type="button" class="btn btn-default check-otp send-sms" id="send-code" value="ПОЛУЧИТЬ КОД">
							</input>
						<?php ActiveForm::end() ?>
					</div> <!--  tab1  -->

					<div class="tabs-item" id="tab-2">
						<?php $form = ActiveForm::begin(
							[
								'id' => 'check-form',
								'options' => [
									'class' => 'form-horizontal',
									//'enctype' => 'multipart/form-data',
								]
							]);
						?>
						<div class="reg-container black-bg w700">
							<div class="form-group">
								<input type="text" class="form-control" name="login" value="" required
									   placeholder="<?= Yii::t('app', 'Введите Ваш ID') ?>">
							</div>
							<div class="form-group">
								<input id="input" type="password" class="form-control" name="password" value="" required
									   placeholder="<?= Yii::t('app', 'Введите Ваш пароль') ?>">
							</div>
						</div>
						<button type="submit" class="btn btn-default hook__mrg-btn"><?= Yii::t('app', 'ВОЙТИ') ?></button>
						<?php ActiveForm::end() ?>
					</div> <!--   tab2 -->
				</div>
			</div>

				<!-- ---------------------------------------- -->





		</div>
    </div>

	<? } else { ?>
	<div class="overlay"></div>
	
	
    <div class="container" style="text-align: center">
        <a href="/"><img src="/images/reg-logo.png" alt=""
                         style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
    </div>

    <div class="reg-container reg-client__container wrapper fadeInDown">
		<div id="formContent">

			<?php $form = ActiveForm::begin(
				[
					'id' => 'check-form',
					'options' => [
						'class' => 'form-horizontal',
						//'enctype' => 'multipart/form-data',
					]
				]);
			?>
			<div class="form-group mb-30px">
				<div class="container first_container">
					<img src="/images/user-cabinet.svg" style="display:block;margin:0 auto; width:59px;float:left;">
					<h2 class="reg-client__headline"><?= Yii::t('app', 'ПОЛЬЗОВАТЕЛЬ') ?></h2>
				</div>
				<label style="margin-left:-33%;">Ваш номер телефона</label>
				<span class="fix_numb" style="position:absolute !important;width:auto !important;">+(998)</span>
				<input type="text" placeholder="Ваш номер телефона" class="form-control reg-client__input" required name="User[phone]" id="phone">
				<label style="margin-left:-58%;">Код смс</label>
				<input type="text" placeholder="Введите код смс" required class="form-control reg-client__input" name="sms">
			</div>
			
			<input type="button" class="btn btn-default check-otp send-sms" id="send-code" value="ПОЛУЧИТЬ КОД">
			</input>
			
			


			<?php ActiveForm::end() ?>
		</div>
		
				<div id="formContent">
			<?php $form = ActiveForm::begin(
				[
					'id' => 'check-form',
					'options' => [
						'class' => 'form-horizontal',
						//'enctype' => 'multipart/form-data',
					]
				]);
			?>


			<div class="form-group mb-30px">
				<div class="container second_container">
					<img src="/images/online-shopping.png" style="display:block;width:59px;margin:0 auto;float:left;">
					 <h2 class="reg-client__headline"><?= Yii::t('app', 'ПАРТНЕР') ?></h2>
				</div>
					<label style="margin-left:-41%;">Ваш ID партнера</label>
				<input type="text" class="form-control reg-client__input" placeholder="Введите ID партнера" required name="login">
				<span style="float:right; margin-right:50px;" class="input-group-addon auth" id="basic-addon1"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></span>
				<label style="margin-left:-33%;">Ваш пароль</label>
				<input type="password" class="form-control reg-client__input" id="input" placeholder="Введите пароль" required name="password">
			</div>

		
			<button type="submit" class="btn btn-default check-otp"><?= Yii::t('app', 'ВОЙТИ') ?>
			</button>


			<?php ActiveForm::end() ?>
		</div>

    </div>
	
	
	<? } ?>

<?php

$msg_retry = Yii::t('app', 'Повторить отправку кода вы сможете через');
$msg_success = Yii::t('app', 'СМС код успешно отправлен на ваш номер');
$msg_server_error = Yii::t('app', 'Ошибка сервера!');
$msg_sec = Yii::t('app', 'сек.');
$msg_phone = Yii::t('app', 'Введите свой номер телефона!');


$script = "$('document').ready(function(){
	
	var timerId;
	var seconds = 50;
	var timerText = '{$msg_retry} ';
	let newValue = 'ВОЙТИ';

    $('.send-sms').click(function(e){
        e.preventDefault();
        phone = $('#phone').val();
         if(phone.length==0){
            alert('{$msg_phone}');
            $('#phone').focus();
            return false;
        }
        phone='998'+phone;
        phone=phone.split('-').join('').split(' ').join('');
        $.ajax({
            type: 'post',
            url: '/send-sms',
            data: 'phone='+phone+'&_csrf=' + yii.getCsrfToken(),

            dataType: 'json',
            success: function(data){
                if(data.status){
                   startTimer(50);
                   alert('{$msg_success}');
				   $('#send-code').val(newValue);    // здесь задаете новое значение для инпута
				   $('#send-code').clone().attr('type','submit').insertAfter('#send-code').prev().remove();
                } else{
                    alert(data.info);
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }

         });

    });
	

	// Событие при наведении
	$('#basic-addon1').on('mouseover', function(){
		$('#input').attr('type', 'text');
	})

	// Событие при потере фокуса
	$('#basic-addon1').on('mouseout', function(){
		$('#input').attr('type', 'password');
	})


    function startTimer(sec){        
        $('.send-sms').fadeOut();
        $('#timer').css('display','block');
        seconds = sec;
        console.log(sec)
        timerId = setInterval(decTime, 1000);        
    }
    
    function decTime(){
        $('#timer').text( timerText + seconds + ' {$msg_sec}');
        console.log(seconds)
        if(seconds<=0) stopTimer();
        seconds--;
    }
    
    function stopTimer(){
        clearInterval(timerId);
        $('.send-sms').fadeIn();
        $('#timer').css('display','none');

    }
	
	$('#phone').mask('99 999-99-99');
	
	
	$(function() {
	var tab = $('#tabs .tabs-items > div'); 
	tab.hide().filter(':first').show(); 
	$('#tabs .tabs-nav a').click(function(){
		tab.hide(); 
		tab.filter(this.hash).show(); 
		$('#tabs .tabs-nav a').removeClass('active');
		$(this).addClass('active');
		return false;
	}).filter(':first').click();

	$('.tabs-target').click(function(){
		$('#tabs .tabs-nav a[href=' + $(this).data('id')+ ']').click();
	});
});
		
});";

$this->registerJs($script, yii\web\View::POS_END);

