<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">
    <p>
        <?= Html::a('Create User', ['/users/create-admin-users'], ['class' => 'btn btn-success']) ?>
    </p>
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
                        'attribute' => 'edit_instructors',
                        'value' => function ($model) {
                            return $model->user_instructors_edit;
                        },
                    ],
                    [
                        'attribute' => 'edit_students',
                        'value' => function ($model) {
                            return $model->user_students_edit;
                        },
                    ],
                    [
                        'attribute' => 'edit_classes',
                        'value' => function ($model) {
                            return $model->user_classes_edit;
                        },
                    ],
                    [
                        'attribute' => 'approve_classes',
                        'value' => function ($model) {
                            return $model->user_approved_classes;
                        },
                    ],
                    [
                        'attribute' => 'approve_blogs',
                        'value' => function ($model) {
                            return $model->user_approved_blogs;
                        },
                    ],
                    [
                        'attribute' => 'approve_trainers',
                        'value' => function ($model) {
                            return $model->user_approved_trainers;
                        },
                    ],
                    [
                        'attribute' => 'instructors_earnings',
                        'value' => function ($model) {
                            return $model->user_instructors_earnings;
                        },
                    ],
                    [
                        'attribute' => 'instructors_invoices',
                        'value' => function ($model) {
                            return $model->user_instructors_invoices;
                        },
                    ],
                    [
                        'attribute' => 'payouts',
                        'value' => function ($model) {
                            return $model->user_payouts;
                        },
                    ],
                    [
                        'attribute' => 'export_emails',
                        'value' => function ($model) {
                            return $model->user_export_emails;
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=> '{Edit}',
                        'buttons' => [
                            'Edit' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/users/update-admin?id={$model['user_id']}", ['title'=>"update"]);
                            }
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
