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
use common\models\lab\Lab;
use common\models\lab\Sampletype;
use common\models\lab\Sample;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TaggingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

 <div class="row-fluid" id ="xyz">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
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


    