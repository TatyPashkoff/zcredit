<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 30.08.2017
 * Time: 17:01
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Страница Контакты';

?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'page-form',
        //'enableClientValidation' => false,
        //'enableAjaxValidation' => false,
        /*'options' => [
            //'class' => 'form-horizontal',
            //'enctype' => 'multipart/form-data',
        ]*/

    ]); ?>


    <div class="col-md-12">
       <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_11" data-toggle="tab" aria-expanded="true">RU</a></li>
                <li class=""><a href="#tab_21" data-toggle="tab" aria-expanded="false">UZ</a></li>
                <li class=""><a href="#tab_22" data-toggle="tab" aria-expanded="false">EN</a></li>
                <li class=""><a href="#tab_24" data-toggle="tab" aria-expanded="false">Социальные сети</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_11">
                    <label>Адрес RU</label>
                    <input type="text" name="pages[address_ru]" class="form-control" value="<?=@$data['address_ru']?>">
                    <label>Описание</label>
                    <textarea name="pages[about_ru]" class="form-control"><?=@$data['about_ru']?></textarea>
                </div>
                <div class="tab-pane" id="tab_21">
                    <label>Адрес UZ</label>
                    <input type="text" name="pages[address_uz]" class="form-control" value="<?=@$data['address_uz']?>">
                    <label>Описание</label>
                    <textarea name="pages[about_uz]" class="form-control"><?=@$data['about_uz']?></textarea>
                </div>
                <div class="tab-pane" id="tab_22">
                    <label>Адрес EN</label>
                    <input type="text" name="pages[address_en]" class="form-control" value="<?=@$data['address_en']?>">
                    <label>Описание</label>
                    <textarea name="pages[about_en]" class="form-control"><?=@$data['about_en']?></textarea>
                </div>


                <div class="tab-pane" id="tab_24">

                    <label>facebook</label>
                    <input type="text" name="pages[fb]" class="form-control" value="<?=@$data['fb']?>">
                    <br>
                    <label>telegram</label>
                    <input type="text" name="pages[telegram]" class="form-control" value="<?=@$data['telegram']?>">
                    <br>
                    <label>vk</label>
                    <input type="text" name="pages[vk]" class="form-control" value="<?=@$data['vk']?>">
                    <br>
                    <label>instagram</label>
                    <input type="text" name="pages[insta]" class="form-control" value="<?=@$data['insta']?>">
                    <br>
                    <label>twitter</label>
                    <input type="text" name="pages[tw]" class="form-control" value="<?=@$data['tw']?>">
                    <br>
                    <label>watsup</label>
                    <input type="text" name="pages[watsup]" class="form-control" value="<?=@$data['watsup']?>">
                    <br>
                    <label>youtube</label>
                    <input type="text" name="pages[youtube]" class="form-control" value="<?=@$data['youtube']?>">
                    <br>


                </div>



            </div>
           <hr>
            <div class="tab-content">
                <label>Email</label>
                <input name="pages[email]" class="form-control" value="<?=@$data['email']?>">
                <label>Код региона</label>
                <input name="pages[code]" class="form-control" value="<?=@$data['code']?>">
                <label>Телефон</label>
                <input name="pages[phone]" class="form-control" value="<?=@$data['phone']?>">
                <label>Факс</label>
                <input name="pages[fax]" class="form-control" value="<?=@$data['fax']?>">
                <?php /*<label>Широта</label>
                <input name="pages[lat]" class="form-control" value="<?=@$data['lat']?>">
                <label>Долгота</label>
                <input name="pages[lon]" class="form-control" value="<?=@$data['lon']?>"> */ ?>
            </div>
        </div>
    </div>

    <input type="hidden" name="Pages[page]" value="contacts">

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success' ]) ?>
    </div>

<?php ActiveForm::end(); ?>


