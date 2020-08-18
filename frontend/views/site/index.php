<style>
	.swiper-wrapper.disabled {
		transform: translate3d(0px, 0, 0) !important;
	}

	.btn-slide {
		float: left;
	}

	.row-buttons {
		margin-top: 5%;
		position: absolute;
		left: -48px;

	}

	.row-buttons a {
		margin-right: 50px;
	}

	.btn-default-slider {
		background: none;
		border: 1px solid #fff;
		font-size: 18px;
		font-weight: bold;
		padding: 13px 20px 13px;
	}


	.btn-default-slider:hover {
		background: #fff;
		color: #000;
		font-weight: bold
	}


	#partners_zm img {
		border: 3px solid #707070;
		border-radius: 10px;
		padding: 5px;
	}

	#header {
		position: fixed;
		width: 100%;
		z-index: 9999;
	}
</style>

<?php

use common\models\Mobile_Detect;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app', 'Сервис Рассрочки - zMarket');
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Покупай сейчас, Оплачивай позже! Платформа по предоставлению отсрочки платежа',
]);
$this->registerMetaTag([
	'name' => 'keywords',
	'content' => 'Рассрочка в Ташкенте, купить в рассрочку телефон, рассрочка телефона, купить в рассрочку, бытовая техника в рассрочку',
]);

$detect = new Mobile_Detect;
?>
<!--favicon-->
<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192" href="images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
<link rel="manifest" href="images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<!--favicon-->
<? if ($detect->isMobile()) { ?>
	<div class="sl-item d-block d-md-none">
		<div class="container tl-main">
			<br>
			<div class="sl-big-text" style="margin-left:30%;font-size: 29px; text-align:left;"><?= Yii::t('app', 'ПРЕИМУЩЕСТВА<br> РАССРОЧКИ') ?></div>
			<a href="/register-client" class="btn btn-default btn-default-slider" style="margin-bottom:15px; background-color:white; color:#009F80"><?= Yii::t('app', 'РЕГИСТРАЦИЯ!') ?></a>

			<div class="row align-items-center">
				<div class="offset-sm-1 col-sm-10">
					<p style="margin-right:80px; color:#fff;font-size:16px;"><?= Yii::t('app', 'ПРОСТАЯ ПОКУПКА') ?></p>
					<p style="margin-right:20px; color:#fff;font-size:16px;"><?= Yii::t('app', 'УДОБНЫЙ СЕРВИС') ?></p>
					<p style="margin-right:-80px; color:#fff;font-size:16px;"><?= Yii::t('app', 'БЫСТРАЯ РЕГИСТРАЦИЯ') ?></p>
				</div>
				<img src="/images/zmrkt.png" alt="" style="margin-left:20%;width:159px;height:30%" class="img-fluid">
			</div>
			<!--row-->
		</div>
		<!--container-->
	</div>
<? } ?>

<div class="sl-item d-none d-md-block">
	<div class="container tl-main">
		<div class="row align-items-center">
			<div class="col-sm-6 how-get-block1">
				<div class="h1"><?= Yii::t('app', 'ПРЕИМУЩЕСТВА РАССРОЧКИ') ?></div>
				<div class="row-buttons">
					<a href="/register-client" class="btn btn-default btn-default-slider btn-slide; margin-bottom:15px; background-color:white; color:#009F80"><?= Yii::t('app', 'РЕГИСТРАЦИЯ!') ?></a>
					<a href="/login" class="btn btn-default btn-default-slider btn-slide"><?= Yii::t('app', 'ВОЙТИ В КАБИНЕТ') ?></a>
				</div>

				<i style="cursor:pointer; position:absolute; top:600px;left:600px;color:#fff;opacity:0.5;" onclick="scrollDown()" class="fa fa-arrow-circle-down fa-4x"></i>



			</div>
			<!--col-sm-6-->
			<div class="col-sm-6" style="margin-top:4%;">
				<img id="get-rass-zm" src="/images/zmrktweb.png" alt="Попробуй рассрочку от ZMARKET">
			</div>
			<!--col-sm-6-->
		</div>
	</div>
	<!--container-->
</div>



<!--Партнеры декстоп-->
<div class="partners-container" style="margin-top:30px;">
	<div class="container container-partners  d-none d-md-block">
		<h2 style="color:#707070; font-weight:bold;"><?= Yii::t('app', 'С РАССРОЧКОЙ ZMARKET БЕРИ ВСЕ И СРАЗУ') ?></h2>
		<div class="row" style="margin-bottom:30px;">
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/11">
					<img src="images/partners/asaxiy.png" width="286px" height="205px" title="Интернет-магазин Asaxiy — низкие цены и широкий ассортимент! | В рассрочку через zMarket">
				</a>

			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/20">
					<img src="images/partners/ema.png" width="286px" height="205px" title="Модная и качественная одежда, обувь и сумки от EMA | В рассрочку через zMarket">
				</a>
			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/37">
					<img src="images/partners/hobbygames.png" width="286px" height="205px" title="Огромный выбор классных настольных игр| В рассрочку через zMarket">
				</a>
			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/49">
					<img src="images/partners/home+.png" width="286px" height="205px" title="Все для дома| В рассрочку через zMarket">
				</a>
			</div>
		</div>
		<!--Партнерский лист с изображениями 1-->
		<div class="row" style="margin-bottom:50px;">
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/41">
					<img src="images/partners/mycom.png" width="286px" height="205px" title="Интернет магазин MYCOM  — низкие цены и широкий ассортимент! | В рассрочку через zMarket">
				</a>
			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/36">
					<img src="images/partners/tehnoshop.png" width="286px" height="205px" title="Интернет магазин Tehnoshop | В рассрочку через zMarket">
				</a>
			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/65">
					<img src="images/partners/pandoraweb.png" width="286px" height="205px" title="Магазин ювелирных изделий PANDORA | В рассрочку через zMarket">
				</a>
			</div>
			<div class="col-sm-3" id="partners_zm">
				<a href="vendors/4">
					<img src="images/partners/terrapro.png" width="286px" height="205px" title="Модная и качественная одежда от TerraPro | В рассрочку через zMarket">
				</a>
			</div>
		</div>
		<div class="container d-none d-md-block">
			<a href="/vendors" class="btn btn-default"><?= Yii::t('app', 'Все партнеры') ?></a>
		</div>
		<!--container-->
	</div>
	<? if ($detect->isMobile()) { ?>
		<!--Партнерский лист с изображениями 1-->
		<div class="h2"><?= Yii::t('app', 'НАШИ ПАРТНЕРЫ') ?></div>
		<div class="container container-partners d-block d-md-none" style="background-color:#F6F6F6;">
			<div class="row">
				<div class="col-sm-6" style="width:50%;margin-top:20px;" id="partners_zm">
					<p style="margin-block-end:3%">
						<a href="vendors/11">
							<img src="images/partners-mob/asaxiy.png" width="100%" height="100%" title="Интернет магазин| В рассрочку через zMarket">
						</a>
					</p>
					<p><?= Yii::t('app', 'Интернет магазин') ?></p>
				</div>
				<div class="col-sm-6" style="width:50%;margin-top:20px;" id="partners_zm">
					<p style="margin-block-end:3%">
						<a href="vendors/65">
							<img src="images/partners-mob/pandoramob.png" width="100%" height="100%" title="Магазин ювелирных изделий PANDORA | В рассрочку через zMarket">
						</a>
					</p>
					<p><?= Yii::t('app', 'Ювелирные изделия') ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6" style="width:50%;" id="partners_zm">
					<p style="margin-block-end:3%">
						<a href="vendors/41">
							<img src="images/partners-mob/mycom.png" width="100%" height="100%" title="Компьютеры и комплектующие | В рассрочку через zMarket">
						</a>
					</p>
					<p><?= Yii::t('app', 'Компьютеры и комплектующие') ?></p>
				</div>
				<div class="col-sm-6" style="width:50%;" id="partners_zm">
					<p style="margin-block-end:3%">
						<a href="vendors/4">
							<img src="images/partners-mob/terrapro.png" width="100%" height="100%" title="Магазин мужской одежды | В рассрочку через zMarket">
						</a>
					</p>
					<p><?= Yii::t('app', 'Магазин мужской одежды') ?></p>
				</div>
			</div>
			<div class="container d-block d-md-none">
				<a href="/vendors" class="btn btn-default" style="margin-bottom:20px;"><?= Yii::t('app', 'Все партнеры') ?></a>
			</div>
			<!--container-->
		</div>
	<? } ?>

</div>
<? if ($detect->isMobile()) { ?>
	<h2 style="font-size:22px; text-align:center;margin-top:30px;"><?= Yii::t('app', 'РАССРОЧКА НА ЛЮБЫЕ ЦЕЛИ') ?></h2>
	<div id="carouselExampleIndicators" class="carousel slide d-block d-md-none" data-ride="carousel" style="background-color:#F6F6F6;">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<div class="d-block-w-100 col-sm4" style="background-color:#fff; border:1px solid #fff;border-radius:10px;margin-right:50px;padding:10px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
					<img class="mx-auto d-block" src="images/done.svg" style="width:80px; height:64px; margin:10px 0 15px 0;" alt="Моментальная регистрация">

					<h3 align="center"><?= Yii::t('app', '01. МОМЕНТАЛЬНАЯ<br /> РЕГИСТРАЦИЯ') ?></h3>
					<p align="center" style="margin-bottom:0;"><?= Yii::t('app', 'Зарегистрируйся на сайте всего за минуту. Для этого нужен только:') ?>
					</p>
					<ul class="list_adv_reg" align="left">
						<li><?= Yii::t('app', 'Мобильный телефон') ?></li>
						<li><?= Yii::t('app', 'Пластиковая карта') ?></li>
						<li><?= Yii::t('app', 'Паспорт') ?></li>
					</ul>
				</div>
			</div>
			<div class="carousel-item">
				<div class="d-block-w-100 col-sm4" style="background-color:#fff; border:1px solid #fff;border-radius:10px;margin-right:50px;padding:10px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
					<img src="images/register.svg" style="width:64px; height:64px; margin-bottom:15px;" class="mx-auto d-block" alt="Получи верификацию">
					<h3 align="center"><?= Yii::t('app', '02. ПОЛУЧИ<br /> ВЕРИФИКАЦИЮ') ?></h3>
					<p align="center"><?= Yii::t('app', 'В течении 15 минут Вы получите смс сообщение о результате верификации случае успешного прохождения верификации на Вашем счету ZMARKET будет открыт лимит на покупку в рассрочку   до 8 000 000 сум') ?>
				</div>
			</div>
			<div class="carousel-item">
				<div class="d-block-w-100 col-sm4" style="background-color:#fff; border:1px solid #fff;border-radius:10px;margin-right:50px;padding:10px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
					<img src="images/election.svg" class="mx-auto d-block" style="width:63px; height:63px; margin-bottom:15px;margin-top:20px" alt="Совершай покупки">
					<h3 align="center"><?= Yii::t('app', '03. СОВЕРШАЙ<br /> ПОКУПКИ') ?></h3>
					<p align="center" style="margin-bottom:0;">
						<?= Yii::t('app', 'Самые популярные<br /> магазины уже<br /> принимают Оплату<br /> через сервис рассрочки<br /> ZMARKET. Нужен только<br /> телефон.') ?> <br /> <br /> <br /><br />
				</div>
			</div>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only"><?= Yii::t('app', 'Назад') ?></span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only"><?= Yii::t('app', 'Вперед') ?></span>
		</a>
		<div class="col-sm" style="background:#fff;">
			<!-- Indicators -->
			<ol class="carousel-indicators" style="padding-top:30px;">
				<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			</ol>
		</div>
	</div>

<? } ?>

<div class="partners-container d-none d-md-block" style="margin-top:30px; background-color:#EEE;">
	<div class="row" style="width:940px; margin:0 auto;">
		<div class="col-sm4" style="width:280px; background-color:#fff; border:1px solid #fff; border-radius:10px; margin-right:50px;padding:20px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
			<img src="images/done.svg" align="center" style="width:80px; height:64px; margin-bottom:15px;" alt="Моментальная регистрация">

			<h3><?= Yii::t('app', '01. МОМЕНТАЛЬНАЯ<br /> РЕГИСТРАЦИЯ') ?></h3>
			<p align="left" style="margin-bottom:0;"><?= Yii::t('app', 'Зарегистрируйся на сайте всего за минуту. Для этого нужен только:') ?>
			</p>
			<ul align="left" class="list_adv_reg_web">
				<li style="text-align: left; margin-top:5%; margin-left:10%"><?= Yii::t('app', 'Мобильный телефон') ?></li>
				<li style="text-align: left; margin-left:10%"><?= Yii::t('app', 'Пластиковая карта') ?></li>
				<li style="text-align: left; margin-left:10%"><?= Yii::t('app', 'Паспорт') ?></li>
			</ul>
		</div>

		<div class="col-sm4" style="width:280px; background-color:#fff; border:1px solid #fff;border-radius:10px;margin-right:50px;padding:10px 20px 10px 20px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
			<img src="images/register.svg" style="width:64px; height:64px; margin-bottom:15px;" alt="Получи верификацию">
			<h3><?= Yii::t('app', '02. ПОЛУЧИ<br /> ВЕРИФИКАЦИЮ') ?></h3>
			<p align="left"><?= Yii::t('app', 'В течении 15 минут Вы получите смс сообщение о результате верификации случае успешного прохождения верификации на Вашем счету ZMARKET будет открыт лимит на покупку в рассрочку   до 8 000 000 сум') ?></p>

		</div>

		<div class="col-sm4" style="width:280px; background-color:#fff; border:1px solid #fff;border-radius:10px;padding:10px 20px 10px 20px;   box-shadow: 0 0 10px 5px rgba(221, 221, 221, 1);">
			<img src="images/election.svg" style="width:63px; height:63px; margin-bottom:15px;" alt="Совершай покупки">
			<h3><?= Yii::t('app', '03. СОВЕРШАЙ<br /> ПОКУПКИ') ?></h3>
			<p align="left">
				<?= Yii::t('app', 'Самые популярные<br /> магазины уже<br /> принимают Оплату<br /> через сервис рассрочки<br /> ZMARKET. Нужен только<br /> телефон.') ?>
			</p>
		</div>
	</div>
</div>
<? if ($detect->isMobile()) { ?>
	<div style="background:#fff;" class="container d-block d-md-none">
		<h3 align="center" style="margin:20px;"><?= Yii::t('app', 'ПОЛУЧИТЬ РАССРОЧКУ') ?></h3>
		<a href="/register-client" class="btn btn-default" style="margin: 0 0 20px 14%;"><?= Yii::t('app', 'РЕГИСТРАЦИЯ') ?></a>
	</div>
	<!--container-->
	<img src="images/footer-girl-zm.png">
<? } ?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script>
	$('document').ready(function() {

		$(function() {
			$('#carouselExampleIndicators').carousel();
		});

		// Enable Carousel Indicators
		$(".carousel-item").click(function() {
			$("#carouselExampleIndicators").carousel(1);
		});
	})

	function scrollDown() {
		window.scrollBy(0, 900); // horizontal and vertical scroll increments
	}
</script>



<style>
	.partners-container li,
	.carousel-item li {
		list-style-type: none;
		/* Убираем маркеры у списка */
	}

	.list_adv_reg_web {
		margin-left: 20px;
	}

	.list_adv_reg {
		margin-left: 60px;
	}

	.list_adv_reg_web li,
	.list_adv_reg li {
		position: relative;
	}

	.list_adv_reg_web li:before,
	.list_adv_reg li:before {
		position: absolute;
		top: -9px;
		left: -20px;
	}

	.partners-container li:before,
	.carousel-item li:before {
		content: ' \25CF';
		font-size: 25px;
		color: #198564;
		margin-right: 8px;
	}


	h3 {
		font-size: 23px;
	}

	@media screen and (max-width: 1280px) {

		.container-partners {
			max-width: 1200px;
		}

	}

	@media (max-width: 1920px) {

		.container {
			max-width: 1300px;
		}

		#get-rass-zm {
			margin-top: 4%;
		}

		.row-buttons {
			margin-top: 5%;
			position: absolute;
			width: 600px;
			left: 100px;

		}

		.how-get-block1 {
			margin-bottom: 35%;
		}

	}

	@media (max-width: 1600px) {

		.container {
			max-width: 1300px;
		}

		.row-buttons {
			margin-top: 5%;
			position: absolute;
			width: 600px;
			left: 210px;

		}

		.sl-item .h1,
		.sl-item h1 {
			margin-left: 40%;
		}

		#get-rass-zm {
			width: 90%;
			height: 90%;
			margin-top: 19%;
		}

		.how-get-block1 {
			margin-bottom: 14%;
		}


	}

	@media (max-width: 767px) {

		.carousel-control-prev,
		.carousel-control-next {
			display: none;
		}

		.list_adv_reg {
			margin-top: 23px;
		}

		.partners-container {
			padding: 0;
		}

		.sl-item:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 136px;
			height: 258px;
			background-image: url('images/bgarrowmob1.png');
			background-position: 0 0;
			background-repeat: no-repeat;
		}

		.sl-item:after {
			content: '';
			position: absolute;
			bottom: 0;
			right: 0;
			width: 136px;
			height: 258px;
			background-image: url('images/bgarrowmob2.png');
			background-position: 0 0;
			background-repeat: no-repeat;
			z-index: 0;
		}

		.carousel-item {
			padding: 20px;
			margin-left: 8%;
		}

		.sl-item:after {
			content: '';
			position: absolute;
			bottom: 0;
			right: 0;
			width: 136px;
			height: 258px;
			background-image: url(../images/bgarrowmob2.png);
			background-position: 0 0;
			background-repeat: no-repeat;
			z-index: 0;
		}

		p {
			font-size: 15px;
		}

		.carousel-indicators {
			margin-top: 25px;
			z-index: 100;
			position: static;

		}

		.carousel-indicators li {
			background: #D2D2D2;
			border: 1px solid #D2D2D2;
			border-radius: 20px;
			width: 30px;
			height: 30px;
		}

		.carousel-indicators .active {
			background: #009F80;
		}
	}
</style>