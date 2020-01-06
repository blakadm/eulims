<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\lab\SampleregisterUser;
use frontend\modules\lab\models\Sampleextend;
use kartik\grid\DataColumn;
use kartik\daterange\DateRangePicker;
use kartik\export\ExportMenu;
use common\models\lab\Lab;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sample Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sample-index">
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
        <?php
            $gridColumns = [
                //['class'=>'kartik\grid\SerialColumn'],
                // [
                //     'label' => 'Request Reference #',
                //     'format' => 'raw',
                //     'value' => function($model, $key, $index, $widget){
                //         return $model->request->request_ref_num;
                //     },
                //     'group'=>true,  // enable grouping
                // ],
                [
                    'label' => 'Sample Code',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        return $model->sample_code;
                    },
                ],
                [
                    'label' => 'Date Received',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        return Yii::$app->formatter->asDate($model->request->request_datetime, 'php:Y-m-d');
                    },
                    'width'=>'100px',
                ],
                [
                    'label' => 'Due Date',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        return Yii::$app->formatter->asDate($model->request->report_due, 'php:Y-m-d');
                    }
                ],
                [
                    'label' => 'Sample',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        return $model->samplename;
                    },
                ],
                [
                    'label' => 'Parameters',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        $parameter =  $model->showParameter($model->sample_id);
                        return $parameter;
                    },
                ],
                [
                    'label' => 'Start Date',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        $start_date = $model->showStartDate($model->sample_id);
                        return $start_date;
                    },
                ],
                [
                    'label' => 'End Date',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        $end_date = $model->showEndDate($model->sample_id);
                        return $end_date;
                    },
                ],
                [
                    'label' => 'Date Sample Disposed',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        $disposed_date = $model->showDisposedDate($model->sample_id);
                        return $disposed_date;
                    },
                ],
                [
                    'label' => 'Manner of Disposal',
                    'format' => 'raw',
                    'value' => function($model, $key, $index, $widget){
                        $manner_disposal = $model->showMannerDisposal($model->sample_id);
                        return $manner_disposal;
                    },
                ],
                [
                    'label' => 'Analyst',
                    'format' => 'raw',
                  
                    'value' => function($model, $key, $index, $widget){
                        $analyst = $model->showAnalyst($model->sample_id);
                        return $analyst;
                    },
                ],
            ]
        ?>
        <?php
            echo GridView::widget([
                'id' => 'sample-register',
                'dataProvider'=>$dataProvider,
                //'filterModel'=>$searchModel,
                'pjax'=>true,
            	'pjaxSettings' => [
            		'options' => [
                        'enablePushState' => false,
                        //'enableReplaceState'=>true,
            		]
            	],
                'striped'=>true,
                'hover'=>true,
                'panel'=>[
                    'type'=>'primary', 
                    'heading'=>'Sample Register',
                ],
                'exportConfig' => [
                    GridView::PDF => [],
                    GridView::EXCEL => [
                        'label' => 'Excel',
                        //'icon' => 'file-excel-o',
                        'iconOptions' => ['class' => 'text-success'],
                        'showHeader' => true,
                        'showPageSummary' => true,
                        'showFooter' => true,
                        'showCaption' => true,
                        'filename' => 'Sample_Register',
                        'alertMsg' => 'The EXCEL export file will be generated for download.',
                        'options' => ['title' => 'Microsoft Excel 95+'],
                        'mime' => 'application/vnd.ms-excel',
                        'config' => [
                            'worksheet' => $this->title,
                            'cssFile' => ''
                        ]
                    ],
                ],
                'columns'=> $gridColumns,
                'toolbar' => [
                    [
                        'content' => Html::button('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['title'=>'Reset Grid', 'onclick'=>'reloadGrid()', 'class' => 'btn btn-default']),
                    ],
                    '{toggleData}',
                    '{export}',
                ],
                'toggleDataOptions' => [
                    'all' => [
                        'icon' => 'resize-full',
                        'label' => 'Show All',
                        'class' => 'btn btn-default',
                        'title' => 'Show all data'
                    ],
                    'page' => [
                        'icon' => 'resize-small',
                        'label' => 'Paginate',
                        'class' => 'btn btn-default',
                        'title' => 'Show first page data'
                    ],
                ],
                'autoXlFormat'=>true,
                'export'=>[
                    'label' => 'Export',
                    'fontAwesome'=>true,
                    'showConfirmAlert'=>false,
                    'target'=>GridView::TARGET_SELF,
                ],
            ]);
        ?>
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
        $.pjax.reload({container:"#sample-register-pjax",url: '/lab/sampleregister?lab_id='+lab_id+'&from_date='+fromdate+'&to_date='+todate,replace:false,timeout: false});
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

            $.get('/lab/sampleregister', {
                data : $('form').serialize(),
                }, function(response){
                <?php if(Yii::$app->session->hasFlash('error')): ?>
                    echo Yii::$app->session->getFlash('error');
                <?php else: ?>
                    var lab_id = $('#lab_id').val();
                    var fromdate = $('#request_date_range-start').val();
                    var todate = $('#request_date_range-end').val();
                    $.pjax.reload({container:"#sample-register-pjax",url: '/lab/sampleregister?lab_id='+lab_id+'&from_date='+fromdate+'&to_date='+todate,replace:false,timeout: false});
                <?php 
                    endif;
                    Yii::$app->session->setFlash('error', null);
                ?>
            });
        }
    });
</script>
