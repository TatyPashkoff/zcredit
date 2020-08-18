<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */

$this->title = 'Добавить филиал';
$this->params['breadcrumbs'][] = ['label' => 'Добавить филиал', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="filial-create">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => Yii::$app->urlManager->createUrl(['/partners/create-filial'])]);?>

    <div class="col-sm">
        <label class="control-label" for="partners-title">Заголовок филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[title]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Краткое описание филиала</label>
        <textarea type="text" id="share-title" class="form-control" name="PartnersFilials[description]"  maxlength="255"  aria-invalid="false"></textarea>
        <label class="control-label" for="partners-title">Изображение филиала</label>
        <input type="file" name="PartnersFilials[photo]" id="img_baner" class="image" accept="image/*">
        <label class="control-label" for="partners-title">Телефон филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[phone]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Адрес филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[address]"  maxlength="255"  aria-invalid="false">
        <?= Html::hiddenInput('PartnersFilials[partner_id]', $_GET['id']) ?>
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
