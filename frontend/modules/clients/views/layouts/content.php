<?php
use yii\widgets\Breadcrumbs;
use frontend\components\ChatWidget;
use frontend\components\MainChatWidget;

//use dmstr\widgets\Alert;

?>

<?= $content ?>

<?php if(!Yii::$app->user->isGuest) {
    //echo 'chat-widget'; exit;
    //echo ChatWidget::widget();
    echo MainChatWidget::widget();
//echo 'chat';   exit;
} ?>  