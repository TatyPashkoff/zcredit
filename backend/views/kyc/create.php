<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Kyc */

$this->title = 'Добавить Kyc';
$this->params['breadcrumbs'][] = ['label' => 'Kycs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kyc-create">

    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
