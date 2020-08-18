<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Обновить Orders: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];

?>
<div class="orders-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
