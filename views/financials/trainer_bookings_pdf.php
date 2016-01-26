<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activity bookings';
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
                'dataProvider' => $data,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'trainer_name',
                        'format' => 'html',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return $r['user_type'] === "studio" ? $r['payment']['payment_bussines_name'] : $r['payment']['payment_first_name'] . " " . $r['payment']['payment_last_name'];
                        },
                    ],
                    [
                        'attribute' => 'bookings_count',
                        'format' => 'html',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return $r['bookings_count'];
                        },
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
