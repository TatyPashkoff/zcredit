<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Markers */

$this->title = 'Обновить Markers: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Markers', 'url' => ['index']];

?>
<div class="markers-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
