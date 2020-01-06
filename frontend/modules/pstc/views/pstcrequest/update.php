<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrequest */

$this->title = 'Update Pstcrequest: ' . $model->pstc_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pstc_request_id, 'url' => ['view', 'id' => $model->pstc_request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstcrequest-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
