<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#fff;
        }
        .title{
            text-align: center;
            width: 100%;
            color: #fff;
        }
        .form-horizontal .control-label {
            text-align: center !important;
            margin-bottom: 10px;
        }

    </style>
 	<?= $this->render('_header') ?>

    <?= $this->render('_menu',['active'=>'orders']) ?>

    <?php
    $form = ActiveForm::begin(
        [
            'id' => 'edit-form',
            'action' =>'/kyc/edit?id=' . $model->id,
            //'enableClientValidation' => false,
            //'enableAjaxValidation' => false,
            'options' => [
                'class' => 'form-horizontal',
                //'enctype' => 'multipart/form-data',
            ]

        ]);

    ?>
    <div class="pad-container">
        <div class="row mb-60px">
            <div class="offset-sm-1 col-sm-10">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="black-container">
                            <h5><?=Yii::t('app','Информация о клиенте и поставщике')?></h5>
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата создания')?></label>
                                        <input value="<?=date('d.m.Y',$model->created_at) ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6"></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Серия паспорта')?></label>
                                        <input value="<?=$model->client->passport_serial ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Поставщик')?></label>
                                        <input value="<?=$model->supplier->company ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Имя Фамилия заемщика')?></label>
                                        <input value="<?=$model->client->username . ' ' . $model->client->lastname ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ID поставщика')?></label>
                                        <input value="<?=$model->supplier->id ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ID клиента')?></label>
                                        <input value="<?=$model->client->id ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Телефон поставщика')?></label>
                                        <input value="<?=$model->supplier->phone ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Телефон клиента')?></label>
                                        <input value="<?=$model->client->phone ?>" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'salary')->textInput(['maxlength' => true,'type'=>'numeric']) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <a href="/get-documents?id=<?=$model->client->id ?>" class="btn btn-default" download=""><?=Yii::t('app','Скачать документы') ?></a>
                                    </div>
                                </div>
                                <?php if(isset($model->credit)){ ?>
                                    <div class="col-6"><a href="/print-act?id=<?=$model->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать акт') ?></a></div>
                                    <div class="col-6"><a href="/print-invoice?id=<?=$model->credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать счет-фактуру') ?></a></div>
                                    <div class="col-6"><a href="/print-graph?id=<?=$model->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать график оплаты') ?></a></div>
                                <?php } ?>

                            </div><!--row-->
                        </div>
                    </div><!--col-sm-6-->
                    <div class="col-sm-6">
                        <div class="black-container">
                            <h5>Информация KYC отдела</h5>
                            <div class="row">


                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <?= $form->field($model, 'credit_year')->textInput(['maxlength' => true]) ?>
                                </div></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <?= $form->field($model, 'credit_month')->textInput(['maxlength' => true]) ?>
                                </div></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <?= $form->field($model, 'credit_rating')->textInput(['maxlength' => true]) ?>
                                </div></div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Просрочка')?></label>
                                        <select name="Kyc[delay]" class="form-control">
                                            <option value="0" <?=$model->delay == 0 ? 'selected' : '' ?>><?=Yii::t('app','Нет')?></option>
                                            <option value="1" <?=$model->delay == 1 ? 'selected' : '' ?>><?=Yii::t('app','Есть')?></option>
                                        </select>
                                    </div></div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата верификации uzcard')?></label>
                                        <input type="text" class="form-control" name="date_verify" value="<?=$model->date_verify >0 ? date('Y-m-d',$model->date_verify) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= $form->field($user, 'summ')->textInput(['maxlength' => true,'type'=>'numeric' ]) ?>

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Статус верификации uzcard')?></label>
                                        <select name="Kyc[status_verify]" class="form-control">
                                            <option value="0" <?=$model->status_verify == 0 ? 'selected' : '' ?>><?=Yii::t('app','Не подтвержден')?></option>
                                            <option value="1" <?=$model->status_verify == 1 ? 'selected' : '' ?>><?=Yii::t('app','Подтвержден')?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Статус подтверждения')?></label>
                                        <select name="Kyc[status]" class="form-control">
                                            <option value="0" <?=$model->status == 0 ? 'selected' : '' ?>><?=Yii::t('app','Не подтвержден')?></option>
                                            <option value="1" <?=$model->status == 1 ? 'selected' : '' ?>><?=Yii::t('app','Подтвержден')?></option>
                                        </select>
                                    </div>
                                </div>



                            </div><!--row-->
                        </div>
                    </div><!--col-sm-6-->
                </div><!--row-->
            </div><!--col-sm-8-->
        </div><!--row-->
    </div>

    <button type="submit" class="btn btn-default mb-40"><?=Yii::t('app','Сохранить') ?></button>


<?php ActiveForm::end() ?>




<?php

$script = " 
$('document').ready(function(){
	


});";
$this->registerJs($script, yii\web\View::POS_END);
