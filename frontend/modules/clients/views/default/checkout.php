<?php
\frontend\assets\MainAsset::register($this);
?>

    <?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

        <?= $this->render('_menu',['active'=>'']) ?>

        <div class="title-with-border"><?=Yii::$app->session->getFlash('info') ?></div>

    </div>

