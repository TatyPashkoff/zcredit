<?php


\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Бонусы');
?>
<div class="bonus-wrapper">
    <div class="container bonus-container">
        <div class="bonus__horizontal-container">
            <div class="bonus__text-item">
                <h1 class="bonus__text-headline">
                    01
                </h1>
                <p class="bonus__text-desc">
                    Выбирай
                    и Покупай товар
                    у партнеров
                    платформы
                    Z-Market
                </p>
                <span class="bonus__text-subline">
                Выбирайте товары у партнеров компаний Z-Market, ими являются магазины,бутики, маркеты и др. где прикреплен стикер Z-MARKET. Приобретайте данные товары через платформу Z-Market в рассрочку.
            </span>
            </div>
            <img class="bonus-container__img" src="/images/z_bonus.png" title="zCoin бонусы для Вас" width="100%" height="100%">
            <div class="bonus__text-item">
                <h1 class="bonus__text-headline">
                    02
                </h1>
                <p class="bonus__text-desc">
                   Оплачивайте своевременно по рассрочке
                </p>
                <span class="bonus__text-subline">
                После покупки через платформу Z-MARKET, консультант в магазине предоставит Вам документ с графиком платежей в котором указаны даты оплаты по рассрочке. Ориентируясь на эти даты погашайте свою рассрочку вовремя и получайте Z-Coin бонусы.
            </span>


            </div>
        </div>

        <div class="hook__bonus-item">
            <div class="hook-bonus__subcont">
                <h1 class="bonus__text-headline">
                    03
                </h1>
                <p class="bonus__text-desc">
                    Получайте кэшбеком Z-Coin бонусы
                </p>
            </div>
            <span class="bonus__text-subline">
                За каждые 10 000 сум вовремя погашенных Вы получаете 1 Z-Coin Бонус. За данные Z-Coin бонусы Вы можете приобрести бонусные пакеты в виде увеличение суммы рассрочки, срока рассрочки или же получить скидку на последующие покупки, а также участвовать в специальных предложениях нашей компании.  
            </span>

        </div>
    </div>
</div>

<section class="b-list">
    <div class="b-list__container">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 mob_bonus">
                    <div class="b-list__item">
                        <h1 class="b-list__headline">
                            Скидка на всё
                        </h1>
                        <span class="b-list__subline">
                При покупке данного пакета получи скидку на
            </span>
                        <h2 class="b-list__subhead">
                            3%
                        </h2>
                        <a href="/login" class="btn btn-default b-list__btn" >
                            Получить
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 mob_bonus">
                    <div class="b-list__item">
                        <h1 class="b-list__headline">
                            Увеличения Лимита Рассрочки
                        </h1>
                        <span class="b-list__subline">
                При покупке данного пакета увеличьте свой лимит до
            </span>
                        <h2 class="b-list__subhead">
                            6 млн
                        </h2>
                        <a href="/login" class="btn btn-default b-list__btn">
                            Получить
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 mob_bonus">
                    <div class="b-list__item">
                        <h1 class="b-list__headline">
                            Увеличение Срока Рассрочки
                        </h1>
                        <span class="b-list__subline">
                При покупке данного пакета увеличьте срок до
            </span>
                        <h2 class="b-list__subhead">
                            9 мес
                        </h2>
                        <a href="/login" class="btn btn-default b-list__btn">
                            Получить
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 visualy hidden">
                    <div class="b-list__item hook__b-list visually-hidden">
                        <h1 class="hook__b-headline b-list__headline">
                            Специальное предложение
                        </h1>
                        <span class="hook__b-headline b-list__subline">
                При покупке данного пакета увеличьте срок до
            </span>
                        <h2 class=" hook__b-headline b-list__subhead">
                            777
                        </h2>
                        <a href="/login" class="btn btn-default b-list__btn">
                            Получить
                        </a>
                    </div>
                </div>
            </div><!--row-->
        </div><!--container-->
    </div>
    <!--<div class="b__subhead">
        <!--<span>
            *Вы уже покупали начальную версию,
бонусы и стоимость увеличины
        </span>
    </div>-->
    <div class="bottom-block">
        <div class="container">
            <div class="h2 white mb-40px">Приступай к покупкам</div>
            <a href="/register-client" class="btn btn-default">Регистрация</a>
        </div><!--container-->
    </div><!--bottom-block-->
</section>