<?php
/*
if(!Yii::$app->user->isGuest){ ?>
    <a href="/logout" class="btn btn-success">Выйти</a>
<?php } */

$lang = Yii::$app->language;

if($lang==''){
    $lang = 'ru';
}
?>

<header id="header">
    <div class="mobile-nav-container">
        <div class="close-menu-button"><i class="fa fa-times" aria-hidden="true"></i></div>
        <div class="container">
            <div class="mnc-container">
                <div class="logo">
                    <a href="/"><img src="/images/logo@2x.png" alt="" class="img-fluid" /></a>
                </div>
                <div class="header-nav">
                    <ul class="list-unstyled">
                        <li><a href="/how-it-works"><?=Yii::t('app','Как работаем?')?></a></li>
                        <!-- <li><a href="/bonus"><?=Yii::t('app','zCoin бонусы')?></a></li> -->
                        <li><a href="/partnership"><?=Yii::t('app','Стать партнером')?></a></li>
                        <li><a href="/faq"><?=Yii::t('app','Частые вопросы')?></a></li>
						<li><a href="https://zmarket.uz/publicoffer.pdf"><?=Yii::t('app','Публичная оферта')?></a></li>
                    </ul>
                </div><!--header-nav-->
                <div class="language-list">
                    <ul class="list-unstyled">
                        <li><a href="/lang/ru">RU</a></li>
                        <li><a href="/lang/uz">UZ</a></li>
                    </ul>
                </div>
            </div><!--mnc-container-->
        </div>
    </div><!---mobile-nav-container-->
    <div class="container">
      <div class="row align-items-center">
        <div class="col-6 col-sm-4 col-xl-2 ">
          <div class="logo">

            <a href="/"><img src="/images/logo@2x.png" alt="" class="img-fluid" /></a>
          </div>
        </div>
        <div class="col-6 col-sm-8 col-xl-8">
          <nav class="header-navbar navbar navbar-expand-lg navbar-dark">

            <button class="navbar-toggler menu-but" type="button" >
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="header-nav">
                <ul class="list-unstyled">
                    <li><a href="/how-it-works"><?=Yii::t('app','Как работаем?')?></a></li>
                    <!-- <li><a href="/bonus"><?=Yii::t('app','zCoin бонусы')?></a></li>-->
                    <li><a href="/partnership"><?=Yii::t('app','Стать партнером')?></a></li>
                    <li><a href="/vendors"><?=Yii::t('app','Наши партнеры')?></a></li>
                    <li><a href="/faq"><?=Yii::t('app','FAQ')?></a></li>
                </ul>
              </div><!--header-nav-->
              <div class="d-block d-lg-none">
                <div class="language-list">
                  <ul class="list-unstyled">
                    <li><a href="/lang/ru">RU</a></li>
                    <li><a href="/lang/uz">UZ</a></li>
                  </ul>
                </div>
                <a href="/login" class="user-block">
                    <img src="/images/head-user.png" alt="">
                </a>
              </div>
            </div>
          </nav>

      </div>
        <div class="col-xl-2 d-none d-xl-block">
          <div class="language-list">
            <ul class="list-unstyled">
                <li><a href="/lang/ru">RU</a></li>
                <li><a href="/lang/uz">UZ</a></li>
            </ul>
          </div>
            <a href="/login" class="user-block">
                <img src="/images/head-user.png" alt="">
            </a>
          </div>
      </div>
    </div><!--container-->
  </header>

<!--
  <div class="menu-bottom">
    <div class="menu-row">
      <div class="icon-wrapper">
		  <a href="/">
			<img class="home-img" src="/images/icon/icon-home.png" alt="home">
			<span>Главная</span>
		  </a>
      </div>
      <div class="icon-wrapper">
		  <a href="/vendors">
			<img class="partners-img" src="/images/icon/icon-people.png" alt="people">
			<span>Партнеры</span>
		  </a>
      </div>
      <div class="circle-chat">
        <div class="circle">
			<a href="https://t.me/zmarketsupports">
				<img class="circle-img" src="/images/icon/icon-chat.png" alt="chat">
			</a>
        </div>
      </div>
      <div class="icon-wrapper">
		  <a href="/login">
			<img class="enter-img" src="/images/icon/icon-person.png" alt="person">
			<span>Вход</span>
		  </a>
      </div>
      <div class="icon-wrapper">
		<a href="/clients/zpay">
			<img class='z-coin-img' src="/images/icon/icon-zcoin.png" alt="zcoin">
			<span class='z-coin-span'>Zpay</span>
		</a>
      </div>
    </div>
  </div>
-->

<nav class="mobile-bottom-nav">
    <div class="mobile-bottom-nav__item mobile-bottom-nav__item--active">
        <div class="mobile-bottom-nav__item-content">
            <a href="/login">
                <i class="fa fa-user"></i>
                Аккаунт
            </a>
        </div>
    </div>
    <!--<div class="mobile-bottom-nav__item">
        <div class="mobile-bottom-nav__item-content">
            <a href="/login">
                <i class="fa fa-id-card"></i>
                Получить
            </a>
        </div>
    </div>-->
    <!--<div class="mobile-bottom-nav__item">
        <div class="mobile-bottom-nav__item-content">
            <a href="/login">
                <i class="fa fa-credit-card"></i>
                Оплатить
            </a>
        </div>
    </div>-->

    <div class="mobile-bottom-nav__item">
        <div class="mobile-bottom-nav__item-content">
            <a href="/vendors">
                <i class="fa fa-shopping-cart"></i>
                Магазины
            </a>
        </div>
    </div>
</nav>



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
