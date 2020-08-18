<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

// уничтожаем сессию и все связанные с ней данные.
Yii::$app->session->destroy();
?>

	<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '662014777893741');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=662014777893741&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<script>
  fbq('track', 'CompleteRegistration');
</script>

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

        <div class="container">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'register-form',
                    'action' => '/login',
                    'options' => [
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    ]

                ]);

            ?>

            <div class="update__settings-container" style="margin: 60px 0px;">
                <h1 class="update__finish-headline" style="text-align: center;font-size: 30px;">
                    <?=Yii::t('app','Спасибо за регистрацию!')?>
                </h1>
            </div>

            <span class="stage__subline">
        В течении 30 минут ваш аккаунт пройдет верификацию.
Статус верификации можно проверить в личном кабинете.
После можете приступать к покупкам.
    </span>
            <button type="submit" class="btn btn-default m-40 update__settings-btn hook-stage">
                <?=Yii::t('app','Войти в кабинет')?>
            </button>

            <input type="hidden" class="form-control" name="complete">


            <?php ActiveForm::end() ?>
        </div>

