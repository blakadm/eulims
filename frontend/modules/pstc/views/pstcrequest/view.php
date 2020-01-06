<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
//use yii\web\JsExpression;
//use yii\widgets\ListView;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $request common\models\referral\Pstcrequest */

$this->title = empty($respond['request_ref_num']) ? $request['pstc_request_id'] : $respond['request_ref_num'];
$this->params['breadcrumbs'][] = ['label' => 'Pstcrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$reference_num = !empty($respond['request_ref_num']) ? $respond['request_ref_num'] : '<i class="text-danger font-italic">Pending request</i>';

if(!empty($respond['request_ref_num'])){//With Reference
    $disableButton="false";
}else{ // NO reference number yet
    $disableButton="true";
}

$accepted = $request['accepted'];
$requestId = $request['pstc_request_id'];
$accepted = $request['accepted'];
?>
<div class="pstcrequest-view">
    <div class="image-loader" style="display: none;"></div>
    <div class="container table-responsive">
        <?php
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-book"></i> Request Reference No. : ' . $reference_num,
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'buttons1' => '',
            'attributes'=>[
                [
                    'group'=>true,
                    'label'=>'Request Details ',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Customer Requested Date',
                            'format'=>'raw',
                            'value' => date('F j, Y h:i A', strtotime($request['created_at'])),
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'PSTC',
                            'format'=>'raw',
                            'value'=> !empty($pstc) ? $pstc['name'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Request Reference Number',
                            'format' => 'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'value'=> $reference_num,
                        ],
                        [
                            'label'=>'Customer / Agency',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && !empty($customer) ? $customer['customer_name'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                    
                ],
                [
                    'columns' => [
                       [
                            'label'=>'Local Request Created Date',
                            'format'=>'raw',
                            'value'=> !empty($respond['request_date_created'])  ? date('F j, Y h:i A', strtotime($respond['request_date_created'])) : "<i class='text-danger font-italic'>Pending request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Address',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && !empty($customer['customer_name']) ? $customer['address'] : "",
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
                            'value'=> !empty($respond['estimated_due_date'])  ? date('F j, Y', strtotime($respond['estimated_due_date'])) : "<i class='text-danger font-italic'>Pending request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Tel no.',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && !empty($customer['customer_name']) ? $customer['tel'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
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
                            'label'=>'Recieved By',
                            //'attribute' => 'received_by',
                            'value'=> $request['received_by'],
                            'format'=>'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%']
                        ],
                        [
                            'label'=>'Submitted By',
                            //'attribute' => 'submitted_by',
                            'value'=> $request['submitted_by'],
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
            $sampleGridColumns = [
                [
                    'header' => 'Sample Code',
                    'attribute'=>'sample_code',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'header' => 'Sample Name',
                    'attribute'=>'sample_name',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'header' => 'Sample Description',
                    'attribute'=>'sample_description',
                    'format' => 'raw',
                    'enableSorting' => false,
                    //'value' => function($data) use ($request){
                    //    return ($request->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data->sampling_date))."</b></span>,&nbsp;".$data->description : $data->description;
                    //},
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
                /* [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {remove}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'urlCreator' => function ($action, $data, $key, $index) {
                        if ($action === 'remove') {
                            $url ='/pstc/pstcsample/delete?id='.$data['pstc_sample_id'];
                            return $url;
                        }
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $data) use ($request) {
                            if($data['active'] == 1 && $request['accepted'] == 0){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['class'=>'btn btn-primary','title'=>'Update Sample','onclick' => 'updateSample('.$data['pstc_sample_id'].','.$data['pstc_request_id'].')']);
                            } else {
                                return null;
                            }
                        },
                        'remove' => function ($url, $data) use ($request) {
                            if($data['sample_code'] == "" && $data['active'] == 1 && $request['accepted'] == 0){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data-confirm'=>"Are you sure you want to delete <b>".$data['sample_name']."</b>?",'data-method'=>'post','class'=>'btn btn-danger','title'=>'Remove Sample','data-pjax'=>'0']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ], */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    /*'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'remove') {
                            $url = '';//'/pstc/pstcsample/delete?id='.$model->pstc_sample_id;
                            return $url;
                        }
                    },*/
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $data) use ($accepted) {
                            if($data['active'] == 1 && $accepted == 0){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['class'=>'btn btn-primary','title'=>'Update PSTC Sample','onclick' => 'updateSample('.$data['pstc_sample_id'].','.$data['pstc_request_id'].','.$data['pstc_id'].',this.title)']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ],
            ];

            echo GridView::widget([
                'id' => 'sample-grid',
                //'dataProvider'=> $sampleDataProvider,
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
                    //'before'=>null,
                    'after'=>false,
                    //'before'=> Html::button('<i class="glyphicon glyphicon-plus"></i> Add Sample', ['value' => Url::to(['/pstc/pstcsample/create','request_id'=>$request['pstc_request_id']]),'title'=>'Add Sample', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']),
                ],
                'columns' => $sampleGridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest/view','request_id'=>$request['pstc_request_id'],'pstc_id'=>$request['pstc_id']])], [
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
                    'attribute'=>'sample_code',
                    'header'=>'Sample Code',
                    //'value' => function($data) use ($sample) {
                    //    return !empty($sample) ? $sample['sample_code'] : null;
                    //},
                    'format' => 'raw',
                    'enableSorting' => false,
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                ],
                [
                    'attribute'=>'sample_name',
                    'header'=>'Sample Name',
                    'format' => 'raw',
                    'enableSorting' => false,
                    //'value' => function($data) use ($sample) {
                    //    return !empty($data['sample']) ? $sample['sample_name'] : null;
                    //},
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                ],
                [
                    //'attribute'=>'testnames.testName',
                    'format' => 'raw',
                    'header'=>'Test/ Calibration Requested',
                    'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                    'enableSorting' => false,
                    'value' => function($data) {
                       if($data['is_package_name'] == 1){
                            return $data['package_name'];
                        } elseif($data['is_package_name'] == 0 && $data['is_package'] == 1){
                            return "&nbsp;&nbsp;<span style='font-size:12px;'>".$data['testname']."</span>";
                        } else {
                            return $data['testname'];
                        }
                    },
                    // 'value' => function($data) {
                    //     if($data->is_package_name == 1){
                    //         return $data->package_name;
                    //     } elseif($data->is_package_name == 0 && $data->is_package == 1){
                    //         return "&nbsp;&nbsp;<span style='font-size:12px;'>".(!empty($data->testnames) ? $data->testnames->testName : null)."</span>";
                    //     } else {
                    //         return !empty($data->testnames) ? $data->testnames->testName : null;
                    //     }
                    // },
                ],
                [
                    //'attribute'=>'methodrefs.method',
                    'format' => 'raw',
                    'header'=>'Test Method',
                    'enableSorting' => false,
                    // 'value' => function($request) {
                    //    return !empty($request->methodrefs) ? $request->methodrefs->method : null;
                    // },
                    'value' => function($data) {
                        if($data['is_package_name'] == 1){
                            return '-';
                        } elseif($data['is_package_name'] == 0 && $data['is_package'] == 1){
                            return "&nbsp;&nbsp;<span style='font-size:12px;'>".$data['method']."</span>";
                        } else {
                            return $data['method'];
                        }
                    },
                    'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                    'pageSummary' => function() use ($countSample, $countAnalysis) {
                        return ($countAnalysis > 0 && $countSample > 0) ? '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>' : '';
                    },
                ],
                [
                    'attribute'=>'fee',
                    'header'=>'Unit Price',
                    'enableSorting' => false,
                    'hAlign'=>'right',
                    'format' => 'raw',
                    // 'value'=>function($request){
                    //     return number_format($request->fee,2);
                    // },
                    'value'=>function($data){
                        return ($data['is_package_name'] == 0 & $data['is_package'] == 1) ? '-' : number_format($data['fee'],2);
                    },
                    'contentOptions' => [
                        'style'=>'max-width:80px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                    'hAlign' => 'right', 
                    'vAlign' => 'left',
                    'width' => '7%',
                    'format' => 'raw',
                    'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample,$countAnalysis) {
                        return ($countSample > 0 && $countAnalysis > 0) ? '<div id="subtotal">₱ '.number_format($subtotal, 2).'</div><div id="discount">₱ '.number_format($discounted, 2).'</div><div id="total"><b>₱ '.number_format($total, 2).'</b></div>' : '';
                    },
                ],
                /* [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {remove}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $data) use ($accepted,$requestId) {
                            if($data['sample_code'] == "" && $data['active'] == 1 && $accepted == 0 && $data['is_package'] == 0 && $data['testcategory_id'] > 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['onclick' => 'updateAnalysis('.$data['pstc_analysis_id'].','.$requestId.',this.title)', 'class' => 'btn btn-primary','title' => 'Update Analysis']);
                            } elseif($data['sample_code'] == "" && $data['active'] == 1 && $accepted == 0 && $data['is_package'] == 1 && $data['is_package_name'] == 1 && $data['testcategory_id'] > 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/pstc/pstcanalysis/updatepackage','analysis_id'=>$data['pstc_analysis_id'],'sample_id'=>$data['pstc_sample_id'],'package_id'=>$data['package_id'],'request_id'=>$requestId]),'onclick'=>'updatePackage(this.value,this.title)', 'class' => 'btn btn-primary','title' => 'Update Package']);
                            } elseif($data['sample_code'] == "" && $data['active'] == 1 && $accepted == 0 && $data['is_package'] == 0 && $data['testcategory_id'] == 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['onclick' => 'updateReferralAnalysis('.$data['pstc_analysis_id'].','.$requestId.',this.title)', 'class' => 'btn btn-primary','title' => 'Update Analysis Not Offered']);
                            } else {
                                return null;
                            }
                        },
                        'remove' => function ($url, $data) use ($accepted,$requestId) {
                            if($data['is_package_name'] == 0 && $data['is_package'] == 0) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/pstc/pstcanalysis/delete?id='.$data['pstc_analysis_id'].'&request_id='.$requestId,['data-confirm'=>"Are you sure you want to delete this analysis?", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Remove Analysis','data-pjax'=>'0']);
                            } elseif($data['is_package_name'] == 1 && $data['is_package'] == 1) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>','/pstc/pstcanalysis/deletepackage?sample_id='.$data['pstc_sample_id'].'&package_id='.$data['package_id'].'&request_id='.$requestId,['data-confirm'=>"This will delete all the analyses under this package. Click OK to proceed. <b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Remove Package','data-pjax'=>'0']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ], */
            ];

            $btn_saveRequest = ($request['is_referral'] == 0) ? Html::button('<span class="glyphicon glyphicon-save"></span> Save as Local Request', ['value' => Url::to(['/pstc/pstcrequest/request_local','request_id'=>$request['pstc_request_id'],'pstc_id'=>$request['pstc_id']]),'title'=>'Save as Local Request', 'onclick'=>'saveRequest(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn']) : Html::button('<span class="glyphicon glyphicon-save"></span> Save as Referral Request', ['value' => Url::to(['/pstc/pstcrequest/request_referral','request_id'=>$request['pstc_request_id'],'pstc_id'=>$request['pstc_id']]),'title'=>'Save as Referral Request', 'onclick'=>'saveRequest(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn']);

            echo GridView::widget([
                'id' => 'analysis-grid',
                'responsive'=>true,
                'dataProvider'=> $analysisDataprovider,
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
                    //'before'=> Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis', ['value' => Url::to(['/pstc/pstcanalysis/create','request_id'=>$request['pstc_request_id']]),'title'=>'Add Analysis', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.Html::button('<i class="glyphicon glyphicon-plus"></i> Add Package', ['value' => Url::to(['/pstc/pstcanalysis/package','request_id'=>$request['pstc_request_id']]),'title'=>'Add Package', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis Not Offered', ['value' => Url::to(['/pstc/pstcanalysis/add_not_offer','request_id'=>$request['pstc_request_id']]),'title'=>'Add Analysis Not Offered', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']),
                    'after'=> false,
                    //'footer'=>$actionButtonConfirm.$actionButtonSaveLocal,
                    //'footer'=>null,
                    'footer'=> ($countSample > 0 && $countAnalysis > 0 && empty($respond['request_ref_num']) && $request['accepted'] == 0) ? $btn_saveRequest : '',
                ],
                'columns' => $analysisgridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest/view','request_id'=>$request['pstc_request_id']])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
    </div>
    <?php if(!empty($respond['request_ref_num']) && $request['accepted'] == 1 && $request['local_request_id'] > 0): ?>
    <div class="container">
        <div class="panel panel-primary">
        <div class="panel-body">
        <!--<div class="table-responsive">-->
        <?php
            $items = [
                [
                    'label'=>'<i class="glyphicon glyphicon-save-file"></i> Uploaded Request Form',
                    'content'=> $this->renderAjax('_attachment',['ref_num'=>$respond['request_ref_num'],'request'=>$request,'attachmentDataprovider'=>$attachmentDataprovider]),
                    'active'=>true,
                    //'linkOptions'=>['data-url'=>Url::to(['/pstcrequest/show_attachment?id='.$model->pstc_request_id])]
                ],
            ];

            echo TabsX::widget([
                'items'=>$items,
                'position'=>TabsX::POS_ABOVE,
                //'bordered'=>true,
                'encodeLabels'=>false
            ]);
        ?>
        <!--</div>-->
        </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php
    Modal::begin([
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
        'bodyOptions'=>[
            'class' => 'modal-body',
            'style'=>'padding-bottom: 20px',
        ],
        'options' => [
            'id' => 'modalPstcRequest',
            'tabindex' => false, // important for Select2 to work properly
            //'tabindex' => 0, // important for Select2 to work properly
        ],
        'header' => '<h4 class="fa fa-clone" style="padding-top: 0px;margin-top: 0px;padding-bottom:0px;margin-bottom: 0px"> <span class="modal-title" style="font-size: 16px;font-family: \'Source Sans Pro\',sans-serif;"></span></h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    echo "<div>";
    echo "<div class='modal-scroll'><div id='modalContent' style='margin-left: 5px;'><div style='text-align:center;'><img src='/images/img-png-loader64.png' alt=''></div></div>";
    echo "<div>&nbsp;</div>";
    echo "</div></div>";
    Modal::end();
?>

<script type="text/javascript">
    function saveRequest(url,title){
        $(".modal-title").html(title);
        $('#modalPstcRequest').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function updateSample(id,requestId,pstcId,title){
        var url = '/pstc/pstcrequest/sample_update?sample_id='+id+'&request_id='+requestId+'&pstc_id='+pstcId;
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    /*function updateSample(id,requestId){
       var url = '/pstc/pstcsample/update?id='+id+'&request_id='+requestId;
        $('.modal-title').html('Update Sample');
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    } */

    function addAnalysis(url,title){
        $(".modal-title").html(title);
        $('#modalAnalysis').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function updateAnalysis(id,requestId,title){
        $.ajax({
            url: '/pstc/pstcanalysis/getdefaultpage?analysis_id='+id,
            success: function (data) {
                $('.image-loader').removeClass('img-loader');
                var url = '/pstc/pstcanalysis/update?id='+id+'&request_id='+requestId+'&page='+data;
                $('.modal-title').html(title);
                $('#modalAnalysis').modal('show')
                    .find('#modalContent')
                    .load(url);
            },
            beforeSend: function (xhr) {
                $('.image-loader').addClass('img-loader');
            }
        });
    }

    function updateReferralAnalysis(id,requestId,title){
        $.ajax({
            url: '/pstc/pstcanalysis/getreferraldefaultpage?analysis_id='+id,
            success: function (data) {
                $('.image-loader').removeClass('img-loader');
                var url = '/pstc/pstcanalysis/update_not_offer?id='+id+'&request_id='+requestId+'&page='+data;
                $('.modal-title').html(title);
                $('#modalAnalysis').modal('show')
                    .find('#modalContent')
                    .load(url);
            },
            beforeSend: function (xhr) {
                $('.image-loader').addClass('img-loader');
            }
        });
    }

    function updatePackage(url,title){
        $('.modal-title').html(title);
        $('#modalAnalysis').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function uploadRequest(title) {
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        var url = "<?= Url::to(['/pstc/pstcattachment/upload','local_request_id'=>$request['local_request_id']]) ?>";
        $('#modalContent').html(_replace);
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function downloadRequest(url) {
        $.ajax({
            url: url,
            success: function (data) {
                $('.image-loader').removeClass('img-loader');
            },
            beforeSend: function (xhr) {
                $('.image-loader').addClass('img-loader');
            }
        });
    }
</script>
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
    background-image: url('/images/img-png-loader64.png');
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