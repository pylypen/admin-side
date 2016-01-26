<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Login', ['login', 'id' => $model->id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Image',
                'attribute' => 'image_url',
                'value' => $model->getImageUrl(),
                'format' => ['image', ['width' => '176', 'height' => '176']],
            ],
            'email:email',
            'user_email',
            'first_name',
            'last_name',
            'role',
            [
                'attribute' => 'type',
                'value' => isset(\common\models\Users::itemAlias('types')[$model->type]) ? \common\models\Users::itemAlias('types')[$model->type] : '',
            ],
            [
                'attribute' => 'account_state',
                'value' => $model::$states[$model->account_state],
            ],
        //  'status',
        ],
    ])

    ?>

</div>
