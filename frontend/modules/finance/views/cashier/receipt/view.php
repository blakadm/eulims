<?php
use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use common\models\finance\Collection;
use yii\helpers\Url;
use common\models\finance\CancelledOr;
use common\models\system\Profile;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Op */
$this->title = 'Receipt';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = ['label' => 'Receipt', 'url' => ['/finance/cashier/receipt']];
$this->params['breadcrumbs'][] = 'View';
$enable=false;
$receiptid=$receipt->receipt_id;
$collectiontype_id=$model->collectiontype_id;
$displayy='';
if($collectiontype_id == 1 || $collectiontype_id == 2){
   $displayy='';
}
else{
   $displayy='display: none';
}

$print_button=Html::button('<span class="glyphicon glyphicon-download"></span> Export Receipt Excel', ['value'=>'/finance/cashier/printview?id='.$model->receipt_id, 'class' => 'btn btn-small btn-success','title' => Yii::t('app', "Print Report"),'onclick'=>"location.href=this.value"]);
$add_paymentitem=Html::button('<i class="glyphicon glyphicon-plus"></i> Add Paymentitem', ['value' => Url::to(['/finance/cashier/addpaymentitem','collectiontype_id'=>$model->collectiontype_id,'receiptid'=>$model->receipt_id]),'title'=>'Add Payment Item', 'onclick'=>'addPaymentitem(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn']);
//$add_extra=Html::button('<i class="glyphicon glyphicon-plus"></i> Excess Payment', ['value' => Url::to(['/finance/cashier/addextra','collectiontype_id'=>$model->collectiontype_id,'receiptid'=>$model->receipt_id]),'title'=>'Excess Payment', 'onclick'=>'addPaymentitem(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn','style'=>$displayy]);

$aftercontent="";
$totaldue=0;
$checksum=$check_sum ? $check_sum : 0;
$walletpaysum=$walletpay_sum ? $walletpay_sum : 0;
if($model->payment_mode_id == 2){
	$customerwallet1=Html::checkbox('sample', false, ['label' => '&nbsp;Use Customer Wallet','value'=>'1','id'=>'chkwallet']);
	$customerwallet2= Html::input('text','password1',$wallet ? $wallet->balance : 0,['disabled'=>true]);
	$totaldue=($model->total - $checksum) - $walletpaysum;
	//echo  $totaldue;
	$customerwallet3= '<label>Amount Due:</label>&nbsp;'.Html::input('text','due',$totaldue,['disabled'=>true,'id' => 'txtdue']);
	$cwbutton=Html::button('<span class="glyphicon glyphicon-save"></span> Confirm use of Customer Wallet', ['class' => 'btn btn-small btn-success','title' => Yii::t('app', "Print Report"),'onclick'=>'myFunction()','disabled'=>true,'id'=>'cwallet']);
	if($totaldue > 0){
		$aftercontent=$customerwallet1."&nbsp;&nbsp;&nbsp;".$customerwallet2."&nbsp;&nbsp;&nbsp;".$customerwallet3."&nbsp;&nbsp;&nbsp;".$cwbutton;
	}
	else{
		$aftercontent='<label>E-Wallet Amount:</label>'.$customerwallet2. '&nbsp;&nbsp;&nbsp;<label>Amount Paid using E-Wallet:</label>&nbsp;'.Html::input('text','ewalletamount',$walletpay_sum ? $walletpay_sum : 0,['disabled'=>true,'id' => 'ewalletamount']);
	}
	
}



if ($model->cancelled){
	$CancelButton='';
	
	$Cancelledor= CancelledOr::find()->where(['receipt_id'=>$model->receipt_id])->one();
	if($Cancelledor){
		$Reasons=$Cancelledor->reason;
		$DateCancelled=date('m/d/Y h:i A', strtotime($Cancelledor->cancel_date));
		// Query Profile Name
		$Profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
		$UserCancell=$Profile->fullname;
		$CancelledBy=$UserCancell;
	}
	$CancelClass='request-cancelled';
	$BackClass='background-cancel';
}else {
	 $Func="LoadModal('Cancel Receipt','/finance/cancelledor/create?id=".$model->receipt_id."',true,500)";
     $CancelButton='&nbsp;&nbsp;&nbsp;<button id="btnCancel" onclick="'.$Func.'" type="button"  class="btn btn-danger"><i class="fa fa-remove"></i> Cancel Receipt</button>';

	 $CancelClass='cancelled-hide';
	 $BackClass='';
	 $Reasons='&nbsp;';
	 $DateCancelled='';
	 $CancelledBy='';
}


?>
<div class="receipt-view" style="position:relative;">
   <div id="cancelled-div" class="outer-div <?= $CancelClass ?>">
        <div class="inner-div">
        <img src="/images/cancelled.png" alt="" style="width: 300px;margin-left: 80px"/>
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <table class="table table-condensed table-hover table-striped table-responsive">
                 <tr>
                    <th style="background-color: lightgray">Date Cancelled</th>
                    <td><?= $DateCancelled ?></td>
                </tr>
                <tr>
                    <th style="width: 120px;background-color: lightgray">Reason of Cancellation</th>
                    <td style="width: 230px"><?= $Reasons ?></td>
                </tr>
                <tr>
                    <th style="background-color: lightgray">Cancelled By</th>
                    <td><?= $CancelledBy ?></td>
                </tr>
            </table>
        </div>
        </div>
    </div> 
   <div class="<?= $BackClass ?>"></div>
   <div class="container">
    <?= DetailView::widget([
	 // echo $form->field($model, 'total')->textInput(['value'=>$wallet->balance,'readonly'=> true])->label('E-Wallet');
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-book"></i> Receipt#: ' . $model->or_number .$CancelButton,
            'type'=>DetailView::TYPE_PRIMARY,
         ],
        'buttons1' => '',
        'attributes' => [
          
            [
                'columns' => [
                    [
                        'label'=>'Payor',
                        'format'=>'raw',
                        'value'=>$model->payor,
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                    [
                        'label'=>'Nature of Collection',
                        'format'=>'raw',
                        'value'=>$model->collectiontype ? $model->collectiontype->natureofcollection : "",
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
                    
            ],
            [
                'columns' => [
                    [
                        'label'=>'Receipt Date',
                        'format'=>'raw',
                        'value'=>$model->receiptDate,
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                    [
                        'label'=>'Total of Collection',
                        'value'=>$model->total,
                        'format' => ['decimal', 2],
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
                    
            ],
          
        ],
    ]) ?>
   </div>
    <div class="main-container">
        <div class="container">
        <div class="table-responsive">
        <?php
            $gridColumns = [
                ['class' => 'kartik\grid\SerialColumn', 
                ],
                [
                    'attribute'=>'details',
                    'enableSorting' => false,
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'attribute'=>'amount',
                    'enableSorting' => false,
                    'hAlign' => 'right', 
                    'vAlign' => 'middle',
                    'width' => '15%',
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete') {
                            $url ='/finance/cashier/remove-paymentitem?paymentitemid='.$model->paymentitem_id;
                            return $url;
                        }
                    },
                    'template' => '{delete}',
                    'buttons'=>[
                       'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data-confirm'=>"Are you sure you want to remove this item?",'data-method'=>'post','class'=>'btn btn-danger','title'=>'Delete','data-pjax'=>'0']);
                        },
                    ],
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ],
              
            ];
            echo GridView::widget([
                'id' => 'collection-grid',
                'dataProvider'=> $paymentitemDataProvider,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'showPageSummary' => true,
                'hover'=>true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Collection</h3>',
                    //remove add collection Html::button('<i class="glyphicon glyphicon-plus"></i> Add Collection', ['disabled'=>$enable, 'value' => Url::to(['add-collection','opid'=>$op_model->orderofpayment_id,'receiptid'=>$model->receipt_id]),'title'=>'Add Collection', 'onclick'=>'addCollection(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn'])." 
                    //'type'=>'primary', 'before'=>$add_paymentitem."&nbsp;&nbsp;&nbsp;".Html::button('<i class="glyphicon glyphicon-print"></i> Print Receipt', ['disabled'=>$enable, 'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['/reports/preview?url=/finance/cashier/print-or','or_number'=>$model->or_number]) . "';" ,'title'=>'Print Receipt',  'class' => 'btn btn-success'])."&nbsp;&nbsp;&nbsp;".$print_button,
                    'type'=>'primary', 'before'=>$add_paymentitem."&nbsp;&nbsp;&nbsp;".$print_button,
					'after'=> $aftercontent

                ],
                'columns' => $gridColumns,
               'toolbar' => [
                ],
            ]);
        ?>
        </div>
    </div>
    </div>
    <?php
     if($model->payment_mode_id == 2){
         
        ?>
          <div class="main-container-check">
        <div class="container">
        <div class="table-responsive">
        <?php
            $gridColumns = [
                [
                    'attribute'=>'bank',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'checknumber',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'checkdate',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'amount',
                    'enableSorting' => false,
                    'hAlign' => 'right', 
                    'vAlign' => 'middle',
                    'width' => '15%',
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete') {
                            $url ='/finance/cashier/remove-check?checkid='.$model->check_id;
                            return $url;
                        }
                    },
                    'template' => '{delete}',
                    'buttons'=>[
                       'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data-confirm'=>"Are you sure you want to delete cheque #: <b>".$model->checknumber."</b>?",'data-method'=>'post','class'=>'btn btn-danger','title'=>'Delete','data-pjax'=>'0']);
                        },
                    ],
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ],
            ];
            echo GridView::widget([
                'id' => 'check-grid',
                'dataProvider'=> $check_model,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'showPageSummary' => true,
                'hover'=>true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Cheque(s) Details</h3>',
                    'type'=>'primary', 'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Add Check', ['disabled'=>$enable, 'value' => Url::to(['add-check','receiptid'=>$model->receipt_id]),'title'=>'Add Check', 'onclick'=>'addCheck(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']),
                    'after'=>false,
                ],
                'columns' => $gridColumns,
               'toolbar' => [
                ],
            ]);
        ?>
        </div>
    </div>
    </div>
    <?php
     }
    ?>
   
    
</div>
<script type="text/javascript">
    $('#chkwallet').on('click',function() {
       if ($(this).prop('checked')) {
			   var total= <?php echo $totaldue ? $totaldue : 0 ?>;
			   var customerwallet= <?php echo $wallet ? $wallet->balance : 0 ?>;
			   var due= total - customerwallet;
			   if (due < 0) { //means customer wallet is less
				   due=0;
			   }
			   $('#txtdue').val(due);
		   
		   
		   $("#cwallet").attr("disabled", false);
        }
        else {
           // do what you need here         
		   $('#txtdue').val(<?php echo $totaldue ? $totaldue : 0 ?>);
		   $("#cwallet").attr("disabled", true);
        }
    });
   
    function addCollection(url,title){
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
    function addCheck(url,title){
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
    // ['/finance/cashier/remove-check?checkid='.$check_model->check_id], 
   $('#btnRemoveCheck').on('click',function() {
       alert('heheh');
   });
  function myFunction() {
	  var txt;
	  var r = confirm("Use E-Wallet?");
	  if (r == true) {
		//txt = "You pressed OK!";
		$.post({
            url: '/finance/cashier/usecustomerwallet?id='+<?php echo $model->receipt_id?>, // your controller action
            success: function(data) {
                
            }
        });
	  } else {
		//txt = "You pressed Cancel!";
	  }
	 //alert(txt);
	}
   
    function addPaymentitem(url,title){
        LoadModal(title,url,'true','600px');
    }
</script>