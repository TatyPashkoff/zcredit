<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }


    </style>
	
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'notify']) ?>
	
        

			<h4 class="mb-35px">Уведомления</h4>
              <table class="table">
                <thead>
                <tr>
                  <th>Дата</th>
                  <th>ID клиента</th>
                  <th>ФИО Клиента</th>
                  <th>Номер телефона</th>
                  <th>Событие</th>
                  <th>Описание</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>12.09.2019</td>
                  <td>22</td>
                  <td>Вася Пупкин</td>
                  <td>99890221335</td>
                  <td>Оплата кредита</td>
                  <td class="yellow">Оплата кредита за май месяц</td>
                </tr>
               
                </tbody>
              </table>

    </div>




<?php

$script = " 
$('document').ready(function(){
   
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
