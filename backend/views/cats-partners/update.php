<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Обновить партнера: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'партнеры', 'url' => ['index']];

?>
<div class="partners-update">

    
    <?= $this->render('_form_cat', [
        'model' => $model,
    ]) ?>

</div>
