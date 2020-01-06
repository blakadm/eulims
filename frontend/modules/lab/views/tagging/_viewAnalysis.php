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
use common\models\lab\Labsampletype;
use common\models\lab\Sampletype;
use common\models\lab\Sampletypetestname;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Testname;
use kartik\money\MaskMoney;

use common\models\system\Profile;
/* @var $this yii\web\View */


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

SCRIPT;
$this->registerJs($js);
?>

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
                'footer'=>Html::button('<i class="glyphicon glyphicon-ok"></i> Start Analysis', ['disabled'=>false,'value' => Url::to(['tagging/startanalysis','id'=>1]), 'onclick'=>'startanalysis()','title'=>'Start Analysis', 'class' => 'btn btn-success','id' => 'btn_start_analysis'])." ".
                Html::button('<i class="glyphicon glyphicon-ok"></i> Completed', ['disabled'=>false,'value' => Url::to(['tagging/completedanalysis','id'=>1]),'title'=>'Completed', 'onclick'=>'completedanalysis()', 'class' => 'btn btn-success','id' => 'btn_complete_analysis']),
            ],
            'pjaxSettings' => [
                'options' => [
                    'enablePushState' => false,
                ]
            ],
            'floatHeaderOptions' => ['scrollingTop' => true],
            'columns' => [
                  [
               'class' => '\kartik\grid\CheckboxColumn',
               'width' => '5px',
            ],
                     [
                        'header'=>'Test Name',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {

                            //return Html::a($model->testname, ['value'=>Url::to(['/lab/csf/resultmodal','id'=>$model->analysis_id]), 'class' => 'btn btn-primary','onclick'=>'LoadModal(this.title, this.value);','title' => Yii::t('app', "View Results")]);
                            return "<b>".$model->testname."</b>";
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Method',
                        'format' => 'raw',
                        'width' => '300px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return $model->method;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                      
                    ],
                    [
                        'header'=>'Analyst',
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value'=> function ($model){

                            if ($model->tagging){
                                $profile= Profile::find()->where(['user_id'=> $model->tagging->user_id])->one();
                               // return $profile->firstname.' '. strtoupper(substr($profile->middleinitial,0,1)).'. '.$profile->lastname;
                                return $profile->fullname;
                            }else{
                                return "";
                            }
                           
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                   
                    ],
                    // [
                    //     'header'=>'ISO accredited',
                    //     'format' => 'raw',
                    //     'enableSorting' => false,
                    //     'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    // ],
                    [
                          'header'=>'Status',
                          'hAlign'=>'center',
                          'format'=>'raw',
                          'value' => function($model) {
                        
                            if ($model->tagging){

                             if ($model->tagging->tagging_status_id==1) {
                                    return "<span class='badge btn-primary' style='width:90px;height:20px'>ONGOING</span>";
                                }else if ($model->tagging->tagging_status_id==2) {
                                    return "<span class='badge btn-success' style='width:90px;height:20px'>COMPLETED</span>";
                                }
                                else if ($model->tagging->tagging_status_id==3) {
                                    return "<span class='badge btn-warning' style='width:90px;height:20px'>ASSIGNED</span>";
                                }
                                else if ($model->tagging->tagging_status_id==4) {
                                    return "<span class='badge btn-danger' style='width:90px;height:20px'>CANCELLED</span>";
                                }
                                 
                          
                            }else{
                                return "<span class='badge btn-default' style='width:80px;height:20px'>PENDING</span>";
                            }
                           
                          },
                          'enableSorting' => false,
                          'contentOptions' => ['style' => 'width:10px; white-space: normal;'],
                      ],
                    [
                        'header'=>'Remarks',
                        'format' => 'raw',
                        'width' => '100px',
                        'value' => function($model) {

                            if ($model->tagging){
                                return "<b>Start Date:&nbsp;&nbsp;</b>".$model->tagging->start_date."
                                <br><b>End Date:&nbsp;&nbsp;</b>".$model->tagging->end_date;
                            }else{
                                return "<b>Start Date: <br>End Date:</b>";
                            }
                           
                    },
                        'enableSorting' => false,
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],
                ],
                
                ['class' => 'kartik\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'template' => '{view}',
                'buttons'=>[
                    'view'=>function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/tagging/updateanalysis','id'=>$model->analysis_id]), 'class' => 'btn btn-primary','onclick'=>'LoadModal(this.title, this.value);','title' => Yii::t('app', "Update Analysis")]);
                    },
                   
                ],
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

