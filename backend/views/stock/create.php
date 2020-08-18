<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Stock */

$this->title = 'Добавить акцию';
$this->params['breadcrumbs'][] = ['label' => 'Акции ZMarket', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
