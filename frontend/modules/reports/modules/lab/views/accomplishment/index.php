<?php

use yii\helpers\Html;
//use yii\grid\GridView;
//use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\lab\Lab;
use kartik\grid\Module;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;
use kartik\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accomplishment Report';
$this->params['breadcrumbs'][] = $this->title;

$pdfHeader="OneLab-Enhanced ULIMS";
$pdfFooter="{PAGENO}";
?>
 <?php $this->registerJsFile("/js/services/services.js"); ?>
<div class="accomplishment-index">
<div class="panel panel-default col-xs-12">
        <div class="panel-heading"><i class="fa fa-adn"></i> </div>
        <div class="panel-body">
    	<?php
    		$form = ActiveForm::begin([
			    'id' => 'accomplishment-form',
			    'options' => [
					'class' => 'form-horizontal',
					//'data-pjax' => true,
				],
				'method' => 'get',
			])
    	?>
    
    		<div class="row">
		        <div id="lab-name" style="width:25%;position: relative; float:left;margin-right: 20px;">
		            <?php
		            	echo '<label class="control-label">Laboratory </label>';
						echo Select2::widget([
						    'name' => 'lab_id',
						    'id' => 'lab_id',
						    'value' => (!isset($_GET['lab_id'])) ? 1 : (int) $_GET['lab_id'],
						    'data' => $laboratories,
						    'theme' => Select2::THEME_KRAJEE,
						    'options' => ['placeholder' => 'Select Laboratory '],
						    'pluginOptions' => [
						        'allowClear' => true,
						    ],
						]);
		            ?>
		            <span class="error-lab text-danger" style="position:fixed;"></span>
		         </div>
		         <div id="date_range" style="position: relative; float: left;margin-left: 20px;">
    				<?php
				        echo '<label class="control-label">Request Date </label>';
		    			echo '<div class="input-group drp-container">';
						echo DateRangePicker::widget([
						    'name'=>'request_date_range',
						    'id'=>'request_date_range',
						    'value' => (!isset($_GET['request_date_range'])) ? date("Y-m-d")." to ".date("Y-m-d") : $_GET['request_date_range'],
						    'useWithAddon'=>true,
						    'convertFormat'=>true,
						    'startAttribute' => 'from_date',
						    'endAttribute' => 'to_date',
						    'options'=>[
						    	'class' => 'form-control',
					            'style' => 'padding:5px;width:300px;',
					        ],
						    'pluginOptions'=>[
						        'locale'=>[
						        	'format' => 'Y-m-d',
						        	'separator'=>' to ',
						        ],
						    ]
						]);
						echo '</div>';
		    		?>
		    		<span class="error-date text-danger" style="position:fixed;"></span>
		    	</div>
		    	 <div style="width:15%;position: relative; float:left;margin: 27px 0 0 10px;">
		    		<button type="button" id="btn-filter" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
		    	</div>
		    </div>
		    <?php ActiveForm::end(); ?>
       </div>
		    <div class="row">
			<?php $this->registerJsFile("/js/services/services.js"); ?>
		    <?php //\yii\widgets\Pjax::begin(); ?>
        	<?php
        		$startDate = Yii::$app->request->get('from_date', date('Y-01-01'));
        		$endDate = Yii::$app->request->get('to_date', date('Y-m-d'));
        		$labId = (int) Yii::$app->request->get('lab_id', 1);
        		$labCode = Lab::findOne($labId)->labcode;

				$gridColumns = [
					['class' => 'kartik\grid\ActionColumn',
					'contentOptions' => ['style' => 'width: 8.7%'],
					'template' => '{view}',
					'buttons'=>[
						'view'=>function ($url, $model) {
							return Html::button('<span class="glyphicon glyphicon-print"></span>', ['value'=>Url::to(['/lab/tagging/monthlyreport','month'=>Yii::$app->formatter->asDate($model->request_datetime, 'php:M'), 'year'=>Yii::$app->formatter->asDate($model->request_datetime, 'php:Y')]), 'class' => 'btn btn-primary modal_method','onclick'=>'LoadModal(this.title, this.value ,true, 1850);','title' => Yii::t('app', "Monthly Report")]);
						},
					   
					],
					],
	    		    [
			            'attribute'=>'request_datetime', 
			            'header' => 'Year',
						//'width'=>'310px',
						// 'value'=>function ($model, $key, $index, $widget) {
						// 	return "ito yun";
						// },
			            'value'=>function ($model, $key, $index, $widget) {
		                    return Yii::$app->formatter->asDate($model->request_datetime, 'php:Y');
		                },
		                'contentOptions' => ['class' => 'bg-info text-primary','style'=>'font-weight:bold;font-size:15px;'],
			            'group'=>true,  // enable grouping,
			            'groupedRow'=>true,                    // move grouped column to a single grouped row
			            //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
			            //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			            'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
			                return [
			                    'mergeColumns'=>[[1]], // columns to merge in summary
			                    'content'=>[             // content to show in each summary cell
			                        1=>'SUB-TOTAL ('.Yii::$app->formatter->asDate($model->request_datetime, 'php:Y').')',
			                       // 2=>GridView::F_SUM,
			                        3=>GridView::F_SUM,
			                        4=>GridView::F_SUM,
			                        5=>GridView::F_SUM,
			                        6=>GridView::F_SUM,
			                        7=>GridView::F_SUM,
			                        8=>GridView::F_SUM,
			                        9=>GridView::F_SUM,
			                        //10=>GridView::F_SUM,
			                    ],
			                    'contentFormats'=>[      // content reformatting for each summary cell
			                        //2=>['format'=>'number', 'decimals'=>0],
			                        3=>['format'=>'number', 'decimals'=>0],
			                        4=>['format'=>'number', 'decimals'=>0],
			                        5=>['format'=>'number', 'decimals'=>2],
			                        6=>['format'=>'number', 'decimals'=>2],
			                        7=>['format'=>'number', 'decimals'=>2],
			                        8=>['format'=>'number', 'decimals'=>2],
			                        9=>['format'=>'number', 'decimals'=>2],
			                        //10=>['format'=>'number', 'decimals'=>2],
			                    ],
			                    'contentOptions'=>[      // content html attributes for each summary cell
			                        1=>['style'=>'font-variant:small-caps'],
			                        //2=>['style'=>'text-align:center'],
			                        3=>['style'=>'text-align:center'],
			                        4=>['style'=>'text-align:center'],
			                        5=>['style'=>'text-align:right'],
			                        6=>['style'=>'text-align:right'],
			                        7=>['style'=>'text-align:right'],
			                        8=>['style'=>'text-align:right'],
			                        9=>['style'=>'text-align:right'],
			                        //10=>['style'=>'text-align:right'],
			                    ],
			                    // html attributes for group summary row
			                    'options'=>['class'=>'text-success bg-warning']
			                ];
			            }
			        ],
		            [
		                'attribute'=>'request_datetime',
		                'header' => 'Month',
		                'value'=>function ($model, $key, $index, $widget) {
		                    return strtoupper(Yii::$app->formatter->asDate($model->request_datetime, 'php:M'));
		                },
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-center'],
		                'pageSummary'=>'GRAND TOTAL',
		                'pageSummaryOptions'=>['class'=>'text-left text-primary bg-success'],
		            ],
		            /*[
		                'attribute'=>'request_ref_num',
		                'header' => 'No. of Customers',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-center'],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $countCustomer =  Yii::$app->formatter->format($model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,4,$model->request_type_id),['decimal',0]);
			            	return ($countCustomer > 0) ? $countCustomer : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-center text-primary'],
		            ],*/
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'No. of Requests',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-center'],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $countRequest =  Yii::$app->formatter->format($model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,1,$model->request_type_id),['decimal',0]);
			            	return ($countRequest > 0) ? $countRequest : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-center text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'No. of Samples',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-center'],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $countSample =  Yii::$app->formatter->format($model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,2,$model->request_type_id),['decimal',0]);
			            	return ($countSample > 0) ? $countSample : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-center text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'No. of Tests',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-center'],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $countAnalysis =  Yii::$app->formatter->format($model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,3,$model->request_type_id),['decimal',0]);
			            	return ($countAnalysis > 0) ? $countAnalysis : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-center text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'Income Generated (Actual Fees Collected)',
		                'headerOptions' => ['class' => 'text-center','style'=>'width:15%'],
			            'contentOptions' => ['class' => 'text-right'],
			            'format'=>['decimal', 2],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $totalIncome = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,8,$model->request_type_id);
			            	return ($totalIncome > 0) ? $totalIncome : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-right text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'Gratis',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-right'],
			            'format'=>['decimal', 2],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $totalGratis = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,6,$model->request_type_id);
			            	return ($totalGratis > 0) ? $totalGratis : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-right text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'Discount',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-right'],
			            'format'=>['decimal', 2],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   $totalDiscount = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,7,$model->request_type_id);
			            	return ($totalDiscount > 0) ? $totalDiscount : 0;
		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-right text-primary'],
		            ],
		            [
		                'attribute'=>'request_ref_num',
		                'header' => 'Gross',
		                'headerOptions' => ['class' => 'text-center'],
			            'contentOptions' => ['class' => 'text-right'],
			            'format'=>['decimal', 2],
			            'value'=>function ($model, $key, $index, $widget) use ($labId, $startDate,$endDate) {
		                   //$totalGrossFee = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,5,$model->request_type_id);
			            	//return ($totalGrossFee > 0) ? $totalGrossFee : 0;
			            	$totalIncome = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,8,$model->request_type_id);
			            	$totalDiscount = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,7,$model->request_type_id);
			            	$totalGratis = $model->computeAccomplishment($labId,date('Y-m-d',strtotime($model->request_datetime)),$startDate,$endDate,6,$model->request_type_id);

			            	$totalGrossFee = $totalIncome + $totalDiscount + $totalGratis;
			            	return ($totalGrossFee > 0) ? $totalGrossFee : 0;

		                },
		                'pageSummary'=>true,
        				'pageSummaryFunc'=>GridView::F_SUM,
        				'pageSummaryOptions'=>['class'=>'text-right text-primary'],
					],
					
			    ];

				    echo GridView::widget([
				    	'id' => 'accomplishment-report',
				        'dataProvider'=>$dataProvider,
				        //'filterModel'=>$searchModel,
				        'showPageSummary'=>true,
				        'summary' => false,
				        'pjax'=>true,
		                'pjaxSettings' => [
		                    'options' => [
		                        'enablePushState' => false,
		                    ]
		                ],
		                'responsive'=>true,
				        'striped'=>false,
				        'hover'=>true,
				        'panel' => [
		                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Accomplishment Report</h3>',
		                    'type'=>'primary',
		                    'after'=>false,
		                    //'before'=>$exportMenu,
		                    //'headerOptions' => ['class' => 'text-center'],
		                ],
				        'exportConfig' => [
					    	GridView::PDF => [
				                'filename' => $labCode.'-Accomplishment_Report('.$startDate.'_'.$endDate.')',
				                'alertMsg'        => 'The PDF export file will be generated for download.',
				                'config' => [
				                    'methods' => [
				                        'SetHeader' => [$pdfHeader],
				                        'SetFooter' => [$pdfFooter]
				                    ],
				                    'options' => [
				                        'title' => 'Accomplishment Report',
				                        'subject' => 'Accomplishment_Report',
				                        'keywords' => 'pdf, preceptors, export, other, keywords, here'
				                    ],
				                ]
				            ],
				            GridView::EXCEL => [
				                'label'           => 'Excel',
				                //'icon'            => 'file-excel-o',
				                'methods' => [
				                    'SetHeader' => [$pdfHeader],
				                    'SetFooter' => [$pdfFooter]
				                ],
				                'iconOptions'     => ['class' => 'text-success'],
				                'showHeader'      => TRUE,
				                'showPageSummary' => TRUE,
				                'showFooter'      => TRUE,
				                'showCaption'     => TRUE,
				                'filename'        =>  $labCode.'-Accomplishment_Report('.$startDate.'_'.$endDate.')',
				                'alertMsg'        => 'The EXCEL export file will be generated for download.',
				                'options'         => ['title' => 'Department of Science OneLab'],
				                'mime'            => 'application/vnd.ms-excel',
				                'config'          => [
				                    'worksheet' => 'Accomplishment',
				                    'cssFile'   => ''
				                ]
				            ],
					    ],
				        'columns'=> $gridColumns,
				        'toolbar' => [
				        	[
		                		'content' => Html::button('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['title'=>'Reset Grid', 'onclick'=>'reloadGrid()', 'class' => 'btn btn-default'])
		                	],
		                	'{export}',
		                ],
	                'autoXlFormat'=>true,
	                'export'=>[
	                	'label' => 'Export',
				        'fontAwesome'=>true,
				        'showConfirmAlert'=>false,
				        'target'=>GridView::TARGET_SELF,
				    ],
				    'tableOptions'=>['id'=>'myTable'],
				    ]);
        	?>
        	<?php //\yii\widgets\Pjax::end(); ?>
        	</div>
        

        </div>
</div>
<script type="text/javascript">

    function reloadGrid(){
    	var lab_id = 1;
		var fromdate = <?= "'".date('Y-01-01')."'" ?>;
		var todate = <?= "'".date('Y-m-d')."'" ?>;
		$("#lab_id").val(lab_id).trigger('change');
		$('#request_date_range-start').val(fromdate).trigger('change');
		$('#request_date_range-end').val(todate).trigger('change');
		$('#request_date_range').val(fromdate+' to '+todate);
		$.pjax.reload({container:"#accomplishment-report-pjax",url: '/reports/lab/accomplishment?lab_id='+lab_id+'&from_date='+fromdate+'&to_date='+todate,replace:false,timeout: false});
    }

	$('#btn-filter').on('click',function(event){
	    event.preventDefault();
	    event.stopImmediatePropagation();
	    if($('#lab_id').val() == ''){
			$('#lab-name').addClass('has-error');
			$('.error-lab').html('Please select laboratory.').fadeIn('fast').fadeOut(3000);
		} else if ($('#request_date_range').val() == ''){
			$('#date_range').addClass('has-error');
			$('.error-date').html('Please specify date range.').fadeIn('fast').fadeOut(3000);
		} else {
			$('#lab-name').removeClass('has-error');
			$('#date_range').removeClass('has-error');
			$('.error-lab').html('');
			$('.error-date').html('');

			$.get('/reports/lab/statistic/samples', {
		        data : $('form').serialize(),
			    }, function(response){
		    	<?php if(Yii::$app->session->hasFlash('error')): ?>
	            	echo Yii::$app->session->getFlash('error');
	            <?php else: ?>
	            	var lab_id = $('#lab_id').val();
	            	var fromdate = $('#request_date_range-start').val();
	            	var todate = $('#request_date_range-end').val();
	            	$.pjax.reload({container:"#accomplishment-report-pjax",url: '/reports/lab/accomplishment?lab_id='+lab_id+'&from_date='+fromdate+'&to_date='+todate,replace:false,timeout: false});
	            <?php 
	        		endif;
	            	Yii::$app->session->setFlash('error', null);
		        ?>
		    });
		}
	});
</script>
