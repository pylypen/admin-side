<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Financials Info';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="users-index">
    <p>
        <?= Html::a('Set max sum for status',
                    ['#'],
                    [
                        'class' => 'btn btn-success',
                        'target '=> '_blank',
                        'data-toggle' => 'modal',
                        'data-target' => '#setSumStatus',
                    ]
            ); ?>
    </p>
    <div class="box">
        <div class="box-body">           
	    <div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-aqua">
		  <div class="inner">
		    <h3>$ <?= $data['gross']; ?></h3>
		    <p>Gross revenue</p>
		  </div>
		  <div class="icon">
		    <i class="ion ion-bag"></i>
		  </div>
		</div>
            </div>
	    <div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-green">
		  <div class="inner">
		    <h3>$ <?= $data['net']; ?></h3>
		    <p>Net revenue</p>
		  </div>
		  <div class="icon">
		    <i class="ion ion-bag"></i>
		  </div>
		</div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="setSumStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Set max sum for status</h4>
      </div>
      <div class="modal-body">
        <form method="get">
            <?php foreach ($sum_status as $val): ?>
                <div class="form-group">
                  <label for="<?= $val->admin_data_param; ?>" class="control-label"><?= $val->admin_data_param; ?>:</label>
                  <input type="number" class="form-control" id="<?= $val->admin_data_param; ?>" name="<?= $val->admin_data_param; ?>" placeholder="$ 000" required="required" min="10" value="<?= $val->admin_data_value; ?>">
                </div>
            <?php endforeach; ?>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
