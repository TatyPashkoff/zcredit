<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app', 'Регистрация клиента');
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Страница регистрации на платформе zMarket. Покупай сейчас, Оплачивай позже! Платформа по предоставлению отсрочки платежа',
]);

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

        .reg-client__input {
            width: 80%;
            padding: 9px 15px 5px;
            /*margin-left: 20%;*/
        }
    </style>
    <div class="container" style="text-align: center">
        <a href="/"><img src="/images/reg-logo.png" alt=""
                         style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
    </div>

    <div class="reg-container reg-client__container">

        <?php $form = ActiveForm::begin(
            [
                'id' => 'register-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>


        <div class="form-group mb-30px">
            <h2 class="reg-client__headline"><?= Yii::t('app', 'Регистрация клиента') ?></h2>
            <span class="fix_numb">+(998)</span>
            <input type="text" class="form-control reg-client__input" name="User[phone]" value placeholder="Ваш номер телефона" required id="phone_register">
        </div>
		<!--
		<div class="form-group mb-30px">
            <input type="text" class="form-control reg-client__input" name="User[promocode]" value="<?php echo $_GET['promocode'];?>" placeholder="Промокод для CashBack">
            <span style="font-size:13px;">(не обязательно)</span>
        </div>
		-->
        <p class="reg-client__text">
              <label for="offer">Ознакомлен с <a href="/publicoffer.pdf"> публичной офертой  </a> &nbsp;&nbsp;&nbsp;
                <br>
                  <label for="offer">и<a href="/publicoffer.pdf"> политикой конфиденциальности</a> &nbsp;&nbsp;&nbsp;
              <input type="checkbox" id="offer" name="offer" value="1" checked></label>
        </p>
        <p class="reg-client__text">
            Если у вас уже имеется аккаунт <a href="/login"><?= Yii::t('app', 'Войдите') ?></a>
        </p>

        <button type="submit" class="btn btn-default check-otp"><?= Yii::t('app', 'Дальше') ?> <i class="fa fa-play"
                                                                                                  aria-hidden="true"></i>
        </button>


        <?php ActiveForm::end() ?>



    </div>

    <div class="partners-container">
    	<div class="row" style="justify-content:center;">
    		<div class="col-md-4">
    			<div class="jumbotron">
    				<p>
            <b>Мы очень серезно относимся к информационной безопасности.</b><br />
            Все данные передаются в зашифрованном криптографическом виде и не доступны третьим лицам. Данные хранятся в безопасности.
    				</p>
    				<p>
              <a id="modal-675517" href="#modal-container-675517" role="button" class="btn btn-primary btn-large" data-toggle="modal">Подробно</a>

    				</p>
    			</div>

    			<div class="modal fade" id="modal-container-675517" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    				<div class="modal-dialog" role="document">
    					<div class="modal-content">
    						<div class="modal-header">
    							<h5 class="modal-title" id="myModalLabel">
    								Политика компании в отношении кибербезопасности
    							</h5>
    							<button type="button" class="close" data-dismiss="modal">
    								<span aria-hidden="true">×</span>
    							</button>
    						</div>
    						<div class="modal-body">
                  <!-- <p>
                    <img alt="" src="https://media.flaticon.com/dist/min/img/flaticon-logo.svg" style="height:103px; width:561px">
                  </p> -->
                  Компания ООО 'Zaamin Market' сотрудничает с ведущими международными и внутренними компаниями в сфере кибер безопасности.
                  <br>
                  <br>
                  <h3>Наши процессинговые сервис партнеры</h3>
                  <br />
                   <img alt="" src="/images/icon/logo_uzcard.png" style="height:90px; width:auto">
                   <img alt="" src="/images/icon/logo_humo.jpg" style="height:90px; width:auto">
                   <br>
                   <br>
                  Все данные между процессинговыми центрами проходят через защищенные каналы связи, а так же в зашифрованном криптографическом виде. Данные пластиковых банковских карт хранятся на серверах процессинговых центров. По полученным данным от процессинговых центров, формируется анализ платежеспособности (скоринг) пользователя сервиса "ZMARKET", а так же формируется услуга рекуррентного (автоматического) платежа. Рекуррентные платежи осуществляются в размере и сроки согласно графика платежей за Товар или услугу до полного погашения оплаты за приобретаемый Товар.     <br>
                <br>
                <h3>Передача данных</h3>
                <br />
                Данные пользователей передаются по зашифрованному соединению SLL. Передача данных с помощью SSL соединения направленно на защиту всех проводимых транзакций и предотвращения нежелательного доступа к информации. SSL соединение - это надежный инструмент, чтобы гарантировать своему ресурсу юридическую и сетевую безопасность при работе с персональными и транзакционными данными.
                <br>
                 <img alt="" src="/images/icon/ssl-image.jpg" style="height:90px; width:auto">
                 <br>
                 <br>
    						</div>
    						<div class="modal-footer">

    							<!-- <button type="button" class="btn btn-primary">
    								Save changes
    							</button> -->
    							<button type="button" class="btn btn-secondary" data-dismiss="modal">
    								Закрыть
    							</button>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    		</div>
    		</div>


<?php
$script = "$('document').ready(function(){
$('#phone').mask('99 999-99-99');
$('#phone_register').mask('99 999-99-99');
});";

$this->registerJs($script, yii\web\View::POS_END);
