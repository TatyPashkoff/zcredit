<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\backend\assets\EditorAsset::register($this);

$this->title = 'Главная страница';

/* @var $this yii\web\View */
/* @var $model common\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="pages-form">

        <?php $form = ActiveForm::begin(
            [
                'id' => 'auto-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    //'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]
            ]);?>

        <div class="col-md-12">
            <!-- Custom Tabs -->


            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab5_1" data-toggle="tab" aria-expanded="true">RU</a></li>
                    <li class=""><a href="#tab5_2" data-toggle="tab" aria-expanded="false">UZ</a></li>
                    <li class=""><a href="#tab5_4" data-toggle="tab" aria-expanded="false">EN</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5_1">
                        <label>Заголовок</label>
                        <input name="info[title_ru]" class="form-control" value="<?=@$data['title_ru']?>">

                        <label>Описание</label>
                        <textarea id="about_ru" name="info[about_ru]" class="form-control"><?=@$data['about_ru']?></textarea>
                    </div>
                    <div class="tab-pane" id="tab5_2">
                        <label>Заголовок</label>
                        <input name="info[title_uz]" class="form-control" value="<?=@$data['title_uz']?>">

                        <label>Описание</label>
                        <textarea id="about_uz" name="info[about_uz]" class="form-control"><?=@$data['about_uz']?></textarea>
                    </div>
                    <div class="tab-pane" id="tab5_4">
                        <label>Заголовок</label>
                        <input name="info[title_en]" class="form-control" value="<?=@$data['title_en']?>">

                        <label>Описание</label>
                        <textarea id="about_en" name="info[about_en]" class="form-control"><?=@$data['about_en']?></textarea>
                    </div>
                </div>
            </div>



            <div class="tab-pane" id="tab_photo">
                <h3>Фоновое изображение - баннер</h3>
                <p>Минимальный рекомендуемый размер 1170 px 420 px</p>
                <button class="btn btn-success img_preview_banner">Загрузить изображение</button>
                <div id="image-preview" style="display:none">
                    <input type="file" name="Pages[tmp_banner]" id="img_preview_banner" class="image" accept="image/*">
                </div>

                <div id="box1" class="container-file"  style="display: <?= \common\helpers\FileHelper::fileExist('/uploads/block/thumb/'.$type.'_thumb.jpg') ? 'block': 'none' ?>">
                    <img width="300px" id="img_preview_banner" class="thumb" src="<?= '/uploads/block/thumb/'.$type.'_thumb.jpg?v=' . rand(1000000,9999999) ?>" alt="">
                    <a href="#" class="btn btn-danger delete-file" data-id="1" data-type="1">Удалить файл</a>
                </div>
            </div>

        </div>

        <input type="hidden" name="Pages[page]" value="main">

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$script = " 
$('document').ready(function(){

	$(document).on('change','.image',function(){
	  var input = $(this)[0];
	  var obj = $(this);
	  if ( input.files && input.files[0] ) {
		if ( input.files[0].type.match('image.*') ) {
		  var reader = new FileReader();		  
		  reader.onload = function(e){ $('img#'+obj.attr('id')).attr('src', e.target.result);}
		  reader.readAsDataURL(input.files[0]);
		  $('#box'+type+'.container-file').fadeIn();
		} else console.log('is not image mime type');
	  } else console.log('not isset files data or files API not support');  
	});  
	$('.img_preview_banner').click(function(e){ e.preventDefault(); type = 1; $('#img_preview_banner.image').click(); });

   
    // удаление из фото-видео галереи
	$('.remove-ajax').click(function(){		    
	    if(!confirm('Подтвердите удаление!') ) return false;

		var id = $(this).data('id');
		//alert(id);
		$(this).parent().remove();
		$.ajax({
			type: 'post',
            url: '/admin/pages/delete-image',            
            dataType: 'json',
            data: 'id='+id +'&type=main&_csrf='+yii.getCsrfToken(),
            success: function(data){
			},
            error: function(jqxhr, status, errorMsg) {
				alert('Статус: ' + status + ' Ошибка: ' + errorMsg );				
			}
        });		
	});	
	
    $('#about_ru').wysihtml5();
	$('#about_uz').wysihtml5();
	$('#about_en').wysihtml5();
});";
$this->registerJs($script, yii\web\View::POS_END);