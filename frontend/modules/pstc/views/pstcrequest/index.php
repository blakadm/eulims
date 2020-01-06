<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use yii\helpers\Url;
use kartik\date\DatePicker;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\PstcrequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pstcrequests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcrequest-index">

      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'pstcrequest-grid',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> PSTC Request</h3>',
            'type'=>'primary',
            'after'=>false,
            'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create PSTC Request', ['value' => Url::to(['pstcrequest/create']),'title'=>'Create PSTC Request', 'onclick'=>'addRequest(this.value,this.title)', 'class' => 'btn btn-success','id' => 'pstccreate']),
            //'before'=>"<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/referrals/referral/create\")' class=\"btn btn-success\"><i class=\"glyphicon glyphicon-plus\"></i> Create Referral Request</button>",
            //'before'=>"<span style='color:#000099;'><b>Note:</b> All referral requests with referral code are shown.</span>",
        ],
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],
            /*[
                'header' => 'Referral Code',
                'attribute' => 'referral_code',
                'format' => 'raw',
                //'value' => function($data){ return $data->referral_code;},
                'headerOptions' => ['class' => 'text-center'],
            ],*/
            [
                'header' => 'Customer',
                'attribute' => 'customer_id',
                'format' => 'raw',
                'value' => function($data){ 
                    return !empty($data->customer) ? $data->customer->customer_name : null;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $customers,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Search customer name', 'id' => 'grid-search-customer_id'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Date Created',
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function($data){
                    //return Yii::$app->formatter->asDate($data->created_at, 'php:F j, Y h:i A');
                    return date('F j, Y h:i A',strtotime($data->created_at));
                },
                'headerOptions' => ['class' => 'text-center'],
                'filterType'=> GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'model' => $searchModel,
                    'options' => ['placeholder' => 'Select date created'],
                    'attribute' => 'created_at',
                    //'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ],
            ],
            [
                'header' => 'Submitted By',
                'attribute' => 'submitted_by',
                'format' => 'raw',
                // 'value' => function($data){
                //     return !empty($data->customer) ? $data->agencyreceiving->name : null;
                // },
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Received By',
                'attribute' => 'received_by',
                'format' => 'raw',
                // 'value' => function($data){
                //     return !empty($data->customer) ? $data->agencyreceiving->name : null;
                // },
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {edit}',
                'dropdown' => false,
                'dropdownOptions' => ['class' => 'pull-right'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'buttons' => [
                    'view' => function($url, $data) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['pstcrequest/view','id'=>$data->pstc_request_id]),'onclick'=>'window.open(this.value,"_blank")', 'class' => 'btn btn-primary','title' => 'View PSTC Request']);
                        //return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['referral/view', 'id' => $data->referral_id], ['class' => 'btn btn-primary','title' => 'View '.$data->referral_code,'target'=>"_blank"]);
                    },
                    'edit' => function($url, $data) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['pstcrequest/view','id'=>$data->pstc_request_id]),'onclick'=>'window.open(this.value,"_blank")', 'class' => 'btn btn-primary','title' => 'View PSTC Request']);
                    }
                ],
            ],
        ],
        'toolbar' => [
            'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest'])], [
                        'class' => 'btn btn-default', 
                        'title' => 'Refresh Grid'
                    ]),
        ],
]); ?>
</div>

<script type="text/javascript">
    $('#pstcrequest-grid tbody td').css('cursor', 'pointer');
    function addRequest(url,title){
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
    
</script>
