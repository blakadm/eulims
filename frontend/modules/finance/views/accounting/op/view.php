<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use common\models\finance\Collection;
use yii\helpers\Url;
use common\components\Functions;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Op */

$this->title = 'Order of Payment';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = ['label' => 'Accounting', 'url' => ['/finance/accounting']];
$this->params['breadcrumbs'][] = ['label' => 'Order of Payment', 'url' => ['/finance/accounting/op']];
$this->params['breadcrumbs'][] = 'View';
$totalAmount=$model->total_amount ? $model->total_amount : 0.00;
//$subTotal=$model->collection ? $model->collection->sub_total : 0.00;
//$bal=$totalAmount - $subTotal;
$disable_button="";
//echo $totalAmount;
//exit;
if($totalAmount == 0.00){
    $disable_button=false;
}else{
    if ($model->receipt_id <> null){
        $disable_button=true;
    }
    else{
        $disable_button=false;
    }
}


$footer="<div class='alert alert-info' style='background: #d9edf7 !important;margin-top: 1px !important;width:550px;'>
                 <a href='#' class='close' data-dismiss='alert'>&times;</a>
                 <p class='note' style='color:#265e8d'> <strong>For partial payment, please click on each amount and modify accordingly.</strong><br />
                 <strong>Note!</strong> Only amount with _ _ _ can be modified. </p>
             </div>";
			 
 if($model->receipt_id == null){
        if($model->payment_mode_id!=5 || $model->payment_mode_id <> 6){//Not Flagged as Online payment
           $receipt_button="<button type='button' value='/finance/cashier/create-receipt?op_id=$model->orderofpayment_id' id='btnCreateReceipt2' style='float: right;' class='btn btn-success' title='Receipt from OP' onclick='addReceipt(this.value,this.title)'><i class='fa fa-save'></i> Create Receipt</button>";    
        }else{
           $receipt_button=""; 
        }
    }
    else{
        $receipt_button="<button type='button' value='/finance/cashier/viewreceipt?receiptid=$model->receipt_id' id='Receipt2' style='float: right;margin-right: 5px' class='btn btn-success' onclick='location.href=this.value'><i class='fa fa-eye'></i> View Receipt</button>";                    
   }		 
?>
<div class="orderofpayment-view">

   <div class="container">
    <?= DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-book"></i> Order of Payment: ' . $model->transactionnum,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'buttons1' => '',
        'attributes' => [
			[
                    'group'=>true,
                    'label'=>'Order of Payment Details '.$receipt_button,
                    'rowOptions'=>['class'=>'info']
            ],
            [
                'columns' => [
                    [
                        'label'=>'Transaction Number',
                        'format'=>'raw',
                        'value'=>$model->transactionnum,
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                    [
                        'label'=>'Customer Name',
                        'format'=>'raw',
                        'value'=>$model->customer ? $model->customer->customer_name : '',
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
                    
            ],
            [
                'columns' => [
                    [
                        'label'=>'Collection Type',
                        'format'=>'raw',
                        'value'=>$model->collectiontype->natureofcollection,
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                    [
                        'label'=>'Address',
                        'format'=>'raw',
                        'value'=>$model->customer ? $model->customer->completeaddress : '',
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
                    
            ],
           [
              'columns' => [
                  [
                        'label'=>'Date',
                        'format'=>'raw',
                        'value'=>$model->order_date,
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true,
                        'hAlign'=>'left',
                  ],
                  [
                    //'attribute'=>'request_datetime',
                    'label'=>' ',
                    'format'=>'raw',
                    'value'=>' ',
                    'valueColOptions'=>['style'=>'width:30%'], 
                    'displayOnly'=>true
                  ],
              ],
           ],
           
        ],
    ]) ?>
   </div>
    <div class="container">
        <div class="table-responsive">
             <?php
            $gridColumns = [
                [
                    'attribute'=>'details',
                    'enableSorting' => false,
                    'contentOptions' => [
                        'style'=>'max-width:180px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                    //'hAlign' => 'right',
                    'pageSummary' => '<span style="float:right;">Total</span>',
                ],
                /*[
                    'attribute'=>'amount',
                    'enableSorting' => false,
                    'contentOptions' => [
                        'style'=>'max-width:80px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                    'hAlign' => 'right', 
                    'vAlign' => 'middle',
                    'width' => '7%',
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],*/
                 [
                    'class' => 'kartik\grid\EditableColumn',
                    'refreshGrid'=>true,
                    'attribute' => 'amount', 
                    'readonly' => function($model, $key, $index, $widget) {
                        if($model->status == 2){
                            return true;
                        }
                        else{
                            return false;
                        }
                         // do not allow editing of inactive records
                     },
                    'editableOptions' => [
                        'header' => 'Amount', 
                        'size'=>'s',
                       // 'hAlign' => 'center',
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 5000]
                        ],
                        'formOptions'=>['action' => ['/finance/accounting/updateamount']],
                    ],
                    'hAlign' => 'left', 
                    'vAlign' => 'middle',
                    'width' => '25%',
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
               
              
              ];
              
             echo GridView::widget([
                'id' => 'paymentitem-grid',
                'dataProvider'=> $paymentitemDataProvider,
                'pjax'=>true,
                'export'=> false,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'showPageSummary' => true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Item(s)</h3>',
                    'type'=>'primary', 'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Add', ['value' => Url::to(['add-collection','opid'=>$model->orderofpayment_id]),'title'=>'Add Payment Item', 'onclick'=>'addPaymentitem(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn','disabled'=>$disable_button]),
                    'after'=>false,
                ],
                'columns' => $gridColumns,
               
            ]);
             ?>
        </div>
    </div>
</div>
<script type="text/javascript">
   
    function addPaymentitem(url,title){
       //var url = 'Url::to(['sample/update']) . "?id=' + id;
       //var url = '/lab/sample/update?id='+id;
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
	
    function addReceipt(url,title){
       LoadModal(title,url,'true','600px');
    }
   
</script>