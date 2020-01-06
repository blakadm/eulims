<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\TypeaheadBasic;
use kartik\widgets\Typeahead;
use yii\helpers\ArrayHelper;

use common\models\lab\Lab;
use common\models\lab\Testcategory;
use common\models\lab\Labsampletype;
use common\models\lab\Sampletype;
use common\models\lab\Request;
use common\models\lab\Sampletypetestname;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Testname;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Analysis */
/* @var $form yii\widgets\ActiveForm */


$js=<<<SCRIPT
   $(".kv-row-checkbox").click(function(){
      var keys = $('#sample-grid').yiiGridView('getSelectedRows');
      var keylist= keys.join();
      $("#sample_ids").val(keylist);
      $("#sample_ids").val(keylist);
      $("#analysis_create").prop('disabled', keys=='');  
   });    
   $(".select-on-check-all").change(function(){
    var keys = $('#sample-grid').yiiGridView('getSelectedRows');
    var keylist= keys.join();
 
    $("#analysis_create").prop('disabled', keys=='');  
   });
  
SCRIPT;
$this->registerJs($js);
?>


<div class="analysis-form">

    <?php $form = ActiveForm::begin(); ?>
 
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

    <?= GridView::widget([
      'id' => 'sample-grid',
      'dataProvider'=> $sampleDataProvider,
      'pjax'=>true,
      'pjaxSettings' => [
          'options' => [
              'enablePushState' => false,
          ]
      ],
      'containerOptions'=>[
          'style'=>'overflow:auto; height:150px',
      ],
      'floatHeaderOptions' => ['scrollingTop' => true],
      'responsive'=>true,
      'striped'=>true,
      'hover'=>true,
      'bordered' => true,
      'panel' => [
         'heading'=>'<h3 class="panel-title">Samples</h3>',
         'type'=>'primary',
         'before' => '',
         'after'=>false,
      ],
      'toolbar' => false,
        'columns' => [
               [
            'class' => '\kartik\grid\CheckboxColumn',
         ],
            'samplename',
            [
                'attribute'=>'description',
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($data){
                    return ($data->request->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".$data->sampling_date."</b></span>,&nbsp;".$data->description : $data->description;
                },
                'contentOptions' => ['style' => 'width:70%; white-space: normal;'],
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'rstl_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'pstcanalysis_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'request_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'sample_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'sample_code')->hiddenInput(['maxlength' => true])->label(false)?>

    <?= $form->field($model, 'testname')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'cancelled')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'is_package')->hiddenInput()->label(false)  ?>

    <?= $form->field($model, 'method')->hiddenInput()->label(false)  ?>
    <?= $form->field($model, 'references')->hiddenInput()->label(false)  ?>
    <?= $form->field($model, 'fee')->hiddenInput()->label(false)  ?>

    <?= Html::textInput('sample_ids', '', ['class' => 'form-control', 'id'=>'sample_ids', 'type'=>'hidden'], ['readonly' => true]) ?>
  
    <?php
        $requestquery = Request::find()->where(['request_id' => $request_id])->one();
      
         $category= ArrayHelper::map(Testcategory::find()
         ->leftJoin('tbl_lab_sampletype', 'tbl_lab_sampletype.testcategory_id=tbl_testcategory.testcategory_id')
         ->Where(['tbl_lab_sampletype.lab_id'=>$requestquery->lab_id])
         ->orderBy(['testcategory_id' => SORT_DESC])->all(),'testcategory_id','category');
    ?>
      <?= Html::textInput('lab_id', $requestquery->lab_id, ['class' => 'form-control', 'id'=>'lab_id', 'type'=>'hidden'], ['readonly' => true]) ?>
    <?= $form->field($model,'category_id')->widget(Select2::classname(),[
                    'data' => $category,
                    'theme' => Select2::THEME_KRAJEE,
                    'options' => ['id'=>'sample-category_id'],
                    'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Test Category'],
            ])->label("Test Category")
    ?>

    <div class="row">
    <div class="col-sm-6">

    <?= $form->field($model, 'sample_type_id')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'data'=>$testcategory,
            'options'=>['id'=>'sample-type_id'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['sample-category_id'],
                'placeholder'=>'Select Sample Type',
                'url'=>Url::to(['/lab/analysis/listtype']),
                'loadingText' => 'Loading Sample Types...',
            ]
        ])->label("Sample Type")
        ?>

    </div>
    <div class="col-sm-6">

        <?= $form->field($model, 'test_id')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'data'=>$sampletype,
            'options'=>['id'=>'sample-sample_type_id'],
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
                'depends'=>['sample-type_id'],
                'placeholder'=>'Select Test Name',
                'url'=>Url::to(['/lab/analysis/listsampletype']),
                'loadingText' => 'Loading Test Names...',
            ]
        ])
        ?>
    </div>
</div>

     
    <div id="methodreference">
    </div>
       

    <div class="row-fluid" id ="xyz">
        <div>
   
   


    <div class="row" style="float: right;padding-right: 30px">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id'=>'analysis_create', 'disabled'=> true]) ?>
        <?php if($model->isNewRecord){ ?>
        <?php } ?>
    <?= Html::Button('Cancel', ['class' => 'btn btn-default', 'id' => 'modalCancel', 'data-dismiss' => 'modal']) ?>

   

    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("$('#sample-test_id').on('depdrop:afterChange',function(){
    var id = $('#sample-test_id').val();
        $.ajax({
            url: '".Url::toRoute("analysis/gettest")."',
            dataType: 'json',
            method: 'GET',
            data: {method_reference_id: id},
            success: function (data, textStatus, jqXHR) {
                $('#analysis-references').val(data.references);
                $('#analysis-fee').val(data.fee);
                $('#analysis-fee-disp').val(data.fee);
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
$this->registerJs("$('#sample-test_id').on('change',function(){
    var id = $('#sample-test_id').val();
        $.ajax({
            url: '".Url::toRoute("analysis/gettest")."',
            dataType: 'json',
            method: 'GET',
            data: {method_reference_id: id},
            success: function (data, textStatus, jqXHR) {
               
           
                $('#analysis-references').val(data.references);
                $('#analysis-fee').val(data.fee);
                $('#analysis-fee-disp').val(data.fee);
                $('.image-loader').removeClass( \"img-loader\" );
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


<!-- <script type="text/javascript">
    $('#sample-test_id').on('change',function(e) {
       e.preventDefault();
         jQuery.ajax( {
            type: 'GET',
            url: '/lab/analysis/getmethod?id='+$(this).val(),
            dataType: 'html',
            success: function ( response ) {        
              $("#xyz").html(response);
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    });
    </script> -->


    <script type="text/javascript">
    $('#sample-sample_type_id').on('change',function() {
        $.ajax({
            url: '/lab/analysis/gettestnamemethod?id='+$(this).val(),
            method: "GET",
            dataType: 'html',
            data: { lab_id: $('#lab_id').val(),
            testcategory_id: 29,
            sampletype_id: 4,
            testname_id: $('#method_id').val()},
            beforeSend: function(xhr) {
               $('.image-loader').addClass("img-loader");
               }
            })
            .done(function( response ) {
                $("#methodreference").html(response); 
                $('.image-loader').removeClass("img-loader");  
            });
    });
</script>
