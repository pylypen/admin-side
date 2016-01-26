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
        <?= Html::a('Get PDF', ['/financials/activity-bookings-pdf'], ['class' => 'btn btn-success','target'=>'_blank']) ?>
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
                        'attribute' => 'actives_name',
                        'format' => 'html',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return ($r['actives_group']) ? '<b>'.$r['actives_name'].'</b>' : $r['actives_name'];
                        },
                    ],
                    [
                        'attribute' => 'bookings_count',
                        'format' => 'html',
                        'value' => function ($model) {
                            $r = $model->toArray();
                            return ($r['actives_group']) ? '<b>'.$r['bookings_count'].'</b>' : $r['bookings_count'];
                        },
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>
