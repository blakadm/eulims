<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use common\components\Functions;
use common\models\finance\Orseries; 
use common\models\finance\DepositType; 
use kartik\widgets\DepDrop;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Check */
/* @var $form yii\widgets\ActiveForm */

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
                'options'=>['id'=>'deposit-deposit_type_id','placeholder' => 'Select Deposit type'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ])->label('Deposit Type');
            ?>
            </div>   
            <div class="col-sm-6">
              <?php 

                echo $form->field($model, 'or_series_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'deposit-or_series_id'],
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'depends'=>['deposit-deposit_type_id'],
                        'placeholder'=>'Select O.R Series',
                        'url'=>Url::to(['/finance/cashier/listorseries']),
                        'loadingText' => 'Loading...',
                ],
                ])->label('O.R Series');
             ?>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?php 

                    echo $form->field($model, 'start_or')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'deposit-start_or'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['deposit-deposit_type_id','deposit-or_series_id'],
                            'placeholder'=>'Select Start O.R',
                            'url'=>Url::to(['/finance/cashier/start-or']),
                            'loadingText' => 'Loading...',
                    ],
                        'pluginEvents'=>[
                            'depdrop:error'=>"function(event,id,value){
                            alert(value);
                            }"
                        ],
                    ])->label('Start O.R');
                 ?>
            </div>
            <div class="col-sm-6">
                <?php 

                    echo $form->field($model, 'end_or')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'deposit-end_or'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                            'depends'=>['deposit-start_or'],
                            'placeholder'=>'Select End O.R',
                            'url'=>Url::to(['/finance/cashier/end-or']),
                            'loadingText' => 'Loading...',
                    ],
                    ])->label('End O.R');
                 ?>
            </div>  
        </div>
        <div class="row">
            <div class="col-sm-6">
                <?php
                echo $form->field($model, 'deposit_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Select Date ...',
                'autocomplete'=>'off'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                        'autoclose'=>true,   
                    ]
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                  <?php
                    echo $form->field($model, 'amount')->widget(MaskMoney::classname(), [
                         'readonly'=>true,
                         'options'=>[
                             'style'=>'text-align: right'
                         ],
                         'pluginOptions' => [
                            'prefix' => '₱ ',
                            'allowNegative' => false,
                         ]
                        ])->label('Amount');
                    ?>
            </div>  
        </div>
        <div class="form-group pull-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id'=>'createCheck']) ?>
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
   
    $('#deposit-start_or').on('change',function() {
        $('#deposit-amount').val('');
    });
    $('#deposit-end_or').on('change',function() {
        getTotal();
    });
    
    function getTotal(){
        var start_or_id=$('#deposit-start_or').val();
        var end_or_id=$('#deposit-end_or').val();
        $.post({
            url: '/finance/cashier/calculate-total-deposit?id='+start_or_id+'&endor='+end_or_id, // your controller action
            dataType: 'html',
            success: function(data) {
                var tot=parseFloat(data);
                $("#deposit-amount-disp").val(tot);
                $("#deposit-amount").val(tot);
                $("#deposit-amount-disp").maskMoney('mask', tot);
            }
        });
    }
    
</script>
