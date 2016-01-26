<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'notification_text',
        'format' => 'html'
    ],
    ['class' => 'yii\grid\ActionColumn'],
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
                        'attribute' => 'notification_text',
                        'format' => 'html'
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
