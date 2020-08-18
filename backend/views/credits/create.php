<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Credits */

$this->title = 'Добавить Credits';
$this->params['breadcrumbs'][] = ['label' => 'Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credits-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
