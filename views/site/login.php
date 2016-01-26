<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
	    <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'action' => '/site/login',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
            ],
        ]); ?>

	    <?= $form
            ->field($model, 'user_email')
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('user_email')]) ?>

	    <?= $form
            ->field($model, 'user_password_hash')
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('user_password_hash')]) ?>

		<?php # $form->field($model, 'rememberMe')->checkbox(['template' => "<div class=\"col-lg-offset-1 col-lg-12\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",]) ?>

		<div class="form-group">
		    <div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		    </div>
		</div>

	    <?php ActiveForm::end(); ?>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
