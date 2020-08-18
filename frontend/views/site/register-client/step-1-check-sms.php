<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Подтверждение смс');

?>
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
        <a href="/">
            <img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;">
        </a>
    </div>

    <div class="reg-container reg-client__container black-bg w700 mb-30px">


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
            <h2 class="reg-client__headline"><?=Yii::t('app','Регистрация клиента')?></h2>
            <input placeholder="Код подтверждения из SMS" type="text" class="form-control reg-client__input" name="sms" required>


        </div>

        <div style="font-weight:400;color: #6EBD8F;margin-bottom: 30px;">На ваш номер был выслан код подтверждения.<br/>
            Если не пришло SMS, нажмите <span id="send_sms" style="text-decoration:underline;color: #86C8A1;cursor: pointer;"><?=Yii::t('app','переотправить') ?></span>.

        </div>


        <button type="submit" class="btn btn-default btn-reg-cont"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>

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
