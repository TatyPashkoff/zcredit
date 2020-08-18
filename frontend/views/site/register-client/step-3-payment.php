<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

?>
    <div class="container" style="text-align: center">
        <a href="/"><img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;"></a>
    </div>
    <div class="container">

        <div class="update__settings-container" style="margin: 10px 0px;">

            <div class="flex-parent">
                <div class="input-flex-container" style="margin-top: 0px !important;">
                    <div class="input active">
                        <span class="hook-fl-mob" data-year="Платежная информация"></span>
                    </div>
                    <div class="input">
                        <span class="hook-fl-pass" data-year="Паспортные данные"></span>
                    </div>
                    <div class="input">
                        <span data-year="Платежная информация"></span>
                    </div>
                </div>
            </div>

            <span class="stage__subline" style="margin-top: 10px;">
            *Данные по карте должны совпадать с указанным ФИО. <br>
    На данной карте должны быть ежемесячные поступления
    В размере 1 млн. сум, на  протяжении 6 месяцев.
        </span>


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
                <!-- <div class="input-flex-container">
                    <div class="form-group col-sm-12 ">
                    <img id="card" style="display: block; margin: 0 auto;">
                    </div>
                </div> -->
                <div class="input-flex-container" style="margin-top: 0px !important;">
                    <div class="form-group col-sm-12 ">
                        <input type="text"  style="text-align: center" class="form-control required" name="User[uzcard]" id="uzcard" required placeholder="Введите номер карты">
                    </div>
                </div>
                <div class="input-flex-container" style="margin-top: 0px; height:auto !important;">
                    <div class="form-group col-sm-12 ">
                        <input  type="text"  style="text-align: center" class="form-control required" name="User[exp]" id="exp" required placeholder="Введите месяц и год (ммгг)">
                    </div>
                </div>
            </div>

            <!--<div class="form-group" style="width: 55%;margin:0 auto;">
                <div class="row">
                    <div class="col-sm-7">
                        <input style="width: 100%;" type="text" class="form-control hook-st-form required" name="User[uzcard]" id="uzcard" required placeholder="Введите номер карты">
                    </div>
                    <div class="col-sm-5">
                        <input style="width: 100%;" type="text" class="form-control hook-st-form required" name="User[exp]" required placeholder="Введите дату и год (ммгг)">
                    </div>
                </div>

            </div>-->

            <?php ActiveForm::end() ?>

        </div>

        <button type="submit" class="btn btn-default m-40 update__settings-btn hook-stage btn-reg-cont">
            Далее
        </button>
    </div>

            <div class="partners-container" style="padding-top: 0px; height:auto !important;">
              <div class="row" style="justify-content:center;">
                <div class="col-md-4">
                  <div class="jumbotron">
                    <p>
                    <b>Мы очень серезно относимся к информационной безопасности.</b><br />
                    Все данные передаются в зашифрованном криптографическом виде и не доступны третьим лицам. Данные хранятся в безопасности.
                    </p>
                    <p>
                      <a id="modal-675517" href="#modal-container-675517" role="button" class="btn btn-primary btn-large" data-toggle="modal">Подробно</a>

                    </p>
                  </div>

                  <div class="modal fade" id="modal-container-675517" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="myModalLabel">
                            Политика компании в отношении кибербезопасности
                          </h5>
                          <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <!-- <p>
                            <img alt="" src="https://media.flaticon.com/dist/min/img/flaticon-logo.svg" style="height:103px; width:561px">
                          </p> -->
                          Компания ООО 'Zaamin Market' сотрудничает с ведущими международными и внутренними компаниями в сфере кибер безопасности.
                          <br>
                          <br>
                          <h3>Наши процессинговые сервис партнеры</h3>
                          <br />
                           <img alt="" src="/images/icon/logo_uzcard.png" style="height:90px; width:auto">
                           <img alt="" src="/images/icon/logo_humo.jpg" style="height:90px; width:auto">
                           <br>
                           <br>
                          Все данные между процессинговыми центрами проходят через защищенные каналы связи, а так же в зашифрованном криптографическом виде. Данные пластиковых банковских карт хранятся на серверах процессинговых центров. По полученным данным от процессинговых центров, формируется анализ платежеспособности (скоринг) пользователя сервиса "ZMARKET", а так же формируется услуга рекуррентного (автоматического) платежа. Рекуррентные платежи осуществляются в размере и сроки согласно графика платежей за Товар или услугу до полного погашения оплаты за приобретаемый Товар.     <br>
                        <br>
                        <h3>Передача данных</h3>
                        <br />
                        Данные пользователей передаются по зашифрованному соединению SLL. Передача данных с помощью SSL соединения направленно на защиту всех проводимых транзакций и предотвращения нежелательного доступа к информации. SSL соединение - это надежный инструмент, чтобы гарантировать своему ресурсу юридическую и сетевую безопасность при работе с персональными и транзакционными данными.
                        <br>
                         <img alt="" src="/images/icon/ssl-image.jpg" style="height:90px; width:auto">
                         <br>
                         <br>
                        </div>
                        <div class="modal-footer">

                          <!-- <button type="button" class="btn btn-primary">
                            Save changes
                          </button> -->
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Закрыть
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
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
