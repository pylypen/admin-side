<?php
use yii\bootstrap\Nav;

#var_dump(Yii::$app->user);exit;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <?=
        yii\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu treeview'],
            'items' => [
                [
                'label' => 'Users Fees',
                'url' => ['/users/index'],
                'visible' => Yii::$app->user->identity->user_superadmin
                ],
                ['label' => 'Financials',
                    'url' => ['#'],
                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Info', 'url' => ['/financials']],
                        ['label' => 'Trainers Revenue', 'url' => ['/financials/trainers-revenue']],
                        ['label' => 'Total number of bookings', 'url' => ['/financials/booked-number']],
                        ['label' => 'Activity bookings', 'url' => ['/financials/activity-bookings']],
                        ['label' => 'Trainers bookings', 'url' => ['/financials/trainers-bookings']],
                    ],
                    'visible' => Yii::$app->user->identity->user_superadmin,
                ],
                [
                    'label' => 'Disputes',
                    'url' => ['#'],
                    'items' => [
                        ['label' => 'Types', 'url' => ['/dispute/types']],
                        ['label' => 'List', 'url' => ['/dispute/list']],
                    ],
                    'visible' => Yii::$app->user->identity->user_superadmin,
                ],
                [
                    'label' => 'Admin Staff',
                    'url' => ['/users/admin-users'],
                    'visible' => Yii::$app->user->identity->user_superadmin,
                ],
                [
                    'label' => 'Profiles awaiting approval',
                    'url' => ['/users/approve-profiles'],
                    'visible' => (Yii::$app->user->identity->user_approved_trainers != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Export Emails',
                    'url' => ['#'],
                    'items' => [
                        ['label' => 'Active students accounts', 'url' => ['/export-emails/active-students']],
                        ['label' => 'Active instructors accounts', 'url' => ['/export-emails/active-instructors']],
                        ['label' => 'Non active students', 'url' => ['/export-emails/inactive-students']],
                        ['label' => 'Non active instructors accounts', 'url' => ['/export-emails/inactive-instructors']],
                    ],
                    'visible' => (Yii::$app->user->identity->user_export_emails != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Notifications',
                    'url' => ['/notifications'],
                ],
                ['label' => 'Promotional coupon codes','url' => ['/coupon']],
                ['label' => 'Edit Instructors','url' => ['/']],
                ['label' => 'Edit Students','url' => ['/']],
                ['label' => 'Edit Classes','url' => ['/']],
                [
                    'label' => 'Approve Classes',
                    'url' => ['/'],
                    'visible' => (Yii::$app->user->identity->user_approved_classes != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Approve Blogs',
                    'url' => ['/'],
                    'visible' => (Yii::$app->user->identity->user_approved_blogs != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Instructors Earnings',
                    'url' => ['/'],
                    'visible' => (Yii::$app->user->identity->user_instructors_earnings != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Instructors Invoices',
                    'url' => ['/'],
                    'visible' => (Yii::$app->user->identity->user_instructors_invoices != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'label' => 'Payouts',
                    'url' => ['/'],
                    'visible' => (Yii::$app->user->identity->user_payouts != 'disallow' || Yii::$app->user->identity->user_superadmin)
                ],
                [
                    'template' => '<a href="/site/logout" data-method="post" >Logout</a>',
                    'visible' => !Yii::$app->user->isGuest
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-lock"></i><span>Sing in</span>', //for basic
                    'url' => ['/site/login'],
                    'visible' => Yii::$app->user->isGuest
                ],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
            'encodeLabels' => false, //allows you to use html in labels
            'activateParents' => true,]);

        ?>

    </section>

</aside>
