<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Export Emails';
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
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
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
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
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
                ],
            ]);

            ?>
        </div>
    </div>
</div>
