<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use common\components\Functions;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Check */
/* @var $form yii\widgets\ActiveForm */
$url ='/finance/cashier/add-check';
                     
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
             <?php echo $form->field($model, 'bank')->textInput() ?>
            </div>   
            <div class="col-sm-6">
             <?php echo $form->field($model, 'checknumber')->textInput() ?>
            </div> 
        </div>
        <div class="row">
          
            <div class="col-sm-6">
            <?php
             echo $form->field($model, 'checkdate')->widget(DatePicker::classname(), [
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
                <?php echo $form->field($model, 'amount')->textInput() ?>
            </div>  
        </div>
      
        <div class="form-group pull-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id'=>'createCheck',
                
                ]) ?>
            <?php if(Yii::$app->request->isAjax){ ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<style>
    .modal-body{
        padding-top: 0px!important;
    }
</style>
