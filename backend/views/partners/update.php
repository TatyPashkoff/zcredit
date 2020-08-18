<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Обновить партнера: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'партнеры', 'url' => ['index']];

?>
<div class="partners-update">

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'data_model2' => $data_model2,
        'partners_filials' => $partners_filials,
        'partners_shares' => $partners_shares,
    ]) ?>

</div>
