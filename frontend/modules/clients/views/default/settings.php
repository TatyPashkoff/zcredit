<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);


?>


	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
        <?php echo Yii::$app->session->getFlash('success'); ?>
        <div class="update__client-container">
            <div class="update__client-title">
                Личная информация
            </div>
        </div>

        <div class="title-with-border"><?=Yii::t('app','Настройки')?></div>

        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'clients-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);



        ?>

        <div class="row update__row">

            <div class="col-4">
                <?= $form->field($model, 'id')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'class' => 'form-control', 'required' => 'required']) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'lastname')->textInput(['maxlength' => true, 'class' => 'form-control', 'required' => 'required']) ?>
            </div>

        </div>

        </div>

        <div class="row update__row mb-40">

            <div class="col-4">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'phone_home')->textInput(['maxlength' => true, 'class' => 'form-control', 'required' => 'required']) ?>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app','Регион')?></label>

                    <select name="User[region_id]" id="user_region_id" class="form-control">
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

            <div class="col-4">
                <?= $form->field($model, 'permanent_address')->textInput(['maxlength' => true, 'required' => 'required']) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'work_place')->textInput(['maxlength' => true, 'required' => 'required']) ?>
            </div>

            <? /*
            <div class="col-4">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
            </div> */ ?>

        </div>

       <!-- <div class="row update__row mb-40">
            <div class="col-4">
                <div class="form-group field-user-phone">
                    <label class="control-label"><?=Yii::t('app','Автопогашение кредита')?></label>
                    <input class="form-control" value="<?=$model->auto_discard == 0 ? Yii::t('app','Отключено') : Yii::t('app','Подключено') ?>" type="text" readonly>

                    <div class="help-block"></div>
                </div>

            </div>
        </div> -->

        <!-- АВТОПОГАШЕНИЕ КРЕДИТА
        <div class="row update__row mb-40 update__client-title">
            <div class="col-4">
                <div class="form-group field-user-phone">
                    <label class="control-label"><?=Yii::t('app','Автопогашение кредита')?></label>
                    <input class="form-control" value="<?=$model->auto_discard == 0 ? Yii::t('app','Отключено') : Yii::t('app','Подключено') ?>" type="text" readonly>

                    <div class="help-block"></div>
                </div>
            </div>


            <div class="col-sm-4">
                <div class="contract-list__item hook-contract__item" >


                </div>
            </div>


        </div>
    -->
    <div class="col-sm-4">
        <div class="contract-list__item hook-contract__item">
            <a class="btn btn-default check-otp hook-contract_btn" href="check-card?id=<?=$model->id ?>" target="_blank">
                <?=Yii::t('app','Привязать карту')?>
            </a>
        </div>
    </div>
        <br>
        <br>
    <div class="update__client-container">
        <div class="update__client-title">
            Загрузите документы
        </div>
    </div>
        <div class="row ">
            <div class="update__settings-item col-3 ">
                <img id="update__preview1" class="update__preview-img" src="<?php echo $model->passport_self !== NULL ? '/uploads/users/' . $model->id. '/' . $model->passport_self : '/images/update__pass.png'; ?>" alt="">
                <span class="update__settings-preview__label">
                    Селфи с паспортом
                </span>

                <input class="update__settings-input" type="file" onchange="readPassportPhoto1(this)" name="User[passport_self]" >
            </div>

            <div class="update__settings-item col-3">
                <img id="update__preview2" class="update__preview-img" src="<?php echo $model->passport_main !== NULL ? '/uploads/users/' . $model->id. '/' . $model->passport_main : '/images/update__pass2.png'; ?>" alt="">
                <span class="update__settings-preview__label">
                    Лицевая сторона паспорта
                </span>
                <input class="update__settings-input" type="file" onchange="readPassportPhoto2(this)" name="User[passport_main]" >
            </div>

            <div class="update__settings-item col-3">
                <img id="update__preview3" class="update__preview-img" src="<?php echo $model->passport_address !== NULL ? '/uploads/users/' . $model->id. '/' . $model->passport_address : '/images/update__pass3.png'; ?>" alt="">
                <span class="update__settings-preview__label">
                    Прописка на паспорте
                </span>
                <input class="update__settings-input" type="file" onchange="readPassportPhoto3(this)" name="User[passport_address]" >
            </div>

        </div>


        <button type="submit" class="btn btn-default m-40 update__settings-btn"><?=Yii::t('app','Сохранить') ?></button>

        <?php ActiveForm::end() ?>

        </div>

<?php
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');
$script = "$('document').ready(function(){
    $('#user_region_id option[value='+{$model->region_id}+']').prop('selected', true);

    $('#user-phone').mask('+(999)-99 999-99-99');
    $('#user-phone_home').mask('+(999)-99 999-99-99');
	
});";

$passportPhoto1 = "function readPassportPhoto1(input) {
console.log('test');
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview1').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$passportPhoto2 = "function readPassportPhoto2(input) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview2').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$passportPhoto3 = "function readPassportPhoto3(input) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    
    reader.onload = function(e) {
      $('#update__preview3').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}";

$this->registerJs($script, yii\web\View::POS_END);
$this->registerJs($passportPhoto1, yii\web\View::POS_END);
$this->registerJs($passportPhoto2, yii\web\View::POS_END);
$this->registerJs($passportPhoto3, yii\web\View::POS_END);

?>
