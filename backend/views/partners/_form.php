<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Partners */
/* @var $form yii\widgets\ActiveForm */


?>





<div class="partners-form">

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'partners-form',
				//'enableClientValidation' => false,
				//'enableAjaxValidation' => false,
				// 'action' => $model->isNewRecord ? '/admin/' : '/admin/.../update?id=' . $model->id ,
            'options' => [
                //'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',

            ]
        ]);

    $items = ArrayHelper::map($data_model2,'id','cat_name');


    $params = ['prompt' => 'Выберите категорию'];

    ?>




    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tabLang_1" data-toggle="tab" aria-expanded="true">Основные данные</a></li>
            <li><a href="#menu1" data-toggle="tab" aria-expanded="true">Загрузка превью</a></li>
            <li><a href="#menu2" data-toggle="tab" aria-expanded="true">Загрузка рекламного банера</a></li>
            <?php if(isset($partners_shares) and isset($partners_filials)  ) {?>
            <li><a href="#menu3" data-toggle="tab" aria-expanded="true">Акции</a></li>
            <li><a href="#menu4" data-toggle="tab" aria-expanded="true">Филиалы</a></li>
            <?php }?>
        </ul>


        <div class="tab-content">
                <div class="tab-pane active" id="tabLang_1">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true,'required'=>true]) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


                    <?= $form->field($model, 'shortdesсription')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

                    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'cat_id')->dropDownList($items,$params) ?>
                    <?= $form->field($model, "status")
                        ->dropDownList([
                            "0" => "Отключен",
                            "1" => "Включен",
                        ], $param = ["options" => [$model->status => ["selected" => true]]]);
                    ?>
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>

            <div id="menu1" class="tab-pane fade">
                <div class="form-group field-partners-image-preview">
                    <label class="control-label" for="partners-image">Превью для партнера</label><br>
                    <button class="btn btn-success img_preview">Загрузить превью</button>
                    <div id="image-preview" style="display:none">
                        <input type="file" name="Partners[tmp_image]" id="img_preview" class="image" accept="image/*">
                    </div>
                    <div class="container-file" style="display: <?= !$model->isNewRecord && $model->image!='' ? 'block': 'none' ?>">
                        <?php
                        $path = '/uploads/partners/'.$model->id.'/thumb/'.$model->image;
                        ?>
                        <img width="200px" id="img_preview" class="thumb" src="<?= $path . '?v=' . rand(1000000,9999999) ?>" alt="">
                        <a href="#" class="btn btn-danger delete-file" data-id="<?=$model->id ?>">Удалить файл</a>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>

            <div id="menu2" class="tab-pane fade">
                <div class="form-group field-partners-image-preview">
                    <label class="control-label" for="partners-image">Банер для партнера</label><br>
                    <button class="btn btn-success img_baner">Загрузить банер</button>
                    <div id="image-preview" style="display:none">
                        <input type="file" name="Partners[tmp_imagebaner]" id="img_baner" class="image" accept="image/*">
                    </div>
                    <div class="container-file" style="display: <?= !$model->isNewRecord && $model->imagebaner!='' ? 'block': 'none' ?>">
                        <?php
                        $path = '/uploads/partners/'.$model->id.'/thumb/'.$model->imagebaner;
                        ?>
                        <img width="200px" id="img_baner" class="thumb" src="<?= $path . '?v=' . rand(1000000,9999999) ?>" alt="">
                        <a href="#" class="btn btn-danger delete-file-baner" data-id="<?=$model->id ?>">Удалить файл</a>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php if(isset($partners_shares) and isset($partners_filials)  ) {?>
            <div id="menu3" class="tab-pane fade">
                <label class="control-label" for="partners-image">Акции для партнера</label><br>
                <div class="container">
                    <div class="row share_list">
                        <?php foreach($partners_shares as $share){?>
                        <div class="col-sm-3" style="border:5px solid #3C8DBC; margin-right:10px;">
                            <div class="form-group">
                            <label class="control-label" for="partners-title">Заголовок акции</label>
                            <a href="/partners/view-update-share?id=<?=$share['id'];?>" title="Редактировать" aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="/partners/delete-share?id=<?=$share['id'];?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                                <br>
                           <span><?=$share['title']?></span>
                            </div>
                            <div class="form-group">
                            <label class="control-label" for="partners-title">Описание акции</label><br>
                            <span><?=$share['description']?></span>
                            </div>
                            <div class="form-group">
                            <label class="control-label" for="partners-title">Фотография акции</label><br>
                            <img style="width:200px;height:200px;" src="https://zmarket.uz/uploads/partners/shares/<?=$share['id']?>/thumb/<?=$share['photo']?>">
                            </div>
                        </div>
                        <?php }?>

                    </div>

                    <div style="margin-top:2%" class="form-group">
                        <div> <a href="/partners/view-share?id=<?=$_GET['id']?>"><div class="btn btn-primary">Создать блок акции</div></a></div>
                    </div>
                </div>
            </div>


            <div id="menu4" class="tab-pane fade">
                <label class="control-label" for="partners-image">Филиалы для партнера</label><br>
                    <div class="container">
                        <div class="row filial_list">
                            <?php foreach($partners_filials as $filial){?>
                                <div class="col-sm-3" style="border:5px solid #3C8DBC; margin-right:10px;">
                                    <div class="form-group">
                                        <label class="control-label" for="partners-title">Заголовок</label>
                                        <a href="/partners/view-update-filial?id=<?=$filial['id'];?>" title="Редактировать" aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a href="/partners/delete-filial?id=<?=$filial['id'];?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                                        <br>
                                        <span><?=$filial['title']?></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="partners-title">Описание</label><br>
                                        <span><?=$filial['description']?></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="partners-title">Фото</label><br>
                                        <img style="width:200px;height:200px;" src="https://zmarket.uz/uploads/partners/filials/<?=$filial['id']?>/thumb/<?=$filial['photo']?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="partners-title">Телефон</label><br>
                                        <span><?=$filial['phone']?></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="partners-title">Адрес</label><br>
                                        <span><?=$filial['address']?></span>
                                    </div>
                                </div>
                            <?php }?>
                        </div>

                        <div style="margin-top:2%" class="form-group">
                            <div> <a href="/partners/view-filial?id=<?=$_GET['id']?>"><div class="btn btn-primary">Создать блок филиала</div></a></div>
                        </div>

                </div>

            </div>
            <?php }?>

    <?php ActiveForm::end(); ?>

</div>
<?php $script = "$('document').ready(function(){
    
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
	     $.ajax({
            type: 'get',
            url: '/admin/partners/delete-image',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $('.container-file').fadeOut();
                }                
            },
            error: function(data){
                //alert('Server error')
                $('.container-file').fadeOut();
            }

         });
	})		
	
});";

$script2 = "$('document').ready(function(){
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
	$('.img_baner').click(function(e){ e.preventDefault(); $('#img_baner.image').click(); });

    $('.delete-file-baner').click(function(e){
	     e.preventDefault();
	     if(!confirm('Подтвердите удаление!')) return false;
	   
	     
	     id = $(this).data('id');	     
	     $.ajax({
            type: 'get',
            url: '/admin/partners/delete-baner-image',
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $('.container-file').fadeOut();
                }                
            },
            error: function(data){
                alert('Server error')
                $('.container-file').fadeOut();
            }

         });
	})		
	
});";


$this->registerJs($script, yii\web\View::POS_END);
$this->registerJs($script2, yii\web\View::POS_END);
?>

<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function(){
        let editor = CKEDITOR.replaceAll();
    })

        /*TODO MAY BE
function addShare() {
    $(".share_list").append(`
        <div style="margin-bottom:50px;" class="col-sm-3">
        <i class="fa fa-fw fa-close"></i><a onclick="deleteFilial()" href="#">Удалить</a>
        <label class="control-label" for="partners-title">Заголовок акции</label>
        <input type="text" id="share-title" class="form-control" name="PartnersShares[title]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Краткое описание акции</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[description]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Изображение акции</label>
        <input type="file" name="PartnersShares[photo]" id="img_baner" class="image" accept="image/*">
        <hr style="color:#009F80; height=5px;">
    </div>
    `);
}

function addFilial () {
    $(".filial_list").append(`
    <div style="margin-bottom:50px;" class="col-sm-3">
        <label class="control-label" for="partners-title">Заголовок филиала</label>
        <i class="fa fa-fw fa-close"></i><a onclick="deleteFilial()" href="#">Удалить</a>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[title]"  maxlength="255" aria-invalid="false">
        <label class="control-label" for="partners-title">Краткое описание филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[description]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Изображение филиала</label>
        <input type="file" name="PartnersFilials[photo]" id="img_baner" class="image" accept="image/*">
        <label class="control-label" for="partners-title">Телефон филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[phone]"  maxlength="255"  aria-invalid="false">
        <label class="control-label" for="partners-title">Адрес филиала</label>
        <input type="text" id="share-title" class="form-control" name="PartnersFilials[address]"  maxlength="255"  aria-invalid="false">
    </div>
    `);
}

function deleteFilial () {
        console.log('hel');
        $('.col-sm-3').remove();
}
*/


</script>
