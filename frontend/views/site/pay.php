<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app', 'Пополнение лицевого счета zMarket');
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Страница пополнения лицевого счета zMarket - оплачивай и получай zCoin бонусы',
]);

?>

    <style>
        .fix_numb {
            font-size: 24px;
            float: left;
            width: 20%;
            padding-top: 10px;
            color: #009f80;
            font-family: Roboto, sans-serif;
            font-weight: 400;
        }

        .reg-client__input {
            width: 80%;
            padding: 9px 15px 5px;
            /*margin-left: 20%;*/
        }
		
		 #phone {
            width: 100%;
            margin-left: 20%;
            padding: 9px 15px 5px;
        }
    </style>
    <div class="container" style="text-align: center">
        <a href="/"><img src="/images/reg-logo.png" alt=""
                         style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
    </div>

    <div class="reg-container reg-client__container">

        <?php $form = ActiveForm::begin(
            [
                'id' => 'register-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>


        <div class="form-group mb-30px">
<!--            <h2 class="reg-client__headline">--><?//= Yii::t('app', 'Телефон без префикса ( 90 555 55 55)') ?><!--</h2>-->
<!---->
<!--            <span class="fix_numb">+(998)</span>-->
<!--			<input type="text" placeholder="Ваш номер:" class="form-control reg-client__input" name="User[phone]" required id="phone">-->
        </div>

        <div class="form-group mb-30px">
            <h2 class="reg-client__headline"><?= Yii::t('app', 'Сумма') ?></h2>
            <input type="text" class="form-control reg-client__input" name="User[amount]" required id="phone">
        </div>

        <p class="reg-client__text">
            <label for="offer">Ознакомлен с <a href="/publicoffer.pdf"> публичной офертой  </a> &nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="offer" name="offer" value="1" checked></label>
        </p>

		

        <input  type="hidden">
        <button type="submit" id="transButton" onclick="setTimeout(addDisableAtr, 300);" class="btn btn-default check-otp"><?= Yii::t('app', 'Оплатить') ?> <i class="fa fa-play"
                                                                                                  aria-hidden="true"></i>
        </button>


        <?php ActiveForm::end() ?>

    </div>

<?php
$script = "$('document').ready(function(){
$('#phone').mask('99 999-99-99');
});";

//$this->registerJs($script, yii\web\View::POS_END);
?>

<script>
    function addDisableAtr(){
        let b = document.querySelector("button");
        b.setAttribute("disabled", "disabled");
    }
    
</script>

