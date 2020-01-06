<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\lab\CsfSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="csf-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'nob') ?>

    <?= $form->field($model, 'tom') ?>

    <?= $form->field($model, 'service') ?>

    <?php // echo $form->field($model, 'd_deliverytime') ?>

    <?php // echo $form->field($model, 'd_accuracy') ?>

    <?php // echo $form->field($model, 'd_speed') ?>

    <?php // echo $form->field($model, 'd_cost') ?>

    <?php // echo $form->field($model, 'd_attitude') ?>

    <?php // echo $form->field($model, 'd_overall') ?>

    <?php // echo $form->field($model, 'i_deliverytime') ?>

    <?php // echo $form->field($model, 'i_accuracy') ?>

    <?php // echo $form->field($model, 'i_speed') ?>

    <?php // echo $form->field($model, 'i_cost') ?>

    <?php // echo $form->field($model, 'i_attitude') ?>

    <?php // echo $form->field($model, 'i_overall') ?>

    <?php // echo $form->field($model, 'recommend') ?>

    <?php // echo $form->field($model, 'essay') ?>

    <?php // echo $form->field($model, 'r_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
