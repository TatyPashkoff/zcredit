<?php

use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;
use yii\helpers\Html;
use common\models\Partners;
use common\models\Mobile_Detect;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app', '   Выбери нужный товар у наших партнеров и оформи его в рассрочку через zMarket');
$detect = new Mobile_Detect;
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<style>
    .mask {
        background-color: rgba(0, 0, 0, .3);
        height: 100%;
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
    }

    body {
        background-color: #eee;
    }
</style>

<div class="zcoin-container container">
    <!-- Верхний текст с заголовками-->
    <!--<div class="vendors__headline d-none d-sm-block">

        Выбери партнера  <span class="zcoin__headline-count">и оформи его товары</span>
        в рассрочку через zMarket
    </div>-->
    <!--<div class="vendors__headline__mobile d-block d-md-none">

        Выбери партнера  <span class="zcoin__headline-count">и оформи его товары</span>
        в рассрочку через zMarket
    </div>-->
    <!--Партнерский лист с изображениями 1-->
    <div class="row" id="vendor_container">
        <?
        // Any mobile device (phones or tablets).
        if (!$detect->isMobile()) { ?>
            <?php $i = 0;
            foreach ($partners as $partner) { ?>
                <?php $cat = (new \yii\db\Query())->select('cat_name')->from('partners_cats')->where('id=:id', [':id' => $partner['cat_id']])->one(); ?>
                <div style="margin: 0 20px 30px 0;" class="col-sm"><a href="vendors/<?php echo $partner['id']; ?>"><img style="width:240px; height:240px; border-radius:20px;" src="<?php echo 'uploads/partners/' . $partner['id'] . '/' . $partner['image']; ?> "></a><span><?= $cat['cat_name'] ?></span></div>



            <?php }
        } else { ?>

            <?php $i = 0;
            foreach ($partners as $partner) { ?>
                <?php $cat = (new \yii\db\Query())->select('cat_name')->from('partners_cats')->where('id=:id', [':id' => $partner['cat_id']])->one(); ?>
                <div id="vendor_item" class="col-sm col-6"><a href="vendors/<?php echo $partner['id']; ?>"><img src="<?php echo 'uploads/partners/' . $partner['id'] . '/' . $partner['image']; ?> "></a><span><?= $cat['cat_name'] ?></span></div>
        <? }
        } ?>
    </div>

    <script>
        $(document).ready(function() {
            $(".nav-pills a").click(function() {
                $(this).tab('show');
            });
        });
    </script>

</div>

<!-- <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home"><img src="<?php echo '/uploads/partners/' . $string['id'] . '/' . $string['image']; ?> ">$partner['cat_name']</a></li> -->


<!-- Nav tabs -->
<!--
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#home">Компьютеры и комплектующие</a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#menu4">Одежда</a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#menu5">Ювелирные изделия</a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#menu6">Зоомагазины</a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#menu7">Настольные игры</a>
      </li>
  </ul>
-->

</div>
</div>
</div>
<div class="bottom-block">
    <div class="container">
        <div class="h2 white">Попробуй преимущество рассрочки</div>
        <a href="/register-client" class="btn btn-default">Регистрация</a>
    </div>
    <!--container-->
</div>