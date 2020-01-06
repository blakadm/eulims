<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcattachment */

$this->title = 'Update Pstcattachment: ' . $model->pstc_attachment_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pstc_attachment_id, 'url' => ['view', 'id' => $model->pstc_attachment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstcattachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
