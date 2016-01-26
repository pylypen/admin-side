<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Update Admin Users:';
$this->params['breadcrumbs'][] = ['label' => 'Admin Users', 'url' => ['admin_users']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="users-update">
    <div class="box">
	<div class="box-body">

	<?php $form = ActiveForm::begin(); ?>
	    
	<?= $form->field($model, 'user_email')->textInput(['maxlength' => true]) ?>
	    
	<?= $form->field($model, 'user_password_hash')->passwordInput(['maxlength' => true]) ?>
	    
	<?= $form->field($model, 'user_instructors_edit')->dropDownList(
		$model->getEnumList('user_instructors_edit'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_students_edit')->dropDownList(
		$model->getEnumList('user_students_edit'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_classes_edit')->dropDownList(
		$model->getEnumList('user_classes_edit'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_approved_classes')->dropDownList(
		$model->getEnumList('user_approved_classes'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_approved_blogs')->dropDownList(
		$model->getEnumList('user_approved_blogs'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_approved_trainers')->dropDownList(
		$model->getEnumList('user_approved_trainers'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_instructors_earnings')->dropDownList(
		$model->getEnumList('user_instructors_earnings'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_instructors_invoices')->dropDownList(
		$model->getEnumList('user_instructors_invoices'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_payouts')->dropDownList(
		$model->getEnumList('user_payouts'),
		['prompt' => '']
	    );
	?>  
	    
	<?= $form->field($model, 'user_export_emails')->dropDownList(
		$model->getEnumList('user_export_emails'),
		['prompt' => '']
	    );
	?>  
	<div class="form-group">
	    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

	</div>
    </div>

</div>
