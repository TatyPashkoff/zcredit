<?php
use common\models\Credits;
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }
        .title{
            text-align: center;
            width: 100%;
            color: #fff;
        }

        .table, .table td{
            color:#fff;
        }

    </style>

    <?= $this->render('_header') ?>

    <div class="reg-container black-bg w700 mb-30px">

        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'clients-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]
            ]);

        ?>
            <div class="title-with-border"><?=Yii::t('app','Подтверждение автопогашения кредита')?></div>

            <div class="form-group mb-30px">
                <label><?=Yii::t('app','Введите код из смс')?></label>
                <div id="timer" style="display: none"></div>
                <input type="text" name="code" id="code" value="" class="form-control">
            </div>

            <button type="submit" class="btn btn-transparent send-sms"><?=Yii::t('app','Продолжить')?></button>

        <?php ActiveForm::end() ?>

        </div>

<?php

$msg_retry = Yii::t('app','Повторить отправку кода вы сможете через');
$msg_success = Yii::t('app','Безакептное списание uzcard успешно подтверждено!');
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_sec = Yii::t('app','сек.');
$msg_code = Yii::t('app','Введите код из смс!');

$script = "$('document').ready(function(){
	
	var timerId;
	var seconds = 50;
	var timerText = '{$msg_retry} ';

    $('.send-sms').click(function(e){
        e.preventDefault();
        code = $('#code').val();
         if(code.length==0){
            alert('{$msg_code}');
            $('#code').focus();
            return false;
        }
        
        $.ajax({
            type: 'post',
            url: '/clients/send-code',
            data: 'code='+code+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   //startTimer(50);
                   alert('{$msg_success}');
                   window.location.href = '/clients';
                } else{
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