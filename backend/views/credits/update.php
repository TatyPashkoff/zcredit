<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Credits */

$this->title = 'Обновить Credits: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credits', 'url' => ['index']];

?>
<div class="credits-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
