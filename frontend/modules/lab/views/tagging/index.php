<?php

use yii\helpers\Html;
use kartik\widgets\DatePicker;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\services\Testcategory;
use common\components\Functions;
use kartik\detail\DetailView;
use yii\helpers\Url;
use common\models\lab\Analysis;
use yii\data\ActiveDataProvider;
use common\models\lab\Batchtestreport;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaggingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tagging';
$this->params['breadcrumbs'][] = ['label' => 'Tagging', 'url' => ['/lab']];
$this->params['breadcrumbs'][] = 'Sample Tagging';

$this->registerJsFile("/js/services/services.js");
$func=new Functions();
?>

<!-- <blockquote class="imgur-embed-pub" lang="en" data-id="a/lc3D4"><a href="//imgur.com/lc3D4">Tadpole Lessons</a></blockquote><script async src="//s.imgur.com/min/embed.js" charset="utf-8"></script> -->
<div class="tagging-index">
    <?php
        echo $func->GenerateStatusTagging("Legend/Status",true);
    ?>

<div class="alert alert-info" style="background: #d4f7e8 !important;margin-top: 1px !important;">
     <a href="#" class="close" data-dismiss="alert" >×</a>
    <p class="note" style="color:#265e8d"><b>Note:</b> Please scan barcode in the dropdown list below. .</p>
    </div>

    <div class="row">
             <div class="col-md-4">
                <?php $form = ActiveForm::begin(); ?>
                <?php
                        $disabled=false; 
                        echo $func->GetSampleCode($form,$model,$disabled,"");
                ?>    
                <?php ActiveForm::end(); ?>
                                </div>
                                    </div>

 <div class="row-fluid" id ="xyz">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'toolbar' => false,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  Sample' ,
            ],
        'columns' => [
            [
                'header'=>'Sample Name',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],
               
            ],
            [
                'header'=>'Description',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],         
            ],
            
        ],
    ]); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'toolbar' => false,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  Analysis',
            ],
        'columns' => [
            [
                'header'=>'Test Name',
                'hAlign'=>'center',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],              
            ],
            [
                'header'=>'Method',
                'hAlign'=>'center',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],       
            ],
            [
                'header'=>'Analyst',
                'hAlign'=>'center',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],   
            ],
            // [
            //     'header'=>'ISO accredited',
            //     'hAlign'=>'center',
            //     'format' => 'raw',
            //     'enableSorting' => false,
            //     'contentOptions' => ['style' => 'width:40px; white-space: normal;'],
               
            // ],
            [
                'header'=>'Status',
                'hAlign'=>'center',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],
               
            ],
            [
                'header'=>'Remarks',
                'hAlign'=>'center',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],      
        ],     
        ],
    ]); ?>
    </div>
</div>

<!-- <div id="divSpinner" style="text-align:center;display:none;font-size:30px">
     <div class="animationload">
            <div class="osahanloading"></div>
     </div>
</div>
 -->
<!-- <script type='text/javascript'>
ShowProgressSpinner(true);

</script> -->

<script type="text/javascript">
    $('#sample-sample_code').on('change',function(e) {
       e.preventDefault();
         jQuery.ajax( {
            type: 'GET',
            url: '/lab/tagging/getanalysis?id='+$(this).val(),
            data: { analysis_id: $('#sample-sample_code').val()},
            dataType: 'html',
            success: function ( response ) {
            // ShowProgressSpinner(true);
                
              $("#xyz").html(response);
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    });
    </script>


    