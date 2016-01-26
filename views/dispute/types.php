<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dispute types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <p>
        <?= Html::a('Add Type', ['#'], [
                        'class' => 'btn btn-success',
                        'data-toggle' => 'modal',
                        'data-target' => '#addType'
            ]) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
                'columns' => [
                    [
                        'attribute' => 'type_id',
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'type_name',
                        'format' => 'html'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=> '{Edit} {Delete}',
                        'buttons' => [
                            'Edit' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/dispute/update-type?id={$model['type_id']}", ['title'=>"Update"]);
                            },
                            'Delete' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', "/dispute/delete-dispute-type?id={$model['type_id']}", ['title'=>"Delete"]);
                            }
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addType" tabindex="-1" role="dialog" aria-labelledby="addType">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Type</h4>
      </div>
      <div class="modal-body">
        <form method="get" action="/dispute/create-dispute-type">
            <div class="form-group">
                <label for="type_name" class="control-label">Type Name:</label>
                <input name="type_name" id="type_name" type="text" required>
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
