<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditHistory */

$this->title = 'Обновить Credit History: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credit Histories', 'url' => ['index']];

?>
<div class="credit-history-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
