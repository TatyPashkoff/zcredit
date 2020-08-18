<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Добавить Orders';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
