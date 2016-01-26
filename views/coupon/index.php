<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotional coupon codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <p>
        <?= Html::a('Create Coupon', ['#'], [
                        'class' => 'btn btn-success',
                        'data-toggle' => 'modal',
                        'data-target' => '#createCoupon'
            ]) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'coupon_token',
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'coupon_percent',
                        'format' => 'html'
                    ],
//                    [ // coupon_expire_time
//                        'label' => 'Last Name',
//                        'value' => function ($model) {
//                            return $model->payment->payment_last_name;
//                        },
//                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="createCoupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create Coupon</h4>
      </div>
      <div class="modal-body">
        <form method="get">
            <div class="form-group">
                <label for="exp_date" class="control-label">Set Expired Date:</label>
                <input name="exp_date" class="datepicker" id="exp_date" type="text" data-provide="datepicker" placeholder="mm/dd/yyyy" required>
            </div>
            <div class="form-group">
                <label for="set_percent" class="control-label">Set Expired Date:</label>
                <input type="text" id="set_percent" name="set_percent" data-min="1" value="" required />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>