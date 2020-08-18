<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Добавить партнера';
$this->params['breadcrumbs'][] = ['label' => 'партнеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-create">


    <?= $this->render('_form_cat', [
        'model' => $model,
    ]) ?>

</div>
