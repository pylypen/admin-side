<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dispute List Class';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    [
                        'attribute' => 'class_id',
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'class_name',
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'class_trainer_name',
                        'value' => function ($model) {
                            $model = $model->toArray();
                            return $model['class_trainer_name'];
                        },
                    ],
                    [
                        'attribute' => 'dispute_count',
                        'value' => function ($model) {
                            $model = $model->toArray();
                            return $model['dispute_count'];
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=> '{Edit}',
                        'buttons' => [
                            'Edit' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/dispute/update-list?id={$model['class_id']}", ['title'=>"hold money/release funds"]);
                            }
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
