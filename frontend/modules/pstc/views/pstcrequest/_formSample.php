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
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Sample */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
if(count($sampletype) > 0){
    $dataSampletype = $sampletype;
} else {
    $dataSampletype = ['' => 'No sample type'] + $sampletype;
}

if(count($testcategory) > 0){
    $dataTestcategory = $testcategory;
} else {
    $dataTestcategory = ['' => 'No test category'] + $testcategory;
}

//$sameSampletype = !empty($model->sampletype_id) ? $model->sampletype_id : 0;
$sameSampletype = 0;
?>

<div class="pstc-sample-form">

    <div class="image-loader" style="display: hidden;"></div>

    <?php $form = ActiveForm::begin(['id'=>'pstc_sample']); ?>

    <em style="color:#990000;">Note: Editing test category or sample type is not allowed. It might affect test names.</em>
    <br><br>
    <?php

        $testcategoryOptions = [
            'language' => 'en-US',
            'width' => '100%',
            'theme' => Select2::THEME_KRAJEE,
            'placeholder' => 'Select Test Category',
            'allowClear' => true,
        ];

        $sampletypeOptions = [
            'language' => 'en-US',
            'width' => '100%',
            'theme' => Select2::THEME_KRAJEE,
            'placeholder' => 'Select Sample Type',
            'allowClear' => true,
        ];

        if($sample_data['is_referral'] == 0):
    ?>
    <div class="form-group required">
        <label class="control-label">Test Category</label>
        <?php

            echo Select2::widget([
                'name'=>'testcategory_id',
                'id'=>'pstcsample-testcategory_id',
                'data' => $dataTestcategory,
                'theme' => Select2::THEME_KRAJEE,
                'size' => Select2::MEDIUM,
                //'theme' => Select2::THEME_BOOTSTRAP,
                'options' => $testcategoryOptions,
                'value' => $sample_data['testcategory_id'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled'=>true,
                    'class'=>'form-control',
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var testcategoryId = this.value;
                        var select = $('#pstcsample-sampletype_id');
                        select.find('option').remove().end();
                        if (testcategoryId > 0){
                            $.ajax({
                                url: '".Url::toRoute("pstcrequest/get_sampletype")."',
                                method: 'GET',
                                data: {testcategory_id:testcategoryId},
                                success: function (data) {
                                    var select2options = ".Json::encode($sampletypeOptions).";
                                    select2options.data = data.data;
                                    select.select2(select2options);
                                    select.val(data.selected).trigger('change');
                                    $('.image-loader').removeClass('img-loader');
                                },
                                beforeSend: function (xhr) {
                                    $('.image-loader').addClass('img-loader');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>Error Encountered!</p>\");
                                }
                            });
                        } else {
                            //alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test category selected!</p>\");
                            console.log('No test category selected!');
                            select.val('').trigger('change');
                        }
                    }",
                ],
            ]);
        ?>
    </div>
    <?php endif; ?>
    <div class="form-group required">
        <label class="control-label">Sample Type</label>
        <?php
            echo Select2::widget([
                'name'=>'sampletype_id',
                'id'=>'pstcsample-sampletype_id',
                'data' => $dataSampletype,
                'theme' => Select2::THEME_KRAJEE,
                'size' => Select2::MEDIUM,
                //'theme' => Select2::THEME_BOOTSTRAP,
                'options' => $sampletypeOptions,
                'value' => $sample_data['sampletype_id'],
                'pluginOptions' => [
                    //'allowClear' => true,
                    'disabled'=>true,
                ],
            ]);
        ?>
    </div>
    <div class="form-group">
        <label class="control-label">Sampling Date</label>
        <?php
            echo DateTimePicker::widget([
                'name'=>'sampling_date',
                'id'=>'pstcsample-sampling_date',
                //'type' => DateTimePicker::TYPE_INPUT,
                'value' => empty($sample_data['sampling_date']) ? '' : date("m/d/Y h:i:s A", strtotime($sample_data['sampling_date'])),
                'options' => [
                    'placeholder' => 'Enter sampling date ...',
                    'autocomplete'=>'off',
                    'class'=>'form-control',
                ],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'mm/dd/yyyy HH:ii:ss P',
                    'todayHighlight' => true,
                    //'startDate' => date('m/d/Y h:i:s A'),
                    'todayBtn' => true,
                    //'removeButton' => false,
                ]
            ]);
		?>
    </div>
    <div class="form-group">
        <div class="required">
            <label class="control-label">Sample Name</label>
            <?= Html::textInput('sample_name',$sample_data['sample_name'],['class'=>'form-control','placeholder' => 'Enter sample name ...','autocomplete'=>'off']) ?>
        </div>
    </div>
    <div class="form-group required">
        <label class="control-label">Sample Description</label>
        <?= Html::textarea('sample_description',$sample_data['sample_description'],['class'=>'form-control','rows' => 4]) ?>
    </div>
    <div class="form-group" style="padding-bottom: 3px;">
        <div style="float:right;">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary','id'=>'btn-update']) ?>
            <?= Html::button('Close', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
            <br>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

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
/*if(!$model->isNewRecord) {
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
} */
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