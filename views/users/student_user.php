<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Student View';
$this->params['breadcrumbs'][] = ['label' => 'Student View', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'First Name',
                'value' => $model['payment']['payment_first_name'],
            ],
            [
                'label' => 'Last Name',
                'value' => $model['payment']['payment_last_name'],
            ],
            [
                'label' => 'Gender',
                'value' => $model['payment']['payment_gender'],
            ],
            [
                'label' => 'Weight',
                'value' => $model['payment']['payment_weight'],
            ],
            [
                'label' => 'Level of fitness',
                'value' => $model['payment']['payment_fitness'],
            ],
            [
                'label' => 'Height',
                'value' => $model['payment']['payment_height'],
            ],
            [
                'label' => 'Strength',
                'value' => $model['payment']['payment_strength'],
            ],
            
        ],
    ])

    ?>

</div>
