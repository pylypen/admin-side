<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = $model->type == \common\models\ClaimPageProfiles::TYPE_CLAIM ? 'Claim Page Expert Display' : 'Main Page Expert Display';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-index">  

    <div class="box">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'options' => [
                        'id' => 'alertForm'
                    ],
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{endWrapper}",
                    ],
            ]);

            ?>
            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'type')->hiddenInput()->label(false); ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-success', 'id' => 'submit_button']) ?>
            </div>
            <?php ActiveForm::end(); ?>



        </div>
    </div>



    <div class="box">
        <div class="box-body">
            <?php Pjax::begin() ?>
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Expert profile',
                        'attribute' => 'expert.id',
                        'format' => 'html',
                        'value' => function ($model) {
                            return Html::a($model->expert->user->name, ['users/view', 'id' => $model->expert->user->id]);
                        }
                        ],
                        [
                            'label' => 'Expert email',
                            'attribute' => 'expert.email',
                            'format' => 'email',
                            'value' => function ($model) {
                                return $model->expert->user->email;
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete-claim-page-profile}',
                            'contentOptions' => ['class' => 'id'],
                            'buttons' => [
                                'delete-claim-page-profile' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this?',
                                                'method' => 'post',
                                            ],
                                    ]);
                                },
                                ],
                            ],
                        ],
                    ]);

                    ?>
                    <?php Pjax::end() ?>
        </div>
    </div>
</div>
