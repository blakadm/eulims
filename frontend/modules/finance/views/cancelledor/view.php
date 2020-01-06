<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOr */

$this->title = $model->cancelled_or_id;
$this->params['breadcrumbs'][] = ['label' => 'Cancelled Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cancelled-or-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cancelled_or_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cancelled_or_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cancelled_or_id',
            'receipt_id',
            'reason',
            'cancel_date',
        ],
    ]) ?>

</div>
