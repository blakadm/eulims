<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Tagging */

$this->title = $model->tagging_id;
$this->params['breadcrumbs'][] = ['label' => 'Taggings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tagging-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tagging_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tagging_id], [
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
            'tagging_id',
            'user_id',
            'analysis_id',
            'start_date',
            'end_date',
            'tagging_status_id',
            'cancel_date',
            'reason',
            'cancelled_by',
            'disposed_date',
            'iso_accredited',
        ],
    ]) ?>

</div>
