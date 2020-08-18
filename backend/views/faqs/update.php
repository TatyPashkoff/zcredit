<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faqs */

$this->title = 'Обновить Faqs: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];

?>
<div class="faqs-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
