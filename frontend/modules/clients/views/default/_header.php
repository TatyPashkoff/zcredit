<script src="//code.jivosite.com/widget/3esIAQig8Z" async></script>
<?php
use common\models\Kyc;
$show_settins_menu = true;
$user = Yii::$app->user->identity->id;
if($kys = Kyc::find()->where(['client_id' => $user, 'status' => 1])->one()){
    $show_settins_menu = false;
}
?>
<!-- old: row justify-content-center align-items-center mb-30px -->
<div class="update__header-container">
    <div class="update__header__item">
        <!-- old: logo mb-20px -->
        <div class="update__header__icon-container">
            <a href="/"><img src="/images/header_icon.png" alt="" class="img-fluid update__header__icon"></a>
        </div>
    </div>
	

	
	<!-- TO DO NEW NAV MENU
	  <div class="menu-bottom">
			<div class="menu-row">
			  <div class="icon-wrapper">
				  <a href="/clients">
					<img class="home-img" src="/images/icon/icon-home.png" alt="home">
					<span><?=Yii::t('app','Главная') ?></span>
				  </a>
			  </div>
			  <div class="icon-wrapper">
				  <a href="/vendors">
					<img class="partners-img" src="/images/icon/icon-people.png" alt="people">
					<span><?=Yii::t('app','Партнеры') ?></span>
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
				  <a href="/clients/settings">
					<img class="enter-img" src="/images/icon/gear.png" alt="person">
					<span><?=Yii::t('app','Настройки') ?></span>
				  </a>
			  </div>
			  <div class="icon-wrapper" style="margin-left:10px;">
				<a href="/clients/zpay">
					<img class='z-coin-img' src="/images/icon/icon-zcoin.png" alt="zcoin">
					<span class='z-coin-span'>Zpay</span>
				</a>
			  </div>
			</div>
	</div>
	-->
    <div class="update__header__item">
        <!-- old: text-right-logo mb-40px text-left -->
        <div class="update__header__subtext">

            <ul class="update__header__ul">
                <li class="update__header__item">
                    <a href="/clients" class="update__header__item-link">
                        <?=Yii::t('app','Кабинет') ?>
                    </a>
                    <a href="/clients/contracts" class="update__header__item-link">
                        <?=Yii::t('app','Договора') ?>
                    </a>
										<!--
                    <a href="/clients/cashback" class="update__header__item-link">
                        <?=Yii::t('app','CashBack') ?>
                    </a>
                    <a href="/clients/cards" class="update__header__item-link">
                        <?//=Yii::t('app','Мои карты') ?>
                    </a>

                    <a href="/clients/zpay" class="update__header__item-link">
                        <?=Yii::t('app','zPay') ?>
                    </a>
					-->
                    <?php if($show_settins_menu) : ?>
                        <a href="/clients/settings" class="update__header__item-link">
                            <?=Yii::t('app','Настройки') ?>
                        </a>
                    <?php endif; ?>
                    <a href="/logout" class="update__header__item-link">
                        <?=Yii::t('app','Выйти') ?>
                    </a>
                </li>
            </ul>
            <!-- до появления ul-меню здесь было это, закомментил. -->
            <?php /**Yii::t('app','Z-MARKET - БЫСТРО, ПРОСТО, ПОЛУЧИ')?*/?>
        </div>
    </div>
</div>
