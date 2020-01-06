<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use common\models\lab\Lab;
use common\models\lab\Testcategory;
use common\models\lab\Sampletype;
use yii\helpers\Url;
//use kartik\money\MaskMoney;


/* @var $this yii\web\View */
/* @var $model common\models\lab\Package */
/* @var $form yii\widgets\ActiveForm */

$sampletypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');


?>

<div class="package-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $model->name='Package';
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-6">
        <?php
         $category= ArrayHelper::map(Testcategory::find()->orderBy(['testcategory_id' => SORT_DESC])->all(),'testcategory_id','category');
    
        $sampletype = [];
    ?>

    
    
    <?= $form->field($model,'testcategory_id')->widget(Select2::classname(),[
                    'data' => $category,
                    'theme' => Select2::THEME_KRAJEE,
                    'options' => ['id'=>'sample-category_id'],
                    'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Test Category'],
            ])->label("Test Category")
        ?>
        </div>
        <div class="col-sm-6">
        <?= $form->field($model, 'sampletype_id')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'data'=>$sampletype,
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
    </div>


    <?= $form->field($model, 'tests')->hiddenInput(['maxlength' => true])->label(false) ?>
        <div  id="methodreference">
    
        </div>
     <div id="form-group pull-right">
     <div class="form-group pull-right">

      <?= Html::textInput('sample_ids', '', ['class' => 'form-control', 'id'=>'sample_ids', 'type'=>'hidden'], ['readonly' => true]) ?>
    

    <div class="methodreference">
    <?php if(Yii::$app->request->isAjax){ ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <?php } ?>
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

  
<script type="text/javascript">
    $('#sample-type_id').on('change',function() {
        $.ajax({
            url: '/lab/package/getmethod?id='+$(this).val(),
            method: "GET",
            dataType: 'html',
            data: { lab_id: 1,
            sampletype_id: $('#sample-type_id').val()},
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