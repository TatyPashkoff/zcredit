<?php


\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Вопросы и ответы - zMarket');
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Покупай сейчас, Оплачивай позже! Платформа по предоставлению отсрочки платежа',
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'Рассрочка в Ташкенте, купить в рассрочку телефон, рассрочка телефона, купить в рассрочку, бытовая техника в рассрочку',
]);
?>
<div class="container upd__faq-cont">

    <h2 class="text-center">
        Частые вопросы
    </h2>
    <div class="accordion" id="accordionExample">
        <div class="card update__faq-card">
            <div class="" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link update__acc-header collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Что такое Zmarket <span class="faq-icon"></span>
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body update__card-content">
                    ZMARKET, является платформой по предоставлению товаров в рассрочку. Мы работаем по торговому соглашению между покупателями в которой отражаем первоначальную стоимость товара и добавляем свою наценку, которая заранее известна покупателю.
                </div>
            </div>
        </div>
        <div class="card update__faq-card">
            <div class="" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link update__acc-header collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Как получить в рассрочку? <span class="faq-icon"></span>
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <p class="card-body update__card-content">Для того чтобы получить товар в рассрочку необходимо пройти процедуру верификации на сайте zmarket.uz. После успешного прохождения верификации Вы можете пойти в любые магазины наших партнеров и приобрести все товары, которые у них есть в наличии. *</p>
				<p>
				- Для этого необходим Ваш ID номер, полученный в системе zmarket.uz, или же номер мобильного телефона, указанный при регистрации.  
				</p>
				<p>
				- Выбрать интересующих Вас товар в магазине наших партнеров;
				</p>
				<p>
				- Сказать сотруднику партнера что вы хотите приобрести товар с помощью zmarket.uz;
				</p>
				<p>
				- Проговорить ID или номер телефона, зарегистрированный на платформе zmarket.uz;
				</p>
				<p>
				- Указать период рассрочки;
				</p>
				<p>
				- Ознакомится с условиями рассрочки и подтвердить покупку;
				</p>
				<p>
				- Оплачивать меньшими платежами в течение периода рассрочки;
				</p>
				<p class="faq_padding">
				* (Кроме алкогольной, табачной, порнографической продукции)
				</p>

				</div>
            </div>
        </div>
        <div class="card update__faq-card">
            <div class="" id="headingThree">
                <h2 class="mb-0">
                    <button class="btn btn-link update__acc-header collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Как пройти верификацию? <span class="faq-icon"></span>
                    </button>
                </h2>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="card-body update__card-content update__faq_padding">
                <p class="card-body update__card-content">Для того чтобы пройти процедуру верификации необходимо оставить заявку регистрации на сайте zmarket.uz, загрузить список документов и дождаться ответа от платформы о статусе верификации.
				<p>
				Список документов необходимые для регистрации:  
				</p>
				<p>
				- Фото паспорта;
				</p>
				<p>
				- Фото прописки;
				</p>
				<p>
				- Селфи с паспортом;
				</p>
				<p>
				- Заработная пластиковая карта UZCARD / HUMO с поступающими за последние 6 месяцев денежными средствами в размере не менее 1 000 000 сум.
				</p>
				<p class="faq_padding"><b>
				* (Обработка заявки составляет 30 минут.)
				</b>
				</p>
				</div>
            </div>
        </div>

        <div class="card update__faq-card">
            <div class="" id="headingThree">
                <h2 class="mb-0">
                    <button class="btn btn-link update__acc-header collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Как оплачивать рассрочку? <span class="faq-icon"></span>
                    </button>
                </h2>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="card-body update__card-content">
                    Вы можете оплачивать рассрочку с помощью пополнение лицевого счета клиента на сайте zmarket.uz. Пополнить лицевой счет Вы сможете с помощью платежных сервисов “Click”, “Payme”, “MyUzcard” или же пополнить Вашу пластиковую карту, которую Вы указали при регистрации и в период оплаты наша платформа автоматический спишет денежные средства и пополнит Ваш лицевой счет на платформе zmarket.uz. И погасит задолженность по рассрочке.
					</div>
            </div>
        </div>

    </div>

</div>