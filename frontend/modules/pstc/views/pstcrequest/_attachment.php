<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;


//print_r($attachmentDataprovider);
//exit;

$attachmentGridColumns = [
    [
        'header' => 'Files',
        'attribute'=>'filename',
        'enableSorting' => false,
        'format' => 'raw',
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
        ],
        'value' => function($data) {
            return $data['filename'];
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{download}',
        'dropdown' => false,
        'dropdownOptions' => ['class' => 'pull-right'],
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'buttons' => [
            'download' => function ($url, $data) use ($request) {
                //if($model->sample_code == "" && $model->active == 1 && $model->request->accepted == 0){
                //    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data-confirm'=>"Are you sure you want to delete <b>".$model->sample_name."</b>?",'data-method'=>'post','class'=>'btn btn-danger','title'=>'Remove Sample','data-pjax'=>'0']);
                //} else {
                //    return null;
                //}
                return Html::button('<i class="glyphicon glyphicon-download-alt"></i>', ['value'=>Url::to(['/pstc/pstcattachment/download','pstc_request_id'=>$data['pstc_request_id'],'local_request_id'=>$request['local_request_id'],'file'=>$data['pstc_attachment_id']]),'title'=>'Download File', 'onclick'=>'downloadRequest(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn']);
                //return $data['pstc_attachment_id'];
                //return $data;
            },
        ],
    ],
];

echo GridView::widget([
    'id' => 'attachment-grid',
    'dataProvider'=> $attachmentDataprovider,
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
        'heading'=>'<h3 class="panel-title">Downloadables</h3>',
        'type'=>'primary',
        //'before'=>null,
        'after'=>false,
        'before'=> ($request['accepted'] == 1 && $request['local_request_id'] > 0 && !empty($ref_num)) ? Html::button('<i class="glyphicon glyphicon-upload"></i> Upload', ['value'=>'#','title'=>'Upload Request Form', 'onclick'=>'uploadRequest(this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']) : '',
    ],
    'columns' => $attachmentGridColumns,
    'toolbar' => [
        //'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest/view','id'=>$model->pstc_request_id])], [
        //            'class' => 'btn btn-default', 
        //            'title' => 'Refresh Grid'
        //        ]),
        'content' => ''
    ],
]);

?>