<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Update Users:';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="users-update">
    <div class="box">
	<div class="box-body">

	<?php $form = ActiveForm::begin(); ?>
	    
	<?= $form->field($model, 'user_email')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'user_fees')->textInput(['maxlength' => true]) ?>


	<div class="form-group">
	    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

	</div>
    </div>

</div>
