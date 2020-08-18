<?php

use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>

<style>
    .update__contract__status-icon {
        display: inline-block;
    }
    </style>

	<?= $this->render('_header') ?>
    <?= $this->render('_menu',['active'=>'contracts']) ?>


<?php if( $model_order ) {

    foreach ( $model_order as $contract ) {  ?>

        <div class="contract-list container">
            <div class="contract-list__head">

                <div class="contract-list__cred-container">
                <span class="contract-list__id">
                    ID договора N<?=$contract->id ?>
                </span>
                    <span class="contract-list__data">
                    Дата договора: <?=date('d.m.Y',$contract->created_at)?>
                </span>
                </div>

                <div class="contract-list__status-container">
                <span class="contract-list__status">
                    Статус оплаты ZMarket
                    <?php if($contract->status){ ?>
                        <i class="fa fa-check"></i>
                    <?php }else{ ?>
                        <span class="update__contract__status-icon" style="background-color: <?= $contract->status == 1 ? '#6EBD8F' : '#FF676D' ?>;"><i class="fa fa-minus" aria-hidden="true"></i></span>
                    <?php } ?>
                </span>
                    <span class="contract-list__status">
                    Статус договора
                    <?php /*if($contract->status){ */?><!--
                        <i class="fa fa-check"></i>
                    <?php /*}else{ */?>
                        <span class="update__contract__status-icon" style="background-color: <?/*= $contract->status == 1 ? '#6EBD8F' : '#FF676D' */?>;"><i class="fa fa-minus" aria-hidden="true"></i></span>
                    --><?php /*} */?>
                        <?=$class_i =  ($contract->credit->confirm == 1 && $contract->credit->user_confirm == 1)
                            ? '<i class="fa fa-check" aria-hidden="true"></i>'
                            : '<span class="update__contract__status-icon" style="background-color:#FF676D"><i class="fa fa-minus" aria-hidden="true"></i></span>'; ?>

                </span>
                </div>
            </div>

            <div class="contract-list__item-container">
                <div class="row align-items-end">
                    <div class="col-sm-2">
                        <div class="contract-list__item">
                <span class="contract-list__headline">
                    Срок рассрочки
                </span>
                            <span class="contract-list__subline">
                    <?=$contract->credit->credit_limit ?> мес.
                </span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="contract-list__item">
                <span class="contract-list__headline">
                    Сумма договора
                </span>
                            <span class="contract-list__subline">
                    <?=number_format($contract->credit->price,0,'',' ') ?>
                </span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="contract-list__item">
                <span class="contract-list__headline">
                    Товары в рассрочку
                </span>
                            <span class="contract-list__subline">
                    <?php if(isset($contract->credit) && isset($contract->credit->creditItems) ){
                        $products = '';
                        foreach ($contract->credit->creditItems as $item){
                            $products .= $item->title .'<br>';
                        }
                        echo $products;
                    }
                    ?>
                </span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="contract-list__item hook-contract__item">

                            <a class="btn btn-default check-otp hook-contract_btn" href="/print-act?id=<?=$contract->credit->id ?>" target="_blank">
                                Акт
                            </a>

                            <a class="btn btn-default check-otp hook-contract_btn" href="/suppliers/get-offer?id=<?=$contract->credit->id ?>" target="_blank">
                                Оферта
                            </a>
                        </div>
                    </div>
                </div><!--row-->





            </div>
        </div>

    <?php } ?>

    <div class="pagination">
        <?= LinkPager::widget([
            'pagination' => $pagination,
        ]);  ?>
    </div>

<?php } ?>



