<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\finance\Collectiontype;
use common\models\finance\Paymentmode;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\db\Query;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\Op */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\components\Functions;

$func= new Functions();
$this->title = 'Receipt';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = 'Receipt';
$Header="Department of Science and Technology<br>";
$Header.="Receipt";
?>
<div class="orderofpayment-index">
    
  <div class="table-responsive">
    <?php 
    $Buttontemplate='{view}{update}'; 
    ?>
      
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'responsive'=>false,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Receipt', ['value' => Url::to(['/finance/cashier/addreceipt']),'title'=>'Create Receipt', 'onclick'=>'addReceipt(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']),
				
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                
        ],
        'exportConfig'=>$func->exportConfig("Receipt", "receipt", $Header),
        'columns' => [
            'or_number',
            [
               'attribute'=>'receiptDate',
               'filterType'=> GridView::FILTER_DATE_RANGE,
               'value' => function($model) {
                    return date_format(date_create($model->receiptDate),"m/d/Y");
                },
                'filterWidgetOptions' => ([
                     'model'=>$model,
                     'useWithAddon'=>true,
                     'attribute'=>'receiptDate',
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
            'payor',
            [
                 'attribute' => 'total',
                 'format' => ['decimal', 2],
                 'pageSummary' => true
            ], 
            [
                'attribute' => 'payment_mode_id',
                'label' => 'Payment Mode',
                'value' => function($model) {
                    return $model->paymentMode->payment_mode;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Paymentmode::find()->asArray()->all(), 'payment_mode_id', 'payment_mode'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Payment Mode', 'id' => 'grid-op-search-payment_mode_id']
            ],
            [
                'attribute' => 'collectiontype_id',
                'label' => 'Collection Type',
                'value' => function($model) {
                    return $model->collectiontype ? $model->collectiontype->natureofcollection : "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Collectiontype::find()->asArray()->all(), 'collectiontype_id', 'natureofcollection'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Collection Type', 'id' => 'grid-op-search-collectiontype_id']
            ],
            [
                'attribute' => 'cancelled',
                'value' => function($model) {
                    return $model->cancelled == 0 ? "No" : "Yes";
                },
                'filter' => ['0'=>'No', '1'=>'Yes'],  
            ],
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Buttontemplate,
                 'buttons'=>[
//                    'view'=>function ($url, $model) {
//                          return Html::a('View', ['/finance/cashier/view-receipt?receiptid='.$model->receipt_id], ['target'=>'_blank','class'=>'btn btn-primary']);
//                    },
                    'view' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/cashier/viewreceipt?receiptid='.$model->receipt_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Receipt")]);
                    },   
				    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => '/finance/cashier/update?id='.$model->receipt_id,'onclick' => 'LoadModal(this.title, this.value);', 'class' => 'btn btn-success
                        ', 'title' => Yii::t('app', "Update Receipt")]);
                    },   
                  ],
            ],

        ],
    ]); ?>
     
  </div>
</div>

 <script type="text/javascript">
   
    function addReceipt(url,title){
       //var url = 'Url::to(['sample/update']) . "?id=' + id;
       //var url = '/lab/sample/update?id='+id;
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
 </script>