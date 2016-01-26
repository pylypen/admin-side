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
            [
                'label' => 'Image',
                'attribute' => 'image_url',
                'value' => $model['payment']['payment_image'],
                'visible' => $model['payment_image_visible'],
                'format' => ['image', ['width' => '176', 'height' => '176']],
            ],
            [
                'label' => 'First Name',
                'value' => $model['payment']['payment_first_name'],
                'visible' => $model['payment_first_name_visible'],
            ],
            [
                'label' => 'Last Name',
                'value' => $model['payment']['payment_last_name'],
                'visible' => $model['payment_last_name_visible'],
            ],
            [
                'label' => 'Bussines Name',
                'value' => $model['payment']['payment_bussines_name'],
                'visible' => $model['payment_bussines_name_visible'],
            ],
            [
                'label' => 'Active Lead',
                'value' => $model['active_lead'],
                'visible' => $model['payment_active_lead_visible'],
            ],
            [
                'label' => 'Clientele',
                'value' => $model['clientele'],
                'visible' => $model['payment_clientele_visible'],
            ],
            [
                'label' => 'Certifications',
                'value' => $model['certifications'],
                'visible' => $model['payment_certifications_visible'],
            ],
            [
                'label' => 'Profession',
                'value' => $model['profession'],
                'visible' => $model['payment_profession_visible'],
            ],
            [
                'label' => 'ZIP',
                'value' => $model['payment']['payment_zip'],
                'visible' => $model['payment_zip_visible'],
            ],
            [
                'label' => 'Gender',
                'value' => $model['payment']['payment_gender'],
                'visible' => $model['payment_gender_visible'],
            ],
            [
                'label' => 'Year Exp.',
                'value' => $model['payment']['payment_year_exp'],
                'visible' => $model['payment_year_exp_visible'],
            ],
            [
                'label' => 'Style',
                'value' => $model['payment']['payment_style'],
                'visible' => $model['payment_style_visible'],
            ],
            [
                'label' => 'Video Url',
                'value' => $model['payment']['payment_video_url'],
                'visible' => $model['payment_video_url_visible'],
            ],
            [
                'label' => 'Video Title',
                'value' => $model['payment']['payment_video_title'],
                'visible' => $model['payment_video_title_visible'],
            ],
            [
                'label' => 'Biography',
                'value' => $model['payment']['payment_biography'],
                'visible' => $model['payment_biography_visible'],
            ],
        ],
    ])

    ?>

</div>
