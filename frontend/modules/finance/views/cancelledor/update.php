<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOr */

$this->title = 'Update Cancelled Or: ' . $model->cancelled_or_id;
$this->params['breadcrumbs'][] = ['label' => 'Cancelled Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cancelled_or_id, 'url' => ['view', 'id' => $model->cancelled_or_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cancelled-or-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
