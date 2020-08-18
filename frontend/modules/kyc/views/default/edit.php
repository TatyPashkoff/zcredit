<?php
use yii\widgets\ActiveForm;
use common\models\KatmOrder;

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#000;
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

        textarea.form-control{
            height: auto !important;
        }
        #scoring_data{
            overflow-y:auto;
            height: 200px;
        }

    </style>
 	<?= $this->render('_header') ?>

    <?= $this->render('_menu',['active'=>'main']) ?>

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
                                        <input value="<?=date('d.m.Y',$model->created_at) ?>" class="form-control" readonly >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Статус клиента')?></label>
                                        <select name="User[status]" class="form-control">
                                            <option value="2" <?=$model->client->status == 2 ? 'selected' : '' ?>><?=Yii::t('app','Заблокирован')?></option>
                                            <option value="1" <?=$model->client->status == 1 ? 'selected' : '' ?>><?=Yii::t('app','Активен')?></option>
                                        </select>
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ПНФЛ')?></label>
											<input value="<?=@$model->client->pnfl ?>" class="form-control" minlength="14" maxlength="14" name="User[pnfl]">
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ID клиента')?></label>
                                        <input id="client_id" name="User[id]" value="<?=$model->client_id ?>" class="form-control" readonly>
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Промокод')?></label>
											<input value="<?=@$model->client->promocode ?>" class="form-control" name="User[promocode]">
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Кешбэк')?></label>
											<input value="<?=@$model->client->cashback ?>" class="form-control" name="User[cashback]">
                                    </div>
                                </div>
								<div class="col-sm-6">   
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Фамилия заемщика')?></label>
                                        <input value="<?=@$model->client->lastname ?>" class="form-control" pattern="^[a-zA-Z ' `]+$" name="User[lastname]" required >
                                        <?//= $form->field($model, 'credit_year')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Имя заемщика')?></label>
                                        <input value="<?=@$model->client->username ?>" class="form-control" pattern="^[a-zA-Z ' `]+$" name="User[username]" required >
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Отчество заемщика')?></label>
                                        <input value="<?=@$model->client->patronymic ?>" class="form-control" pattern="^[a-zA-Z ' `]+$" name="User[patronymic]" required>
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата рождения')?></label>
                                        <input type="date" value="<?=@$model->client->birthday ?>" class="form-control" name="User[birthday]"  required>
                                    </div>
                                </div>
                                <?php if(isset($model->supplier)){ ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=Yii::t('app','Поставщик')?></label>
                                            <input value="<?=@$model->supplier->company ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ID поставщика')?></label>
                                        <input value="<?=$model->supplier_id ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <?php }?>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Телефон клиента')?></label>
                                        <input value="<?=@$model->client->phone ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
									<div class="form-group">
										<label><?=Yii::t('app','Домашний телефон')?></label>
										<input value="<?=@$model->client->phone_home ?>" class="form-control" readonly>
									</div>
								</div>

                                <?php if(isset($model->supplier)){ ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=Yii::t('app','Телефон поставщика')?></label>
                                            <input value="<?=@$model->supplier->phone ?>" class="form-control" readonly>
                                        </div>
                                    </div>

                                <?php }?>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label"><?=Yii::t('app','Регион')?></label>
                                        <select name="User[region_id]" id="user_region_id" class="form-control" required>
                                            <option value="0"><?=Yii::t('app','Не задан')?></option>
											<optgroup label="город Ташкент">
                                                <option value="284">Бектемирский район</option>
                                                <option value="285">Мирабадский район</option>
                                                <option value="286">Мирзо-Улугбекский район</option>
                                                <option value="287">Сабир-Рахимовский район</option>
                                                <option value="288">Сергелийский район</option>
                                                <option value="289">Учтепинский район</option>
                                                <option value="290">Яшнабадский район</option>
                                                <option value="291">Чиланзарский район</option>
                                                <option value="292">Шайхантахурский район</option>
                                                <option value="293">Юнусабадский район</option>
                                                <option value="294">Яккасарайский район</option>
                                                <option value="295">Алмазарский район</option>
                                            </optgroup>
											<optgroup label="Ташкентская область">
                                                <option value="1">г. АЛМАЛЫК</option>
                                                <option value="2">г. АНГРЕН</option>
                                                <option value="217">г. АХАНГАРАН</option>
                                                <option value="218">г. БЕКАБАД</option>
                                                <option value="219">г. ЧИРЧИК</option>
                                                <option value="220">г. ЯНГИЮЛЬ </option>
                                                <option value="221">Аккурганский район</option>
                                                <option value="222">Ахангаранский район</option>
                                                <option value="223">Бекабадский район</option>
                                                <option value="224">Букинский район</option>
                                                <option value="225">Куйичирчикский район</option>
                                                <option value="226">Зангиатинский район</option>
                                                <option value="227">Юкоричирчикский район</option>
                                                <option value="228">Кибрайский район</option>
                                                <option value="229">Паркентский район</option>
                                                <option value="230">Пскентский район</option>
                                                <option value="231">Уртачирчикский район</option>
                                                <option value="232">Ташкентский район</option>
                                                <option value="233">Янгиюльский район</option>
                                                <option value="234">Бостанлыкский район</option>
                                                <option value="235">Чиназский район</option>
                                                <option value="236">г. ЯНГИАБАД</option>
                                                <option value="237">г. АККУРГАН</option>
                                                <option value="238">г. ГАЗАЛКЕНТ</option>
                                                <option value="239">г. БУКА</option>
                                                <option value="240">г. ДУСТОБОД</option>
                                                <option value="241">г. КЕЛЕС</option>
                                                <option value="242">г. ПАРКЕНТ</option>
                                                <option value="243">г. ПСКЕНТ</option>
                                                <option value="244">г. ТОЙТЕПА</option>
                                                <option value="245">г. ЧИНАЗ</option>
                                            </optgroup>
                                            <optgroup label="Андижанская область">
                                                <option value="3">г. АНДИЖАН</option>
                                                <option value="4">г. АСАКА </option>
                                                <option value="5">Алтынкульский район</option>
                                                <option value="6">Андижанский район</option>
                                                <option value="7">Балыкчинский район</option>
                                                <option value="8">Бозский район</option>
                                                <option value="9">Булакбашинский район</option>
                                                <option value="10">Джалалкудукский район</option>
                                                <option value="11">Избасканский район</option>
                                                <option value="12">Улугноpский район</option>
                                                <option value="13">Кургантепинский район</option>
                                                <option value="14">Асакинский район</option>
                                                <option value="15">Мархаматский район</option>
                                                <option value="16">Шахриханский район</option>
                                                <option value="17">Пахтаабадский район</option>
                                                <option value="18">Ходжаабадский район</option>
                                                <option value="19">г. КАРАСУ</option>
                                                <option value="20">г. ХАНАБАД </option>
                                                <option value="21">г. ШАХРИХАН</option>
                                                <option value="22">г. АХУНБАБАЕВ</option>
                                                <option value="23">г. ПАЙТУГ</option>
                                                <option value="24">г. КУРГАНТЕПА</option>
                                                <option value="25">г. МАРХАМАТ</option>
                                                <option value="26">г. ПАХТААБАД</option>
                                                <option value="27">г. ХОДЖААБАД</option>
                                            </optgroup>
                                            <optgroup label="Бухарская область">
                                                <option value="28">г. БУХАРА</option>
                                                <option value="29">Алатский район</option>
                                                <option value="30">Бухарский район</option>
                                                <option value="31">Вабкентский район</option>
                                                <option value="32">Гиждуванский район</option>
                                                <option value="33">Каганский район</option>
                                                <option value="34">Каракульский район</option>
                                                <option value="35">Караулбазарский район</option>
                                                <option value="36">Пешкунский район</option>
                                                <option value="37">Ромитанский район</option>
                                                <option value="38">Жондоpский район</option>
                                                <option value="39">Шафирканский район</option>
                                                <option value="40">г. ГИЖДУВАН</option>
                                                <option value="41">г. КАГАН </option>
                                                <option value="42">г. АЛАТ</option>
                                                <option value="43">г. ГАЛЛААСИЯ</option>
                                                <option value="44">г. ВАБКЕНТ</option>
                                                <option value="45">г. КАРАКУЛЬ</option>
                                                <option value="46">г. КАPАУЛБАЗАP</option>
                                                <option value="47">г. РОМИТАН</option>
                                                <option value="48">г. ШАФИРКАН</option>
                                            </optgroup>
                                            <optgroup label="Джизакская область">
                                                <option value="49">г. ГАЛЛЯАРАЛ</option>
                                                <option value="50">г. ДЖИЗАК</option>
                                                <option value="51">Арнасайский район</option>
                                                <option value="52">Бахмальский район</option>
                                                <option value="53">Галляаральский район</option>
                                                <option value="54">Джизакский район</option>
                                                <option value="55">Дустликский район</option>
                                                <option value="56">Заминский район</option>
                                                <option value="57">Зарбдарский район</option>
                                                <option value="58">Мирзачульский район</option>
                                                <option value="59">Зафарабадский район</option>
                                                <option value="60">Пахтакорский район</option>
                                                <option value="61">Фаришский район</option>
                                                <option value="62">Янгиободский район</option>
                                                <option value="63">г. ДУСТЛИК</option>
                                                <option value="64">г. ГАГАРИН</option>
                                                <option value="65">г. ПАХТАКОР</option>
                                            </optgroup>
                                            <optgroup label="Кашкадарьинская область">
                                                <option value="66">г. КАРШИ</option>
                                                <option value="67">Гузарский район</option>
                                                <option value="68">Дехканабадский район</option>
                                                <option value="69">Камашинский район</option>
                                                <option value="70">Каршинский район</option>
                                                <option value="71">Касанский район</option>
                                                <option value="72">Китабский район</option>
                                                <option value="73">Миришкорский район</option>
                                                <option value="74">Мубарекский район</option>
                                                <option value="75">Нишанский район</option>
                                                <option value="76">Касбинский район</option>
                                                <option value="77">Чиракчинский район</option>
                                                <option value="78">Шахрисабзский район</option>
                                                <option value="79">Яккабагский район</option>
                                                <option value="80">Бахаристанский район</option>
                                                <option value="81">Усман-Юсуповский район</option>
                                                <option value="82">г. ШАХРИСАБЗ </option>
                                                <option value="83">г. ГУЗАР</option>
                                                <option value="84">г. КАМАШИ</option>
                                                <option value="85">г. БЕШКЕНТ</option>
                                                <option value="86">г. КАСАН</option>
                                                <option value="87">г. КИТАБ</option>
                                                <option value="88">г. МУБАРЕК</option>
                                                <option value="89">г. ЯНГИ-НИШАН</option>
                                                <option value="90">г. ЧИРАКЧИ</option>
                                                <option value="91">г. ЯККАБАГ</option>
                                            </optgroup>
                                            <optgroup label="Навоийская область">
                                                <option value="92">г. ЗАРАВШАН</option>
                                                <option value="93">г. НАВОИ</option>
                                                <option value="94">Канимехский район</option>
                                                <option value="95">Кызылтепинский район</option>
                                                <option value="96">Навбахорский район</option>
                                                <option value="97">Карманинский район</option>
                                                <option value="98">Нуратинский район</option>
                                                <option value="99">Тамдынский район</option>
                                                <option value="100">Учкудукский район</option>
                                                <option value="101">Хатырчинский район</option>
                                                <option value="102">Навоийский район    </option>
                                                <option value="103">г. КЫЗЫЛТЕПА</option>
                                                <option value="104">г. НУРАТА</option>
                                                <option value="105">г. УЧКУДУК</option>
                                                <option value="106">г. ЯНГИРАБОД</option>
                                            </optgroup>
                                            <optgroup label="Наманганская область">
                                                <option value="107">г. НАМАНГАН</option>
                                                <option value="108">Мингбулакский pайон</option>
                                                <option value="109">Касансайский район</option>
                                                <option value="110">Наманганский район</option>
                                                <option value="111">Нарынский район</option>
                                                <option value="112">Папский район</option>
                                                <option value="113">Туракурганский район</option>
                                                <option value="114">Уйчинский район</option>
                                                <option value="115">Учкурганский район</option>
                                                <option value="116">Чартакский район</option>
                                                <option value="117">Чустский район</option>
                                                <option value="118">Янгикурганский район</option>
                                                <option value="119">г. КАСАНСАЙ</option>
                                                <option value="120">г. УЧКУРГАН</option>
                                                <option value="122">г. ЧАРТАК</option>
                                                <option value="123">г. ЧУСТ  </option>
                                                <option value="124">г. ХАККУЛАБАД</option>
                                                <option value="125">г. ПАП</option>
                                                <option value="126">г. ТУРАКУРГАН</option>
                                            </optgroup>
                                            <optgroup label="Республика Каракалпакстан">
                                                <option value="127">г. НУКУС</option>
                                                <option value="128">Амударьинский район</option>
                                                <option value="129">Берунийский район</option>
                                                <option value="130">Караузякский район</option>
                                                <option value="131">Кегейлийский район</option>
                                                <option value="132">Кунградский район</option>
                                                <option value="133">Канлыкульский район</option>
                                                <option value="134">Муйнакский район</option>
                                                <option value="135">Нукусский район</option>
                                                <option value="136">Тахтакупырский район</option>
                                                <option value="137">Турткульский район</option>
                                                <option value="138">Ходжейлийский район</option>
                                                <option value="139">Чимбайский район</option>
                                                <option value="140">Шуманайский район</option>
                                                <option value="141">Элликкалинский район</option>
                                                <option value="142">Бузатайский район   </option>
                                                <option value="143">г. БЕРУНИЙ </option>
                                                <option value="144">г. КУНГРАД </option>
                                                <option value="145">г. ТАХИАТАШ</option>
                                                <option value="146">г. ТУРТКУЛЬ</option>
                                                <option value="147">г. ХОДЖЕЙЛИ</option>
                                                <option value="148">г. ЧИМБАЙ</option>
                                                <option value="149">г. МАНГИТ</option>
                                                <option value="150">г. МУЙНАК</option>
                                                <option value="151">г. ХОДЖЕЙЛИ</option>
                                                <option value="152">г. ЧИМБАЙ</option>
                                                <option value="153">г. ШУМАНАЙ</option>
                                                <option value="154">г. БУСТАН</option>
                                            </optgroup>
                                            <optgroup label="Самаркандская область">
                                                <option value="155">Каттакурганский район</option>
                                                <option value="156">г. САМАРКАНД</option>
                                                <option value="157">Акдарьинский район</option>
                                                <option value="158">Булунгурский район</option>
                                                <option value="159">Джамбайский район</option>
                                                <option value="160">Иштыханский район</option>
                                                <option value="161">Кошрабадский район</option>
                                                <option value="162">Нарпайский район</option>
                                                <option value="163">Пайарыкский район</option>
                                                <option value="164">Пастдаргомский район</option>
                                                <option value="165">Пахтачийский район</option>
                                                <option value="166">Самаркандский район</option>
                                                <option value="167">Нурабадский район</option>
                                                <option value="168">Ургутский район</option>
                                                <option value="169">Тайлякский район</option>
                                                <option value="170">Гузалкентский район</option>
                                                <option value="171">Челакский район</option>
                                                <option value="172">г. УРГУТ </option>
                                                <option value="173">г. БУЛУНГУР</option>
                                                <option value="174">г. ДЖАМБАЙ</option>
                                                <option value="175">г. ИШТЫХАН</option>
                                                <option value="176">г. АКТАШ</option>
                                                <option value="177">Г.ПАЙАPЫК</option>
                                                <option value="178">г. ДЖУМА</option>
                                                <option value="179">г. НУРАБАД</option>
                                            </optgroup>
                                            <optgroup label="Сурхандарьинская область">
                                                <option value="180">г. ТЕРМЕЗ</option>
                                                <option value="181">Алтынсайский район</option>
                                                <option value="182">Ангорский район</option>
                                                <option value="183">Байсунский район</option>
                                                <option value="184">Бандыханский район</option>
                                                <option value="185">Музрабадский район</option>
                                                <option value="186">Денауский район</option>
                                                <option value="187">Джаркурганский район</option>
                                                <option value="188">Кумкурганский район</option>
                                                <option value="189">Кизирикский район</option>
                                                <option value="190">Сариасийский район</option>
                                                <option value="191">Термезский район</option>
                                                <option value="192">Шерабадский район</option>
                                                <option value="193">Шурчинский район</option>
                                                <option value="194">Узунский район      </option>
                                                <option value="195">г. ДЕНАУ </option>
                                                <option value="196">г. БАЙСУН</option>
                                                <option value="197">г. ДЕНАУ</option>
                                                <option value="198">г. ДЖАРКУРГАН</option>
                                                <option value="199">г. КУМКУРГАН</option>
                                                <option value="200">г. ШЕРАБАД</option>
                                                <option value="201">г. ШУРЧИ</option>
                                            </optgroup>
                                            <optgroup label="Сырдарьинская область">
                                                <option value="202">г. ГУЛИСТАН</option>
                                                <option value="203">г. ШИРИН </option>
                                                <option value="204">г. ЯНГИЕР</option>
                                                <option value="205">Акалтынский район</option>
                                                <option value="206">Баяутский район</option>
                                                <option value="207">Сайхунабадский район</option>
                                                <option value="208">Гулистанский район</option>
                                                <option value="209">Сардобский район</option>
                                                <option value="210">Мирзаабадский район</option>
                                                <option value="211">Сырдарьинский район</option>
                                                <option value="212">Хавастский район</option>
                                                <option value="213">Мехнатабадский район</option>
                                                <option value="214">г. СЫРДАРЬЯ</option>
                                                <option value="215">г. БАХТ  </option>
                                            </optgroup>

                                            <optgroup label="Ферганская область">
                                                <option value="246">г. КОКАНД</option>
                                                <option value="247">г. КУВАСАЙ </option>
                                                <option value="248">г. МАРГИЛАН</option>
                                                <option value="249">г. ФЕРГАНА</option>
                                                <option value="250">Алтыарыкский район</option>
                                                <option value="251">Ахунбабаевский район</option>
                                                <option value="252">Багдадский район</option>
                                                <option value="253">Бувайдинский район</option>
                                                <option value="254">Бешарыкский район</option>
                                                <option value="255">Кувинский район</option>
                                                <option value="256">Учкуприкский район</option>
                                                <option value="257">Риштанский район</option>
                                                <option value="258">Сохский район</option>
                                                <option value="259">Ташлакский район</option>
                                                <option value="260">Узбекистанский район</option>
                                                <option value="261">Ферганский район</option>
                                                <option value="262">Дангаринский район</option>
                                                <option value="263">Фуркатский район</option>
                                                <option value="264">Язъяванский район</option>
                                                <option value="265">г. КУВА  </option>
                                                <option value="266">г. КИРГУЛИ </option>
                                                <option value="267">Куштепинский район</option>
                                                <option value="268">г. БЕШАРЫК</option>
                                                <option value="269">г. РИШТАН</option>
                                                <option value="270">г. ЯЙПАН</option>
                                            </optgroup>
                                            <optgroup label="Хорезмская область">
                                                <option value="271">г. УРГЕНЧ</option>
                                                <option value="272">Багатский район</option>
                                                <option value="273">Гурленский район</option>
                                                <option value="274">Кошкупырский район</option>
                                                <option value="275">Ургенчский район</option>
                                                <option value="276">Хазараспский район</option>
                                                <option value="277">Ханкинский район</option>
                                                <option value="278">Хивинский район</option>
                                                <option value="279">Шаватский район</option>
                                                <option value="280">Янгиарыкский район</option>
                                                <option value="281">Янгибазарский район</option>
                                                <option value="282">г. ХИВА  </option>
                                                <option value="283">г. ПИТНАК (ДРУЖБА)  </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Адрес')?></label>
                                        <input value="<?=@$model->client->address ?>" class="form-control" name="User[address]" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Адрес постоянного места жительства')?></label>
                                        <input value="<?=@$model->client->permanent_address ?>" class="form-control" name="User[permanent_address]" >
                                    </div>
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Место работы/учебы')?></label>
                                        <input value="<?=@$model->client->work_place ?>" class="form-control" name="User[work_place]" >
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'salary')->textInput(['maxlength' => true,'type'=>'numeric']) ?>
                                    </div>
                                </div>
                                <div class="col-sm-6"></div>


                                <div class="col-md-12 title"><?=Yii::t('app','Данные карты')?></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Номер карты')?></label>
                                        <input value="<?=@$model->client->uzcard ?>" class="form-control" name="User[uzcard]">
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Срок годности')?></label>
                                        <input value="<?=@$model->client->exp ?>" class="form-control" name="User[exp]">
                                    </div>
                                </div>


                                <div class="col-md-12 title"><?=Yii::t('app','Паспортные данные')?></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Серия паспорта')?></label>
                                        <input value="<?=@$model->client->passport_serial ?>" class="form-control" name="User[passport_serial]" required>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Номер паспорта')?></label>
                                        <input value="<?=@$model->client->passport_id ?>" class="form-control" type="number" name="User[passport_id]" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата выдачи')?></label>
                                        <input type="date" value="<?=@$model->client->passport_date ?>" class="form-control" name="User[passport_date]"  required>
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата сдачи')?></label>
                                        <input type="date" value="<?=@$model->client->passport_date_end ?>" class="form-control" name="User[passport_date_end]"  required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Кем выдан')?></label>
                                        <textarea class="form-control"  name="User[passport_issuer]" required><?=@$model->client->passport_issuer ?></textarea>
                                    </div>
                                </div>

								 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ИНН')?></label>
                                        <input value="<?= $model->client->inn ?>" class="form-control" name="User[inn]" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Тип лица')?></label>
                                        <select name="User[orentity]" class="form-control" required>
                                            <option value="2" <?=$model->client->orentity == 2 ? 'selected' : '' ?>><?=Yii::t('app','Физическое лицо')?></option>
                                            <option value="1" <?=$model->client->orentity == 1 ? 'selected' : '' ?>><?=Yii::t('app','Юридическое лицо')?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 title"><?=Yii::t('app','Скачать документы')?></div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <a href="/uploads/users/<?=@$model->client->id  .'/' . @$model->client->passport_main ?>" class="btn btn-lg btn-primary" download=""><i class="fa fa-download"></i> <?=Yii::t('app','Паспорт') ?></a>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <a href="/uploads/users/<?=@$model->client->id  .'/' . @$model->client->passport_address ?>" class="btn btn-lg btn-primary" download=""><i class="fa fa-download"></i>  <?=Yii::t('app','Прописка') ?></a>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <a href="/uploads/users/<?=@$model->client->id  .'/' . @$model->client->passport_self ?>" class="btn btn-lg btn-primary" style="margin-left: 15px" download=""><i class="fa fa-download"></i>  <?=Yii::t('app','Селфи') ?></a>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 title"><?=Yii::t('app','Данные для скоринга')?></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата запроса') ?></label>
                                         <input type="text" value="<?=$model_scoring->updated_at !='' ? date('d.m.y',$model_scoring->updated_at) : '' ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Сумма скоринга')?></label>
                                        <input type="text" id="scoring_sum" value="<?=$model_scoring->summ >0 ? $model_scoring->summ : '1000000' ?>" class="form-control required" name="Scoring[summ]">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата от')?></label>
                                        <input type="date" id="scoring_datestart" value="<?=$model_scoring->date_start !='' ? date('Y-m-d',$model_scoring->date_start) : '2019-05-01' ?>" class="form-control required">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата до')?></label>
                                        <input type="date" id="scoring_dateend" value="<?=$model_scoring->date_end != '' ? date('Y-m-d',$model_scoring->date_end) : '2020-01-31' ?>" class="form-control required">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label></label>
                                        <div class="btn btn-default get-scoring"><?=Yii::t('app','Получить данные скоринга')?></div>
                                    </div>
                                </div>

                                <div class="col-md-12 title"><?=Yii::t('app','Результат скоринга')?></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Успешных запросов:') . ' ' . $scoring_success ?></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Не успешных:') . ' ' . $scoring_fail   ?></label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','ФИО клиента')?></label>
                                        <input type="text" id="scoring_username" value="<?=$model_scoring->fullname ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Телефон')?></label>
                                        <input type="text" id="scoring_phone" value="<?=$model_scoring->phone ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','СМС информирование')?></label>
                                        <input type="text" id="scoring_sms" value="<?=$model_scoring->sms ? Yii::t('app','Подключен') : Yii::t('app','Не подключено') ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Номер карты')?></label>
                                        <?php $humo = '9860' . $model_scoring->bank_c . $model_scoring->card_h ?>
                                        <?php $humo = substr_replace($humo, '******', -10, 6); ?>
                                        <?php $card = $model_scoring->pan ? $model_scoring->pan : $humo ?>
                                        <input id="scoring_card" type="text" value="<?=$card ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Срок годности')?></label>
                                        <input id="scoring_exp" type="text" value="<?=$model_scoring->exp ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label></label>
                                        <div id="balance" class="btn btn-default get-balance"><?=Yii::t('app','Баланс')?></div>
                                    </div>
                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Баланс, сум')?></label>
                                        <input type="text" id="scoring_balance" value="<?=$model_scoring->balance ?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Данные по месяцам')?></label>
                                        <div class="-form-control" id="scoring_data">
                                            <table class="table">
                                            <?php
                                            if($model_scoring->data) {
                                                $data = json_decode($model_scoring->data, true);
                                                if(!is_null($data) && is_array($data) && count($data)) {
                                                    foreach ($data as $k => $v) { ?>
                                                        <tr>
                                                            <td><?= $k ?></td>
                                                            <td><?= $v ? 'true' : 'false' ?></td>
                                                        </tr>
                                                    <?php }
                                                }else{ ?>
                                                    <tr>
                                                        <td><?=Yii::t('app','Нет данных') ?></td>
                                                    </tr>
                                                <?php }
                                            }else{ ?>
                                                <tr>
                                                     <td><?=Yii::t('app','Нет данных') ?></td>
                                                </tr>
                                            <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php /* if(isset($model->credit)){ ?>
                                    <div class="col-6"><a href="/print-act?id=<?=$model->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать акт') ?></a></div>
                                    <div class="col-6"><a href="/print-invoice?id=<?=$model->credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать счет-фактуру') ?></a></div>
                                    <div class="col-6"><a href="/print-graph?id=<?=$model->credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Распечатать график оплаты') ?></a></div>
                                <?php } */ ?>

                            </div><!--row-->
                        </div>
                    </div><!--col-sm-6-->
                    <div class="col-sm-6">
                        <div class="black-container">
                            <h5><?=Yii::t('app','Информация KYC отдела') ?></h5>
                            <div class="row">


                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <?//= $form->field($model, 'credit_year')->textInput(['maxlength' => true]) ?>
                                        <select name="Kyc[credit_year]" class="form-control">
                                            <option value="3000000" <?=(int)$model->credit_year == 3000000 ? 'selected' : '' ?>>3000000</option>
                                            <option value="5000000" <?=(int)$model->credit_year == 5000000 ? 'selected' : '' ?>>5000000</option>
                                            <option value="8000000" <?=(int)$model->credit_year == 8000000 ? 'selected' : '' ?>>8000000</option>
                                        </select>
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
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Дата верификации uzcard')?></label>
                                        <input type="text" class="form-control" name="date_verify" value="<?=$model->date_verify >0 ? date('Y-m-d',$model->date_verify) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= $form->field($user, 'summ')->textInput( ['maxlength' => true,'type'=>'numeric']) ?>
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
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Сообщение смс клиенту')?></label>
                                        <textarea class="form-control" id="msg" rows="5"></textarea>

                                    </div>
                                    <div class="btn btn-default send-sms" data-id="<?=$model->client_id ?>"><?=Yii::t('app','Отправить смс сообщение клиенту') ?></div>
                                </div>
                                <div style="margin: 5% 0 5% 5%" class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Заметка')?></label>
                                        <textarea name="Kyc[comment]" class="form-control" id="msgComment" rows="5"><?=$model->comments?></textarea>
                                    </div>
                                    <div class="btn btn-default send-comment"><?=Yii::t('app','Сохранить заметку') ?></div>
                                </div>


								 <!-- СКОРИНГ КАТМ -->
							<h5><?=Yii::t('app','Получить данные по KATM')?></h5>
							 <div style="margin-top:1%;" class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Регион')?></label>
                                        <select id="regionKatm" name="Katm['region']" class="form-control">
                                            <option value="26">Город Ташкент</option>
											<option value="27">Таш область</option>
											<option value="03">Андижанская область</option>
											<option value="6">Бухарарская область</option>
											<option value="8">Джизакская область</option>
											<option value="10">Кашкадаринская область</option>
											<option value="35">Республика Каракалпакстан</option>
											<option value="12">Навои</option>
											<option value="14">Наманганская область</option>
											<option value="18">Самаркандская область</option>
											<option value="24">Сырдарьинская область</option>
											<option value="22">Сурхандарьинская область</option>
											<option value="30">Ферганская область</option>
											<option value="33">Хорезмская область</option>
                                        </select>
                                    </div>
                                </div>

							<div style="margin-top:1%;" class="col-sm-12">
                                    <div class="form-group">
                                        <label><?=Yii::t('app','Район')?></label>
                                        <select id="streetKatm" name="Katm['street']" class="form-control">
										<option value="0"><?=Yii::t('app','Не задан')?></option>
											<optgroup label="город Ташкент">
                                                <option value="198">Учтепинский район</option>
                                                <option value="200">Юнусабадский район</option>
												<option value="206">Яккасарайский район</option>
												<option value="197">Бектемирский</option>
												<option value="201">Мирзо-улугбекский</option>
												<option value="202">Миробадский</option>
												<option value="203">Шайхонтохурский</option>
												<option value="204">Алмазарский</option>
												<option value="205">Сергелийский</option>
												<option value="207">Яшнабадский</option>
												<option value="208">Чиланзарский</option>
                                            </optgroup>
											<optgroup label="Ташкентская область">
                                                <option value="128">г. АЛМАЛЫК</option>
                                                <option value="129">г. АНГРЕН</option>
                                                <option value="133">г. АХАНГАРАН</option>
                                                <option value="130">г. БЕКАБАД</option>
                                                <option value="131">г. ЧИРЧИК</option>
                                                <option value="147">г. ЯНГИЮЛЬ </option>
                                                <option value="221">Аккурганский район</option>
                                                <option value="222">Ахангаранский район</option>
                                                <option value="137">Бекабадский район</option>
                                                <option value="224">Букинский район</option>
                                                <option value="140">Куйичирчикский район</option>
                                                <option value="136">Зангиатинский район</option>
                                                <option value="145">Юкоричирчикский район</option>
                                                <option value="138">Кибрайский район</option>
                                                <option value="139">Паркентский район</option>
                                                <option value="141">Пскентский район</option>
                                                <option value="143">Уртачирчикский район</option>
                                                <option value="142">Ташкентский район</option>
                                                <option value="146">Янгиюльский район</option>
                                                <option value="134">Бостанлыкский район</option>
                                                <option value="144">Чиназский район</option>
                                                <option value="236">г. ЯНГИАБАД</option>
                                                <option value="132">г. АККУРГАН</option>
                                                <option value="238">г. ГАЗАЛКЕНТ</option>
                                                <option value="135">г. БУКА</option>
                                                <option value="240">г. ДУСТОБОД</option>
                                                <option value="138">г. КЕЛЕС</option>
                                                <option value="242">г. ПАРКЕНТ</option>
                                                <option value="243">г. ПСКЕНТ</option>
                                                <option value="244">г. ТОЙТЕПА</option>
                                                <option value="245">г. ЧИНАЗ</option>
                                            </optgroup>
                                            <optgroup label="Андижанская область">
                                                <option value="001">г. АНДИЖАН</option>
                                                <option value="002">г. АСАКА </option>
                                                <option value="016">Алтынкульский район</option>
                                                <option value="006">Андижанский район</option>
                                                <option value="008">Балыкчинский район</option>
                                                <option value="009">Бозский район</option>
                                                <option value="010">Булакбашинский район</option>
                                                <option value="011">Джалалкудукский район</option>
												<option value="018">Худжаободский район</option>
												<option value="013">Комсомолабадский район</option>
                                                <option value="012">Избасканский район</option>
                                                <option value="210">Улугноpский район</option>
                                                <option value="014">Кургантепинский район</option>
                                                <option value="007">Асакинский район</option>
                                                <option value="015">Мархаматский район</option>
                                                <option value="214">Шахриханский район</option>
                                                <option value="004">г. КАРАСУ</option>
                                                <option value="003">г. ХАНАБАД </option>
                                                <option value="005">г. ШАХРИХАН</option>
												<option value="008">г. БАЛИКЧИ</option>
												<option value="009">г. БУЗ</option>
                                                <option value="024">г. КУРГАНТЕПА</option>
                                                <option value="015">г. МАРХАМАТ</option>
                                                <option value="017">г. ПАХТААБАД</option>
                                                <option value="018">г. ХОДЖААБАД</option>
                                            </optgroup>
                                            <optgroup label="Бухарская область">
                                                <option value="030">г. БУХАРА</option>
                                                <option value="019">Алатский район</option>
                                                <option value="022">Бухарский район</option>
                                                <option value="020">Вабкентский район</option>
                                                <option value="032">Гиждуванский район</option>
                                                <option value="033">Каганский район</option>
                                                <option value="023">Каракульский район</option>
                                                <option value="028">Караулбазарский район</option>
                                                <option value="036">Пешкунский район</option>
                                                <option value="024">Ромитанский район</option>
                                                <option value="025">Жондоpский район</option>
                                                <option value="026">Шафирканский район</option>
                                                <option value="021">г. ГИЖДУВАН</option>
                                                <option value="220">г. КАГАН </option>
												<option value="027">г. ПЕШКУ </option>
                                                <option value="042">г. АЛАТ</option>
                                                <option value="043">г. ГАЛЛААСИЯ</option>
                                                <option value="044">г. ВАБКЕНТ</option>
                                                <option value="045">г. КАРАКУЛЬ</option>
                                                <option value="046">г. КАPАУЛБАЗАP</option>
                                                <option value="047">г. РОМИТАН</option>
                                                <option value="048">г. ШАФИРКАН</option>
                                            </optgroup>
                                            <optgroup label="Джизакская область">
                                                <option value="049">г. ГАЛЛЯАРАЛ</option>
                                                <option value="031">г. ДЖИЗАК</option>
                                                <option value="041">Арнасайский район</option>
                                                <option value="037">Бахмальский район</option>
												<option value="040">Шарофрашидовский район</option>
                                                <option value="033">Галляаральский район</option>
                                                <option value="054">Джизакский район</option>
                                                <option value="055">Дустликский район</option>
                                                <option value="036">Заминский район</option>
                                                <option value="042">Зарбдарский район</option>
                                                <option value="035">Мирзачульский район</option>
                                                <option value="039">Зафарабадский район</option>
                                                <option value="032">Пахтакорский район</option>
                                                <option value="038">Фаришский район</option>
                                                <option value="217">Янгиободский район</option>
                                                <option value="034">г. ДУСТЛИК</option>
                                                <option value="064">г. ГАГАРИН</option>
                                                <option value="065">г. ПАХТАКОР</option>
                                            </optgroup>
                                            <optgroup label="Кашкадарьинская область">
                                                <option value="043">г. КАРШИ</option>
                                                <option value="067">Гузарский район</option>
                                                <option value="046">Дехканабадский район</option>
                                                <option value="047">Камашинский район</option>
                                                <option value="044">Каршинский район</option>
                                                <option value="071">Касанский район</option>
                                                <option value="052">Китабский район</option>
                                                <option value="073">Миришкорский район</option>
                                                <option value="056">Мубарекский район</option>
                                                <option value="054">Нишанский район</option>
                                                <option value="053">Касбинский район</option>
                                                <option value="050">Чиракчинский район</option>
                                                <option value="213">Шахрисабзский район</option>
                                                <option value="051">Яккабагский район</option>
                                                <option value="057">Бахаристанский район</option>
                                                <option value="055">Усман-Юсуповский район</option>
                                                <option value="049">г. ШАХРИСАБЗ </option>
												<option value="054">г. НИШАН </option>
                                                <option value="045">г. ГУЗАР</option>
                                                <option value="084">г. КАМАШИ</option>
                                                <option value="085">г. БЕШКЕНТ</option>
                                                <option value="048">г. КАСАН</option>
                                                <option value="052">г. КИТАБ</option>
                                                <option value="088">г. МУБАРЕК</option>
                                                <option value="089">г. ЯНГИ-НИШАН</option>
                                                <option value="090">г. ЧИРАКЧИ</option>
                                                <option value="091">г. ЯККАБАГ</option>
                                            </optgroup>
                                            <optgroup label="Навоийская область">
                                                <option value="059">г. ЗАРАВШАН</option>
                                                <option value="058">г. НАВОИ</option>
                                                <option value="094">Канимехский район</option>
                                                <option value="063">Кызылтепинский район</option>
                                                <option value="064">Навбахорский район</option>
                                                <option value="061">Карманинский район</option>
                                                <option value="098">Нуратинский район</option>
                                                <option value="099">Тамдынский район</option>
                                                <option value="211">Учкудукский район</option>
                                                <option value="066">Хатырчинский район</option>
                                                <option value="102">Навоийский район    </option>
                                                <option value="103">г. КЫЗЫЛТЕПА</option>
                                                <option value="104">г. НУРАТА</option>
                                                <option value="105">г. УЧКУДУК</option>
                                                <option value="106">г. ЯНГИРАБОД</option>
                                            </optgroup>
                                            <optgroup label="Наманганская область">
                                                <option value="068">г. НАМАНГАН</option>
                                                <option value="108">Мингбулакский pайон</option>
                                                <option value="070">Касансайский район</option>
                                                <option value="110">Наманганский район</option>
                                                <option value="071">Нарынский район</option>
                                                <option value="112">Папский район</option>
                                                <option value="073">Туракурганский район</option>
                                                <option value="114">Уйчинский район</option>
                                                <option value="115">Учкурганский район</option>
                                                <option value="079">Чартакский район</option>
												<option value="070">Косонский район</option>
                                                <option value="076">Чустский район</option>
                                                <option value="077">Янгикурганский район</option>
                                                <option value="119">г. КАСАНСАЙ</option>
                                                <option value="120">г. УЧКУРГАН</option>
                                                <option value="122">г. ЧАРТАК</option>
                                                <option value="123">г. ЧУСТ  </option>
                                                <option value="124">г. ХАККУЛАБАД</option>
                                                <option value="125">г. ПАП</option>
                                                <option value="126">г. ТУРАКУРГАН</option>
                                            </optgroup>
                                            <optgroup label="Республика Каракалпакстан">
                                                <option value="127">г. НУКУС</option>
                                                <option value="209">Амударьинский район</option>
												<option value="186">Акмангитский район</option>
                                                <option value="129">Берунийский район</option>
                                                <option value="193">Караузякский район</option>
                                                <option value="182">Кегейлийский район</option>
                                                <option value="184">Кунградский район</option>
                                                <option value="192">Канлыкульский район</option>
                                                <option value="185">Муйнакский район</option>
                                                <option value="216">Нукусский район</option>
                                                <option value="187">Тахтакупырский район</option>
												<option value="222">Тахиаташский район</option>
                                                <option value="188">Турткульский район</option>
                                                <option value="189">Ходжейлийский район</option>
                                                <option value="190">Чимбайский район</option>
                                                <option value="183">Шуманайский район</option>
                                                <option value="194">Элликкалинский район</option>
                                                <option value="195">Бузатайский район</option>
												<option value="196">Мангитский район</option>
                                                <option value="143">г. БЕРУНИЙ </option>
                                                <option value="144">г. КУНГРАД </option>
                                                <option value="145">г. ТАХИАТАШ</option>
                                                <option value="146">г. ТУРТКУЛЬ</option>
                                                <option value="147">г. ХОДЖЕЙЛИ</option>
                                                <option value="148">г. ЧИМБАЙ</option>
                                                <option value="149">г. МАНГИТ</option>
                                                <option value="150">г. МУЙНАК</option>
                                                <option value="151">г. ХОДЖЕЙЛИ</option>
                                                <option value="152">г. ЧИМБАЙ</option>
                                                <option value="153">г. ШУМАНАЙ</option>
                                                <option value="154">г. БУСТАН</option>
                                            </optgroup>
                                            <optgroup label="Самаркандская область">
                                                <option value="085">Каттакурганский район</option>
                                                <option value="096">г. САМАРКАНД</option>
                                                <option value="080">Акдарьинский район</option>
                                                <option value="081">Булунгурский район</option>
                                                <option value="083">Джамбайский район</option>
                                                <option value="084">Иштыханский район</option>
                                                <option value="086">Кошрабадский район</option>
                                                <option value="087">Нарпайский район</option>
                                                <option value="091">Пайарыкский район</option>
                                                <option value="089">Пастдаргомский район</option>
                                                <option value="090">Пахтачийский район</option>
                                                <option value="092">Самаркандский район</option>
                                                <option value="088">Нурабадский район</option>
                                                <option value="094">Ургутский район</option>
                                                <option value="093">Тайлякский район</option>
												<option value="215">Темирюльский район</option>
                                                <option value="082">Гузалкентский район</option>
                                                <option value="095">Челекский район</option>
                                                <option value="219">г. УРГУТ </option>
                                                <option value="173">г. БУЛУНГУР</option>
                                                <option value="174">г. ДЖАМБАЙ</option>
                                                <option value="175">г. ИШТЫХАН</option>
                                                <option value="218">г. АКТАШ</option>
                                                <option value="177">Г.ПАЙАPЫК</option>
                                                <option value="178">г. ДЖУМА</option>
                                                <option value="179">г. НУРАБАД</option>
                                            </optgroup>
                                            <optgroup label="Сурхандарьинская область">
                                                <option value="098">г. ТЕРМЕЗ</option>
                                                <option value="111">Алтынсайский район</option>
                                                <option value="107">Ангорский район</option>
                                                <option value="099">Байсунский район</option>
                                                <option value="112">Бандыханский район</option>
                                                <option value="102">Музрабадский район</option>
                                                <option value="100">Денауский район</option>
                                                <option value="101">Джаркурганский район</option>
                                                <option value="109">Кумкурганский район</option>
                                                <option value="108">Кизирикский район</option>
                                                <option value="106">Сариасийский район</option>
                                                <option value="110">Термезский район</option>
                                                <option value="103">Шерабадский район</option>
                                                <option value="104">Шурчинский район</option>
                                                <option value="105">Узунский район      </option>
                                                <option value="195">г. ДЕНАУ </option>
                                                <option value="196">г. БАЙСУН</option>
                                                <option value="197">г. ДЕНАУ</option>
                                                <option value="198">г. ДЖАРКУРГАН</option>
                                                <option value="199">г. КУМКУРГАН</option>
                                                <option value="200">г. ШЕРАБАД</option>
                                                <option value="201">г. ШУРЧИ</option>
                                            </optgroup>
                                            <optgroup label="Сырдарьинская область">
                                                <option value="114">г. ГУЛИСТАН</option>
                                                <option value="116">г. ШИРИН </option>
                                                <option value="115">г. ЯНГИЕР</option>
                                                <option value="118">Акалтынский район</option>
                                                <option value="119">Баяутский район</option>
                                                <option value="123">Сайхунабадский район</option>
                                                <option value="120">Гулистанский район</option>
                                                <option value="121">Сардобский район</option>
                                                <option value="210">Мирзаабадский район</option>
                                                <option value="122">Сырдарьинский район</option>
                                                <option value="212">Хавастский район</option>
                                                <option value="213">Мехнатабадский район</option>
                                                <option value="117">г. СЫРДАРЬЯ</option>
                                                <option value="113">г. БАХТ  </option>
                                            </optgroup>
                                            <optgroup label="Ферганская область">
                                                <option value="148">г. КОКАНД</option>
                                                <option value="151">г. КУВАСАЙ </option>
                                                <option value="149">г. МАРГИЛАН</option>
                                                <option value="150">г. ФЕРГАНА</option>
												<option value="148">г. КУКОН</option>
                                                <option value="158">Алтыарыкский район</option>
                                                <option value="251">Ахунбабаевский район</option>
                                                <option value="153">Багдадский район</option>
                                                <option value="154">Бувайдинский район</option>
                                                <option value="152">Бешарыкский район</option>
                                                <option value="157">Кувинский район</option>
											    <option value="151">Кувассойский район</option>
                                                <option value="164">Учкуприкский район</option>
                                                <option value="160">Риштанский район</option>
                                                <option value="161">Сохский район</option>
                                                <option value="162">Ташлакский район</option>
                                                <option value="163">Узбекистанский район</option>
                                                <option value="261">Ферганский район</option>
                                                <option value="156">Дангаринский район</option>
                                                <option value="166">Фуркатский район</option>
                                                <option value="156">Язъяванский район</option>
                                                <option value="265">г. КУВА  </option>
                                                <option value="266">г. КИРГУЛИ </option>
                                                <option value="159">Куштепинский район</option>
                                                <option value="268">г. БЕШАРЫК</option>
                                                <option value="269">г. РИШТАН</option>
                                                <option value="270">г. ЯЙПАН</option>
                                            </optgroup>
                                            <optgroup label="Хорезмская область">
                                                <option value="271">г. УРГЕНЧ</option>
                                                <option value="172">Багатский район</option>
                                                <option value="273">Гурленский район</option>
                                                <option value="176">Кошкупырский район</option>
                                                <option value="178">Ургенчский район</option>
                                                <option value="173">Хазараспский район</option>
                                                <option value="277">Ханкинский район</option>
                                                <option value="278">Хивинский район</option>
                                                <option value="177">Шаватский район</option>
                                                <option value="175">Янгиарыкский район</option>
                                                <option value="179">Янгибазарский район</option>
                                                <option value="169">г. ХИВА  </option>
                                                <option value="170">г. ПИТНАК (ДРУЖБА)  </option>
                                            </optgroup>

                                        </select>
                                    </div>
							</div>
							<? if($token = (new\yii\db\Query())->select('token')->from('katm_orders')->where('user_id=:user_id', [':user_id' => $model->client->id])->one()) {?>
								<div style="margin-top:1%;" class="col-sm-6">
									<input type="text" id="token" value="<?=$token['token'] ?>" class="form-control" readonly>
								</div>
							<? } ?>
							
							<? if($token = (new\yii\db\Query())->select('claim_id')->from('katm_orders')->where('user_id=:user_id', [':user_id' => $model->client->id])->one()) {?>
								<div style="margin-top:1%;" class="col-sm-6">
									<input type="text" id="claim_id" value="<?=$token['claim_id'] ?>" class="form-control" readonly>
								</div>
							<? } ?>
							
								<div class="row" style="margin-top:10px;">
									
								   <div class="col-6">
                                        <div class="form-group">
                                            <div class="btn btn-lg btn-primary send-manual-katm"><i class="fa fa-user-plus"></i><?=Yii::t('app','РУЧНАЯЗАЯВКА') ?></div>
                                        </div>
                                    </div>
									
                                    <div class="col-">
                                        <div class="form-group">
                                            <div class="btn btn-lg btn-primary send-katm"><i class="fa fa-user-plus"></i><?=Yii::t('app','АВТОЗАЯВКА') ?></div>
                                        </div>
                                    </div>
								</div>
						
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<div class="btn btn-lg btn-primary check-katm"><i class="fa fa-search-plus"></i><?=Yii::t('app','ПРОВЕРКАKATM') ?></div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<a target="_blank" href="<?php echo 'katm-report?id='.$model->client->id;?>" class="btn btn-lg btn-primary" style="margin-left: 15px"><i class="fa fa-address-card"></i><?=Yii::t('app','ПРОСМОТР') ?></a>
										</div>
									</div>
								</div>
                               

                                <!-- СКОРИНГ КАТМ -->



                            </div><!--row-->
                        </div>
                    </div><!--col-sm-6-->
                </div><!--row-->
            </div><!--col-sm-8-->
        </div><!--row-->
    </div>
<div style="width: 10%; margin: 0 auto;">
    <button  type="submit" class="btn btn-default mb-40"><?=Yii::t('app','Сохранить') ?></button>
    <p></p><br><br>
</div>

    <?php ActiveForm::end() ?>

<?php

$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');
$msg_required_length_field = Yii::t('app','Необходимо заполнить данное поле!');
$msg_send_sms = Yii::t('app','Подтвердите отправку сообщения!');
$msg_text = Yii::t('app','Введите текст сообщения!');
$msg_region = Yii::t('app','Укажите регион!');
$client_id = (int)@$model->client->id;
$region_id = isset($model->client) ? (int)$model->client->region_id : 0;
$script = "


$('document').ready(function(){

$('.get-balance').click(function(){
//alert('balance');
$.ajax({
            type: 'post',
            url: '/kyc/get-balance',          
            data: 'id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data){  
                //console.log(data);  
                //console.log(data.balance.result[0].balance);  //нулевой баланс
                //console.log(data.balance.result); 
                console.log(data.balance.error);  
                //console.log(data.balance);                                                 
                if(typeof(data.balance.error) != 'undefined'){
                    sum = data.balance.error.message;
                }else{               
                    //sum = data.balance; 
                    sum = data.balance / 100; 
                }
                if(typeof(data.balance.result) != 'undefined'){                             
                    sum = data.balance.result[0].balance;
                }  
                console.log(sum);               
                if(typeof(sum) == 'undefined'){
                    sum = 'Token not found';
                }              
	              $('#scoring_balance').val(sum);  
                	               
                }else{
                   alert('data');
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
})

    var id = {$client_id};
	$('#user_region_id option[value='+{$region_id}+']').prop('selected', true);
	$('.get-scoring').click(function(){

	    submit = true;
	    $('.required').each(function(){
	       if($(this).val().length==0){
                $(this).focus();
                alert('{$msg_required_field}');
	            submit = false;
	            return false;
	        }
	    });

		$('.required-length').each(function(){
	       if($(this).val().length<14){
                $(this).focus();
                alert('{$msg_required_length_field}');
	            submit = false;
	            return false;
	        }
	    });

	    if(!submit) return false;

	    ds = $('#scoring_datestart').val();
	    de = $('#scoring_dateend').val();
	    ss = $('#scoring_sum').val();

	    $.ajax({
            type: 'post',
            url: '/kyc/get-scoring',
            data: 'id='+id+'&ds='+ds+'&de='+de+'&ss='+ss+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            //console.log(data);            
                if(data.status){               
                    $('#scoring_username').val(data.Scoring.fullname);
                    $('#scoring_card').val(data.Scoring.pan);
                    $('#scoring_exp').val(data.Scoring.exp);
                    $('#scoring_phone').val(data.Scoring.phone);
                    $('#scoring_balance').val(data.Scoring.balance);
                    $('#scoring_sms').val(data.Scoring.sms);
                    res = '<table class=\"table\">';
                    $.each(JSON.parse(data.Scoring.data),function(i,v){
                        res += '<tr><td>'+i + '</td><td>' + v + '</td></tr>';
                    })
                    res += '</table>';
                    $('#scoring_data').html(res );

                }else{
                if(data.info){
                   alert(data.info);
                   }else{
                   alert('данные скоринга на сумму '+ss+' сум успешно получены');
                   res = '<table class=\"table\">';
                   $.each(JSON.parse(data),function(i,v){
                        res += '<tr><td>'+i + '</td><td>' + v + '</td></tr>';
                    })
                    res += '</table>';
                    $('#scoring_data').html(res );
                   }
                   
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });

	})


    $('.send-comment').click(function(){
        let msgComments = $('#msgComment').val();
        $.ajax({
            type: 'post',
            url: '/kyc/save-comments',
            data: '&msgComments='+msgComments+'&id='+id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            console.log(data);
                if(data.status){
                console.log(data);
                   alert(data.info);
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
    })


	$('.send-katm').click(function(){
		if(!confirm('Точно отправить заявку?')) return false;
	    let regionKatm = $('#regionKatm').val();
	    let streetKatm = $('#streetKatm').val();
	    let clientId = $('#client_id').val();
	    $.ajax({
            type: 'post',
            url: '/kyc/send-katm-data',
            data: '&regionKatm='+regionKatm+'&streetKatm='+streetKatm+'&clientId='+clientId+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            console.log(data);
            alert(data.info);
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	
		$('.send-manual-katm').click(function(){
		if(!confirm('Точно отправить заявку?')) return false;
	    let regionKatm = $('#regionKatm').val();
	    let streetKatm = $('#streetKatm').val();
	    let clientId = $('#client_id').val();
	    $.ajax({
            type: 'post',
            url: '/kyc/send-manual-katm-data',
            data: '&regionKatm='+regionKatm+'&streetKatm='+streetKatm+'&clientId='+clientId+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            console.log(data);
            alert(data.info);
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	
	
	$('.check-katm').click(function(){
		let token = $('#token').val();
		let clientId = $('#client_id').val();
		let claimId = $('#claim_id').val();
	    $.ajax({
            type: 'post',
            url: '/kyc/check-katm-data',
            data: '&token='+token+'&clientId='+clientId+'&claimId='+claimId+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
			console.log(data);
			alert(data.info);
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	
		$('.view-katm').click(function(){
	    $.ajax({
            type: 'post',
            url: '/kyc/view-katm-data',
            data: '+_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
            console.log(data);
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})




	$('.send-sms').click(function(){
	    if($('#msg').val().length==0){
	      alert('{$msg_text}');
	      $('#msg').focus();
	      return false;
	    }
	    if(!confirm('{$msg_send_sms}')) return false;
	    id = $(this).data('id');
	    msg = $('#msg').val();
	    $.ajax({
            type: 'post',
            url: '/kyc/send-sms',
            data: 'id='+id+'&msg='+msg+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert(data.info);
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	$('form#edit-form').submit(function(e){
	    console.log('submit1')

	    if($('#user_region_id').val()==0){
	        e.preventDefault();
	        alert('{$msg_region}');
	        $('#user_region_id').focus();
	        return false;
	    }
	    	    console.log('submit2')

	    return true;
	})

	$('#uzcard').mask('9999 9999 9999 9999');
	$('#exp').mask('99 99');
});";
$this->registerJs($script, yii\web\View::POS_END);
