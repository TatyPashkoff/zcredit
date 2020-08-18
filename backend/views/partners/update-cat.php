<?php

use yii\helpers\Html;
use common\models\PartnersCats;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Обновить категорию партнера: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'партнеры', 'url' => ['index']];
$model2 = new PartnersCats();

$data_model2 = $model2->selectParnersCats();
?>
<div class="partners-update">

    
    <?= $this->render('_form_cat', [
        'dataProvider' => $dataProvider,
        'model' => $model,
    ]) ?>

</div>
