<?php
use common\models\Mobile_Detect;
$detect = new Mobile_Detect;
?>
<style>
.stepper .line {
    width: 2px;
    background-color: lightgrey !important;
}

.stepper .lead {
    font-size: 1.1rem;
  }
</style>

<div class="container">
<h1 align="center"><?= Yii::t('app', 'РАССРОЧКА ВСЕГДА РЯДОМ') ?></h1>
<div class="stepper d-flex flex-column mt-5">
    <div class="d-flex mb-1">
      <div class="d-flex flex-column pr-4 align-items-center">
        <div class="rounded-circle py-2 px-3 bg-success text-white mb-1">1</div>
        <div class="line h-100"></div>
      </div>
      <div>
        <h5 class="text-dark"><?= Yii::t('app', 'Выберите понравившейся товар ') ?></h5>
        <p class="lead text-muted pb-3"><?= Yii::t('app', 'Обратитесь в партнерский магазин ZMARKET и выберите понравившейся товар') ?></p>
      </div>
    </div>
    <div class="d-flex mb-1">
      <div class="d-flex flex-column pr-4 align-items-center">
        <div class="rounded-circle py-2 px-3 bg-success text-white mb-1">2</div>
        <div class="line h-100"></div>
      </div>
      <div>
        <h5 class="text-dark"><?= Yii::t('app', 'Подойдите к кассе партнера') ?></h5>
        <p class="lead text-muted pb-3"><?= Yii::t('app', 'Сообщите кассиру что оплачиваете через ZMARKET') ?></p>
      </div>
    </div>
    <div class="d-flex mb-1">
      <div class="d-flex flex-column pr-4 align-items-center">
        <div class="rounded-circle py-2 px-3 bg-success text-white mb-1">3</div>
        <div class="line h-100 d-none"></div>
      </div>
      <div>
        <h5 class="text-dark"><?= Yii::t('app', 'Выберите период рассрочки') ?></h5>
        <p class="lead text-muted pb-3"><?= Yii::t('app', 'Подтвердите покупку с помощью SMS кода и наслаждайтесь покупкой') ?></p>
      </div>
    </div>
  </div>
  <h2 align="center"><?= Yii::t('app', 'ВОТ КАК РАБОТАЕТ ZMARKET') ?></h2>
  <div class="col-md-12 col-xl-8 hook-feat-2">
	<div class="video-how-to-zm embed-responsive embed-responsive-16by9">
		<video class="embed-responsive-item" controls="" poster="https://zmarket.uz/images/zmarket-screen.jpg">
			<source src="/videorassrochki.mp4" type="video/mp4">
		</video>
	</div>
 </div>
<div class="container btn-how-to-zm">
	<!-- <a href="/vendors" class="btn btn-default">Все партнеры</a> -->
</div><!--container-->
<? if (!$detect->isMobile()) { ?>
<div class="support_zm_how">
		<h2 align="center" style="margin-top: 5px"><?= Yii::t('app', 'ОСТАЛИСЬ ВОПРОСЫ?') ?></h2>
	<div>
		<img src="images/footer-girl-zm.png">
	</div>
	<div class="second_block_support_how">
		<p>
		<?= Yii::t('app', 'На Ваш вопрос уже есть ответ в<br /> разделе поддержки ') ?>
		</p>
		<a href="t.me/zmarketsupports" class="btn btn-default"><?= Yii::t('app', 'Поддержка') ?></a>
	</div>
</div><!--container-->
</div>
<? } ?>

<? if ($detect->isMobile()) { ?>
<div class="support_zm_how">
		<h2 align="center"><?= Yii::t('app', 'ОСТАЛИСЬ ВОПРОСЫ?') ?></h2>
	<div class="second_block_support_how">
		<p>
		<?= Yii::t('app', 'На Ваш вопрос уже есть ответ в разделе поддержки ') ?>
		</p>
		<a href="https://t.me/zmarketsupports" class="btn btn-default btn-how-to-zm"><?= Yii::t('app', 'Поддержка') ?></a>
	</div>
			<img src="images/footer-girl-zm.png">
</div><!--container-->
<? } ?>

<style>
@media (min-width: 1024px) {
	.container h1 {
		margin-top:5%;
	}
	
	.stepper {
		width:600px;
		margin-left:30%;
	} 

	.support_zm_how {
		background:#F6F6F6; 
		padding:20px;
		height:400px;
	}

	.support_zm_how div {
		float:left;
	}

	.second_block_support_how {
		margin:10% 0 0 15%;
	}

	.video-how-to-zm {
		margin: 5% 0 0 25%;
	}

	.btn-how-to-zm {
		margin: 5% 0 5% 35%;
	}
}

@media (max-width: 767px) {
	
.container h1 {
	margin-top:15%;
}

	.btn-how-to-zm {
		margin-left:10%;
	}
	
	.second_block_support_how {
		background:#F6F6F6;
		padding:20px;
		margin-top:10%;
	}
	
	.second_block_support_how p {
		font-size:15px;
	}

}
</style>