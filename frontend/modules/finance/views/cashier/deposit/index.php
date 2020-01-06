<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\finance\DepositType;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\db\Query;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\Deposit */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\components\Functions;

$func= new Functions();
$this->title = 'Deposit';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = 'Deposit';
$Header="Department of Science and Technology<br>";
$Header.="Deposit";
?>
<div class="deposit-index">
     <div class="table-responsive">
    
    <?php 
    $Buttontemplate='{view}'; 
    ?>
      
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
       'responsive'=>true,
        'striped'=>true,
        'showPageSummary' => true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
                'type'=>'primary', 'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Deposit', ['value' => Url::to(['add-deposit']),'title'=>'Create Deposit','onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','id' => 'modalBtn']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                
        ],
        'exportConfig'=>$func->exportConfig("Deposit", "deposit", $Header),
        'columns' => [
            [
               'attribute'=>'deposit_date',
               'filterType'=> GridView::FILTER_DATE_RANGE,
               'value' => function($model) {
                    return date_format(date_create($model->deposit_date),"m/d/Y");
                },
                'filterWidgetOptions' => ([
                     'model'=>$model,
                     'useWithAddon'=>true,
                     'attribute'=>'order_date',
                     'startAttribute'=>'createDateStart',
                     'endAttribute'=>'createDateEnd',
                     'presetDropdown'=>TRUE,
                     'convertFormat'=>TRUE,
                     'pluginOptions'=>[
                         'allowClear' => true,
                        'locale'=>[
                            'format'=>'Y-m-d',
                            'separator'=>' to ',
                        ],
                         'opens'=>'left',
                      ],
                     'pluginEvents'=>[
                        "cancel.daterangepicker" => "function(ev, picker) {
                        picker.element[0].children[1].textContent = '';
                        $(picker.element[0].nextElementSibling).val('').trigger('change');
                        }",
                        
                        'apply.daterangepicker' => 'function(ev, picker) { 
                        var val = picker.startDate.format(picker.locale.format) + picker.locale.separator +
                        picker.endDate.format(picker.locale.format);

                        picker.element[0].children[1].textContent = val;
                        $(picker.element[0].nextElementSibling).val(val);
                        }',
                      ] 
                     
                ]),        
               
            ],
            'start_or',
            [
                'attribute' => 'end_or', 
                'pageSummary' => '<span style="float:right;">Total:</span>',
            ],
                        
            [
                 'attribute' => 'amount',
                 'format' => ['decimal', 2],
                 'pageSummary' => true
            ],      
            [
                'attribute' => 'deposit_type_id',
                'label' => 'Deposit Type',
                'value' => function($model) {
                    return $model->depositType ? $model->depositType->deposit_type : " ";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(DepositType::find()->asArray()->all(), 'deposit_type_id', 'deposit_type'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Deposit Type', 'id' => 'grid-deposit-search-deposit_type_id']
            ],
        ],
    ]); ?>
     
  </div>
</div>
