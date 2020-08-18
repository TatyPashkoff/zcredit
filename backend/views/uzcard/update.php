<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Uzcard */

$this->title = 'Обновить Uzcard: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uzcards', 'url' => ['index']];

?>
<div class="uzcard-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
