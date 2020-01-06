<?php
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\components\Functions;
use kartik\grid\GridView;

use common\models\lab\Tagging;
use common\models\lab\Analysis;
use common\models\lab\Request;
use common\models\lab\Sample;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */

?>


<div class="row" style='min-height: 0px;'>
  <div class="col-sm-1" style='min-height: 0px;'>
    <img src='/uploads/dost.svg' style=' width: 90; height: 90px;margin-left:30px;margin-top:20px' /> 
  </div>

  <div class="col-sm-11" style='min-height: 0px;'>
    <h3 style="color:#142142;font-family:Century Gothic;font-size:200%;7px 7px 0px rgba(0, 0, 0, 0.2);"><b>&nbsp;&nbsp;Department of Science and Technology</b></h3>
    <h3 style="color:#1a4c8f;font-family:Century Gothic;font-size:200%;7px 7px 0px rgba(0, 0, 0, 0.2);"><b>&nbsp;&nbsp;REGIONAL STANDARDS AND TESTING LABORATORY</b></h3>
  </div>
</div>

<div class="request-view">

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Tracking Widget</title>
  <style>
    .tracking-widget {
      display: flex;
      width: 980px;
      min-height: 140px;
      margin: 12px auto;
      padding: 5px;
      align-items: center;
    }

    .tracking-widget > .status {
      text-align: center;
    }

    .tracking-widget > .status > .icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 96px;
      height: 96px;
      background-color: #fafafa;
      border: 4px solid #9e9e9e;
      border-radius: 50%;
    }

    .tracking-widget > .status.done > .icon, .tracking-widget > .status.done + .connector {
      border-color: #2b2bb3;
      background-color: #68b4ed;
    }

    .tracking-widget > .status > .icon > img {
      width: 60%;
      height: 60%;
    }
    .tracking-widget > .status > div {
      font-size: 11px;
      margin-top: 4px;
    }
    .tracking-widget > .connector {
      flex: 1;
      border: 2px solid #999999;
      height: 0;
      margin-bottom: 13px;
    }
  </style>
</head>
<body>
<?php


$samplesQuery = Sample::find()->where(['request_id' =>$request->request_id])->one();
$analysis= Analysis::find()->where(['sample_id'=> $samplesQuery->sample_id])->one();
$tagging= Tagging::find()->where(['analysis_id'=> $analysis->analysis_id])->one();

// if ($tagging->tagging_status_id==1){
//   $analysis_started = "done";
// }else{
  $analysis_started = "";
//}

// if ($tagging->tagging_status_id==2){
//   $done_analyzing = "done";
//}else{
  $done_started = "";
//}






$paid = "done";

$report_claimed = "done";

?>
<div class="col-sm-8">
<div class="alert alert-info" style="!important;margin-top: 1px !important;">
     <a href="#" class="close" data-dismiss="alert" >×</a>
    <p class="note" ><b>Request Status:</b> Request Started Analysis
    </p>  
    </div>
<div class="panel panel-info">
<div class="panel-body">
  <div class="tracking-widget">
    <div class='status  done'>
      <div class="icon">
        <img src="/uploads/track/t1-people-carry-solid.svg" />
      </div>
      <div style="font-family:Century Gothic;font-size:80%;">Sample Received<br></div>
    </div>
    <div class="connector"></div>
    <div class="status <?php echo $analysis_started ?>">
      <div class="icon">
        <img src="/uploads/track/t2-flask-solid.svg" />
      </div>
      <div style="font-family:Century Gothic;font-size:80%;">Analysis Started<br></div>
    </div>
    <div class="connector"></div>
    <div class="status ">
      <div class="icon">
        <img src="/uploads/track/t3-clipboard-check-solid.svg" />
      </div>
      <div style="font-family:Century Gothic;font-size:80%;">Done Analyzing<br></div>
    </div>
    <div class="connector"></div>
    <div class="status">
      <div class="icon">
        <img src="/uploads/track/t4-file-contract-solid.svg" />
      </div>
      <div style="font-family:Century Gothic;font-size:80%;">Paid<br></div>
    </div>
    <div class="connector"></div>
    <div class="status">
      <div class="icon">
        <img src="/uploads/track/t4-file-contract-solid.svg" />
      </div>
      <div style="font-family:Century Gothic;font-size:80%;">Reports Claimed<br></div>
    </div>
  </div>
</div>
</div>
</div>
<div class="col-sm-4">
<?php
        echo DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> Request Details',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            [
                'columns' => [
                    [
                        'label'=>'Request Ref #',
                        'value'=>$request->request_ref_num,
                        'displayOnly'=>true,    
                    ],
                ],
            ],
            [
              'columns' => [
                  [
                      'label'=>'Due Date',
                      'value'=>$request->report_due,
                      'displayOnly'=>true,       
                  ],            
              ],
          ],
            [
                'columns' => [
                    [
                        'label'=>'Request Date',
                        'value'=>$request->request_datetime,
                        'displayOnly'=>true,       
                    ],            
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Customer',
                        'format'=>'raw',
                        'value'=>$request->customer ? $request->customer->customer_name : "",
                        'displayOnly'=>true
                    ],
                ],         
              ], 
              [
                'columns' => [
                    [
                        'label'=>'Tel no.',
                        'format'=>'raw',
                        'value'=>$request->customer ? $request->customer->tel : "",
                        'displayOnly'=>true
                    ],
                ],         
              ], 
              [
                'columns' => [
                    [
                        'label'=>'Address',
                        'format'=>'raw',
                        'value'=>$request->customer ? $request->customer->address : "",
                        'displayOnly'=>true
                    ],
                ],         
              ], 
            
        ],
    ]);
    ?>
       </div>
</body>
</html>

</div>
   
<div class="row" style="padding-left:15px;padding-right:15px">
<div class="col-sm-12">       
<?php    
        echo DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> Request Tracking',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            [
                'columns' => [
                    [
                        'label'=>'Sample Received',
                        'value'=>$request->request_datetime,
                        'displayOnly'=>true,    
                    ],
                ],
            ],
            [
              'columns' => [
                  [
                      'label'=>'Analysis Started',
                      'value'=>'',
                      'displayOnly'=>true,       
                  ],            
              ],
          ],
            [
                'columns' => [
                    [
                        'label'=>'Done Analyzing',
                        'value'=>'',
                        'displayOnly'=>true,       
                    ],            
                ],
            ],
            // [
            //     'columns' => [
            //         [
            //             'label'=>'Paid',
            //             'format'=>'raw',
            //             'value'=>'',
            //             'displayOnly'=>true
            //         ],
            //     ],         
            //   ], 
              [
                'columns' => [
                    [
                        'label'=>'Reports Claimed',
                        'format'=>'raw',
                        'value'=>'',
                        'displayOnly'=>true
                    ],
                ],         
              ], 
              [
                'columns' => [
                    [
                        'label'=>'OR Date.',
                        'format'=>'raw',
                        'value'=>'',
                        'displayOnly'=>true
                    ],
                ],         
              ], 
            
        ],
    ]);
    ?>
</div>
</div>