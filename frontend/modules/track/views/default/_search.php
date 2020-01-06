<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\lab\RequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'request_ref_num') ?>

    <?= $form->field($model, 'request_datetime') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'lab_id') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'payment_type_id') ?>

    <?php // echo $form->field($model, 'modeofrelease_ids') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'discount_id') ?>

    <?php // echo $form->field($model, 'purpose_id') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'report_due') ?>

    <?php // echo $form->field($model, 'conforme') ?>

    <?php // echo $form->field($model, 'receivedBy') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'posted') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'selected') ?>

    <?php // echo $form->field($model, 'other_fees_id') ?>

    <?php // echo $form->field($model, 'request_type_id') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'recommended_due_date') ?>

    <?php // echo $form->field($model, 'est_date_completion') ?>

    <?php // echo $form->field($model, 'items_receive_by') ?>

    <?php // echo $form->field($model, 'equipment_release_date') ?>

    <?php // echo $form->field($model, 'certificate_release_date') ?>

    <?php // echo $form->field($model, 'released_by') ?>

    <?php // echo $form->field($model, 'received_by') ?>

    <?php // echo $form->field($model, 'payment_status_id') ?>

    <?php // echo $form->field($model, 'completed') ?>

    <?php // echo $form->field($model, 'request_old_id') ?>

    <?php // echo $form->field($model, 'oldColumn_requestId') ?>

    <?php // echo $form->field($model, 'oldColumn_sublabId') ?>

    <?php // echo $form->field($model, 'oldColumn_orId') ?>

    <?php // echo $form->field($model, 'oldColumn_completed') ?>

    <?php // echo $form->field($model, 'oldColumn_cancelled') ?>

    <?php // echo $form->field($model, 'oldColumn_create_time') ?>

    <?php // echo $form->field($model, 'customer_old_id') ?>

    <?php // echo $form->field($model, 'tmpCustomerID') ?>

    <?php // echo $form->field($model, 'local_request_id') ?>

    <?php // echo $form->field($model, 'local_customer_id') ?>

    <?php // echo $form->field($model, 'is_sync_up') ?>

    <?php // echo $form->field($model, 'is_updated') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'sample_received_date') ?>

    <?php // echo $form->field($model, 'referral_id') ?>

    <?php // echo $form->field($model, 'contact_num') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
