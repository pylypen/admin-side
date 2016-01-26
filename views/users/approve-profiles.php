<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Approve Profile';
$this->params['breadcrumbs'][] = $this->title;

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
                    'user_id',
                    'user_email',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=> '{Approve}',
                        'buttons' => [
                            'Approve' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/users/trainer?id={$model->user_id}", ['title'=>"Approve"]);
                            }
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
