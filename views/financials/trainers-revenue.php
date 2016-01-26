<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trainers Revenue';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'user_email',
        'format' => 'html'
    ],
    [
        'attribute' => 'last_login_time',
        'value' => function ($model) {
            return date('Y-m-d H:i', $model->user_login_time);
        },
    ],
];

// Renders a export dropdown menu
echo ExportMenu::widget([
    'options' => $data,
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered table-hover'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'user_email',
            'format' => 'html'
        ],
        [
            'label' => 'First Name',
            'value' => function ($model) {
                return $model->payment->payment_first_name;
            },
        ],
        [
            'label' => 'Last Name',
            'value' => function ($model) {
                return $model->payment->payment_last_name;
            },
        ],
        [
            'label' => 'Bussines Name',
            'value' => function ($model) {
                return $model->payment->payment_bussines_name;
            },
        ],
        [
            'label' => 'Trainer Revenue',
            'value' => function ($model, $key, $index, $grid) {
                return '$ '.$grid->grid->options['gusers'][$key];
            },
        ],
    ],
    'exportConfig' => [
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_EXCEL_X => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_TEXT => false
    ]
]);

?>
<div class="users-index">
    <div class="box">
        <div class="box-body">           
        <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>$ <?= $data['gross']; ?></h3>
            <p>Trainers Revenue</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
        </div>
            </div>
        <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= $data['date_start']; ?> - <?= $data['date_end']; ?></h3>
            <p>Revenue Date Period</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
        </div>
            </div>
        </div>
    </div> 

    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'options' => $data,
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'user_email',
                        'format' => 'html'
                    ],
                    [
                        'label' => 'First Name',
                        'value' => function ($model) {
                            return $model->payment->payment_first_name;
                        },
                    ],
                    [
                        'label' => 'Last Name',
                        'value' => function ($model) {
                            return $model->payment->payment_last_name;
                        },
                    ],
                    [
                        'label' => 'Bussines Name',
                        'value' => function ($model) {
                            return $model->payment->payment_bussines_name;
                        },
                    ],
                    [
                        'label' => 'Trainer Revenue',
                        'value' => function ($model, $key, $index, $grid) {
                            return '$ '.$grid->grid->options['gusers'][$key];
                        },
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
