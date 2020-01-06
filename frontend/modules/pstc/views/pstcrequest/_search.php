<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\PstcrequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcrequest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pstc_request_id') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'pstc_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'discount_id') ?>

    <?php // echo $form->field($model, 'submitted_by') ?>

    <?php // echo $form->field($model, 'received_by') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'accepted') ?>

    <?php // echo $form->field($model, 'local_request_id') ?>

    <?php // echo $form->field($model, 'pstc_respond_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
