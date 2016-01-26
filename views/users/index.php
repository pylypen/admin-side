<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">
    <p>
        <?php #Html::a('Create User', ['create-expert'], ['class' => 'btn btn-success']) ?>
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
		    'payment_first_name',
		    'payment_last_name',
		    'payment_bussines_name',
		    'country_short_name',
                    'user_type',
		    'user_fees',
		    
//                    [
//                        'attribute' => 'account_state',
//                        'value' => function ($model) {
//                            return $model::$states[$model->account_state];
//                        },
//                        //
//                        'filter' => Html::activeDropDownList($searchModel, 'account_state', \common\models\Users::$states, ['class' => 'form-control', 'prompt' => 'Select state']),
//                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
			'template'=> '{Edit}',
                        'buttons' => [
                            'Edit' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/users/update?id={$model['user_id']}", ['title'=>"update"]);
                            }
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
