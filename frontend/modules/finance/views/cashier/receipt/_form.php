<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use common\models\finance\DepositType;
use common\models\finance\Paymentmode;
use common\models\finance\Collectiontype;
use common\models\finance\Orseries;
use common\components\Functions;
use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Receipt */
/* @var $form yii\widgets\ActiveForm */
$paymentlist='';
$func= new Functions();
$customer_name='';
if($op_model == ""){
    $customer_name='';
}else{
    $customer_name=$op_model->customer->customer_name;
}
?>

<div class="receipt-form" style="margin:0important;padding:0px!important;padding-bottom: 10px!important;">

    <?php $form = ActiveForm::begin(); ?>
    <div class="alert alert-info" style="background: #d9edf7 !important;margin-top: 1px !important;">
     <a href="#" class="close" data-dismiss="alert" >×</a>
    <p class="note" style="color:#265e8d">Fields with <i class="fa fa-asterisk text-danger"></i> are required.</p>
     
    </div>
   
    <div style="padding:0px!important;">
        <div class="row">
            <div class="col-sm-6">
           <?php 

                echo $form->field($model, 'deposit_type_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(DepositType::find()->all(), 'deposit_type_id', 'deposit_type'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Please Select ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ])->label('Deposit Type');
            ?>
            </div>   
            <div class="col-sm-6">
             <?php
             echo $form->field($model, 'receiptDate')->widget(DatePicker::classname(), [
             'options' => ['placeholder' => 'Select Date ...',
             'autocomplete'=>'off'],
             'type' => DatePicker::TYPE_COMPONENT_APPEND,
                 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'todayHighlight' => true,
                     'autoclose'=>true,   
                     'startDate' => date('Y-m-d'),
                     'endDate' => date('Y-m-d')
                 ]
             ]);
             ?>
            </div>
        </div>
        <div class="row">
          
            <div class="col-sm-6">
           <?php 

                echo $form->field($model, 'payment_mode_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Paymentmode::find()->all(), 'payment_mode_id', 'payment_mode'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Payment mode ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ])->label('Payment Mode');
            ?>
            </div>
            <div class="col-sm-6">
           <?php 

                echo $form->field($model, 'collectiontype_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Collectiontype::find()->all(), 'collectiontype_id', 'natureofcollection'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Collection Type ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ])->label('Collection Type');
            ?>
            </div>  
        </div>
        <div class="row">
          
            <div class="col-sm-6">
           <?php 

                echo $form->field($model, 'or_series_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Orseries::find()->all(), 'or_series_id', 'or_series_name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'O.R Series ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ])->label('O.R Series');
            ?>
             <?php echo $form->field($model, 'or')->hiddenInput()->label(false) ?>   
            </div> 
             <div class="col-sm-6">
                <!--<span  id ="nxt_or" class='alert alert-success'>O.R. #: <i class='glyphicon glyphicon-info-sign' title='Next O.R. number on selected series. \nO.R. # displayed herein is for guidance and reference only.'></i></span>
            --> 
           
               <div style="margin-top:25px;">
                   <span class="btn btn-block btn-success">O.R. #: <span id="next_or"></span> <i class='glyphicon glyphicon-info-sign' title='Next O.R. number on selected series. \nO.R. # displayed herein is for guidance and reference only.'></i></span>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
             
            <?php echo $form->field($model, 'payor')->textInput(['value'=>$customer_name ? $customer_name : $model->payor])->label('Payor') ?>
            </div>
        </div>
		
		
        <div class="form-group pull-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id'=>'createOP']) ?>
            <?php if(Yii::$app->request->isAjax){ ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
 <?php
    // This section will allow to popup a notification
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully","WARNING");
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Successfully Saved","SUCCESS",true);
            unset($session['savepopup']);
            $session->close();
        }
        if (isset($session['errorpopup'])) {
            $func->CrudAlert("Transaction Error","ERROR",true);
            unset($session['errorpopup']);
            $session->close();
        }
        if (isset($session['checkpopup'])) {
            $func->CrudAlert("Insufficient Wallet Balance","INFO",true,false,false);
            unset($session['checkpopup']);
            $session->close();
        }
    }
?>
</div>
<style>
    .modal-body{
        padding-top: 0px!important;
    }
</style>
<script type="text/javascript">
    $('#ext_receipt-or_series_id').on('change',function(e) {
        jQuery.ajax( {
            type: 'POST',
            url: '/finance/cashier/nextor?id='+$(this).val(),
            dataType: 'json',
            success: function ( response ) {
                if(response.success === true)
                {
                    //$("span.btn").appendClass("btn-success");
                    $('span.btn').removeClass("btn-danger");
                    $('span.btn').addClass("btn-success");
                    $('#next_or').html(response.nxtOR);
                    $('#ext_receipt-or').val(response.nxtOR);
                } else {
                    //$("span.btn").appendClass("btn-warning");
                    $('span.btn').addClass("btn-danger");
                    $('#next_or').html(response.nxtOR);
                    $('#ext_receipt-or').val('');
                }
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
       // alert('zdfsdf');
       
    });
</script>