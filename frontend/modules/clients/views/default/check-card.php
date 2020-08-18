<?php
\frontend\assets\MainAsset::register($this);

    use yii\widgets\ActiveForm;

    $this->title = Yii::t('app','Добавление карты');

    ?>

<?= $this->render('_header') ?>

    <div class="container">

        <div class="update__settings-container" style="margin: 60px 0px;">


            <?php $form = ActiveForm::begin(
                [
                    'id' => 'register-form',
                    'options' => [
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    ]

                ]);

            ?>

            <div class="flex-parent">
                <div class="input-flex-container">
                    <div class="form-group col-sm-12 " >
                        <input type="text" style="text-align: center" class="form-control required" name="User[uzcard]" id="uzcard" required placeholder="Введите номер карты">
                    </div>
                </div>
                <div class="input-flex-container">
                    <div class="form-group col-sm-12 ">
                        <input  type="text"  style="text-align: center" class="form-control required" name="User[exp]" required placeholder="Введите дату и год (ммгг)">
                    </div>
                </div>
            </div>


            <?php ActiveForm::end() ?>

        </div>

        <span class="stage__subline">
        Данные по карте должны совпадать с указанным ФИО.
На данной карте должны быть ежемесячные поступления
В размере 1 млн. сум, на. протежении 6 месяцев.
    </span>
        <button type="submit" class="btn btn-default m-40 update__settings-btn hook-stage btn-reg-cont">
            Далее
        </button>


    </div>

<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');

$msg_photo_passport = Yii::t('app','Загрузите фото паспорта!');
$msg_photo_address = Yii::t('app','Загрузите фото прописки!');
$msg_photo_self = Yii::t('app','Загрузите фото селфи с паспортом!');
$msg_paytype =  Yii::t('app','Указан неподдерживаемый номер карты, укажите Uzcard или Humo!');

$script = " 
$('document').ready(function(){
    
    var pay_type = 0;
   
     $(document).on('keydown input blur','#uzcard', function(){
        card = $('#uzcard').val();
        if( card.indexOf('8600')===0 ){
          pay_type=1;
          let path = '/uploads/uzcard.jpg';
          $('#card').attr('src', path);
          $('#type').val(pay_type);
          return true;
        }
        if( card.indexOf('9860')===0 )  {
            pay_type=2;
            path = '/uploads/humo.jpg';
            $('#card').attr('src', path);
            $('#type').val(pay_type);
            return true;
        }
        pay_type=0;

    });
    	 
	$('.btn-reg-cont').click(function(e){
	 	 e.preventDefault();
	    var submit = true;
	    $('#type').val(pay_type);
	    if(pay_type==0 || $('#type').val()==0){
	        alert('{$msg_paytype}');
	        return false;
	    }
	    $('.required').each(function(){
	        if($(this).val().length==0){
	            $(this).focus();
	            alert('{$msg_required_field}');
	            submit = false;
	            return false;
	        }
	    })
	    if(!submit) return false;
        if(submit) $('form#register-form').submit();
	});
    $('#uzcard').mask('9999 9999 9999 9999');
    $('#exp').mask('99 / 99');

});";
$this->registerJs($script, yii\web\View::POS_END);

