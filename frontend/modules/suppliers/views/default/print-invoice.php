<?php

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#fff;
        }

    </style>
	
	print invoice




<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$script = " 
$('document').ready(function(){
    window.print();
   window.close();
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
