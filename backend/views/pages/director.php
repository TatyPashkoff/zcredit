<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 30.08.2017
 * Time: 17:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Страница О нас';

?>

    <style>
        .thumb-info {
            width: 150px;
        }

        .imageThumb {
            max-height: 100px;
            border: 1px solid #aaa;
            padding: 5px;
            cursor: pointer;
        }
        .pip,
        .pip_old {
            display: inline-block;
            margin: 10px 10px 0 0;
        }
        .remove,
        .remove-ajax {
            display: block;
            background: red;
            border: 1px solid #aaa;
            color: white;
            text-align: center;
            cursor: pointer;
        }
        .remove:hover,
        .remove-ajax:hover {
            background: #cc2406;
        }
        .colors {
            float: left;
        }
        .color {
            width: 40px;
            height: 20px;
            float: left;
            margin: 0px 7px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
    </style>

<?php

    $form = ActiveForm::begin(
    [
        'id' => 'page-form',
        //'enableClientValidation' => false,
        //'enableAjaxValidation' => false,
        'options' => [
            // 'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ]
    ]);?>

    <div class="col-md-12">

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_about_desc_11" data-toggle="tab" aria-expanded="true">RU</a></li>
                <li class=""><a href="#tab_about_desc_21" data-toggle="tab" aria-expanded="false">UZ</a></li>
                <li class=""><a href="#tab_about_desc_31" data-toggle="tab" aria-expanded="false">TR</a></li>
                <li class=""><a href="#tab_about_desc_41" data-toggle="tab" aria-expanded="false">EN</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_about_desc_11">
                    <label>Ф.И.О.</label>
                    <input type="text" name="info[name_ru]" class="form-control" value="<?=@$data['name_ru']?>">
                    <label>Должность</label>
                    <input type="text" name="info[position_ru]" class="form-control" value="<?=@$data['position_ru']?>">
                    <label>Описание</label>
                    <textarea name="info[desc_ru]" class="form-control" rows="6"><?=@$data['desc_ru']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_21">
                    <label>Ф.И.О.</label>
                    <input type="text" name="info[name_uz]" class="form-control" value="<?=@$data['name_uz']?>">
                    <label>Должность</label>
                    <input type="text" name="info[position_uz]" class="form-control" value="<?=@$data['position_uz']?>">
                    <label>Описание</label>
                    <textarea name="info[desc_uz]" class="form-control" rows="6"><?=@$data['desc_uz']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_31">
                    <label>Ф.И.О.</label>
                    <input type="text" name="info[name_tr]" class="form-control" value="<?=@$data['name_tr']?>">
                    <label>Должность</label>
                    <input type="text" name="info[position_tr]" class="form-control" value="<?=@$data['position_tr']?>">
                    <label>Описание</label>
                    <textarea name="info[desc_tr]" class="form-control" rows="6"><?=@$data['desc_tr']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_41">
                    <label>Ф.И.О.</label>
                    <input type="text" name="info[name_en]" class="form-control" value="<?=@$data['name_en']?>">
                    <label>Должность</label>
                    <input type="text" name="info[position_en]" class="form-control" value="<?=@$data['position_en']?>">
                    <label>Описание</label>
                    <textarea name="info[desc_en]" class="form-control" rows="6"><?=@$data['desc_en']?></textarea>
                </div>
            </div>
        </div>       

        <div class="tab-pane" id="tab_photo">
            <p>Фото<br>Рекомендуемый размер 200 px 480 px</p>
            <button class="btn btn-success img_preview">Загрузить изображение</button>
            <div id="image-preview" style="display:none">
                <input type="file" name="Pages[tmp_image]" id="img_preview" class="image" accept="image/*">
            </div>

            <div class="container-file" style="display: <?=\common\helpers\FileHelper::fileExist('/uploads/director/director.jpg') ? 'block': 'none' ?>">
                <img width="200px" id="img_preview" class="thumb" src="<?= '/uploads/director/director.jpg?v=' . rand(1000000,9999999) ?>" alt="">
                <a href="#" class="btn btn-danger delete-file" data-id="1" data-type="director">Удалить файл</a>
            </div>

        </div>



    </div>


    <input type="hidden" name="Pages[page]" value="director">

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
    </div>

<?php ActiveForm::end(); ?>

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
		  $('.container-file').fadeIn();
		} else console.log('is not image mime type');
	  } else console.log('not isset files data or files API not support');  
	});  
	$('.img_preview').click(function(e){ e.preventDefault(); $('#img_preview.image').click(); });
	
    $('.delete-file').click(function(e){
	     e.preventDefault();
	     if(!confirm('Подтвердите удаление!')) return false;
	     id = $(this).data('id');
	     type = $(this).data('type');
	     if(type==undefined) {
	        console.log('type не задан!')
	        return false;
	     }
	     $.ajax({
            type: 'post',
            url: '/admin/pages/delete-file',
            data: 'id='+id+'&type='+type+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $('.container-file').fadeOut();
                }
                
            },
            error: function(data){
                alert('err')
            }

         });
	})	
   
	
});";
$this->registerJs($script, yii\web\View::POS_END);

