<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Kyc */

$this->title = 'Обновить Kyc: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kycs', 'url' => ['index']];

?>
<div class="kyc-update">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
