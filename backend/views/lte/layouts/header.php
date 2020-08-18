<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img src="/favicon.png"></span><span class="logo-lg">' . $_SERVER['SERVER_NAME'] /*Yii::$app->name*/ . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <?php
            // сообщения
          /* if($query = \common\models\Messages::find()->where(['status'=>'0'])->orderBy('date DESC')){
                $msg_count = $query->count(); // count($messages);
                if($msg_count>0) {
                    $messages = $query->limit(12)->all();
                }else{
                    $messages = [];
                    $msg_count = 0;
                }
            }else{
               $messages = [];
               $msg_count = 0;
           }*/
        $messages = [];
        $msg_count = 0;
        ?>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <?php if( $msg_count ){ ?>
                        <span class="label label-success"><?=$msg_count ?></span>
                        <?php } ?>
                    </a>


                    <ul class="dropdown-menu">
                        <li class="header"> <?= $msg_count>0 ? 'У Вас новые сообщения от:' : 'Нет сообщений'?></li>
                        <?php if($msg_count){

                        foreach ($messages as $message){ ?>

                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="/admin/messages/update?id=<?=$message->id?>">
                                        <div class="pull-left">
                                            <?php /*<img src="/frontend/web/uploads/users/<?=$message->user->id .'/' .$message->user->image?>" class="img-circle"
                                                 alt="User Image"/> */ ?>
                                        </div>
                                        <h4>
                                            <?=$message->name?>
                                            <small><i class="fa fa-clock-o"></i> <?=date('d.M h:i',$message->date)?></small>
                                        </h4>
                                    </a>
                                </li>
                                <!-- end message -->

                            </ul>
                        </li>
                        <?php } ?>
                        <li class="footer"><a href="/admin/messages">Все сообщения</a></li>
                        <?php } ?>
                    </ul>
                </li>
               <?php /*
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may
                                        not fit into the page and may cause design problems
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Create a nice theme
                                            <small class="pull-right">40%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-green" style="width: 40%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">40% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Some task I need to do
                                            <small class="pull-right">60%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-red" style="width: 60%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Make beautiful transitions
                                            <small class="pull-right">80%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                */ ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php /*<img src="/frontend/web/uploads/users/<?=Yii::$app->user->id .'/'. Yii::$app->user->identity->image?>" class="user-image" alt="User Image"/> */ ?>
                        <span class="hidden-xs"><?=@Yii::$app->user->identity->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php /* <img src="/frontend/web/uploads/users/<?=Yii::$app->user->id . '/' .Yii::$app->user->identity->image  ?>" class="img-circle"
                                 alt="User Image"/> */
                            $role = @Yii::$app->user->identity->role ==9 ? 'Администратор':'Менеджер';
                            ?>
                            <p>
                                <?=@Yii::$app->user->identity->username ?> - <?=$role?>
                                <?php // <small></small> ?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <?php /*<li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li> */ ?>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php /* <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div> */ ?>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <?php /*<li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> */ ?>
            </ul>
        </div>
    </nav>
</header>