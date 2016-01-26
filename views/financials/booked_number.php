<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Total number of bookings';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">
    <p>
        <?= Html::a('Get PDF', ['/financials/booked-number-pdf'], ['class' => 'btn btn-success','target'=>'_blank']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $data,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'class_name',
                    [
                        'attribute' => 'trainer_name',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return $r['class_trainer_name'];
                        },
                    ],
                    [
                        'attribute' => 'activity_name',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return $r['activity_name']['actives_name'];
                        },
                    ],
                    [
                        'attribute' => 'booked_number',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return $r['pay_count'];
                        },
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
