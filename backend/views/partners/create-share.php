<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Добавить акцию';
$this->params['breadcrumbs'][] = ['label' => 'партнеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="filial-create">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => Yii::$app->urlManager->createUrl(['/partners/create-share'])]);?>

    <div class="col-sm">
        <label class="control-label" for="partners-title">Заголовок акции</label>
        <input type="text" id="share-title" class="form-control" name="PartnersShares[title]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Краткое описание акции</label>
        <textarea type="text" id="share-title" class="form-control" name="PartnersShares[description]"  maxlength="255"  aria-invalid="false"></textarea>
        <label class="control-label" for="partners-title">Изображение акции</label>
        <input type="file" name="PartnersShares[photo]" id="img_baner" class="image" accept="image/*">
        <?= Html::hiddenInput('PartnersShares[partner_id]', $_GET['id']) ?>
    </div>

    <div style="margin-top:2%;" class="form-group">
    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function(){
        let editor = CKEDITOR.replaceAll();
    })
</script>
