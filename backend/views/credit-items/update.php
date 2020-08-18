<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditItems */

$this->title = 'Обновить Credit Items: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Credit Items', 'url' => ['index']];

?>
<div class="credit-items-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
