<?php

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

			<ul class="update__header__menu">
            <?php /*<li>
                <a href="/kyc" class="update__header__menu-item <?=$active=="main" ? 'active': '' ?>"><?=Yii::t('app','Клиенты')?></a>
            </li> */ ?>
			<li>
                <a href="/kyc" class="update__header__menu-item <?=$active=="scoring" ? 'active': '' ?>"><?=Yii::t('app','Клиенты')?></a>
            </li>

            <li>
                <a href="/kyc/scoring" class="update__header__menu-item <?=$active=="scoring" ? 'active': '' ?>"><?=Yii::t('app','Скоринг')?></a>
            </li>

            <li>
                <a href="/kyc/contracts" class="update__header__menu-item <?=$active=="contracts" ? 'active': '' ?>"><?=Yii::t('app','Договора')?></a>
            </li>

            <li>
                <a href="/kyc/polises" class="update__header__menu-item <?=$active=="polises" ? 'active': '' ?>"><?=Yii::t('app','Полисы')?></a>
            </li>



			
			</ul>

        </div>




<?php

$script = "$('document').ready(function(){
	
	var timerId;
	var seconds = 10 * 1000; // время опроса в секундах
    
    var user_id = '{$user_id}'; // id компании
    
    function startTimer(){
        timerId = setInterval(notify, seconds);        
    }
    
    function notify(){       
        $.ajax({
            type: 'post',
            url: '/suppliers/get-notify',
            data: 'id='+user_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                console.log(data.status + ' ' + user_id + ' ' + data.count);
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
               alert('Ошибка сервера')
            }
    
         });
    }
    
    function stopTimer(){
        clearInterval(timerId);
    }
    
   // startTimer(); // запуск нотификаций с сервера
	
});";

$this->registerJs($script, yii\web\View::POS_END);
