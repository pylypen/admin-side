<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/backend/images/avatar.png" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity ? Yii::$app->user->identity->user_email : "" ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/backend/images/avatar.png" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->user->identity ? Yii::$app->user->identity->user_email : "" ?> ?>
                                <small><?= Yii::$app->user->identity ? Yii::$app->user->identity->user_email : "" ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!--                        <li class="user-body">
                                                    <div class="col-xs-4 text-center">
                                                        <a href="#">Followers</a>
                                                    </div>
                                                    <div class="col-xs-4 text-center">
                                                        <a href="#">Sales</a>
                                                    </div>
                                                    <div class="col-xs-4 text-center">
                                                        <a href="#">Friends</a>
                                                    </div>
                                                </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a('Profile', ['users/profile'], ['class' => 'btn btn-default btn-flat']); ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('Sign out', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']); ?>
                            </div>
                        </li>
                    </ul>
                </li>                
            </ul>
        </div>
    </nav>
</header>
