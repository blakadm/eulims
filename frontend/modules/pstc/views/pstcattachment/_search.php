<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\PstcattachmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcattachment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pstc_attachment_id') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'pstc_request_id') ?>

    <?= $form->field($model, 'uploadedby_user_id') ?>

    <?= $form->field($model, 'uploadedby_name') ?>

    <?php // echo $form->field($model, 'upload_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
