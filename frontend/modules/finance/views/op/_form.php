<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\finance\Collectiontype;
use common\models\finance\Paymentmode;
use common\models\lab\Customer;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use common\components\Functions;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Op */
/* @var $form yii\widgets\ActiveForm */
$paymentlist='';
$disable='';
 if($status == 0){
 $disable=true;
 }
 else{
 $disable=false;    
 }

?>
<?php
    if(!$model->isNewRecord){
    ?>
    <script type="text/javascript">
       $(document).ready(function(){
           $(".select-on-check-all").click();
        });
    </script>
    <?php
    
    }
?>
<div class="orderofpayment-form" style="margin:0important;padding:0px!important;padding-bottom: 10px!important;">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'payment_mode_id')->hiddenInput()->label(false) ?>
    <div class="alert alert-info" style="background: #d9edf7 !important;margin-top: 1px !important;">
     <a href="#" class="close" data-dismiss="alert" >×</a>
    <p class="note" style="color:#265e8d">Fields with <i class="fa fa-asterisk text-danger"></i> are required.</p>
     </div>
   
    <div style="padding:0px!important;">
        <div class="row">
            <div class="col-sm-6">
           <?php 

                echo $form->field($model, 'collectiontype_id')->widget(Select2::classname(), [
                'data' => $collection_type,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Collection Type ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ]);
            ?>
            </div>   
            <div class="col-sm-6">
             <?php
             echo $form->field($model, 'order_date')->widget(DatePicker::classname(), [
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
            $disabled=false;
            if($status == 1){
            $disabled=true;
            }
            
            echo $form->field($model,'customer_id')->widget(Select2::classname(),[
                'data' => $customers,
                'theme' => Select2::THEME_KRAJEE,
                'options' => [
                    'placeholder' => 'Select Customer',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var customerId = $(this).val();
                        $.ajax({
                            url: '".Url::toRoute("/finance/op/getlistrequest")."',
                            //dataType: 'json',
                            method: 'GET',
                            data: {id:customerId},
                            success: function (data, textStatus, jqXHR) {
                                $('.image-loader').removeClass( \"img-loader\" );
                                if (customerId > 0){
                                    $('#op-subsidiary_customer_ids').prop('disabled', false);
                                } else {
                                     $('#op-subsidiary_customer_ids').prop('disabled', true);
                                }
                                $('#requests').html(data);
                            },
                            beforeSend: function (xhr) {
                                $('.image-loader').addClass( \"img-loader\" );
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus+' '+errorThrown);
                            }
                        });
                    }",
                ],
            ]);
            ?>    
           
            </div>
             <div class="col-sm-6">
               <?= $form->field($model, 'subsidiary_customer_ids')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Customer::find()->where(['not',['customer_id'=>$model->customer_id]])->all(),'customer_id','customer_name'),
                'theme' => Select2::THEME_KRAJEE,    
                'language' => 'en',
                    'disabled'=>true,
                    'options' => [
                    'placeholder' => 'Select Subsidiary Customer(s)...',
                    'multiple' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                            var customerId = $('#op-customer_id').val();
                            var subsidiaryId = $(this).val();
                            var ids='';
                            if (subsidiaryId != ''){
                                ids=subsidiaryId+','+customerId;
                            } else {
                                ids=customerId;
                            }
                            $.ajax({
                                url: '".Url::toRoute("/finance/op/getlistrequest")."',
                                //dataType: 'json',
                                method: 'GET',
                                data: {id:ids},
                                success: function (data, textStatus, jqXHR) {
                                    $('.image-loader').removeClass( \"img-loader\" );
                                    if (customerId > 0){
                                        $('#op-subsidiary_customer_ids').prop('disabled', false);
                                    } else {
                                        $('#op-subsidiary_customer_ids').prop('disabled', true);
                                    }
                                    $('#requests').html(data);
                                },
                                beforeSend: function (xhr) {
                                    $('.image-loader').addClass( \"img-loader\" );
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log(textStatus+' '+errorThrown);
                                }
                            });
                    }
                    ",
                ]
                    ])->label('Subsidiary Customer(s) * (Optional)'); ?> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">  
            <div class="row">
            <div class="col-lg-12">  
                <div id="requests" style="padding:0px!important;">    	
                  
                </div> 
            </div>
            </div> 
            </div>
        </div> 
		 <?php echo $form->field($model, 'RequestIds')->hiddenInput()->label(false) ?>
        <div class="row">
            <div class="col-lg-12"> 
                <?= $form->field($model, 'purpose')->textarea(['maxlength' => true]); ?>
            </div>
        </div>

        <input type="text" id="wallet" name="wallet" hidden>
        
        <div class="form-group pull-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'createOP']) ?>
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

<style type="text/css">
/* Absolute Center Spinner */
.img-loader {
    position: fixed;
    z-index: 999;
    /*height: 2em;
    width: 2em;*/
    height: 64px;
    width: 64px;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-image: url('/images/img-png-loader64.png');
    background-repeat: no-repeat;
}
/* Transparent Overlay */
.img-loader:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
}
</style>
