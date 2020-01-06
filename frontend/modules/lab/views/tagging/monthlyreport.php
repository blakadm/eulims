<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Customer;
use common\models\lab\Discount;
use common\models\lab\Lab;
use common\models\lab\Sample;
use common\models\lab\Analysis;
use common\models\lab\Testcategory;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\LabsampletypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo "<h1>Monthly Report for <b>".$month." ".$year."</b></h1>";

?>
    <?php $this->registerJsFile("/js/services/services.js"); ?>

    <?= GridView::widget([
        'dataProvider' => $requestdataprovider,
        'id'=>'analysis-grid',
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-analysis']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  Monthly Report' ,
               // 'footer'=>Html::button('<i class="glyphicon glyphicon-ok"></i> Start Analysis', ['disabled'=>false,'value' => Url::to(['tagging/startanalysis','id'=>1]), 'onclick'=>'startanalysis()','title'=>'Start Analysis', 'class' => 'btn btn-success','id' => 'btn_start_analysis'])." ".
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
                        'header'=>'Request Reference Number',
                        'format' => 'raw',
                        'width' => '140px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return $model->request_ref_num;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Name of Client',
                        'format' => 'raw',
                        'width' => '150px',
                        'enableSorting' => false,
                        'value' => function($model) {

                            $client = Customer::find()->where(['customer_id'=>$model->customer_id ])->one();
                            return $client->customer_name;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Address',
                        'format' => 'raw',
                        'width' => '200px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            
                            $client = Customer::find()->where(['customer_id'=>$model->customer_id ])->one();
                            return $client->address;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Non setup',
                        'format' => 'raw',
                        'width' => '80px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            $client = Customer::find()->where(['customer_id'=>$model->customer_id ])->one();

                            if ($client->customer_type_id==2){
                                return "1";
                            }else{
                                return "0";
                            }
                            
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Setup',
                        'format' => 'raw',
                        'width' => '80px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            $client = Customer::find()->where(['customer_id'=>$model->customer_id ])->one();

                            if ($client->customer_type_id==1){
                                return "1";
                            }else{
                                return "0";
                            }
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Sample Code',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {

                            $samplesquery = Sample::find()->where(['request_id' => $model->request_id])->all();
                            $listsamples = "";
                            foreach($samplesquery as $sample){
                                $listsamples .= $sample['sample_code']."<br />";
                            }  
                            return $listsamples;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Sample Name',
                        'format' => 'raw',
                        'width' => '120px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            $samplesquery = Sample::find()->where(['request_id' => $model->request_id])->all();
                            $listsamples = "";
                            foreach($samplesquery as $sample){
                                $listsamples .= $sample['samplename']."<br />";
                            }  
                            return $listsamples;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Test Name',
                        'format' => 'raw',
                        'width' => '210px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            $ids = "";
                            $samplesquery = Sample::find()->where(['request_id' => $model->request_id])->all();
                            foreach($samplesquery as $samples){
                                $ids .= $samples['sample_id'].",";
                        }  
                            $len = strlen($ids);
                            $x = $len-2;  
                            $testname_id = substr($ids,0,$x);
                            $testnames = "";
                                $analysisquery = Analysis::find()->where(['IN', 'sample_id', [$testname_id]])->all();
                                foreach($analysisquery as $analysis){
                                    $testnames .= $analysis['testname']."<br />";
                            }  
                            return $testnames;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Request Total',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return $model->total;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Paid Non Setup',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return "";
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Paid Setup',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return "";
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Gratis Non Setup',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return "";
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Gratis Setup',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return "";
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Discount',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                           
                            $discountquery = Discount::find()->where(['discount_id' => $model->discount_id])->one();
                            $samplesquery = Sample::find()->where(['request_id' => $model->request_id])->one();
                            $rate =  $discountquery->rate;
                            $t = $model->total;
                            $d = $t*"0.".$rate;
                           return $rate;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                    [
                        'header'=>'Total Fees Collected',
                        'format' => 'raw',
                        'width' => '100px',
                        'enableSorting' => false,
                        'value' => function($model) {
                            return $model->total;
                        },
                        'contentOptions' => ['style' => 'width:40px; white-space: normal;'],                 
                    ],
                  
            
        ],
    ]); 
    ?>



   

