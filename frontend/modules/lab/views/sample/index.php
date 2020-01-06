<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\Functions;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Samples';
$this->params['breadcrumbs'][] = ['label' => 'Lab', 'url' => ['/lab']];
$this->params['breadcrumbs'][] = $this->title;
$func=new Functions();
?>
<div class="sample-index">
<div class="panel panel-default col-xs-12">
        <div class="panel-heading"><i class="fa fa-adn"></i> </div>
        <div class="panel-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <fieldset>
        <legend>Legend/Status</legend>
        <div style='padding: 0 10px'>
			<span class='badge btn-success legend-font'><span class='glyphicon glyphicon-ok'></span> ACTIVE</span>
            <span class='badge btn-danger legend-font'><span class='glyphicon glyphicon-ban-circle'></span> CANCELLED</span>
        </div>
		</fieldset>

    <?= GridView::widget([
		'id' => 'sample-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title">Samples</h3>',
			'type'=>'primary',
			'after'=>false,
		],
        'columns' => [
            [
                'attribute' => 'request_id',
                'format' => 'raw',
                'value' => function($data){ return $data->request->request_ref_num;},
                'group'=>true,  // enable grouping
				'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'sampletype_id',
                'format' => 'raw',
                'value' => function($data){ return !empty($data->sampletype->type) ? $data->sampletype->type : null;},
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $sampletypes,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Sample Type', 'id' => 'grid-search-sampletype_id'],
				'headerOptions' => ['class' => 'text-center'],
            ],
            [
				'attribute'=>'sample_code',
				'headerOptions' => ['class' => 'text-center'],
			],
			[
				'attribute'=>'samplename',
				'headerOptions' => ['class' => 'text-center'],
			],
            [
                'attribute'=>'description',
                'format' => 'raw',
                'contentOptions' => [
                    'style'=>'max-width:40%; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
				'headerOptions' => ['class' => 'text-center'],
            ],
			[
                'attribute'=>'active',
				'header' => 'Status',
                'format' => 'raw',
				'value' => function($data){ return ($data->active == 1) ? "<span class='badge btn-success legend-font'>ACTIVE</span>" : "<span class='badge btn-danger legend-font'>CANCELLED</span>";},
				'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['1' => 'Active', '0' => 'Cancelled'],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Select Status', 'id' => 'grid-search-active'],
				'contentOptions' => ['class' => 'text-center'],
				'headerOptions' => ['class' => 'text-center'],
            ],
        ],
		'toolbar' => [
			'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', [Url::to([''])], [
						'class' => 'btn btn-default', 
						'title' => 'Reset Grid'
					]),
			//'{toggleData}',
		],
		
    ]); ?>
        </div>
</div>
</div>

<script type="text/javascript">
    $("#modalBtn").click(function(){
        $(".modal-title").html($(this).attr('title'));
        $("#modal").modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
</script>
