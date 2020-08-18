<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CreditItems */

$this->title = 'Добавить Credit Items';
$this->params['breadcrumbs'][] = ['label' => 'Credit Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-items-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
