<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;

$this->title = 'Кабинет';
$user_id = Yii::$app->user->id;
?>
    <style>
        .table-row{
            border: 1px solid #ccc;
            border-top:0px;
            padding: 5px 0px;
            cursor:pointer;
        }
        .table-row-header{
            border: 1px solid #ccc;
            padding: 5px 0px;
            font-weight:bold;
            text-decoration:capitalize;
        }
        .table-row:hover{
            background:#eee;
        }
        .order-detail{
            margin: 15px 0px;
            width: 100%;
        }
        .bold{
            font-weight:bold;
        }
        #myModalOrderDetail{
            z-index: 9999;
            margin-top: 25px;
        }

    </style>

<div class="inner-page-banner-area" style="background-image:url(/uploads/header-banners/1092x187-01.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcrumb-area">
                    <h1>Кабинет</h1>
                    <ul>
                        <li><a href="/">Главная</a> /</li>
                        <li>Персональный кабинет</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-account-page-area">
    <div class="container">
        <div class="woocommerce">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <nav class="woocommerce-MyAccount-navigation">
                        <ul>
                            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard"><a href="#dashboard" data-toggle="tab" aria-expanded="false">Общая</a></li>
                            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard"><a href="#orders" data-toggle="tab" aria-expanded="false">Заказы</a></li>
                            <?php // <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard"><a href="#addresses" data-toggle="tab" aria-expanded="false">Адрес</a></li> ?>
                            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard"><a href="#details" data-toggle="tab" aria-expanded="false">Безопасность</a></li>
                            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard active"><a href="#logout" data-toggle="tab" aria-expanded="true">Выйти</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="tab-content">
                        <div class="tab-pane fade" id="dashboard">
                            <div class="woocommerce-MyAccount-content">
                                <header class="row woocommerce-Address-title title">
                                    <h3 class="col-xs-12 metro-title">Персональные данные</h3>
                                </header>

                                <?php $form = ActiveForm::begin(); ?>

                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="User[first_name]" id="account_first_name" value="" placeholder="Имя">
                                </p>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="User[last_name]" id="account_last_name" value="" placeholder="Фамилия">
                                </p>
                                <div class="clear"></div>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[phone]" id="account_email" value="" placeholder="Телефон">
                                </p>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="User[email]" id="account_email" value="" placeholder="Email">
                                </p>
                                <div class="clear"></div>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[country]" id="account_email" value="" placeholder="Страна">
                                </p>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[city]" id="account_email" value="" placeholder="Город">
                                </p>
                                <div class="clear"></div>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[region]" id="account_email" value="" placeholder="Район">
                                </p>
                                <p class="col-md-6 col-sm-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[zip]" id="account_email" value="" placeholder="Почтовый индекс">
                                </p>
                                <div class="clear"></div>
                                <p class="col-xs-12 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="User[address]" id="account_email" value="" placeholder="Адрес доставки">
                                </p>
                                <input type="submit" class="woocommerce-Button button btn-shop-now-fill" name="save_account_details" value="Сохранить">



                                <?php ActiveForm::end() ?>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="orders">
                            <div class="woocommerce-message"><a class="woocommerce-Button button" href="shop1.html">В каталог</a>У вас еще нет заказов.
                            </div>

                            <div class="col-md-12">
                                <div class="row table-row-header">
                                    <div class="col-md-2">№ заказа</div>
                                    <div class="col-md-2">Дата</div>
                                    <div class="col-md-2">Кол-во</div>
                                    <div class="col-md-2">Сумма</div>
                                    <div class="col-md-2">Статус</div>
                                </div>
                                <div class="row table-row" data-id="1">
                                    <div class="col-md-2">1001</div>
                                    <div class="col-md-2">10.03.2018</div>
                                    <div class="col-md-2">1</div>
                                    <div class="col-md-2">250</div>
                                    <div class="col-md-2 status-payed">Оплачено</div>
                                </div>
                                <div class="row table-row" data-id="1">
                                    <div class="col-md-2">1001</div>
                                    <div class="col-md-2">10.03.2018</div>
                                    <div class="col-md-2">1</div>
                                    <div class="col-md-2">250</div>
                                    <div class="col-md-2 status-payed">Оплачено</div>
                                </div>
                                <div class="row table-row" data-id="1">
                                    <div class="col-md-2">1001</div>
                                    <div class="col-md-2">10.03.2018</div>
                                    <div class="col-md-2">1</div>
                                    <div class="col-md-2">250</div>
                                    <div class="col-md-2 status-payed">Оплачено</div>
                                </div>
                                <div id="order_modal" data-toggle="modal" data-target="#myModalOrderDetail" style="display:none"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="downloads">
                            <div class="woocommerce-info"><a class="woocommerce-Button button" href="#">Go shop</a>No downloads available yet.
                            </div>
                        </div>
                        <?php /* <div class="tab-pane fade" id="addresses">
                            <div class="woocommerce-MyAccount-content wd-myaccount-content-wrapper">
                                <p>
                                    Здесь можно указать адреса доставки, которые будут автоматически вставлены во время оформления заказа.</p>
                                <div class="u-columns woocommerce-Addresses addresses">
                                    <div class="woocommerce-Address">
                                        <header class="woocommerce-Address-title title">
                                            <h3>Адрес биллинга</h3>
                                        </header>
                                        <address>
                                            <textarea name="address" class="form-control">

                                            </textarea>
                                        </address>
                                    </div>
                                    <div class="woocommerce-Address">
                                        <header class="woocommerce-Address-title title">
                                            <h3>Адрес доставки</h3>
                                        </header>
                                        <address>
                                            <textarea name="address_shipping" class="form-control">

                                            </textarea>
                                        </address>
                                    </div>
                                </div>
                                <input type="submit" class="woocommerce-Button button btn-shop-now-fill" name="save_account_details" value="Сохранить">
                            </div>
                        </div> */ ?>
                        <div class="tab-pane fade" id="details">
                            <div class="woocommerce-MyAccount-content">
                                <?php /*<header class="row woocommerce-Address-title title">
                                    <h3 class="col-xs-12 metro-title">ACCESS YOUR ACCOUNT</h3>
                                </header> */ ?>
                                <form class="row woocommerce-EditAccountForm edit-account" action="" method="post">

                                    <fieldset class="col-xs-12">
                                        <legend>Сменить пароль</legend>
                                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                            <label for="password_current">Текущий пароль</label>
                                            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current">
                                        </p>
                                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                            <label for="password_1">Новый пароль</label>
                                            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1">
                                        </p>
                                    </fieldset>
                                    <div class="clear"></div>
                                    <p class="col-xs-12">
                                        <input type="hidden" id="_wpnonce" name="_wpnonce" value="96df2c51c6">
                                        <input type="hidden" name="_wp_http_referer" value="/my-account/edit-account/">
                                        <input type="submit" class="woocommerce-Button button btn-shop-now-fill" name="save_account_details" value="Сохранить">
                                        <input type="hidden" name="action" value="save_account_details">
                                    </p>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade active in" id="logout">
                            <div class="woocommerce-message">Для выхода из аккаунта нажмите на кнопку <a href="/logout">Выйти</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="myModalOrderDetail" class="modal fade" role="dialog" style="display: none;">
        <div class="modal-dialog clearfix">
            <div class="modal-body">
                <button type="button" class="close myclose" data-dismiss="modal">×</button>
                <div class="clearfix">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 order-detail">
                        <div class="row table-row-header">
                            <div class="col-md-6">Товар</div>
                            <div class="col-md-2">Кол-во</div>
                            <div class="col-md-2">Цена</div>
                            <div class="col-md-2">Сумма</div>
                        </div>
                        <div class="row table-row">
                            <div class="col-md-6">Букет цветов</div>
                            <div class="col-md-2">5</div>
                            <div class="col-md-2">25000</div>
                            <div class="col-md-2">125000</div>
                        </div>		<div class="row table-row">
                            <div class="col-md-6">Букет цветов</div>
                            <div class="col-md-2">5</div>
                            <div class="col-md-2">25000</div>
                            <div class="col-md-2">125000</div>
                        </div>
                        <div class="row table-row bold">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">Итого</div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">125000</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn-services-shop-now" data-dismiss="modal">Закрыть</a>
            </div>
        </div>
    </div>
<?php

$script = "
$(document).ready(function () {
    $('.table-row').click(function(){
        var id = $(this).data('id');
        $('#order_modal').click();
    });
        
});";
$this->registerJs($script, yii\web\View::POS_END);