<?php
use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

    </style>

    <?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

        <div class="update__contract-container">
            <div class="update__score-header">
                <h2 class="update__score-headline update__client-title">
                    <?=Yii::t('app','Договора')?>
                </h2>
            </div>

            <div class="update__contract-container__block">

                <?php
                if($contracts) {
                    foreach ($contracts as $contract) {
                        if(!isset($contract->credit)) continue;
                        $credit = $contract->credit;
                        ?>
                        <div class="update-contract__ctr">
                            <div class="update__contract-item__head">

                                <div class="update__contract-item__wrapper">

                                    <h3 class="update__contract-item__headline update__contract__hh">
                                        <?= Yii::t('app', 'Договор') ?> <?= $contract->id ?>
                                    </h3>
                                    <span class="update__contract-item__subline">
                                        <?= Yii::t('app', 'Дата договора') ?>
                                        : <?= date('d.m.Y', $contract->created_at) ?>
                                    </span>
                                </div>

								<div class="contract-list__status-container">
									<span  style=" padding-left: 25px; width:50%; float:left;margin:0;" class="contract-list__status">
										Статус погашения
										<?php if($contract->status){ ?>
											<i class="fa fa-check"></i>
										<?php }else{ ?>
											<span class="update__contract__status-icon" style="background-color: <?= $contract->status == 1 ? '#6EBD8F' : '#FF676D' ?>;"><i class="fa fa-minus" aria-hidden="true"></i></span>
										<?php } ?>
									</span>
										<span style="padding-left: 25px; width:50%;float:left; margin:0;" class="contract-list__status">
										Статус договора
										<?php /*if($contract->status){ */?><!--
											<i class="fa fa-check"></i>
										<?php /*}else{ */?>
											<span class="update__contract__status-icon" style="background-color: <?/*= $contract->status == 1 ? '#6EBD8F' : '#FF676D' */?>;"><i class="fa fa-minus" aria-hidden="true"></i></span>
										--><?php /*} */?>
											<?=$class_i =  ($contract->credit->confirm == 1)
												? '<i class="fa fa-check" style="margin-top:5px;" aria-hidden="true"></i>'
												: '<span class="update__contract__status-icon" style="background-color:#FF676D"><i class="fa fa-minus" aria-hidden="true"></i></span>'; ?>

									</span>
								</div>
                            </div>

                            <div class="update-contract__wrapper" id="contract_mobile_wrapper">

                                <div class="update__contract-item">
                                    <h2 class="update__main__item-title update__score-content__headline">
                                        <?= Yii::t('app', 'Срок договора') ?>
                                    </h2>
                                    <span class="update__score-content__subline">
                            <?= $credit->credit_limit ?> <?= Yii::t('app', 'мес') ?>.
                        </span>
                                </div>

                                <div class="update__contract-item">
                                    <h2 class="update__main__item-title update__score-content__headline">
                                        <?= Yii::t('app', 'Сумма договора') ?>
                                    </h2>
                                    <span class="update__score-content__subline">
                            <?= number_format($credit->price, 2, '.', ' ') ?>
                        </span>
                                </div>

                                <div class="update__contract-item">
                                    <h2 class="update__main__item-title update__score-content__headline">
                                        <?= Yii::t('app', 'Остаток по рассрочке') ?>
                                    </h2>
                                    <span class="update__score-content__subline">
                            <?= number_format($credit->credit, 2, '.', ' ') ?>
                        </span>
                                </div>


                                <div class="update__contract-item">
                                    <h2 class="update__main__item-title update__score-content__headline">
                                        <?= Yii::t('app', 'Дата погашения') ?>
                                    </h2>
                                    <span class="update__score-content__subline">
                            <?= date('d.m.Y', $contract->date_end) ?>
                        </span>
                                </div>

                                <div class="update__contract-item update__contract__btn-container">
                                    <!-- <button style="display:block;" class="btn btn-default m-40 update__settings-btn update__contract-btn print-act"
                                            style="margin-bottom: 20px;" data-credit_id="<?=$credit->id ?>">
                                        <?= Yii::t('app', 'Акт') ?>
                                    </button> -->

                                    <a class="btn btn-default m-40 update__settings-btn update__contract-btn" href="/get-offer?id=<?=$credit->id ?>" target="_blank">
                                        Оферта
                                    </a>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                } ?>

            </div>

        </div>


        <div class="pagination update__pagination">
            <?= LinkPager::widget([
                'pagination' => $pagination,
            ]);  ?>
        </div>

        </div>

<?php
$msg_print_act = Yii::t('app','Распечатать АКТ');
$print_act_url = '/print-act';

$script = " 
$('document').ready(function(){  
	 $('.print-act').click(function(){
    	   	    
	    var formData = new FormData();
	    formData.append('_csrf' , yii.getCsrfToken());
	    var params = 'menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes'
        window.open('{$print_act_url}'+'?id=' + $(this).data('credit_id'), '{$msg_print_act}', params)
	  	  	    
	    return false;	       
	 })
});";
$this->registerJs($script, yii\web\View::POS_END);