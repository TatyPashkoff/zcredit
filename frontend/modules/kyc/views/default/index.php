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

        <?= $this->render('_menu',['active'=>'main']) ?>


        <div class="title-with-border"><?=Yii::t('app','KYC - база клиентов')?></div>

        <div class="col-md-12">
            <div class="col-sm-6">
                <label><?=Yii::t('app','Фильтр')?> </label>
                <select id="filter_clients" class="form-control">
                    <option value="0" <?=$filter_type==0 ? 'selected':'' ?>><?=Yii::t('app','Все')?></option>
                    <option value="1" <?=$filter_type==1 ? 'selected':'' ?>><?=Yii::t('app','Подтвержденные')?></option>
                    <option value="2" <?=$filter_type==2 ? 'selected':'' ?>><?=Yii::t('app','Не подтвержденные')?></option>
                    <option value="3" <?=$filter_type==3 ? 'selected':'' ?>><?=Yii::t('app','По дате обновления')?></option>
                    <!--<option value="3" <?/*=$filter_type==4 ? 'selected':'' */?>><?/*=Yii::t('app','За сегодня')*/?></option>-->
                </select>
                <label><?=Yii::t('app','ID ')?> </label>
                <input type="text" id="filter_id" placeholder="Поиск по ID " class="form-control" >
               <!-- <input type="text" id="pnfl" placeholder="Поиск по pnfl " class="form-control" >-->
            </div>


        </div>

        <div id="table_block">
            <table class="table">
                <thead>
                <tr>
                    <th><?=Yii::t('app','Дата заявки') ?></th>
                    <th><?=Yii::t('app','ID клиента') ?></th>
                    <th><?=Yii::t('app','ФИО Клиента') ?></th>
                    <th><?=Yii::t('app','Номер паспорта клиента') ?></th>
                    <?php /* <th><?=Yii::t('app','Документы клиента') ?></th> */ ?>
                    <th title="Верификация uzcard"><?=Yii::t('app','Вериф. uzcard') ?></th>
                    <th><?=Yii::t('app','Телефон') ?></th>
                    <th title="Просрочки платежей"><?=Yii::t('app','Проср. плат') ?></th>
                    <th title="Кредитный лимит в месяц, сум"><?=Yii::t('app','Кред. лим. в мес, сум') ?></th>
                    <th title="Кредитный лимит в год, сум"><?=Yii::t('app','Кред. лим. в год, сум') ?></th>
                    <th title="Заработная плата в суммах"><?=Yii::t('app','Зараб. пл, сум') ?></th>
                    <th title="ИНН"><?=Yii::t('app','ИНН') ?></th>
                    <th><?=Yii::t('app','Статус') ?></th>
                    <th title="Заполнение документов"><?=Yii::t('app','Зап док') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php if($model_kyc) {

                    foreach ($model_kyc as $item) {

                        switch ($item->client->status_client_complete) {
                            case 1:
                                $complete = "Тел подтвержден";
                                $class_text_complete = "text-danger";
                                break;
                            case 2:
                                $complete = "Карта";
                                $class_text_complete = "text-success";
                                break;
                            case 3:
                                $complete = "Паспорт";
                                $class_text_complete = "text-danger";
                                break;
                            case 4:
                                $complete = "Завершено";
                                $class_text_complete = "text-success";
                                break;
                            default:
                                $complete = 'Не завершено';
                                $class_text_complete = "text-danger";
                        }



                        ?>
                        <tr>
                            <td><?=date('d.m.Y',$item->created_at)?></td>
                            <td><?=@$item->client->id ?></td>
                            <td><?=@$item->client->username  . ' '. @$item->client->lastname ?></td>
                            <td><?=@$item->client->passport_serial . ' ' . @$item->client->passport_id ?></td>
                            <?php /*<td><a href="/get-documents?id=<?=$item->client_id ?>"><?=Yii::t('app','Скачать документы клиента')?></a></td> */ ?>
                            <td><?=$item->status_verify ? Yii::t('app','Да'): Yii::t('app','Нет') ?></td>
                            <td><?=@$item->client->phone ?></td>
                            <td><?=$item->delay ? Yii::t('app','Есть') : Yii::t('app','Нет') ?></td>
                            <td><?=number_format($item->credit_month,2,'.',' ')?></td>
                            <td><?=number_format($item->credit_year,2,'.',' ')?></td>
                            <td><?=number_format($item->salary,2,'.',' ')?></td>
                            <td><?=$item->client->inn ?></td>
                            <td><?=$item->status ? Yii::t('app','Подтверж.'): Yii::t('app','Не подтвер.') ?></td>
                            <td class="<?=$class_text_complete ?>"><?=$complete ?></td>
                            <td><a href="/kyc/edit?id=<?=$item->id ?>" title="<?=Yii::t('app','Изменить')?>"><i class="fa fa-address-card-o"></i></a></td>
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
$msg_server_error = Yii::t('app','Ошибка сервера!');

$script = " 
$('document').ready(function(){
   
   $('#filter_clients').change(function(){        
        type = $(this).val();
        $.ajax({
            type: 'post',
            url: '/kyc/index',
            data: 'type='+type+'&_csrf=' + yii.getCsrfToken(),
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
   
   var timerId;
        $('#filter_id').bind('input',function(e){
             clearTimeout(timerId);
             user_id = $(filter_id).val();
             timerId = setTimeout(function() {
                    $.ajax({ 
                        type: 'post',
                        url: '/kyc/index',
                        data: 'user_id='+user_id+'&_csrf=' + yii.getCsrfToken(),
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
                 
             },2);
        }); 
        var timerId2;
        $('#pnfl').bind('input',function(e){
             clearTimeout(timerId2);
             pnfl = $(pnfl).val();
             timerId2 = setTimeout(function() {
                    $.ajax({ 
                        type: 'post',
                        url: '/kyc/index',
                        data: 'user_id='+pnfl+'&_csrf=' + yii.getCsrfToken(),
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
                 
             },2);
        });        
     
     setTimeout(function() {location.reload()}, 600000)
     
});";
$this->registerJs($script, yii\web\View::POS_END);