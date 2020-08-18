<?php if( ! Yii::$app->user->isGuest || @Yii::$app->user->identity->role ==9 ) { ?>

    <aside class="main-sidebar">

    <section class="sidebar">

        <?php

        if( Yii::$app->user->identity->role == 9 ) { // админ

            /*$menu_lang[] =  ['label' => 'Добавить язык', 'icon' => 'fa fa-newspaper-o', 'url' => ['/translate/add']];
            $langs = \common\helpers\FileHelper::getDir(Yii::getAlias('@common/messages'));

            foreach($langs as $lang){
                $menu_lang[] = ['label' => $lang, 'icon' => 'fa fa-newspaper-o', 'url' => ['/translate/'.$lang] ];
            }*/

            //print_r($menu_lang);

            //echo $menu_lang; exit;

            $items = [
                ['label' => 'Главная', 'icon' => 'fa fa-dashboard', 'url' => ['/']],
                ['label' => 'Администраторы', 'icon' => 'fa fa-user', 'url' => ['/managers']] ,
                ['label' => 'Клиенты', 'icon' => 'fa fa-user', 'url' => ['/clients']] ,
                ['label' => 'Поставщики', 'icon' => 'fa fa-user', 'url' => ['/suppliers']] ,
                ['label' => 'KYC', 'icon' => 'fa fa-user', 'url' => ['/kyc']] ,

                ['label' => 'Партнеры', 'icon' => 'fa fa-address-book-o', 'url' => ['/partners']],
                ['label' => 'Категории партнеров', 'icon' => 'fa fa-address-book-o', 'url' => ['/cats-partners']],
                ['label' => 'Маркеры', 'icon' => 'fa fa-map-marker', 'url' => ['/markers']],
                ['label' => 'Биллинг',
                    'icon' => 'fa fa-clone',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Пополнения', 'icon' => 'fa fa-file-o', 'url' => ['/billing-history']],
                        ['label' => 'Списания', 'icon' => 'fa fa-file-o', 'url' => ['/billing-payments']],
                        ['label' => 'Оплата за сервисы', 'icon' => 'fa fa-address-book-o', 'url' => ['/billing-services']],
                        ['label' => 'Договора', 'icon' => 'fa fa-address-book-o', 'url' => ['/billing-credits']],
                    ]
                ],
                //['label' => 'Скоринг', 'icon' => 'fa fa-file-o', 'url' => ['/scoring']],

                 ['label' => 'Кредиты', 'icon' => 'fa fa-address-book-o', 'url' => ['/credits']],
                 //['label' => 'Просроченные кредиты', 'icon' => 'fa fa-address-book-o', 'url' => ['/credit-history']],
                ['label' => 'Акции ZMarket', 'icon' => 'fa fa-file-o', 'url' => ['/stock']],

               /* ['label' => 'Главная страница',
                    'icon' => 'fa fa-clone',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Главная', 'icon' => 'fa fa-file-o', 'url' => ['/pages/main']],
                        ['label' => 'О компании', 'icon' => 'fa fa-file-o', 'url' => ['/pages/about']],
                        //['label' => 'Контакты', 'icon' => 'fa fa-file-o', 'url' => ['/pages/contacts']],
                       // ['label' => 'Баннеры', 'icon' => 'fa fa-file-o', 'url' => ['/banners']],
                    ]
                ],*/
                //['label' => 'Заказы', 'icon' => 'fa fa-newspaper-o', 'url' => ['/orders']],
                ['label' => 'Выйти', 'icon' => 'fa fa-exit', 'url' => ['/site/logout']]
            ];

        }elseif( Yii::$app->user->identity->role == 3 ){ // KYC менеджер

            $items = [
                ['label' => 'Главная', 'icon' => 'fa fa-dashboard', 'url' => ['/']],
                ['label' => 'KYC', 'icon' => 'fa fa-user', 'url' => ['/kyc']] ,

                ['label' => 'Выйти', 'icon' => 'fa fa-exit', 'url' => ['/site/logout']]
            ];

        }

    ?>
    <!-- /.search form -->
    <?= dmstr\widgets\Menu::widget(
        [
            'options' => ['class' => 'sidebar-menu'],
            'items' => $items
            ,
        ]
    ); ?>

    </section>

</aside>
<?php } // isGuest ?>