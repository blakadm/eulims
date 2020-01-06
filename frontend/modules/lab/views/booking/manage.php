<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\Functions;
use common\models\lab\Customer;

$func= new Functions();
$this->title = 'Booking';
$this->params['breadcrumbs'][] = ['label' => 'Calendar', 'url' => ['/lab/booking']];
$this->params['breadcrumbs'][] = 'Manage Booking';
$this->registerJsFile("/js/finance/finance.js");
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\BankaccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="table-responsive">
    <?php 
    $Buttontemplate='{update}{delete}'; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'before'=>Html::button('<span class="glyphicon glyphicon-plus"></span> Create Booking', ['value'=>'/lab/booking/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Bank account"),'id'=>'btnBankaccount','onclick'=>'LoadModal(this.title, this.value);']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
      
        
        'columns' => [
            [
                'attribute' => 'customer_id',
                'label' => 'Customer Name',
                'value' => function($model) {
                    if($model->customer){
                        return $model->customer->customer_name;
                    }else{
                        return "";
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Customer::find()->asArray()->all(), 'customer_id', 'customer_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Customer Name', 'id' => 'grid-op-search-customer_id']
            ],
            'description',
            'qty_sample',
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Buttontemplate,
                'buttons'=>[
                    'update'=>function ($url,$model) {
						return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/booking/update','id'=>$model->booking_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Update")]);

                    },
                ]
            ],
        ],
       
    ]); ?>
</div>
