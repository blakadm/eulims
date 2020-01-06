<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\DepositType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposit-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deposit_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(Yii::$app->request->isAjax){ ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
