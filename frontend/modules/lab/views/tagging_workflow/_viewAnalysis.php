<?php
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use common\models\lab\Tagging;
use common\models\lab\Analysis;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\services\Testcategory;
use common\components\Functions;
use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;

use kartik\widgets\Select2;
use kartik\widgets\DepDrop;

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\TypeaheadBasic;
use kartik\widgets\Typeahead;

use common\models\lab\Lab;

use common\models\lab\Workflow;
use common\models\lab\Sample;
use common\models\lab\Labsampletype;
use common\models\lab\Sampletype;
use common\models\lab\Sampletypetestname;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Testname;
use common\models\lab\Tagginganalysis;
use kartik\money\MaskMoney;
use common\models\system\Profile;

$js=<<<SCRIPT

$(".kv-row-checkbox").click(function(){
   var keys = $('#analysis-grid').yiiGridView('getSelectedRows');
   var keylist= keys.join();
   $("#sample_ids").val(keylist);
   
});    
$(".select-on-check-all").change(function(){
 var keys = $('#analysis-grid').yiiGridView('getSelectedRows');
 var keylist= keys.join();
 $("#sample_ids").val(keylist);

});

function tag(mid){
    
      $.ajax({
         url: '/lab/tagging/tag',
         method: "post",
          data: { id: mid},
          beforeSend: function(xhr) {
             $('.image-loader').addClass("img-loader");
             }
          })
          .done(function(response) {   
              alert("boom");            
          });
  }

SCRIPT;
$this->registerJs($js);
?>

<?php $this->registerJsFile("/js/services/services.js"); ?>

<?= GridView::widget([
        'dataProvider' => $sampleDataProvider,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-analysis']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  Sample' ,
            ],
        'columns' => [
            [
                'header'=>'Sample Name',
                'format' => 'raw',
                'width' => '30%',
                'value' => function($model) {
                    return "<b>".$model->samplename."</b>";
                },
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'], 
            ],
            [
                'header'=>'Description',
                'format' => 'raw',
                'enableSorting' => false,
                'attribute'=>'description',
                'contentOptions' => ['style' => 'width:40px; white-space: normal;'],   
            ],
            
        ],
    ]); ?>
<script type='text/javascript'>
 
</script>

<?= GridView::widget([
        'dataProvider' => $analysisdataprovider,
        'id'=>'analysis-grid',
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-analysis']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  Analysis' ,
            ],
            'pjaxSettings' => [
                'options' => [
                    'enablePushState' => false,
                ]
            ],
            'floatHeaderOptions' => ['scrollingTop' => true],
            'columns' => [
                [
                    'header'=>'Tag',
                    'hAlign'=>'center',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 5%;word-wrap: break-word;white-space:pre-line;'],
                    'value'=>function($model){
                        return Html::button('<span class="glyphicon glyphicon-ok"></span>', ['value'=>Url::to(['/lab/tagging/tag','id'=>$model->analysis_id]),'onclick'=>'LoadModal(this.title, this.value, true, 950);', 'class' => 'btn btn-success','title' => Yii::t('app', "Tagging")]);
                    },
                    'enableSorting' => false,
                ],
                     [
                        'header'=>'Test Name',
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return "<b>".$model->testname."</b>";
                        },
                        'contentOptions' => ['style' => 'width:10%; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Method',
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return $model->method;
                        },
                        'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                      
                    ],
                    [
                        'header'=>'Analyst',
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value'=> function ($model){
                           
                                $tagging = Tagging::find()->where(['cancelled_by'=> $model->analysis_id])->one(); 
                                if ($tagging)
                                {
                                $profile= Profile::find()->where(['user_id'=> $tagging->user_id])->one();
                                    if ($profile){
                                        //return $profile->firstname.' '. strtoupper(substr($profile->middleinitial,0,1)).'. '.$profile->lastname;
                                        return $profile->fullname;
                                    }else{
                                        return '';
                                    }
                                    
                                }else{
                                    return '';
                                }
                               
                        
                           
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                   
                    ],
                    [
                        'header'=>'Progress',
                        'hAlign'=>'center',
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value'=> function ($model){
                            // $analysis = Analysis::findOne(['analysis_id' => $model->analysis_id]);
                            // $modelmethod=  Methodreference::findOne(['method'=>$analysis->method]); 
                            
                            // if ($modelmethod){
                            //     $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysis->test_id, 'method_id'=>$analysis->testcategory_id]);                           
                            //     if ($testnamemethod){
                            //         return "";
                            //     }
                            //     $count = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();     
                            //     if ($testnamemethod->testname_method_id){
                            //         return "";
                            //     }
                                

                            //     if ($count==0){
                            //         return $analysis->completed.'/'.$count;
                            //     }else{
                            //         $percent = $analysis->completed / $count * 100;
                            //         $formattedNum = number_format($percent);
                                    
                            //         return $analysis->completed.'/'.$count." = ".$formattedNum."%";  
                            //     }
                            // }else{
                            //     return "";
                            // }

                            return "";
                           
                                           
                        },
                        'contentOptions' => ['style' => 'width:8%; white-space: normal;'],                   
                    ],
                    // [
                    //     'header'=>'Cycle Time',
                    //     'format' => 'raw',
                    //     'enableSorting' => false,
                    //     'value'=> function ($model){
                    //         return "";
                           
                    //     },
                    //     'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                   
                    // ],
                    [
                          'header'=>'Status',
                          'hAlign'=>'center',
                          'format'=>'raw',
                          'value' => function($model) {
                            // $analysis = Analysis::findOne(['analysis_id' => $model->analysis_id]);
                            // $modelmethod=  Methodreference::findOne(['method'=>$analysis->method]);                              
                            // $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysis->test_id, 'method_id'=>$analysis->testcategory_id]);                           
                            // if ($testnamemethod){
                            //     return "";
                            // }
                            // $count = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();     
                            
                            
                         
                            //  if ($analysis->completed==0) {
                            //     return "<span class='badge btn-default' style='width:90px;height:20px'>PENDING</span>";
                            //     }else if ($analysis->completed==$count) {

                                  
                            //         $samples = Sample::find()->where(['sample_id' =>$analysis->sample_id])->one();
                            //         $samplecount= Tagginganalysis::find()
                            //         ->leftJoin('tbl_analysis', 'tbl_tagging_analysis.cancelled_by=tbl_analysis.analysis_id')
                            //         ->leftJoin('tbl_sample', 'tbl_analysis.sample_id=tbl_sample.sample_id')    
                            //         ->where(['tbl_tagging_analysis.tagging_status_id'=>2, 'tbl_analysis.sample_id'=>$samples->sample_id ])
                            //         ->all();  
                            //         $scount = count($samplecount); 

                            //         $Connection= Yii::$app->labdb;
                            //         $sql="UPDATE `tbl_sample` SET `completed`='$scount' WHERE `sample_id`=".$analysis->sample_id;
                            //         $Command=$Connection->createCommand($sql);
                            //         $Command->execute(); 

                            //         return "<span class='badge btn-success' style='width:90px;height:20px'>COMPLETED</span>";
                                    
                            //     }
                            //     else if ($analysis->completed>=1) {
                            //         return "<span class='badge btn-primary' style='width:90px;height:20px'>ONGOING</span>";
                            //     }
                            //     else if ($analysis->completed==0) {
                                    
                            //     }

                            return "";
                          
                          
                           
                          },
                          'enableSorting' => false,
                          'contentOptions' => ['style' => 'width:5%; white-space: normal;'],
                      ],
                    [
                        'header'=>'Remarks',
                        'format' => 'raw',
                        'width' => '100px',
                        'value' => function($model) {

                            $tagginganalysis = Tagginganalysis::findOne(['cancelled_by' => $model->analysis_id]);
                           if ($tagginganalysis){


                                return "<b>Start Date:&nbsp;&nbsp;</b>".$tagginganalysis->start_date."
                                <br><b>End Date:&nbsp;&nbsp;</b>".$tagginganalysis->end_date;
                                return "";
                            }else{
                                return "<b>Start Date: <br>End Date:</b>";
                            }
                           
                    },
                        'enableSorting' => false,
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],
                ],
            
        ],
    ]); 
    ?>
<?= Html::textInput('sample_ids', '', ['class' => 'form-control', 'id'=>'sample_ids', 'type'=>'hidden'], ['readonly' => true]) ?>
<?= Html::textInput('aid', $analysis_id, ['class' => 'form-control', 'id'=>'aid', 'type'=>'hidden'], ['readonly' => true]) ?>
   
<script type="text/javascript">
   function startanalysis() {

         jQuery.ajax( {
            type: 'POST',
            url: 'tagging/startanalysis',
            data: { id: $('#sample_ids').val(), analysis_id: $('#aid').val()},
            success: function ( response ) {

                 $("#xyz").html(response);
               },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    }

    function completedanalysis() {

         jQuery.ajax( {
            type: 'POST',
            url: 'tagging/completedanalysis',
            data: { id: $('#sample_ids').val(), analysis_id: $('#aid').val()},
            success: function ( response ) {

                 $("#xyz").html(response);
               },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });

    }
</script>