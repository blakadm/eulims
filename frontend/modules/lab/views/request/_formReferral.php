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
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */
/* @var $form yii\widgets\ActiveForm */

$disabled= $model->posted ? true:false;
$disabled=$disabled || $model->status_id==2;
if(!empty($notified) == 1){
    $disabled = true;
    $notice = 1;
} else {
    $disabled = false;
    $notice = 0;
}
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
        $("#exrequestreferral-discount_id").val(0).trigger('change');
        $("#exrequestreferral-discount_id").prop('disabled',false);
    }else{//Fully Subsidized
        $("#exrequestreferral-discount_id").val(0).trigger('change');
        $("#exrequestreferral-discount_id").prop('disabled',true);
    }
    //$("#payment_type_id").val(this.value);
    $("#exrequestreferral-payment_type_id").val(this.value);
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
$sameLab = !empty($model->lab_id) ? $model->lab_id : 0;
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
    <?= $notice == 1 ? '<span style="top:6px;font-size:13px;position:absolute;" class="label label-danger">Edit not allowed because referral notification had been made.</span>':''; ?>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->where('request_type_id =:requestTypeId',[':requestTypeId'=>2])->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
        'pluginOptions' => [
            'allowClear' => false
        ]
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'sample_received_date')->widget(DateTimePicker::classname(), [
        //'readonly'=>true,
        'disabled' => $disabled,
    'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->sample_received_date));
        },
        'convertFormat' => true,
    'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            //'startDate'=>$RequestStartDate,
    ],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#exrequestreferral-sample_received_date').val();
                var d=dv.split(' ');
                $('#exrequestreferral-request_date').val(d[0]);
            }",
        ]
    ])->label('Sample Received Date'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
     <?= $form->field($model, 'lab_id')->widget(Select2::classname(), [
        'data' => $labreferral,
        //'type'=>DepDrop::TYPE_SELECT2,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Laboratory','disabled'=>$disabled],
        'pluginOptions'=>[
           //'depends'=>['rstlid','erequest-request_type_id'],
           'placeholder'=>'Select Laboratory',
           //'url'=>Url::to(['/api/ajax/getlab']),
           'LoadingText'=>'Loading...',
           'allowClear' => true
        ],
        'pluginEvents'=>[
            "change" => "function() { 
                if(this.value==3){//Metrology
                   $('#div_met').show();
                }else{
                   $('#div_met').hide();
                }

            }",
        ]
    ])->label('Laboratory'); ?>
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
    <div class="row" style="padding-left: 5px">
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
                echo $func->GetReferralCustomerList($form, $model, $disabled,'Customer');
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
        'data' => $modereleasereferral,
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
        'data' => $discountreferral,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Discount','disabled'=>$disabled || $model->payment_type_id==2],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents'=>[
            "change" => 'function() { 
                var discountid=this.value;
                console.log(discountid);
                $.get("/ajax/getdiscountreferral", {
                        discountid: discountid
                    }, function(result){
                    if(result){
                       $("#exrequestreferral-discount").val(result.rate);
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
        'data' => $purposereferral,
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
	'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'format' => 'yyyy-mm-dd'
	],
        'pluginEvents'=>[
            "change" => "",
        ]
    ]); ?>
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
    <div class="row" style="float: right;padding-right: 15px">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['disabled'=>$disabled,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-update']) ?>
        <?php if($model->isNewRecord){ ?>
        <?= Html::resetButton('Reset', ['disabled'=>$disabled,'class' => 'btn btn-danger']) ?>
        <?php } ?>
        <?= Html::Button('Cancel', ['class' => 'btn btn-default', 'id' => 'modalCancel', 'data-dismiss' => 'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
if(!$model->isNewRecord) {
    $this->registerJs("
        $('#exrequestreferral-lab_id').on('change', function() {
            var lab = $('#exrequestreferral-lab_id').val();
            if(lab != ".$sameLab." && lab > 0){
                $('#btn-update').attr('onclick','confirmLab()');
            } else {
                $('#btn-update').removeAttr('onclick');
            }
        });

        $('#btn-update').on('click', function(e){
            e.preventDefault();
            var lab = $('#exrequestreferral-lab_id').val();
            if(lab == ".$sameLab."){
                $('.request-form form').submit();
            }
        });
    ");
}
?>
<script type="text/javascript">
function confirmLab(){
    BootstrapDialog.show({
        title: "<span class='glyphicon glyphicon-warning-sign' style='font-size:18px;'></span> Warning",
        type: BootstrapDialog.TYPE_DANGER,
        message: "<div class='alert alert-danger'><p style='font-weight:bold;font-size:14px;'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp; Changing Laboratory will erase the samples and analyses of this request.</p><br><strong>Reasons:</strong><ul><li>Sample Type might not be available for the selected laboratory</li><li>Test/Calibration and Method might not be available for the selected laboratory</li></ul></div>",
        buttons: [
            {
                label: 'Proceed',
                cssClass: 'btn-primary',
                action: function(thisDialog){
                    thisDialog.close();
                    $('.request-form form').submit();
                }
            },
            {
                label: 'Close',
                action: function(thisDialog){
                    thisDialog.close();
                }
            }
        ]
    });
}
</script>