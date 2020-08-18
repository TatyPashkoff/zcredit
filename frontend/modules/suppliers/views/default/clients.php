<?php

use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }


        .circle{
            border-radius: 50%;
            border: 4px solid #fff; ;
            background: #0acb94;
            width:50px;
            height: 50px;
            padding: 5px 0 0 0;
            font-size: 22px;
            color: #fff;
        }
        .title-info{
            text-align: center;
            color: #0acb94;
        }


    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'clients']) ?>


        <!-- title-with-border -->
        <div class="update__client-title"><?=Yii::t('app','Клиенты')?></div>

        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','№ пп') ?></th>
                <th><?=Yii::t('app','ID клиента') ?></th>
                <th><?=Yii::t('app','ФИО Клиента') ?></th>
                <th><?=Yii::t('app','Номер паспорта клиента') ?></th>
                <th><?=Yii::t('app','Документы клиента') ?></th>
                <?php /*<th><?=Yii::t('app','Верификация uzcard') ?></th> */ ?>
                <th><?=Yii::t('app','Телефон') ?></th>
                <?php /* <th><?=Yii::t('app','Просрочки платежей') ?></th>
                <th><?=Yii::t('app','Кредитный лимит в месяц, сум') ?></th>
                <th><?=Yii::t('app','Кредитный лимит в год, сум') ?></th> */ ?>
                <th><?=Yii::t('app','Статус') ?></th>
            </tr>
            </thead>
            <tbody>

            <?php if($model_clients) {
                $npp=0;
                foreach ($model_clients as $item) {
                    $npp++;
                    ?>
                    <tr>
                        <td><?=$npp ?></td>
                        <td><?=$item->id ?></td>
                        <td><?=@$item->username  . ' '. @$item->lastname ?></td>
                        <td><?=@$item->passport_serial . ' ' . @$item->passport_id ?></td>
                        <td><a href="/get-documents?id=<?=$item->id ?>"><?=Yii::t('app','Скачать документы клиента')?></a></td>
                        <?php /*<td><?=$item->status_verify ? Yii::t('app','Да'): Yii::t('app','Нет') ?></td> */ ?>
                        <td><?=@$item->phone ?></td>
                        <?php /*  <td><?=$item->delay ? Yii::t('app','Есть') : Yii::t('app','Нет') ?></td>*/ ?>
                    <?php /*<td><?=number_format($item->credit_month,2,'.',' ')?></td> */ ?>
                        <?php /*<td><?=number_format($item->credit_year,2,'.',' ')?></td>*/ ?>
                        <?php /*<td><?=number_format($item->salary,2,'.',' ')?></td>*/ ?>
                        <?php /*<td><?=$item->credit_rating ?></td>*/ ?>
                        <td><?=$item->status ? Yii::t('app','Вкл'): Yii::t('app','Откл') ?></td>
                    </tr>
                <?php } ?>

            <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?= LinkPager::widget([
                'pagination' => $pagination,
            ]);  ?>
        </div>

    </div>




<?php

$script = " 
$('document').ready(function(){
   
	 
});";
$this->registerJs($script, yii\web\View::POS_END);