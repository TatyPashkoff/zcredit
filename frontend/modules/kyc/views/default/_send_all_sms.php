<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>

<?= $this->render('_header') ?>
<style>

    .datepicker-days {
        display: block !important;
    }

    .modal {
        position: absolute !important;
        left: 50% !important;
        top: 50% !important;
        z-index:9999 !important;
        transform: translate(-50%,-50%) !important;
        background:#fff important;
        max-width:100% !important;
        overflow-y: auto important;
        height:685px !important;
        flex:auto important;

    }

    .modal-backdrop.show {
        opacity:0 !important;
        display:none !important;
    }

    .modal a.close-modal {
        top: 10px !important;
        right: 10px !important;
    }

    .modal a.close-modal[class*="icon-"] {
        top: -10px;
        right: -10px;
        width: 20px;
        height: 20px;
        color: #fff;
        line-height: 1.25;
        text-align: center;
        text-decoration: none;
        text-indent: 0;
        background: #900;
        border: 2px solid #fff;
        -webkit-border-radius:  26px;
        -moz-border-radius:     26px;
        -o-border-radius:       26px;
        -ms-border-radius:      26px;
        -moz-box-shadow:    1px 1px 5px rgba(0,0,0,0.5);
        -webkit-box-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        box-shadow:         1px 1px 5px rgba(0,0,0,0.5);
    }

    .modal button {
        width:250px;
    }

    .modal a.close-modal {
        top:5px;
        right:5px;
    }

    .kv-date-calendar {
        width:50px;
    }

    .kv-date-remove {
        width: 50px;
    }

    #w0-kvdate, #w1-kvdate, #w2-kvdate {
        z-index: 0;
    }


</style>
<div class="reg-container black-bg w800 mb-30px">
    <?php  $this->render('_menu',['active'=>'main']);?>
    <?php
    $form = ActiveForm::begin(
    [
    'id' => 'sendsms-form',
    'action' =>'/kyc/send-all-sms',
    'options' => [
    'class' => 'form-horizontal',
    ]

    ]);?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <div class="form-group">
        <div class="col-12-sm">
            <?php

            echo '<label>Начальная дата</label>';
            echo DatePicker::widget([
                'name' => 'SmsMailing[datestart]',
                'options' => ['placeholder' => 'Выберите начальную дату', 'required'=>'true'],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
    </div>
        <div class="form-group">
            <div class="col-12-sm">
                <?php

                echo '<label>Конечная дата</label>';
                echo DatePicker::widget([
                    'name' => 'SmsMailing[dateend]',
                    'options' => ['placeholder' => 'Выберите конечную дату','required'=>'true'],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-12-sm">
                <?php

                echo '<label>День отправки</label>';
                echo DatePicker::widget([
                    'name' => 'SmsMailing[sendday]',
                    'options' => ['placeholder' => 'Выберите день отправки', 'required'=>'true'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]);
                ?>
            </div>
            <div class="col-12-sm">
                <label for="exampleFormControlSelect1">Час отправки</label>
                <select name="SmsMailing[sendhour]" id="send-hour" class="form-control" required='true'>
                    <option>Задать время</option>
                    <option value="17">17:00</option>
                    <option value="18">18:00</option>
                    <option value="19">19:00</option>
                    <option value="20">20:00</option>
                    <option value="21">21:00</option>
                    <option value="22">22:00</option>
                </select>
            </div>
        </div>
<!-- TO DO
    <div class="form-group">
        <label for="exampleFormControlSelect1">Выберите тип для получения</label>
        <select name="typesms-view" id="typesms-view" class="form-control">
            <option>Выберите тип</option>
            <option value="0">C телефоном</option>
            <option value="1">С паспортом</option>
            <option value="2">С картой</option>
        </select>
    </div>
-->
    <div class="form-group">
        <label for="exampleFormControlSelect1" required>Выберите тип для отправки</label>
        <select name="typesms-insert" id="typesms-insert" class="form-control" required>
            <option>Выберите тип</option>
            <option value="0">C телефоном</option>
            <option value="1">С паспортом</option>
            <option value="2">С картой</option>
        </select>
    </div>



    <div class="row" style="margin-top:30px;">
        <div class="col-6-sm" style="margin-right:20px;">
            <?php //Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="exampleFormControlSelect1">Введите текст рассылки</label>
        <textarea name="SmsMailing[msg]" cols="80" class="form-control" id="msg" rows="5" spellcheck="false"></textarea>

    </div>
</div>



<button  style="margin-top:20px;" type="submit" class="btn send-sms btn-success">Отправить пользователям рассылку</button>

<?php  ActiveForm::end() ?>

<div class="form-group">
<!-- TO DO 
<a href="#ex_one" rel="modal:open"><button style="margin-top:20px;" onclick="sendAjax()" class="btn btn-danger">Посмотреть пользователей</button></a><br />
-->
<div id="ex_one" class="modal">
    <div id="table_info">

    </div>
    <a href="#close-modal" data-toggle="offcanvas" rel="modal:close" class="close-modal "><button style="margin:2% 0 2% 0;" class="btn btn-danger">ЗАКРЫТЬ</button></a>
</div>
</div>








<script type="text/javascript">
    function sendAjax () {
        let datestart = $('#w0').val();
        let dateend = $('#w1').val();
        let typesmsView = $('#typesms-view').val();
        let typesmsInsert = $('#typesms-insert').val();
        $.ajax({
        type: 'post',
        url: '/kyc/send-all-sms',
        data: 'datestart='+datestart+'&dateend='+dateend+'&typesmsView='+typesmsView+'&typesmsInsert='+typesmsInsert+'&_csrf=' + yii.getCsrfToken(),
        dataType: 'json',
        success: function(data){
            console.log(data);
        $('#table_info').html(data.html);
        },
        error: function(data){
        alert('{$msg_server_error}')
        }
        });
    }

    $(function() {
        $('a[href="#ex_one"]').click(function (event) {
            event.preventDefault();
            $(this).modal({
                escapeClose: false,
                clickClose: false,
                showClose: false
            });
        });
    });

    $('.send-sms').click(function(){
        $('.required').each(function(){
           if($(this).val().length==0){            
                $(this).focus();
                alert('{$msg_required_field}'); 
                submit = false;
                return false;
            } 
        });
        if(!confirm('Точно отправить рассылку?')) return false;

    })

</script>
