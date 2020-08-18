<?php

use common\models\User;

$user_id = Yii::$app->user->id;

?>

<style>
	.menu{
		margin:0px 15px 50px 15px;		
		
	}
	.menu li{
		float:left;		
		margin:5px;
	}
	.menu li:last-child{
		float:right !important;
	}
	
	.menu-item{
		color:#000 !important;
		background:#24f3af;
		border-radius:5px;
		padding:10px;
		cursor:pointer;
		
	}
	.menu-item:hover{
		background-color:#ebf8c3;
	}

	.menu-item.active{
		background-color:#ebf8c3;
	}

    #notify-count{
        position: relative;
        display: inline-block;
        top: -20px;
        width:22px;
        height: 22px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        background: #fff;
        // border:2px solid #fff;

    }

    .title{
        color:#fff;
    }
	
</style>



<div class="row">

			<ul class="menu">
            <?php /*<li>
                <a href="/clients" class="menu-item <?=$active=="main" ? 'active': '' ?>"><?=Yii::t('app','Общие')?></a>
            </li> */ ?>

            <li>
                <a href="/clients/credit-history" class="menu-item <?=$active=="credit_history" ? 'active': '' ?>"><?=Yii::t('app','Мои кредиты')?></a>
            </li>  
		    <?php /* <li>
                <a href="/clients/credit-plan" class="menu-item <?=$active=="credit_plan" ? 'active': '' ?>"><?=Yii::t('app','График оплат')?></a>
            </li> */ ?>
			<?php /* <li>
                <a href="/clients/notify" class="menu-item <?=$active=="notify" ? 'active': '' ?>"><?=Yii::t('app','Уведомления')?> <span id="notify-count">3</span></a>
            </li>
			<li>
                <a href="/clients/payments" class="menu-item <?=$active=="payments" ? 'active': '' ?>"><?=Yii::t('app','Оплата')?></a>
            </li>
            */ ?>
			<li>
                <a href="/clients/settings" class="menu-item <?=$active=="settings" ? 'active': '' ?>"><?=Yii::t('app','Настройки')?></a>
            </li>

			<li>
                <a href="/logout" class="menu-item"><?=Yii::t('app','Выйти')?></a>
            </li>

            <li style="float: right">
                <span class="title">
                ID: <?=Yii::$app->user->id . ' ' . Yii::t('app','Баланс') .': ' . User::getBalance(Yii::$app->user->id)  . ' ' . Yii::t('app','сум')?>
                </span>
            </li>
			
			</ul>

        </div>


<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$script = "$('document').ready(function(){
	
	var timerId;
	var seconds = 10 * 1000; // время опроса в секундах
    
    var user_id = '{$user_id}'; // id пользователя
    
    function startTimer(){
        timerId = setInterval(notify, seconds);        
    }
    
    function notify(){       
        $.ajax({
            type: 'post',
            url: '/clients/get-notify',
            data: 'id='+user_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   if(data.count>0){ 
                        $('#notify-count').text(data.count);
                        $('#notify-count').fadeIn();
                   }else{
                        $('#notify-count').hide();
                        $('#notify-count').text('');
                   }                   
                } 
                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
    }
    
    function stopTimer(){
        clearInterval(timerId);
    }
    
    //startTimer(); 
	
});";

$this->registerJs($script, yii\web\View::POS_END);
