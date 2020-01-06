<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\TypeaheadBasic;
use kartik\widgets\Typeahead;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Sample */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
if(count($sampletype) > 0){
    $data = $sampletype;
} else {
    $data = ['' => 'No Sampletype'] + $sampletype;
}
$sameSampletype = !empty($model->sampletype_id) ? $model->sampletype_id : 0;
?>

<div class="sample-form">

    <div class="image-loader" style="display: hidden;"></div>

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>
    <?php if(empty($model->sample_id)): ?>
    <div class="row">
        <div class="col-sm-3" style="margin-top: 10px;">
            <label>Sample Quantity</label>
        </div>
        <div class="col-sm-3">
            <div class="input-group" style="margin-bottom: 15px;">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="qnty">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <input type="text" name="qnty" class="form-control input-number" value="1" min="1" max="100" style="width: 50px;text-align: center;">
                <span class="input-group-btn" style="float:left;">
                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="qnty">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
            </div>
        </div>
        <div class="err-message" style="margin-top: 10px;font-size: 12px;color: #FF0000;"></div>
    </div>
    <?php endif; ?>

    <?php
        if($labId == 2){
            $startDiv = '<div class="col-sm-6">';
            $endDiv = "</div>";
        } else {
            $startDiv = '<div class="col-sm-12">';
            $endDiv = "</div>";
        }
    ?>

    <div class="row">
        <?php
            echo $startDiv;
            echo $form->field($model,'sampletype_id')->widget(Select2::classname(),[
                    'data' => $sampletype,
                    'theme' => Select2::THEME_KRAJEE,
                    //'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['id'=>'sample-sampletype_id'],
                    'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Sample Type'],
                ]);
            echo $endDiv;

			if($labId == 2){
				echo $startDiv;
				echo $form->field($model, 'sampling_date')->widget(DateTimePicker::classname(), [
					'options' => [
						'placeholder' => 'Enter sampling date ...',
						'autocomplete'=>'off'
					],
					//'value' => $model->sampling_date ? date('m/d/Y h:i A', strtotime($model->sampling_date)) : date("m/d/Y h:i A"),
					'value'=>function($model){
						return date("m/d/Y h:i:s A", strtotime($model->sampling_date));
					},
					//'readonly' => true,
					'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'mm/dd/yyyy HH:ii:ss P',
						'todayHighlight' => true,
						//'startDate' => date('m/d/Y h:i:s A'),
					]
				]);
				echo $endDiv;
			} else {
				echo "";
			}
		?>
    </div>
    <div class="row">
        <div class="col-sm-12">
        <?php
            echo '<label class="control-label">Sample Template</label>';
            echo Select2::widget([
                'name' => 'saved_templates',
                'data' => $sampletemplate,
                'theme' => Select2::THEME_KRAJEE,
                'pluginOptions' => ['allowClear' => true,'placeholder' => 'Search sample template ...'],
                'options' => ['id' => 'saved_templates']
            ]);
            echo "<br>";
        ?>
        </div>
    </div>
        
    <?= $form->field($model, 'samplename')->textInput(['maxlength' => true,'placeholder' => 'Enter sample name ...']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'customer_description')->textarea(['rows' => 3])->label("Description provided by Customer") ?>

    <?php
        if(empty($model->sample_id)){
            echo Html::checkbox('sample_template', false, ['label' => '&nbsp;Save as template','value'=>"1"]);
            echo "<br>";
        }
    ?>
    <div class="form-group" style="padding-bottom: 3px;">
        <div style="float:right;">
            <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-update']) ?>
            <?= Html::button('Close', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
            <br>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
$('.btn-number').click(function(e){
    e.preventDefault();
    
    $(".err-message").html("");
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    $(".err-message").html("");
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        //alert('Sorry, the minimum value was reached');
        $(".err-message").html("Sorry, the minimum value was reached.");
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        //alert('Sorry, the maximum value was reached');
        $(".err-message").html("Sorry, the maximum value was reached.");
        $(this).val($(this).data('oldValue'));
    }
    
});

$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

function confirmSampletype(){
    BootstrapDialog.show({
        title: "<span class='glyphicon glyphicon-warning-sign' style='font-size:18px;'></span> Warning",
        type: BootstrapDialog.TYPE_DANGER,
        message: "<div class='alert alert-danger'><p style='font-weight:bold;font-size:14px;'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp; Changing Sample Type will erase the analyses under this sample.</p><br><strong>Reason:</strong><ul><li>Test/Calibration and Method might not be available for the selected Sample Type</li></ul></div>",
        buttons: [
            {
                label: 'Proceed',
                cssClass: 'btn-primary',
                action: function(thisDialog){
                    thisDialog.close();
                    $('.sample-form form').submit();
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
<?php
$this->registerJs("$('#saved_templates').on('change',function(){
    var id = $('#saved_templates').val();
        $.ajax({
            //url: '".Url::toRoute("sample/getlisttemplate?template_id='+id+'")."',
            url: '".Url::toRoute("sample/getlisttemplate")."',
            dataType: 'json',
            method: 'GET',
            //data: {id: $(this).val()},
            data: {template_id: id},
            success: function (data, textStatus, jqXHR) {
                $('#sample-samplename').val(data.name);
                $('#sample-description').val(data.description);
                $('.image-loader').removeClass( \"img-loader\" );
            },
            beforeSend: function (xhr) {
                //alert('Please wait...');
                $('.image-loader').addClass( \"img-loader\" );
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('An error occured!');
                alert('Error in ajax request');
            }
        });
});");
?>
<?php
if(!$model->isNewRecord) {
    $this->registerJs("
        $('#sample-sampletype_id').on('change', function() {
            var sampletype = $('#sample-sampletype_id').val();
            if(sampletype != ".$sameSampletype." && sampletype > 0){
                $('#btn-update').attr('onclick','confirmSampletype()');
            } else {
                $('#btn-update').removeAttr('onclick');
            }
        });

        $('#btn-update').on('click', function(e){
            e.preventDefault();
            var sampletype = $('#sample-sampletype_id').val();
            if(sampletype == ".$sameSampletype."){
                $('.sample-form form').submit();
            }
        });
    ");
}
?>
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
    background-image: url('/images/img-loader64.gif');
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