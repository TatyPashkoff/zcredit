<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

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
    <a href="/"><img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
</div>
<div class="container">
    <div class="reg-container black-bg mb-30px text-center" style="margin: 60px 0">
        <div class="inline-block" style="display: inline-block">
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

            <div class="form-group uzcard-ad">
                <p>Введите смс код для привязки карты</p>
				<p>На <b>балансе</b> Вашего мобильного телефона должно быть не менее <b>100 сум</b></p>

                <input type="text" class="form-control" id="user_sms_code" name="code" required>
            </div>


        </div>

        <button type="submit" class="btn btn-default m-40 btn-reg-cont"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>

        </div>
        <?php ActiveForm::end() ?>

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
		<!-- <div class="reg-container black-bg w700 mb-30px"> -->

            <!-- <div class="form-group uzcard-ad">
			<br>
            <p><b>Мы очень серьезно относимся к информационной безопасности.</b></p>
			<p>Ваши данные хранятся в безопасности, а данные Карты мы не храним у себя.  Все данные передаются в зашифрованном криптографическом виде и не доступны третьим лицам.</p>

            </div> -->


        </div>
	</div>

</div>
