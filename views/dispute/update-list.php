<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Set Dispute Status for Class:';
$this->params['breadcrumbs'][] = ['label' => 'Set Dispute Status for Class', 'url' => ['/dispute/list']];
$this->params['breadcrumbs'][] = ['label' => $model->class_id, 'url' => ['view', 'id' => $model->class_id]];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="users-index">
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    [
                        'attribute' => 'student_name',
                        'value' => function ($model) {
                            $model = $model->toArray();
                            return $model['student_name'];
                        },
                    ],
                    [
                        'attribute' => 'dispute_type',
                        'value' => function ($model) {
                            $model = $model->toArray();
                            return $model['dispute_type'];
                        },
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>

<div class="users-update">
    <div class="box">
	<div class="box-body">

	<?php $form = ActiveForm::begin(); ?>
	    
	<?= 
        $form->field($model, 'class_name')
        ->dropDownList(
            ['approved' => 'Release funds', 'declined' => 'Hold money'],
            ['prompt'=>'Choose...']    // options
        );
    ?>
	
	<div class="form-group">
	    <?= Html::submitButton('Set status', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

	</div>
    </div>

</div>
