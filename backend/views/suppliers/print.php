<?php

use yii\helpers\Html;
use yii\web\UrlManager;
/**/ ?><!-- <pre> <?php /*var_dump($user->password_login)*/ ?> </pre>--><?php /*exit;*/
/*  */ ?><!-- <pre> <?php /*var_dump($model)*/ ?> </pre>--><?php /*exit;*/

?>
<style>
    @page {
        size: 150mm 300mm;
        margin: 0;
    }

    body {
        width: 100%;
    }

    footer {
        display: none;
    }
</style>
<style>
    hr {
        border: none;
        background-color: #009F80;
        color: #009F80;
        height: 3px;
    }
</style>
<style>
    h1 {
        font-size: 20px;
    }

    .facture-list {
        padding: 0px;
        margin: 0px;
    }

    .facture-list__item {
        list-style: none;
    }

    .feature-list__item-head {
        list-style: none;
        font-weight: 600;
    }

    .feature-cred__list {
        padding: 0px;
        margin: 0px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .feature-cred__list-item-head {
        padding: 0px;
        margin: 0px;
    }

    .feature-cred__list-item {
        list-style: none;
    }

    .id-container {
        display: flex;
        justify-content: space-between;
    }

    .check-container__summary-list {
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: space-between;
    }

    .check-container__summary-list > li {
        list-style: none;
    }

    .check-container__summary-head {
        display: flex;
        justify-content: space-between;
    }
</style>
<div style="margin-top: 50px; text-align: center;">
    <div class="feature-cred">
        <h1 class="container__headline">
           <img src="https://zmarket.uz/images/reg-logo.png" alt="" class="img-fluid update__header__icon">
            <?//= Html::img('@web/images/reg-logo.png', ['class' => 'img-fluid update__header__icon', 'alt' => 'ZMarket' ]) ?>
        </h1>
        <p> БЫСТРО, ПРОСТО, УДОБНО</p>
    </div>
    <hr>
</div>

<div>
    <p>
        Здравствуйте, Партнер!
    </p>
</div>
<div>
    <p>
        Добро пожаловать в уникальную платформу zMarket. Платформа по предоставлению отсрочки платежа.
    </p>
</div>

<ul class="facture-list">

    <li class="facture-list__item">
                        <span class="feature-list__item-head">
                            Наименование компании :
                        </span>
        <span class="feature-list__item-content">
                             <?= $user->company ?>
                        </span>
    </li>


    <li class="facture-list__item">
                        <span class="feature-list__item-head">
                            Филиал :
                        </span>
        <span class="feature-list__item-content">
                            <?= $user->address_filial ?>
                        </span>
    </li>
    <li class="facture-list__item">
                        <span class="feature-list__item-head">
                           Номер печати :
                        </span>
                         <span class="feature-list__item-content">
                            <?= $user->seal_number ?>
                        </span>
        <span class="feature-list__item-content">

                        </span>
    </li>


    <li class="facture-list__item">
                        <span class="feature-list__item-head">
                            Принтер :
                        </span>
                        <span class="feature-list__item-content">
                            <?= $user->printer_number ?>
                        </span>
        <span class="feature-list__item-content">

                        </span>
    </li>
</ul>

<br>
<div>
    <p style="text-align: center">
        Информационный лист
    </p>
</div>
<br>
<div>
    <p>
        Перед тем как начать работу Вам необходимо будет зайти в Ваш личный кабинет.
    </p>

    <ul class="facture-list">

        <li class="facture-list__item">
                        <span class="">
                           - Вход в личный кабинет происходит через страницу www.zmarket.uz;
                        </span>

        </li>


        <li class="facture-list__item">
                        <span class="">
                             -Необходимо нажать на кнопку входа; &emsp;&emsp;&emsp;  <img
                                    style="width: 20px; height: 20px; "
                                    src="https://c7.hotpng.com/preview/416/62/448/login-google-account-computer-icons-user-activity.jpg"
                                    class="img-fluid update__header__icon">
                        </span>

        </li>
        <li class="facture-list__item">
                        <span class="">
                          - После необходимо будет выбрать поле Партнер и ввести Ваш ID и пароль;
                        </span>

        </li>
        <br>
        <li class="facture-list__item">
                        <span class="feature-list__item-head">
                         Ваш ID:
                        </span>
            <span class="feature-list__item-head">
                         <?= $user->id ?>
                        </span>

        </li>
        <br>
        <li class="facture-list__item">
                        <span class="feature-list__item-head">
                        Ваш пароль:
                        </span>
            <span class="feature-list__item-head">
                         <?= $user->password_login ?>
                        </span>
        </li>
        <br>
    </ul>
</div>
<div>
    <p>
        *Внимание данная информация является конфиденциальной и не подлежит разглашению третьим лицам
    </p>
</div>
<div>
    <p>
        Вместе мы достигнем новых высот, приветствуем Вас в наших рядах.
    </p>
</div>
<div>
    <p>
        С Уважением, команда zMarket!
    </p>
</div>

<table style="width: 100%; border: 0;">
    <tr>
        <td class="col">
            Служба поддержки
        </td>
        <td class="col">
            +998 95 479 0770
        </td>
        <td class="col">
            +998 95 479 7007
        </td>
    </tr>
</table>


</div>
<?php

$script = " 
$('document').ready(function(){
   window.print();
   //window.close();
});";
$this->registerJs($script, yii\web\View::POS_END);