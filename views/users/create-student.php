<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Create student profile';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="users-create">
    <?=
    $this->render('_form-student', [
        'model' => $model,
    ])

    ?>

</div>
