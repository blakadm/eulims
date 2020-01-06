<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcattachment */

$this->title = $model->pstc_attachment_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcattachment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pstc_attachment_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pstc_attachment_id], [
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
            'pstc_attachment_id',
            'filename',
            'pstc_request_id',
            'uploadedby_user_id',
            'uploadedby_name',
            'upload_date',
        ],
    ]) ?>

</div>
