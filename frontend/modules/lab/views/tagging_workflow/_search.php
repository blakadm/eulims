<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TaggingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tagging-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tagging_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'analysis_id') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'tagging_status_id') ?>

    <?php // echo $form->field($model, 'cancel_date') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'cancelled_by') ?>

    <?php // echo $form->field($model, 'disposed_date') ?>

    <?php // echo $form->field($model, 'iso_accredited') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
