<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PartnersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список категорий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-index">

    <p>
        <?= Html::a('Добавить категорию', ['create-cat'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cat_name',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}',

			],
        ],
    ]); ?>

</div>
