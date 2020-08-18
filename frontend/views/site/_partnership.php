<?php


\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Станьте нашим партнером!');
?>

<div class="top-slider d-none d-md-block">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <div class="sl-item" style="background: #6EBD8F">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="h1"><?=Yii::t('app','Стань партнером zMarket')?></div>
                            <a href="/register-supplier" class="btn btn-default"  style="background: #009f80"><?=Yii::t('app','Регистрация')?></a>
                        </div><!--col-sm-6-->
                        <div class="col-sm-6">
                            <img src="/images/sl1.png" alt="" class="img-fluid">
                        </div><!--col-sm-6-->
                    </div><!--row-->
                </div><!--container-->
            </div>
        </div>
    </div><!--swiper-wrapper-->
</div>

<!--<section class="mobile__head">
    <h1 class="mobile__head-headline">
        Стань нашим партнером
    </h1>
    <span class="mobile__head-subline">
        Укажите свой средний чек и привлеките больше клиентов
    </span>
    <a href="/register-supplier" class="btn btn-default mobile__head-btn">
        Регистрация
    </a>
</section>-->
<div class="partners-container">
    <div class="container">
        <div class="h2 mb-40px"><?=Yii::t('app','Стань партнером')?></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="offset-0 col-12 offset-xl-2 col-xl-8">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="/partner.mp4" allowfullscreen></iframe>
                </div>
        </div>
</div>
    </div>
</div>
<div class="par-features__container">
    <div class="container">
        <h2 class="par-features__headline mb-50px">
        <?=Yii::t('app','Что мы предлагаем?')?>
        </h2>
        <div class="row">
            <div class="col-sm-6">
                <div class="par-features__lists-item">
                    <div class="par-features__lists-item__icon">
                        <img src="/images/par_one.svg" alt="">
                    </div>
                    <div class="par-features__lists-item__head">
                        <h1 class="par-features__item-headline">
                        <?=Yii::t('app','Удобная и бесплатная CRM система')?>
                        </h1>
                        <p class="par-features__item-subline">
                        <?=Yii::t('app','Множество вендоров на данный момент не имеет полнофункциональной CRM (ERP) системы. Подключаясь к системе zMarket они могут бесплатно использовать удобный POS инструмент.')?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="par-features__lists-item">
                    <div class="par-features__lists-item__icon">
                        <img src="/images/par_three.svg" alt="">
                    </div>
                    <div class="par-features__lists-item__head">
                        <h1 class="par-features__item-headline">
                        <?=Yii::t('app','Бесплатный маркетинг')?>
                        </h1>
                        <p class="par-features__item-subline">
                        <?=Yii::t('app','Эффективный и главное бесплатный для вендоров маркетинг и рекламные кампании. Все рекламные кампании будут втч. проводится для увеличения продаж вендоров. ')?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="par-features__lists-item">
                    <div class="par-features__lists-item__icon">
                        <img src="/images/par_two.svg" alt="">
                    </div>
                    <div class="par-features__lists-item__head">
                        <h1 class="par-features__item-headline">
                        <?=Yii::t('app','Увеличение продаж,')?>
                        <?=Yii::t('app','собственная клиентская база')?>
                        </h1>
                        <p class="par-features__item-subline">
                        <?=Yii::t('app','Дополнительный приток клиентов которые в ином случае не могли бы позволить приобрести товар. Формируемая обширная сеть клиентов.')?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="par-features__lists-item">
                    <div class="par-features__lists-item__icon">
                        <img src="/images/par_four.svg" alt="">
                    </div>
                    <div class="par-features__lists-item__head">
                        <h1 class="par-features__item-headline">
                        <?=Yii::t('app','Доступ на региональные рынки')?>
                        </h1>
                        <p class="par-features__item-subline">
                        <?=Yii::t('app','Централизованный и бесплатный доступ (втч. Логистика) на региональные рынки для вендоров.')?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!--container-->
</div>


<div class="partners-container">
    <div class="container">
        <div class="h2 mb-40px"><?=Yii::t('app','Стать партнером')?></div>
        <a href="/register-supplier" class="btn btn-default"><?=Yii::t('app','Регистрация')?></a>
    </div><!--container-->

    <!--Partner-slider-->
    <!--<div class="container">

        <div class="h2"><?=Yii::t('app','Наши партнеры')?></div>


        <!--<div class="offset-sm-2 col-sm-8">
                <p><?=Yii::t('app','Во-первых, для оформления займа не требуется кипа бумаг и явка в офис. Во-вторых, для получения займа нужен только паспорт. В-третьих, рассмотрение заявки на займ занимает всего несколько минут. В банках же все происходит гораздо медленнее, а процент одобрения заявок ниже.')?></p>
            </div>
        </div><!--row-->
        <!--<div class="partners-image">
            <div class="row">
                <?php if($partners = \common\models\Partners::find()->where(['status'=>1])->limit(5)->all()) {
                    foreach ($partners as $partner) { ?>
                        <div class="col">
                            <a href="<?=$partner->site ?>" class="partner-link">
                                <img src="<?=$partner->getImage() ?>" alt="" class="img-fluid"/>
                            </a>
                        </div>
                    <?php }
                } ?>
            </div><!--row-->
        </div><!--partners-image-->

<!--partners-container-->

<!--<div class="bottom-block">
    <div class="container">
        <div class="h2 mb-40px">Стать партнером</div>
        <a href="/register-supplier" class="btn btn-default">Регистрация</a>
    </div><!--container-->
<!--bottom-block-->

<!--
    <div class="forms-page-container update__form-page">

        <div class="container">
            <div class="logo mb-50px"><a href="/"><img src="/images/logo-back@2x.png" alt="" class="img-fluid"></a></div>-->
<!-- <div class="lang-container black-bg">
            <ul class="list-unstyled">
                <li><a href="/lang/uz" class="btn btn-default <?/*=$lang=='uz' ? 'active':'' */?>"><?/*=Yii::t('app','Ozbek tili')*/?></a></li>
                <li><a href="/lang/ru" class="btn btn-default <?/*=$lang=='ru' ? 'active':'' */?>"><?/*=Yii::t('app','Русский язык')*/?></a></li>
                <li><a href="/lang/en" class="btn btn-default <?/*=$lang=='en' ? 'active':'' */?>"><?/*=Yii::t('app','English')*/?></a></li>
            </ul>
        </div>-->
<!--
            <div class="top-main-menu">
                <ul class="list-unstyled">
                    <li><a href="/register-client-phone"><?=Yii::t('app','Регистрация для клиента')?></a></li>
                    <li><a href="/register-supplier"><?=Yii::t('app','Регистрация для поставщика')?></a></li>
                    <li><a href="/login"><?=Yii::t('app','Вход для клиента')?></a></li>
                    <li><a href="/login"><?=Yii::t('app','Вход для поставщика')?></a></li>
                </ul>
            </div>
        </div>
    </div>
    -->

