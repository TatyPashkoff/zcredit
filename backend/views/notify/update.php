<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Notify */

$this->title = 'Обновить Notify: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notifies', 'url' => ['index']];

?>
<div class="notify-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
