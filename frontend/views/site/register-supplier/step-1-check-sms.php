<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Подтверждение смс');

?>

    <div class="container" style="text-align: center">
        <a href="/">
            <img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;">
        </a>
    </div>
    <div class="reg-container reg-hook-container black-bg w700 mb-30px">
        <h3 style="color:#86C8A1;"> <?=Yii::t('app','Регистрация поставщика')?></h3>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'check-form',
                'options' => [
                    'class' => 'form-horizontal',
                    //'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <div class="reg-container black-bg w700 mb-30px">


            <div class="form-group mb-30px">
                <label style="color:#86C8A1;"><?=Yii::t('app','Введите код подтверждения из СМС')?></label>
                <input style="width: 30%;
    margin: auto;" type="text" class="form-control" name="sms" required>
            </div>

            <div id="timer" style="display: none; color:red"></div>
            <div style="color: #86C8A1!important;">
                На ваш номер был выслан код подтверждения.<br>
                Если не пришло SMS, нажмите <span id="send_sms" style="color:red;cursor:pointer">переотправить</span>.
            </div>


        </div>

        <button type="submit" class="btn btn-default check-otp"><?=Yii::t('app','Далее')?> <i class="fa fa-play" aria-hidden="true"></i></button>

        <input type="hidden" name="User['phone]" id="phone" value="<?=@Yii::$app->session->get('phone') ?>">

        <?php ActiveForm::end() ?>

    </div>

<?php

$msg_retry = Yii::t('app','Повторить отправку кода вы сможете через');
$msg_success = Yii::t('app','СМС код успешно отправлен на ваш номер');
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_sec = Yii::t('app','сек.');
$msg_phone = Yii::t('app','Введите свой номер телефона!');

$script = "$('document').ready(function(){
	
	var timerId;
	var seconds = 50;
	var timerText = '{$msg_retry} ';


    $('#send_sms').click(function(e){
        e.preventDefault();
        phone = $('#phone').val();
        if(phone.length==0){
            alert('{$msg_phone}');
            $('#phone').focus();
            return false;
        }
        $.ajax({

            type: 'post',
            url: '/send-sms',
            data: 'phone='+phone+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert('{$msg_success}');
                   startTimer(50);                   
                }else{
                   alert(data.info);
                } 
            },
            error: function(data){
               alert('{$msg_server_error}')
            }

         });

    });


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

	
	
	
});";

$this->registerJs($script, yii\web\View::POS_END);




