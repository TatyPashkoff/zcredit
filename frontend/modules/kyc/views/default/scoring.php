<?php

use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

    </style>
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'scoring']) ?>


        <div class="title-with-border"><?=Yii::t('app','История запросов скоринга')?></div>

        <div class="col-md-12">
            <div class="col-sm-6">
                    <label><?=Yii::t('app','Фильтр') . ' ' . $filter_type ?> </label>
                    <select id="filter_scoring" class="form-control">
                        <option value="0" <?=$filter_type==0 ? 'selected':'' ?>><?=Yii::t('app','Все')?></option>
                        <option value="1" <?=$filter_type==1 ? 'selected':'' ?>><?=Yii::t('app','Успешные')?></option>
                        <option value="2" <?=$filter_type==2 ? 'selected':'' ?>><?=Yii::t('app','Не успешные')?></option>
                    </select>
            </div>


        </div>

        <div id="table_block">
        <table class="table">
            <thead>
            <tr>
                <th><?=Yii::t('app','Дата запроса') ?></th>
                <th><?=Yii::t('app','Статус') ?></th>
                <th><?=Yii::t('app','Описание') ?></th>

            </tr>
            </thead>
            <tbody>

            <?php if($model_scoring) {

                foreach ($model_scoring as $item) {
                    ?>
                    <tr>
                        <td><?=date('d.m.Y / H:i',$item->created_at)?></td>
                        <td><?=$item->status ? Yii::t('app','Успешно') : Yii::t('app','Не успешно') ?></td>
                        <td><?=$item->status ? 'OK' : $item->info ?></td>
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

    </div>
<?php
$msg_server_error =  Yii::t('app','Ошибка сервера');

$script = " 
$('document').ready(function(){
   $('#filter_scoring').change(function(){        
        type = $(this).val();
        $.ajax({
            type: 'post',
            url: '/kyc/scoring',
            data: 'type='+type+'&&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   $('#table_block').html(data.html);
                } 
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
   })
	 
});";
$this->registerJs($script, yii\web\View::POS_END);