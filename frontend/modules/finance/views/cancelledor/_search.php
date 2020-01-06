<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cancelled-or-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cancelled_or_id') ?>

    <?= $form->field($model, 'receipt_id') ?>

    <?= $form->field($model, 'reason') ?>

    <?= $form->field($model, 'cancel_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
