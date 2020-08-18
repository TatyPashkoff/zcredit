<?php
use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

    </style>

    <?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

        <div class="update__client-container">
            <div class="update__client-title">
                <?=Yii::t('app','CashBack')?>
            </div>
        </div>

        <!-- old: row mb-40 products-block -->
        <div class="hook-client-cont">
        В разработке


        </div>

    </div>

<?php
$script = " 
$('document').ready(function(){  
		 	 
});";
$this->registerJs($script, yii\web\View::POS_END);