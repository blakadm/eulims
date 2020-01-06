<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use \yii\helpers\ArrayHelper;
use common\models\system\Rstl;
use kartik\widgets\DatePicker;
use common\models\lab\Lab;
use common\models\lab\Request;
use common\components\Functions;
use common\models\lab\Customer;
use common\models\lab\Sample;
use common\models\lab\RequestType;
use yii\bootstrap\Modal;
use common\models\finance\Paymentitem;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requests';
$this->params['breadcrumbs'][] = ['label' => 'Lab', 'url' => ['/lab']];
$this->params['breadcrumbs'][] = $this->title;
$func=new Functions();
$Header="Department of Science and Technology<br>";
$Header.="Laboratory Request";
if(Yii::$app->user->can('allow-cancel-request')){
    $Button="{view}{update}{delete}";
}else{
    $Button="{view}{update}";
}

?>

<div class="request-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
        echo $func->GenerateStatusLegend("Legend/Status",false);
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'RequestGrid',
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow-x: none!important','class'=>'kv-grid-container'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="glyphicon glyphicon-book"></i>  Request',
            //'before'=> (Yii::$app->user->identity->profile->rstl_id > 100) ? "<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/lab/request/createreferral\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Referral Request</button>" : "<button type='button' onclick='LoadModal(\"Create Request\",\"/lab/request/create\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Request</button>&nbsp;&nbsp;&nbsp;<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/lab/request/createreferral\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Referral Request</button>",
			'before'=> (Yii::$app->user->identity->profile->rstl_id > 100) ? "<button type='button' onclick='LoadModal(\"Create Request\",\"/lab/request/create\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Request</button>" : "<button type='button' onclick='LoadModal(\"Create Request\",\"/lab/request/create\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Request</button>&nbsp;&nbsp;&nbsp;<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/lab/request/createreferral\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Referral Request</button>",
        ],
        'pjax' => true, // pjax is set to always true for this demo
        'pjaxSettings' => [
            'options' => [
                    'enablePushState' => false,
              ],
        ],
        'rowOptions' => function($model){
            $Obj=$model->getPaymentStatusDetails($model->request_id);
            if($Obj){
                $class=$Obj[0]['class'];
                $objClass= explode('-', $class);
                $nClass=$objClass[1];
            }else{
                $nClass="btn-default";
            }
            return ['class'=>$nClass];
        },
        'exportConfig'=>$func->exportConfig("Laboratory Request", "laboratory request", $Header),
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            ['attribute'=>'request_ref_num',
             'width' => '180px',
            ],
            [
                'label'=>'Request Date',
                'attribute'=>'request_datetime',
                'width' => '180px',
                'value'=>function($model){
                    //return date('d/m/Y H:i:s',strtotime($model->request_datetime));
                    return ($model->request_type_id == 2 && $model->request_datetime == '0000-00-00 00:00:00') ? null : date('d/m/Y H:i:s',strtotime($model->request_datetime));
                },
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'request_datetime',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Select date ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ],
                    //'contentOptions' => ['style' => 'width: 20%;word-wrap: break-word;white-space:pre-line;'],
                ]),
            ],
            [
                'attribute' => 'customer_id', 
                'label'=>'Customer',
                'vAlign' => 'middle',
                'width' => '400px',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) { 
                    return $model->customer ? $model->customer->customer_name : "";
                },
              
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Customer::find()->orderBy('customer_name')->asArray()->all(), 'customer_id', 'customer_name'), 
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Select Customer'],
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
            ],
            [
                'label'=>'Total',
                'attribute'=>'total',
                'width' => '100px',
                'hAlign'=>'right',
                'format' => ['decimal', 2],
               
            ],
            [
                'label'=>'Report Due',
                'attribute'=>'report_due',
                'width' => '100px',
                'hAlign'=>'center'
            ],
            [
                'label'=>'Analyses Status',
                'attribute'=>'status_id',
                'hAlign'=>'center',
                'format'=>'raw',
                'width' => '110px',
                'value'=>function($model){
         
                    $samples_count= Sample::find() 
                    ->leftJoin('tbl_request', 'tbl_request.request_id=tbl_sample.request_id')    
                    ->where(['tbl_request.request_id'=>$model->request_id ])
                    ->all();              
                    $sampletagged= Sample::find()
                    ->leftJoin('tbl_analysis', 'tbl_sample.sample_id=tbl_analysis.sample_id')
                    ->leftJoin('tbl_tagging', 'tbl_analysis.analysis_id=tbl_tagging.analysis_id') 
                    ->leftJoin('tbl_request', 'tbl_request.request_id=tbl_analysis.request_id')    
                    ->where(['tbl_tagging.tagging_status_id'=>2, 'tbl_request.request_id'=>$model->request_id ])
                    ->all();                 
                    $scount = count($samples_count); 
                    $rcount = count($sampletagged); 
                    
                    if ($rcount==0){
                        return Html::button('<span"><b>PENDING</span>', ['value'=>Url::to(['/lab/tagging/samplestatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 1200);', 'class' => 'btn btn-default','title' => Yii::t('app', "Analyses Monitoring"), 'style'=>'width:110px;']);
                        
                    }elseif ($scount>$rcount){
                        return Html::button('<span"><b>ONGOING</span>', ['value'=>Url::to(['/lab/tagging/samplestatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 1200);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Analyses Monitoring"), 'style'=>'width:110px']);
                        
                    }elseif ($scount==$rcount){
                        return Html::button('<span"><b>COMPLETED</span>', ['value'=>Url::to(['/lab/tagging/samplestatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 1200);', 'class' => 'btn btn-success','title' => Yii::t('app', "Analyses Monitoring"), 'style'=>'width:110px']);                  
                    }           
              }
            ],
            [
                'label'=>'Report Status',
                'hAlign'=>'center',
                'format'=>'raw',
                'width' => '100px',
                'value'=>function($model){
                    if($model->testreports){
                        $req = Request::findOne($model->request_id);
                        //return "<a class='badge badge-success' href='/reports/lab/testreport/view?id=".$req->testreports[0]->testreport_id."' style='width:80px!important;height:20px!important;'>View</a>";
                        return Html::button('<span"><b>VIEW</span>', ['value'=>Url::to(['/lab/request/reportstatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 500);', 'class' => 'btn btn-success','title' => Yii::t('app', "Report Status"), 'style'=>'width:100px']);
                    }else{
                        //return "<span class='badge badge-default' style='width:80px!important;height:20px!important;'>None</span>";
                        return Html::button('<span"><b>NONE</span>', ['value'=>Url::to(['/lab/request/reportstatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 500);', 'class' => 'btn btn-default','title' => Yii::t('app', "Report Status"), 'style'=>'width:100px']);
                    }
                    
                }
            ],
            [
                'label'=>'Payment Status',
                'hAlign'=>'center',
                'format'=>'raw',
                'width' => '100px',
                'value'=>function($model){
                    
                    if ($model->payment_status_id==2)
                    {
                        return Html::button('<span"><b>PAID</span>', ['value'=>Url::to(['/lab/request/paymentstatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 500);', 'class' => 'btn btn-success','title' => Yii::t('app', "Payment Status"), 'style'=>'width:100px']);
                    }else{
                        return Html::button('<span"><b>UNPAID</span>', ['value'=>Url::to(['/lab/request/paymentstatus','id'=>$model->request_id]),'onclick'=>'LoadModal(this.title, this.value, true, 500);', 'class' => 'btn btn-default','title' => Yii::t('app', "Payment Status"), 'style'=>'width:100px']);
                    }
                    // $Obj=$model->getPaymentStatusDetails($model->request_id);
                    // if($Obj){
                    //    //return "<span class='badge ".$Obj[0]['class']."' style='width:80px!important;height:20px!important;'>".$Obj[0]['payment_status']."</span>"; 
                      
                    // }else{
                    //    //return "<span class='badge badge-primary' style='width:80px!important;height:20px!important;'>Unpaid</span>";
                      
                    // }
                   //
                }
            ],
            [
                'attribute' => 'request_type_id', 
                'label'=>'Request Type',
                'vAlign' => 'middle',
                'value' => function ($model, $key, $index, $widget) { 
                   return !empty($model->requesttype) ? $model->requesttype->request_type : "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(RequestType::find()->asArray()->all(), 'request_type_id', 'request_type'), 
                'format' => 'raw',
                'width' => '20px',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Select Type','id' => 'grid-search-request_type_id'],
                'contentOptions' => ['class' => 'text-center','style'=>'width: 50%;'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Button,
                'buttons' => [
                    
                    'view' => function ($url, $model){
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/lab/request/view?id=' . $model->request_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                    },
                    'update' => function ($url, $model) {
                        return (!empty($model->request_ref_num) && $model->request_type_id == 2) ? '' : (Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => $model->request_type_id == 2 ? '/lab/request/updatereferral?id='. $model->request_id : '/lab/request/update?id='. $model->request_id , 'onclick' => 'LoadModal(this.title, this.value);', 'class' => 'btn btn-success', 'title' => $model->request_type_id == 2 ? Yii::t('app', "Update Referral Request") : Yii::t('app', "Update Request")]));
                    },
                    'delete' => function ($url, $model) { //Cancel
                        if(!empty($model->request_ref_num) && $model->request_type_id == 2) {
                            return '';
                        } else {
                            if($model->IsRequestHasOP()){
                                if($model->IsRequestHasReceipt()){
                                    return Html::button('<span class="glyphicon glyphicon-ban-circle"></span>', ['value' => '/lab/cancelrequest/create?req=' . $model->request_id,'onclick' => 'LoadModal(this.title, this.value,true,"420px");', 'class' => 'btn btn-danger', 'title' => Yii::t('app', "Cancel Request")]);
                                }else{
                                    return Html::button('<span class="glyphicon glyphicon-ban-circle"></span>', ['class' => 'btn btn-danger','disabled'=>true, 'title' => Yii::t('app', "Cancel Request")]);
                                }
                            }else{
                                return Html::button('<span class="glyphicon glyphicon-ban-circle"></span>', ['value' => '/lab/cancelrequest/create?req=' . $model->request_id,'onclick' => 'LoadModal(this.title, this.value,true,"420px");', 'class' => 'btn btn-danger', 'title' => Yii::t('app', "Cancel Request")]);
                            }
                        }
                    }
                ],
            ],
        ],
]); ?>
</div>
