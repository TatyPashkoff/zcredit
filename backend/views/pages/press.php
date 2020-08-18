<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 30.08.2017
 * Time: 17:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Дополнительно для прессы';

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

        .preview-box{
            margin:20px 10px;
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

            <div style="padding:15px">Тема и сообщение для отправки письма из раздела Пресса</div>

            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_about_desc_11" data-toggle="tab" aria-expanded="true">RU</a></li>
                <li class=""><a href="#tab_about_desc_21" data-toggle="tab" aria-expanded="false">UZ</a></li>
                <li class=""><a href="#tab_about_desc_31" data-toggle="tab" aria-expanded="false">TR</a></li>
                <li class=""><a href="#tab_about_desc_41" data-toggle="tab" aria-expanded="false">EN</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_about_desc_11">
                    <label>Тема RU</label>
                    <input type="text" name="info[title_ru]" class="form-control" value="<?=@$data['title_ru']?>">
                    <label>Сообщение RU</label>
                    <textarea name="info[text_ru]" class="form-control" rows="6"><?=@$data['text_ru']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_21">
                    <label>Тема UZ</label>
                    <input type="text" name="info[title_uz]" class="form-control" value="<?=@$data['title_uz']?>">
                    <label>Сообщение UZ</label>
                    <textarea name="info[text_uz]" class="form-control" rows="6"><?=@$data['text_uz']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_31">
                    <label>Тема TR</label>
                    <input type="text" name="info[title_tr]" class="form-control" value="<?=@$data['title_tr']?>">
                    <label>Сообщение TR</label>
                    <textarea name="info[text_tr]" class="form-control" rows="6"><?=@$data['text_tr']?></textarea>
                </div>
                <div class="tab-pane" id="tab_about_desc_41">
                    <label>Тема EN</label>
                    <input type="text" name="info[title_en]" class="form-control" value="<?=@$data['title_en']?>">
                    <label>Сообщение EN</label>
                    <textarea name="info[text_en]" class="form-control" rows="6"><?=@$data['text_en']?></textarea>
                </div>
            </div>
        </div>

        <h3>Файлы для скачивания из раздела пресса</h3>
        <div class="tab-pane" id="tab_photo">
            <p>Изображение баннера</p>
            <button class="btn btn-success img_preview_banner">Загрузить файл баннера</button>

            <div id="image-preview" style="display:none">
                <input type="file" name="Pages[tmp_banner_name]" id="img_preview_banner" class="file" data-id="1" data-input="banner_name">
                <input type="hidden" name="info[tmp_banner_name]" id="banner_name" value="<?=@$data['tmp_banner_name']?>">
            </div>
            <div id="container-1" class="preview-box" style="display: <?= ! @$model->isNewRecord && isset($data['tmp_banner_name']) && $data['tmp_banner_name']  !='' ? 'block' : 'none' ?>">
                <img width="100px" id="img_preview_1" class="thumb" src="<?= preg_match('/(.png|.jpg|.jpeg)/i',@$data['tmp_banner_name'])  ? '/uploads/press/' . @$data['tmp_banner_name'] : '/uploads/file.png' ?>" alt="">
                <a href="/admin/press/delete" class="btn btn-danger delete-file" data-id="1">Удалить файл</a>
                <p>Файл: <span class="file-name"><?=@$data['tmp_banner_name'] ?></span></p>
            </div>


        </div>

        <div class="tab-pane" id="tab_photo">
            <p>Изображение логотипа</p>
            <button class="btn btn-success img_preview">Загрузить файл лого</button>
            <div id="image-preview" style="display:none">
                <input type="file" name="Pages[tmp_logo_name]" id="img_preview" class="file" data-id="2" data-input="logo_name">
                <input type="hidden" name="info[tmp_logo_name]" id="logo_name" value="<?=@$data['tmp_logo_name']?>">
            </div>
            <div id="container-2" class="preview-box" style="display: <?= ! @$model->isNewRecord && isset($data['tmp_logo_name']) && $data['tmp_logo_name']  !='' ? 'block' : 'none' ?>">
                <img width="100px" id="img_preview_2" class="thumb" src="<?= preg_match('/(.png|.jpg|.jpeg)/i',@$data['tmp_logo_name'])  ? '/uploads/press/' . @$data['tmp_logo_name'] : '/uploads/file.png' ?>" alt="">
                <a href="/admin/press/delete" class="btn btn-danger delete-file" data-id="2">Удалить файл</a>
                <p>Файл: <span class="file-name"><?=@$data['tmp_logo_name'] ?></span></p>
            </div>

        </div>

        <div class="tab-pane" id="tab_photo">
            <p>Изображение визитки</p>
            <button class="btn btn-success img_preview2">Загрузить файл визитки</button>
            <div id="image-preview" style="display:none">
                <input type="file" name="Pages[tmp_visit_name]" id="img_preview2" class="file" data-id="3" data-input="visit_name">
                <input type="hidden" name="info[tmp_visit_name]" id="visit_name" value="<?=@$data['tmp_visit_name']?>">
            </div>
            <div id="container-3" class="preview-box" style="display: <?= ! @$model->isNewRecord && isset($data['tmp_visit_name']) && $data['tmp_visit_name']  !='' ? 'block' : 'none' ?>">
                <img width="100px" id="img_preview_3" class="thumb" src="<?= preg_match('/(.png|.jpg|.jpeg)/i',@$data['tmp_visit_name'])  ? '/uploads/press/' . @$data['tmp_visit_name'] : '/uploads/file.png' ?>" alt="">
                <a href="/admin/press/delete" class="btn btn-danger delete-file" data-id="3">Удалить файл</a>
                <p>Файл: <span class="file-name"><?=@$data['tmp_visit_name'] ?></span></p>
            </div>


        </div>

    </div>

    <input type="hidden" name="Pages[page]" value="press">

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
$script = " 
$('document').ready(function(){

    $(document).on('change','.file',function(){
        var input = $(this)[0];
        var input_to = $(this).data('input');
        var obj = $(this);
        var id = $(this).data('id');
        if ( input.files && input.files[0] ) {
            $('#img_preview_'+id).attr('src','');
            $('#'+input_to).val( input.files[0].name );
            $('#container-'+id + ' .file-name').text( input.files[0].name );
            $('#container-'+id).fadeIn(); 
        }
	}); 
	 
	$('.img_preview').click(function(e){ e.preventDefault(); $('#img_preview.file').click(); });
	$('.img_preview2').click(function(e){ e.preventDefault(); $('#img_preview2.file').click(); });
	$('.img_preview_banner').click(function(e){ e.preventDefault(); $('#img_preview_banner.file').click(); });
		
	$('.delete-file').click(function(e){
	     e.preventDefault();
	     if(!confirm('Подтвердите удаление файла!')) return false;
	     id = $(this).data('id');
	     $.ajax({
            type: 'post',
            url: '/admin/press/delete-files',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $('#container-'+id).fadeOut();                    
                }                
            },
            error: function(data){
                alert('err')
            }
         });
	})
	
});";
$this->registerJs($script, yii\web\View::POS_END);

