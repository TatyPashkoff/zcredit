<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Markers */

$this->title = 'Добавить Markers';
$this->params['breadcrumbs'][] = ['label' => 'Markers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="markers-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
