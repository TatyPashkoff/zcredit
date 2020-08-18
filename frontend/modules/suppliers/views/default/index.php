<?php

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }


        .circle{
            border-radius: 50%;
            border: 4px solid #fff; ;
            background: #0acb94;
            width:50px;
            height: 50px;
            padding: 5px 0 0 0;
            font-size: 22px;
            color: #fff;
        }
        .title-info{
            text-align: center;
            color: #0acb94;
        }


    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'main']) ?>


        <!-- old: row mb-40 products-block -->
        <div class="supliers__main-container">
            <div class="row">
                <div class="col-sm-5">
                    <h3 class="update__main__headline">
                        <?=$company = (isset($user->company)) ? $user->company . '<br> ' . $user->brand : 'название компании'; ?>
                        <div class="upd-icon"><i class="fa fa-check"></i></div>
                    </h3>
                    <span class="update__main__id">
                    ID: V - <?=$user->id ?>
                </span>
                    <?php /* <span class="update__main__data">
                    Дата верификации: 23.02.2020
                </span> */ ?>
                </div>
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="update__main__item">
                                <!-- old: info-group mb-30px-->
                                <div class="update__main__item-container">
                                    <!-- old: title-info -->
                                    <h3 class="update__main__item-title"><?=Yii::t('app','Активных договоров')?></h3>
                                    <!-- old: circle -->
                                    <div class="update__main__item-count"><?=$credits_count ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="update__main__item">
                                <!-- old: info-group mb-30px-->
                                <div class="update__main__item-container">
                                    <!-- old: title-info -->
                                    <h3 class="update__main__item-title"><?=Yii::t('app','Погашено договоров')?></h3>
                                    <!-- old: circle -->
                                    <div class="update__main__item-count"><?=$credits_stop ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="update__main__item update__hook-last">
                                <!-- old: info-group mb-30px-->
                                <div class="update__main__item-container">
                                    <!-- old: title-info -->
                                    <h3 class="update__main__item-title"><?=Yii::t('app','Выдано товаров')?></h3>
                                    <!-- old: circle -->
                                    <div class="update__main__item-count"><?=$products_sale ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- old: col-4 -->



        </div>

        <div class="supliers__main-container update__hook-last-cont">
            <div class="row">
                <div class="col-sm-5">
                    <div class="update__main-sub__item">
                        <h3 class="update__main-sub__headline update__hook__main-headline">
                            Адрес
                        </h3>
                        <span class="update__main-sub__data">
                        <?=$user->address ?>
                        </span>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="update__main-sub__item">
                                <!-- old: info-group mb-30px-->
                                <div class="update__main-sub__item-container">
                                    <!-- old: title-info -->
                                    <h3 class="update__main-sub__headline">
                                        Директор
                                    </h3>
                                    <!-- old: circle -->
                                    <div class="update__main-sub__item-small">
                                        <?=$user->username . ' '  . $user->lastname ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="update__main-sub__item update__hook-last">
                                <!-- old: info-group mb-30px-->
                                <div class="update__main-sub__item-container">
                                    <!-- old: title-info -->
                                    <h3 class="update__main-sub__headline">ИНН</h3>
                                    <!-- old: circle -->
                                    <div class="update__main-sub__item-small">
                                        <?=$user->inn ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>


<?php

$script = " 
$('document').ready(function(){
   
	 
});";
$this->registerJs($script, yii\web\View::POS_END);