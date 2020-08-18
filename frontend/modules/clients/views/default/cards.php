<?php

use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label {
            color: #fff;
        }

    </style>

<?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

        <!--<div class="update__client-container">
            <div class="update__client-title">
                <? /*= Yii::t('app', 'Мои карты') */ ?>
            </div>
        </div>-->
        <div class="update__client-title h2"><?= Yii::t('app', 'Ведутся технические работы') ?></div>
        <!-- old: row mb-40 products-block -->

       <!-- <div class="row mb-40">
    <div class="col-4">
                <span class="update__settings-preview__label">
                            Основная карта
               </span>
        <div>
            <?php /*if ($model->auto_discard_type == 1) : */?>
                <img alt="" src="/images/icon/logo_uzcard.png" style="height:90px; width:auto">
            <?php /*endif */?>
            <?php /*if ($model->auto_discard_type == 2) : */?>
                <img alt="" src="/images/icon/logo_humo.jpg" style="height:90px; width:auto">
            <?php /*endif */?>
        </div>

        <div>
            <br>
            <?/*= $model->scoring->pan */?>
        </div>
    </div>
</div>

<?php
/*
if ($cards_add = \common\models\CardsAdd::find()->where(['user_id' => $model->id])->All()) {
    foreach ($cards_add as $card) {
        if ($scoring = \common\models\Scoring::find()->where(['cards_add_id' => $card->id])->one()) { */?>
            <div class="row mb-40">
                <div class="col-4">
                    <div>
                        <?php /*if ($card->type == 1) : */?>
                            <img alt="" src="/images/icon/logo_uzcard.png" style="height:90px; width:auto">
                        <?php /*endif */?>
                        <?php /*if ($card->type == 2) : */?>
                            <img alt="" src="/images/icon/logo_humo.jpg" style="height:90px; width:auto">
                        <?php /*endif */?>
                    </div>

                    <div>
                        <br>
                        <?php /*$humo = '9860' . $scoring->bank_c . $scoring->card_h */?>
                        <?php /*$humo = substr_replace($humo, '******', -10, 6); */?>
                        <?php /*$card = $scoring->pan ? $scoring->pan : $humo */?>
                        <?/*= $card; */?>
                    </div>
                </div>
            </div>

            <?php
/*
        }
    }
}


*/?>

    <br>

    <div id='add_card_' style="display: none;">
        <div class="input-flex-container">
            <div class="form-group col-sm-12 ">
                <input type="text" style="text-align: center" class="form-control required" name="User[uzcard]"
                       id="uzcard" required placeholder="номер карты">
            </div>
        </div>
        <div class="input-flex-container" style="margin-top: 0px; height:auto !important;">
            <div class="form-group col-sm-12 ">
                <input type="text" style="text-align: center" class="form-control required" name="User[exp]"
                       id="exp" required placeholder="месяц и год (ммгг)">
            </div>
        </div>
        <div class="input-flex-container">
            <div class="form-group col-sm-12 ">
                <input type="text" style="display: none; text-align: center" class="form-control required_sms"
                       name="User[sms_code]"
                       id="sms_code" required placeholder="смс код">
            </div>
        </div>

        <div class="btn btn-default btn-send-sms"><?/*= Yii::t('app', 'Отправить смс') */?></div>
        <div class="btn btn-default send-card" style="display: none;"><?/*= Yii::t('app', 'Потвердить') */?></div>


    </div>
    <br><br>
    <div class="col-sm-4">
        <div class="update__settings-item">
            <div class="update-container-bottom" id="add_card">
                <label class="file-type load-image"><span><i class="fa fa-plus"></i></span></label>
                <span class="update__settings-preview__label"> Добавить карту</span>
            </div>
        </div>
    </div>
    </div>-->

<?php

$msg_server_error = Yii::t('app', 'Ошибка сервера!');
$msg_required_field = Yii::t('app', 'Необходимо заполнить данное поле!');
$msg_paytype = Yii::t('app', 'Указан неподдерживаемый номер карты, укажите Uzcard или Humo!');
$script = " 
$('document').ready(function(){ 
id = {$model->id};

    $('#add_card').click(function(){    
	    $('#add_card_').fadeIn();
	    $('#add_card').fadeOut();
	 }) 
 
 
    var pay_type = 0;

      $(document).on('keydown input blur','#uzcard', function(){
        card = $('#uzcard').val();
        if( card.indexOf('8600')===0 ){
          pay_type=1;         
          $('#type').val(pay_type);
          return true;
        }
        if( card.indexOf('9860')===0 )  {
            pay_type=2;           
            $('#type').val(pay_type);
            return true;
        }
        pay_type=0;
    });
    
    $('.btn-send-sms').click(function(e){
	 	 e.preventDefault();
	 	card = $('#uzcard').val();
	    exp = $('#exp').val();
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
	     $.ajax({
            type: 'post',
            url: '/clients/register-card',
            data: 'id='+id+'&card='+card+'&exp='+exp+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){                 
                    card_id = data.card_id;
                    $('.btn-send-sms').fadeOut();
	                $('.send-card').fadeIn();
	                $('#sms_code').fadeIn();    
                }                
                //console.log(data);  
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        });  
	  
	});
	
	$('.send-card').click(function(e){	
	 	 e.preventDefault();
	 	 $('.required_sms').each(function(){
	        if($(this).val().length==0){
	            $(this).focus();
	            alert('{$msg_required_field}');
	            submit = false;
	            return false;
	        }
	    })	
	    
	 	code = $('#sms_code').val();	 	
	                $.ajax({
                        type: 'post',
                        url: '/clients/sms-confirm',
                        data: 'id='+id+'&code='+code+'&card_id='+card_id+'&_csrf=' + yii.getCsrfToken(),
                        dataType: 'json',
                        success: function(data){
                        if(data.status){                  
                                // отобразить карту в строке и плюсик
                                console.log(data);
                            }
                            //alert(data.info);
                            console.log(data);  
                        },
                        error: function(data){
                           alert('{$msg_server_error}');
                           console.log(data);  
                        }
                    });  
	
	});     
	
    $('#uzcard').mask('9999 9999 9999 9999');
    $('#exp').mask('99 / 99'); 
		 	 
});";
$this->registerJs($script, yii\web\View::POS_END);