<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Update Dispute Type:';
$this->params['breadcrumbs'][] = ['label' => 'Dispute Type', 'url' => ['/dispute/types']];
$this->params['breadcrumbs'][] = ['label' => $model->type_id, 'url' => ['view', 'id' => $model->type_id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="users-update">
    <div class="box">
	<div class="box-body">

	<?php $form = ActiveForm::begin(); ?>
	    
	<?= $form->field($model, 'type_name')->textInput(['maxlength' => true]) ?>
	
	<div class="form-group">
	    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

	</div>
    </div>

</div>
