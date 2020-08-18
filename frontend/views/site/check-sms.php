<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Подтверждение смс');

?>

<?= $this->render('_header') ?>

    <div class="reg-container black-bg w700 mb-30px">
        <a href="#" class="btn btn-default mb-40px"><i class="fa fa-user" aria-hidden="true"></i> <?=Yii::t('app','Регистрация / Вход')?></a>

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
                <label><?=Yii::t('app','Ваш номер телефона')?></label>
                <input type="text" class="form-control" name="User[phone]" id="phone" required>


            </div>
            <div class="form-group mb-30px">
                <div id="timer" style="display: none"></div>
                <div class="btn btn-transparent send-sms"><?=Yii::t('app','Отправить код в смс')?></div>
            </div>

            <div class="form-group mb-30px">
                <label><?=Yii::t('app','Введите код подтверждения из СМС')?></label>
                <input type="text" class="form-control" name="sms" required>
            </div>


        </div>

        <button type="submit" class="btn btn-transparent btn-reg-cont"><?=Yii::t('app','Продолжить')?> <i class="fa fa-play" aria-hidden="true"></i></button>

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


    $('.send-sms').click(function(e){
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

	
	$('#phone').mask('+(999)-99 999-99-99');
	
	
});";

$this->registerJs($script, yii\web\View::POS_END);





