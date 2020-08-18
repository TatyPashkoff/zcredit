<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <?php /* <h1><?= Html::encode($this->title) ?></h1> */ ?>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            'password_hash',
            'password_reset_token',
            'email:email',
            'auth_key',
            'role',
            'status',
            'balance',
            'balance_hash',
            'ratio_count',
            'ratio_sum',
            'city_id',
            'shop_id',
            'region_id',
            'updated_at',
            'created_at',
            'settings:ntext',
            'image',
        ],
    ]) ?>

</div>
