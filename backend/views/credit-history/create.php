<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CreditHistory */

$this->title = 'Добавить Credit History';
$this->params['breadcrumbs'][] = ['label' => 'Credit Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-history-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
