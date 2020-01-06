<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cancelled-or-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'receipt_id')->textInput() ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true,'placeholder' => "Reason of Cancellation"]) ?>

    <?= $form->field($model, 'cancel_date')->textInput() ?>

    <?= $form->field($model, 'cancelledby')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
