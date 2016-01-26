<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Trainer View';
$this->params['breadcrumbs'][] = ['label' => 'Trainer View', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-view">

    <p> 
        <?php if ($approve) : ?>
            <?= Html::a('Approve', ['/users/approve-trainer', 'id' => $model['user_id']], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Image',
                'attribute' => 'image_url',
                'value' => $model['payment']['payment_image'],
                'format' => ['image', ['width' => '176', 'height' => '176']],
            ],
            [
                'label' => 'First Name',
                'value' => $model['payment']['payment_first_name'],
            ],
            [
                'label' => 'Last Name',
                'value' => $model['payment']['payment_last_name'],
            ],
            [
                'label' => 'Bussines Name',
                'value' => $model['payment']['payment_bussines_name'],
            ],
            [
                'label' => 'Active Lead',
                'value' => $model['active_lead'],
            ],
            [
                'label' => 'Clientele',
                'value' => $model['clientele'],
            ],
            [
                'label' => 'Certifications',
                'value' => $model['certifications'],
            ],
            [
                'label' => 'Profession',
                'value' => $model['profession'],
            ],
            [
                'label' => 'ZIP',
                'value' => $model['payment']['payment_zip'],
            ],
            [
                'label' => 'Gender',
                'value' => $model['payment']['payment_gender'],
            ],
            [
                'label' => 'Year Exp.',
                'value' => $model['payment']['payment_year_exp'],
            ],
            [
                'label' => 'Style',
                'value' => $model['payment']['payment_style'],
            ],
            [
                'label' => 'Video Url',
                'value' => $model['payment']['payment_video_url'],
            ],
            [
                'label' => 'Video Title',
                'value' => $model['payment']['payment_video_title'],
            ],
            [
                'label' => 'Biography',
                'value' => $model['payment']['payment_biography'],
            ],
        ],
    ])

    ?>

</div>
