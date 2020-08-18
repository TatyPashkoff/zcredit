<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Stock */

$this->title = 'Обновить акцию: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'акции Zmarket', 'url' => ['index']];

?>
<div class="stock-update">

    
    <?= $this->render('_form', [
        'model' => $model,
        'vendors' => $vendors,
    ]) ?>

</div>
