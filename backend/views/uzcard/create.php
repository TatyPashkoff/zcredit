<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Uzcard */

$this->title = 'Добавить Uzcard';
$this->params['breadcrumbs'][] = ['label' => 'Uzcards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uzcard-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
