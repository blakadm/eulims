<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\components\Functions;
use common\components\ReferralComponent;
use common\models\lab\Cancelledrequest;
use common\models\lab\Discount;
use common\models\lab\Request;
use common\models\lab\Sample;
use common\models\lab\Sampletype;
use common\models\finance\Paymentitem;

use common\models\lab\Package;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
//use kop\y2sp\ScrollPager;
use yii\widgets\ListView;
use kartik\tabs\TabsX;

$this->title = empty($request['referral_code']) ? $request['referral_id'] : $request['referral_code'];
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("/css/modcss/progress.css", [], 'css-search-bar');

$rstl_id=$rstl_id=(int) Yii::$app->user->identity->profile->rstl_id;
$statusreceived="progress-todo";
$statusshipped="progress-todo";
$statusaccepted="progress-todo";
$statusongoing="progress-todo";
$statuscompleted="progress-todo";
$statusuploaded="progress-todo";

foreach($logs as $log){
    if($log->referralstatus_id == 1){
       $statusreceived ="progress-done";
    }
    if($log->referralstatus_id == 2){
       $statusshipped ="progress-done";
    }
    if($log->referralstatus_id == 3){
       $statusaccepted ="progress-done";
    }
    if($log->referralstatus_id == 4){
       $statusongoing ="progress-done";
    }
    
    if($log->referralstatus_id == 5){
       $statuscompleted ="progress-done";
    }
    if($log->referralstatus_id == 6){
       $statusuploaded ="progress-done";
    }
}

$haveStatus = "";
//$Func="LoadModal('Update Received Track','/finance/cancelop/create?op=".$model->orderofpayment_id."',true,500)";
//$UpdateButton='<button id="btnUpdate" onclick="'.$Func.'" type="button" style="float: right;padding-right:5px;margin-left: 5px" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel Order of Payment</button>';
if(!isset(\Yii::$app->session['config-item'])){
   \Yii::$app->session['config-item']=1; //Laboratories 
}
switch(\Yii::$app->session['config-item']){
    case 1: //Laboratories
        $LogActive=true;
        $ResultActive=false;
        $TrackActive=false;
        break;
    case 2: // Technical Managers
        $LogActive=false;
        $ResultActive=true;
        $TrackActive=false;
        break;
    case 3: //Discount
        $LogActive=false;
        $ResultActive=false;
        $TrackActive=true;
        break;
}
$Session= Yii::$app->session;

if(empty($request['referral_code'])){
    $labelpanel = '<i class="glyphicon glyphicon-book"></i> Referral Code ' . $request['referral_code'];
} else {
    //$btnPrint = "<a href='/referrals/referral/print-referral?id=".$model->referral_id."' class='btn-sm btn-default' style='color:#000000;margin-left:15px;'><i class='fa fa-print'></i> Print</a>";
    //$btnPrint = "<a href='/referrals/referral/printref?id=".$request['referral_id']."' class='btn btn-success' style='margin-left: 5px'  target='_blank'><i class='fa fa-print'></i> Print Referral</a>";
    $btnPrint = "<a href='/reports/preview?url=/lab/request/print-request?id=".$request['local_request_id']."' class='btn btn-success' style='margin-left: 5px'  target='_blank'><i class='fa fa-print'></i> Print Referral</a>";
    $labelpanel = '<i class="glyphicon glyphicon-book"></i> Referral Code ' . $request['referral_code'] .' '.$btnPrint;
}
?>
<div class="section-request">
<div class="request-view ">
    <div class="image-loader" style="display: none;"></div>
    <div class="container table-responsive">
        <?php
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
				'heading'=>$labelpanel,
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'buttons1' => '',
            'attributes'=>[
                [
                    'group'=>true,
                    'label'=>'Referral Details ',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Referral Code',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'value'=> $request['referral_code'],
                        ],
                        [
                            'label'=>'Customer / Agency',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['customer_name'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Referral Date / Time',
                            'format'=>'raw',
                            'value'=> ($request['referral_date_time'] != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($request['referral_date_time'], 'php:F j, Y h:i a') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Address',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['address'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                    
                ],
                [
                    'columns' => [
                       [
                            'label'=>'Sample Received Date',
                            'format'=>'raw',
                            'value'=> !empty($request['sample_received_date']) ? Yii::$app->formatter->asDate($request['sample_received_date'], 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No sample received date</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Tel no.',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['tel'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Estimated Due Date',
                            'format'=>'raw',
                            'value'=> ($request['report_due'] != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($request['report_due'], 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Fax no.',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['fax'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            //'attribute'=>'report_due',
                            'label'=>'Referred by',
                            'format'=>'raw',
                            'value'=> !empty($receiving_agency) ? $receiving_agency : null,
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Referred to',
                            'format'=>'raw',
                            //'value'=>$model->customer ? $model->customer->fax : "",
                            'value'=> !empty($testing_agency) ? $testing_agency : null,
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'group'=>true,
                    'label'=>'Payment Details',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Deposit Slip',
                            'value'=>function() use ($depositslip,$model,$request){
                                $link = '';
                                if($depositslip > 0){
                                    foreach ($depositslip as $deposit) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$deposit['filename'],'/referrals/attachment/download?request_id='.$request['local_request_id'].'&file='.$deposit['attachment_id'].'&referral_id='.$deposit['referral_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Deposit Slip','target'=>'_self'])."<br>";
                                    }
                                }
                                return $link;
                            },
                            'format'=>'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%;vertical-align: top;'],
                            'labelColOptions' => ['style' => 'width: 20%; text-align: right; vertical-align: top;'],
                        ],
                        [
                            'label'=>'Official Receipt',
                            'format'=>'raw',
                            'value'=>function() use ($officialreceipt,$model,$request){
                                $link = '';
                                if($officialreceipt > 0){
                                    foreach ($officialreceipt as $or) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$or['filename'],'/referrals/attachment/download?request_id='.$request['local_request_id'].'&file='.$or['attachment_id'].'&referral_id='.$deposit['referral_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Official Receipt','target'=>'_self'])."<br>";
                                    }
                                }
                                return $link;
                            },
                            'valueColOptions'=>['style'=>'width:30%;vertical-align: top;'], 
                            'displayOnly'=>true,
                            'labelColOptions' => ['style' => 'width: 20%; text-align: right; vertical-align: top;'],
                        ],
                    ],
                ],              
                [
                    'group'=>true,
                    'label'=>'Transaction Details',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [ 
                            'label'=>'Received By',
                            'format'=>'raw',
                            'value'=>$request['cro_receiving'],
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%']
                        ],
                        [
                            'label'=>'Conforme',
                            'value'=> $request['conforme'],
                            'format'=>'raw',
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
            ],

        ]);
        ?>
    </div>
    <div class="container">
        <div class="table-responsive">
        <?php
            $gridColumns = [
                [
                    'attribute'=>'sample_code',
                    'enableSorting' => false,
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'attribute'=>'sample_name',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'description',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data) use ($request){
                        return ($request['lab_id'] == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data['sampling_date']))."</b></span>,&nbsp;".$data['description'] : $data['description'];
                    },
                   'contentOptions' => [
                        'style'=>'max-width:180px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'attribute'=>'customer_description',
                    'header'=>'Description provided by Customer',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data){
                        return empty($data['customer_description']) ? "<span style='color:#444444;font-size:11px;'><i>No information provided</i></span>" : $data['customer_description'];
                    },
                   'contentOptions' => [
                        'style'=>'max-width:180px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
            ];

            echo GridView::widget([
                'id' => 'sample-grid',
                'dataProvider'=> $sampleDataProvider,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Samples</h3>',
                    'type'=>'primary',
                    'before'=>null,
                    'after'=>false,
                ],
                'columns' => $gridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id']])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
        </div>
    </div>
    <div class="container">
    <?php

        $analysisgridColumns = [
            [
                'attribute'=>'sample_name',
                'header'=>'Sample',
                'format' => 'raw',
                'enableSorting' => false,
                /*'value' => function($model) {
                    //return $model->sample ? $model->sample->samplename : '-';
                },*/
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
               
            ],
            [
                'attribute'=>'sample_code',
                'header'=>'Sample Code',
                /*'value' => function($model) {
                    //return $model->sample ? $model->sample->sample_code : '-';
                },*/
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],
            [
                'attribute'=>'test_name',
                'format' => 'raw',
                'header'=>'Test/ Calibration Requested',
                'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                'enableSorting' => false,
            ],
            [
                'attribute'=>'method',
                'format' => 'raw',
                'header'=>'Test Method',
                'enableSorting' => false,  
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',             
            ],
            [
                'attribute'=>'fee',
                'header'=>'Unit Price',
                'enableSorting' => false,
                'hAlign'=>'right',
                'format' => 'raw',
                'value'=>function($data){
                    return number_format($data['analysis_fee'],2);
                },
                'contentOptions' => [
                    'style'=>'max-width:80px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'hAlign' => 'right', 
                'vAlign' => 'left',
                'width' => '7%',
                'format' => 'raw',
                'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample) {
                    if($countSample > 0){
                        return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
                    } else {
                        return '';
                    }
                },
            ],
        ];
            echo GridView::widget([
                'id' => 'analysis-grid',
                'responsive'=>true,
                'dataProvider'=> $analysisdataprovider,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'showPageSummary' => true,
                'hover'=>true,
                
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Analysis</h3>',
                    'type'=>'primary',
                    'before'=> null,
                    'after'=> false,
                    'footer'=>null,
                ],
                'columns' => $analysisgridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id']])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
    </div>
    <?php if($request['referral_code']){ ?>
    <div class="container" <?php echo $haveStatus; ?>>
        <ul class="progress-track">
                <li class="<?php echo $statusreceived; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-dropbox fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Received</span>
                </li>
                <li class="<?php echo $statusshipped; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-truck fa-fw fa-flip-horizontal to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Shipped</span>
                </li>
                <li class="<?php echo $statusaccepted; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span style="margin-left:2px;" class="fa fa-cube fa-fw fa-lg to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Accepted</span>
                </li>
                <li class="<?php echo $statusongoing; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-flask fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Ongoing</span>
                </li>
                <li class="<?php echo $statuscompleted; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-check fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Completed</span>
                </li>
                <li class="<?php echo $statusuploaded; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-upload fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Uploaded</span>
                </li>
        </ul>
    </div>
    <div class="container">
     <?php
       // echo "<a href='/referrals/referral/printref?id=".$request['referral_id']."' class='btn btn-success' style='margin-left: 5px'  target='_blank'><i class='fa fa-print'></i> Print Referral</a>";
       // echo "<br /><br />";
       /* echo "<pre>";
        print_r($request);
        echo "</pre>";*/
     ?>
     </div>
      <div class="container">
         <div class="panel panel-primary">
        
        <div class="panel-body">
        <?php  

        echo TabsX::widget([
            'position' => TabsX::POS_ABOVE,
            'align' => TabsX::ALIGN_LEFT,
            'encodeLabels' => false,
            'id' => 'tab_referral',
            'items' => [
                [
                    'label' => 'Logs',
                    'content' => $this->renderAjax('_notification',['notificationDataProvider'=>$notificationDataProvider]),
                    'active' => $LogActive,
                    'options' => ['id' => 'log'],
                   // 'visible' => Yii::$app->user->can('access-terminal-configurations')
                ],
                [
                    'label' => 'Results',
                    'content' => $this->renderAjax('_results',['testresult'=>$testresult,'model'=>$request]),
                    'active' => $ResultActive,
                    'options' => ['id' => 'result'],
                   // 'visible' => Yii::$app->user->can('access-terminal-configurations')
                ],
                [
                    'label' => 'Referral Track',
                    'content' =>$this->renderAjax('_viewtracking',['track'=>$modelRefTracktesting,'modelRefTrackreceiving'=>$modelRefTrackreceiving,'model'=>$request]),//$TrackContent ,
                    'active' => $TrackActive,
                    'options' => ['id' => 'referral_track'],
                   // 'visible' => Yii::$app->user->can('access-terminal-configurations')
                ]
            ],
        ]);
        ?>
        </div>
        </div>      
    </div>
    <?php } ?>
</div>
</div>
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
<script type="text/javascript">
    function addreceivedtrack(url,title){
        //alert(title);
       LoadModal(title,url,'true','600px');
   }
    function addtestingtrack(url,title){
       LoadModal(title,url,'true','600px');
   }
   function addresult(url,title){
       LoadModal(title,url,'true','600px');
   } 
</script>