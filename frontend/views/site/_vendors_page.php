<?php

use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;
use yii\helpers\Html;
use common\models\Partners;

\frontend\assets\MainAsset::register($this);

$category_filial = (new \yii\db\Query())->select('cat_name')->from('partners_cats')->where('id=:id', [':id' => $partner['cat_id']])->one();

$partner_title = $partner['title'];
$this->title =  Yii::t('app', 'Сеть магазинов {partner_title} в Узбекистане и в Ташкенте!', [
    'partner_title' => $partner_title,
    'category_filial' => $category_filial['cat_name'],
]);
?>

<script src="/js/holder/holder.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.13.0/js/v4-shims.js"></script>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?php if (!empty($partner['imagebaner'])) { ?>
                <img class="first-slide img-fluid card-img-top" src="/uploads/partners/<?= $partner['id']; ?>/<?= $partner['imagebaner']; ?>" alt="First slide">
            <? } else { ?>
                <img class="first-slide img-fluid" src="holder.js/1920x600?theme=thumb&bg=55595c&fg=eceeef&text=Сеть магазинов" alt="First slide">
            <?php } ?>
            <!-- <div class="container">
                <div class="carousel-caption text-left" id="partners-left-container">
                    <h1 style="color:#fff"><?= $partner['title']; ?></h1>
                    <p style="color:#fff"><?= $partner['shortdesсription'] ?></p>
                </div>
                <div class="carousel-caption text-right">
                    <p><a class="btn btn-lg btn-primary" href="http://<?= $partner['site']; ?>" role="button">Перейти в магазин</a></p>
                </div>
            </div> -->
        </div>
    </div>
</div>




<!-- Marketing messaging and featurettes
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->

<div class="container marketing">
    <br>
    <?php
    $partners_shares = (new \yii\db\Query())->select('*')->from('partners_shares')->where('partner_id=:partner_id', [':partner_id' => $partner['id']])->all();
    if (!empty($partners_shares)) { ?>
        <h1 style="margin-top:20px;" align="center">Наши акции</h1>

        <div class="row">
            <?php
            foreach ($partners_shares as $index => $partner_share) {
            ?>

                <div class="col-md-12">
                    <div class="card mb-12 box-shadow">
                        <?php if (!empty($partner_share['photo'])) { ?>
                            <img style="width:100%;height:auto;" class="card-img-top" src="/uploads/partners/shares/<?= $partner_share['id'] ?>/<?= $partner_share['photo'];  ?>">
                        <?php } else { ?>
                            <img tyle="width:auto;height:auto;" class="card-img-top" src="holder.js/100px200?theme=thumb&bg=55595c&fg=eceeef&text=Акция">
                        <?php } ?>
                        <div class="card-body">
                            <h6><?= $partner_share['title'] ?></h6>
                            <p class="align=center"><?= $partner_share['description'] ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div><!-- /.row -->
        <div class="container">
        <?php }
    if (!empty($partner['description'])) { ?>
            <!-- <h1 align="center"><?= $partner['title'] ?> </h1> -->
            <div class="blog-post">

                <!-- <?= $partner['description']; ?> -->
            </div>
            <br>
        <?php }
    $partners_filials = (new \yii\db\Query())->select('*')->from('partners_filials')->where('partner_id=:partner_id', [':partner_id' => $partner['id']])->all();
    if (!empty($partners_filials)) { ?>
            <hr class="featurette-divider">
            <h1 align="center">Филиалы магазинов</h1>
            <?php foreach ($partners_filials as $index => $partner_filial) { ?>
                <div class="row featurette">
                    <div class="col-md-5">
                        <?php if (!empty($partner_filial['photo'])) { ?>
                            <img class="featurette-image img-fluid mx-auto" alt="500x500" style="max-width: 100%; height: auto;" src="/uploads/partners/filials/<?= $partner_filial['id'] ?>/<?= $partner_filial['photo'];  ?>">
                        <?php } else { ?>
                            <img class="featurette-image img-fluid mx-auto" style="width: 400px; height: 300px;" src="holder.js/400x300?theme=thumb&bg=55595c&fg=eceeef&text=Наш филиал">
                        <?php } ?>
                    </div>
                    <div class="col-md-7">
                        <h2 class="featurette-heading"><?= $partner_filial['title']; ?></h2>
						<? if ($partner_filial['phone'] !== '') {?>
                        <div>
                            <div style="float:left; margin-right:5%;"><i class="fa fa-phone-square fa-2x"></i><br /></div>
                            <div><span>Телефоны:</span><br /><?= $partner_filial['phone']; ?></div>
                        </div>
						<? } ?>
						<? if ($partner_filial['address'] !== '') {?>
                        <div style="margin-top:10px;">
                            <div style="float:left; margin-right:4%;"><i class="fa fa-address-card fa-2x"></i></div>
                            <div><span>Адрес:</span><br /><span><?= $partner_filial['address']; ?></span></div>
                        </div>
						<? } ?>
						<? if ($partner_filial['workhour'] !== '') {?>
                        <div style="margin-top:10px;">
                            <div style="float:left; margin-right:4%;"><i class="fa fa-history fa-2x"></i></div>
                            <div><span>Режим работы:</span><br /><span><?= $partner_filial['workhour']; ?></span></div>
                        </div>
						<? } ?>
						<? if ($partner_filial['cards'] !== '') {?>
                        <div style="margin-top:10px;">
                            <div style="float:left; margin-right:4%;"><i class="fa fa-cash-register fa-2x"></i></div>
                            <div><span>Способы оплаты:</span><br /><span><?= $partner_filial['cards']; ?></span></div>
                        </div>
						<? } ?>
						<? if ( $partner_filial['site'] !== '') {?>
                        <div style="margin-top: 10px;">
                            <div style="float:left; margin-right:5%;"><i class="fa fa-globe fa-2x"></i></div>
                            <div><span>Сайт:</span><br /><span><a class="card-link" href="<?= $partner['site']; ?>" role="button">Перейти на страницу партнера</a></span></div>
                        </div>
						<? } ?>

                    </div>
                </div>
                <hr class="featurette-divider">
            <?php } ?>


            <!-- /END THE FEATURETTES -->

        </div><!-- /.container -->
</div>
<!-- БЛОК КАК КУПИТЬ В РАССРОЧКУ -->
<div class="container">
    <h1 style="margin-top:20px;" align="center">Как купить через ZMARKET?</h1>
    <div class="row">

        <div class="col-md-4" style="padding-top: 30px;">
            <img alt="Bootstrap Image Preview" src="/images/icon/contract.png" class="rounded mx-auto d-block" />
            <br>
            <div id="card-141928">
                <div class="card">
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" data-parent="#card-141928" href="#card-element-144659"><b>1) МОМЕНТАЛЬНАЯ РЕГИСТРАЦИЯ</b></a>
                    </div>
                    <div id="card-element-144659" class="collapse show">
                        <div class="card-body">
                            Зарегистрируйтесь на сайте или в магазине всего за пару минут
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-success" style="float:right" data-toggle="collapse" data-parent="#card-141928" href="#card-element-334604"><b>Для этого нужно +</b></a>
                    </div>
                    <div id="card-element-334604" class="collapse">
                        <div class="card-body">
                            * Паспорт
                            <br />
                            * Заработная пластиковая карта
                            <br>
                            UZCARD / HUMO
                            <br>
                            <b>(Ежемесячные поступления должны превышать 1 млн. сумм ежемесчно в течении последних 6 месяцев)</b>
                            <br />
                            * Мобильный телефон
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-4" style="padding-top: 30px;">
            <img alt="Bootstrap Image Preview" src="/images/icon/bullhorn.png" class="rounded mx-auto d-block" />
            <br>
            <div id="card-141928">
                <div class="card">
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" data-parent="#card-141928" href="#card-element-144659"><b>2) ПРОЙДИТЕ ВЕРИФИКАЦИЮ</b></a>
                    </div>
                    <div id="card-element-144659" class="collapse show">
                        <div class="card-body">
                            В течении 15 минут Вы получите смс сообщение о результате верификации.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-success" style="float:right" data-toggle="collapse" data-parent="#card-141928" href="#card-element-33464"><b>Успешная верификация +</b></a>
                    </div>
                    <div id="card-element-33464" class="collapse">
                        <div class="card-body">
                            В случае успешной верификации, на Вашем счету ZMARKET будет открыт лимит на покупку в рассрочку до 3 000 000 сум.
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4" style="padding-top: 30px;">
            <img alt="Bootstrap Image Preview" src="/images/icon/shop.png" class="rounded mx-auto d-block" />
            <br>
            <div id="card-141928">
                <div class="card">
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" data-parent="#card-141928" href="#card-element-144659"><b>3) СОВЕРШАЙТЕ ПОКУПКИ</b></a>
                    </div>
                    <div id="card-element-144659" class="collapse show">
                        <div class="card-body">
                            Популярные магазины уже продают свои товары в рассрочку через ZMARKET.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-success" style="float:right" data-toggle="collapse" data-parent="#card-141928" href="#card-element-33460"><b>Нужен только телефон +</b></a>
                    </div>
                    <div id="card-element-33460" class="collapse">
                        <div class="card-body">
                            * В партнерском магазине выберите понравившиеся товар
                            <br />
                            * Сообщите кассиру, что оплачиваете через ZMARKET
                            <br />
                            * Выберите период рассрочки и подтвердите покупку с помощью SMS-кода
                            <br />
                            * Наслаждайтесь покупкой и оплачиваете за покупку с отсрочкой платежа
                        </div>
                    </div>
                </div>
            </div>
        </div>





    </div>
</div>
<!-- БЛОК КАК КУПИТЬ В РАССРОЧКУ -->

<br />
<?php } ?>
</main>
<div class="container">
    <div class="row justify-content-center">
        <?php if (!empty($partner['site'])) { ?>
            <p><a class="btn btn-lg btn-primary" href="https://zmarket.uz/register-client" role="button">РЕГИСТРАЦИЯ</a></p>
        <?php } else { ?>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">РЕГИСТРАЦИЯ</a></p>
        <?php } ?>
    </div>

    <!--КАК КУПИТЬ-->
</div>