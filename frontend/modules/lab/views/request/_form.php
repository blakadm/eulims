<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\lab\Lab;
use kartik\datetime\DateTimePicker;
use common\models\lab\Customer;

use yii\web\JsExpression;
use kartik\widgets\DatePicker;
use common\models\lab\Paymenttype;
use common\models\lab\Discount;
use common\models\lab\Modeofrelease;
use common\models\lab\Purpose;
use kartik\widgets\SwitchInput;
use common\components\Functions;
use yii\bootstrap\Modal;
use common\models\lab\RequestType;
use common\models\lab\Request;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */
/* @var $form yii\widgets\ActiveForm */

$disabled= $model->posted ? true:false;
$disabled=$disabled || $model->status_id==2;
if($disabled){
    $Color="#eee";
}else{
    $Color="white";
}
if($model->lab_id==3){
    $PanelStyle='';
}else{
    $PanelStyle='display: none';
}
$js=<<<SCRIPT
    if(this.value==1){//Paid
        $("#erequest-discount_id").val(0).trigger('change');
        $("#erequest-discount_id").prop('disabled',false);
    }else{//Fully Subsidized
        $("#erequest-discount_id").val(8).trigger('change');
       
    }
    $("#erequest-payment_type_id").val(this.value);  
SCRIPT;

        
$rstl_id= Yii::$app->user->identity->profile->rstl_id;
// Check Whether previous date will be disabled
$TRequest=Request::find()->where(["DATE_FORMAT(`request_datetime`,'%Y-%m-%d')"=>date("Y-m-d"),'rstl_id'=>$rstl_id])->count();
if($TRequest>0){
    $RequestStartDate=date("Y-m-d");
}else{
    $RequestStartDate="";
}

$model->modeofreleaseids=$model->modeofrelease_ids;
?>

<div class="request-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'rstl_id')->hiddenInput()->label(false) ?>
    <?php if($model->request_ref_num!=NULL){ ?>
    <?= $form->field($model, 'request_ref_num')->hiddenInput(['maxlength' => true])->label(false) ?>
    <?php } ?>
    <?= $form->field($model, 'request_date')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'posted')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'modeofrelease_ids')->hiddenInput(['id'=>'modeofrelease_ids'])->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'total')->hiddenInput(['maxlength' => true])->label(false) ?>
    <input type="hidden" id="rstlid" name="rstlid" value="<?= $GLOBALS["rstl_id"] ?>">
<div class="row">

    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->where('request_type_id =:requestTypeId',[':requestTypeId'=>1])->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
        'pluginOptions' => [
            'allowClear' => false
        ]
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
	'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
        },
        'convertFormat' => true,
	'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            'startDate'=>$RequestStartDate,
	],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#erequest-request_datetime').val();
                var d=dv.split(' ');
                $('#erequest-request_date').val(d[0]);
            }",
        ]
    ])->label('Request Date'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
   

<?= $form->field($model, 'lab_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Lab::find()->where('active =:active',[':active'=>1])->all(),'lab_id','labname'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Lab','readonly'=>'readonly'],
        'pluginOptions' => [
            'allowClear' => false
        ]
    ])->label('Lab'); ?>


    </div>
    <div class="col-md-6">
        <label class="control-label">Payment Type</label>
        <div class="col-md-12">
        <?php echo $form->field($model, 'payment_type_id')->radioList(
            ArrayHelper::map(Paymenttype::find()->all(),'payment_type_id','type'),
            ['itemOptions' => ['disabled' => $disabled,'onchange'=>$js]]
        )->label(false); ?>
        </div>
    </div>
</div>
<div class="panel panel-success" id="div_met" style="padding-bottom: 10px;<?= $PanelStyle ?>">
    <div class="panel-heading">Metrology Request Details</div>
    <div class="row" style="padding-left: 5px">*
        <div class="col-md-6">
            <label class="control-label">Recommended Due Date</label>
            <div class="col-md-12">
                <?php
                echo DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'recommended_due_date',
                    'readonly' => true,
                    'disabled' => $disabled,
                    'options' => ['placeholder' => 'Enter Date'],
                    'value' => function($model) {
                        return date("m/d/Y", $model->recommended_due_date);
                    },
                    'pluginOptions' => [
                        'autoclose' => true,
                        'removeButton' => false,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'pluginEvents' => [
                        "change" => "function() {  }",
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Estimated Date Completion</label>
            <div class="col-md-12">
                <?php
                echo DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'est_date_completion',
                    'readonly' => true,
                    'disabled' => $disabled,
                    'options' => ['placeholder' => 'Enter Date'],
                    'value' => function($model) {
                        return date("m/d/Y", $model->est_date_completion);
                    },
                    'pluginOptions' => [
                        'autoclose' => true,
                        'removeButton' => false,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'pluginEvents' => [
                        "change" => "function() {  }",
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="row" style="padding-left: 5px">
        <div class="col-md-6">
            <label class="control-label">Date Release of Equipment</label>
            <div class="col-md-12">
                <?php
                echo DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'equipment_release_date',
                    'readonly' => true,
                    'disabled' => $disabled,
                    'options' => ['placeholder' => 'Enter Date'],
                    'value' => function($model) {
                        return date("m/d/Y", $model->equipment_release_date);
                    },
                    'pluginOptions' => [
                        'autoclose' => true,
                        'removeButton' => false,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'pluginEvents' => [
                        "change" => "function() {  }",
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Date Release of Certificate</label>
            <div class="col-md-12">
                <?php
                echo DatePicker::widget([
                    'model' => $model,
                    'attribute' => 'certificate_release_date',
                    'readonly' => true,
                    'disabled' => $disabled,
                    'options' => ['placeholder' => 'Enter Date'],
                    'value' => function($model) {
                        return date("m/d/Y", $model->certificate_release_date);
                    },
                    'pluginOptions' => [
                        'autoclose' => true,
                        'removeButton' => false,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'pluginEvents' => [
                        "change" => "function() {  }",
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
            <div class="input-group">
                <?php
                $func = new Functions();
                echo $func->GetCustomerList($form, $model, $disabled,'Customer');
                if($disabled){
                    $btnDisp=" disabled='disabled'";
                }else{
                    $btnDisp="";
                }
                ?> 
                <span class="input-group-btn" style="padding-top: 25.5px">
                    <button onclick="LoadModal('Create New Customer', '/customer/info/create');"<?= $btnDisp ?> class="btn btn-default" type="button"><i class="fa fa-user-plus"></i></button>
                </span>
            </div>
    </div>
    <div class="col-md-6">
     <?= $form->field($model, 'modeofreleaseids')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Modeofrelease::find()->all(),'modeofrelease_id','mode'),
        //'initValueText'=>$model->modeofrelease_ids,
        'language' => 'en',
         'options' => [
            'placeholder' => 'Select Mode of Release...',
            'multiple' => true,
            'disabled'=>$disabled
        ],
        'pluginEvents' => [
            "change" => "function() { 
                $('#modeofrelease_ids').val($(this).val());
            }
            ",
        ]
    ])->label('Mode of Release'); ?> 
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'discount_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Discount::find()->all(),'discount_id','type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Discount','disabled'=>$disabled || $model->payment_type_id==2],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents'=>[
            "change" => 'function() { 
                var discountid=this.value;
                console.log(discountid);
                $.post("/ajax/getdiscount/", {
                        discountid: discountid
                    }, function(result){
                    if(result){
                       $("#erequest-discount").val(result.rate);
                    }
                });
            }
        ',]
    ])->label('Discount'); ?>   
    </div>
     <div class="col-md-6">
    <?= $form->field($model, 'discount')->textInput(['maxlength' => true,'readonly'=>true,'style'=>'background-color: '.$Color])->label('Discount(%)') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'purpose_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Purpose::find()->all(),'purpose_id','name'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Purpose'); ?>
    </div>
    <div class="col-md-6">

 

    <?= $form->field($model, 'report_due')->widget(DatePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
	'options' => ['placeholder' => 'Report Due'],
        'value'=>function($model){
             return date("m/d/Y",$model->report_due);
        },
       //'setOnEmpty' => true, 
        'value' => '',
	'pluginOptions' => [
            'autoclose' => true,
            // 'setOnEmpty' => true,
            'removeButton' => false,
            'format' => 'yyyy-mm-dd'
	],
        'pluginEvents'=>[
            "change" => "",
        ]
    ])->label('Report Due'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'conforme')->textInput(['readonly' => $disabled]) ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'receivedBy')->textInput(['readonly' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'contact_num')->textInput(['readonly' => $disabled]) ?>
    </div>
    <div class="col-md-6">
    
    </div>
</div>
    <div class="row" style="float: right;padding-right: 15px">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['disabled'=>$disabled,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if($model->isNewRecord){ ?>
        <?= Html::resetButton('Reset', ['disabled'=>$disabled,'class' => 'btn btn-danger']) ?>
        <?php } ?>
        <?= Html::Button('Cancel', ['class' => 'btn btn-default', 'id' => 'modalCancel', 'data-dismiss' => 'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
