<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Notify */

$this->title = 'Добавить Notify';
$this->params['breadcrumbs'][] = ['label' => 'Notifies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notify-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
