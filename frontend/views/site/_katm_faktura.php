<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>

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
<div style="width:800px; margin: 10% auto 0 auto;"  class="reg-container black-bg w800 mb-30px">
<h1>KATM Сверка счет фактур</h1>
    <?php
    $form = ActiveForm::begin(
    [
    'id' => 'sendsms-form',
    'action' =>'/get-faktura',
    'options' => [
    'class' => 'form-horizontal',
    ]

    ]);?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <div class="form-group">
        <div class="col-12-sm">
            <?php

            echo '<label>Начальная дата</label>';
            echo DatePicker::widget([
                'name' => 'datestart',
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
				'name' => 'dateend',
				'options' => ['placeholder' => 'Выберите конечную дату','required'=>'true'],
				'pluginOptions' => [
					'format' => 'dd-mm-yyyy',
					'todayHighlight' => true
				]
			]);
			?>
		</div>
	</div>

</div>



<button  style="margin: 20px 0 20% 28%;" type="submit" onclick="sendAjax()" class="btn send-katm btn-success">Посмотреть число отчетов</button>

<?php  ActiveForm::end() ?>


<script type="text/javascript">
    function sendAjax () {
        let datestart = $('#w0').val();
        let dateend = $('#w1').val();
        $.ajax({
        type: 'post',
        url: '/get-faktura',
        data: 'datestart='+datestart+'&dateend='+dateend+'&_csrf=' + yii.getCsrfToken(),
        dataType: 'json',
        success: function(data){
           alert(data.info);
        },
        error: function(data){
        alert('{$msg_server_error}')
        }
        });
    }
</script>
