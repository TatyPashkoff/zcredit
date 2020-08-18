<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Services */

$this->title = 'Обновить Services: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];

?>
<div class="services-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
