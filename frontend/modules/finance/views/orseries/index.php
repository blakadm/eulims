<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\finance\Orcategory;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\OrseriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


use common\components\Functions;

$func= new Functions();
$this->title = 'OR Series';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = 'OR Series';

$Header="Department of Science and Technology<br>";
$Header.="O.R Series";
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
                'before'=>Html::button('<span class="glyphicon glyphicon-plus"></span> Create O.R Series', ['value'=>'/finance/orseries/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New O.R Series"),'id'=>'btnOrseries','onclick'=>'LoadModal(this.title, this.value);']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
        'exportConfig'=>$func->exportConfig("Orseries", "orseries", $Header),
        
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'or_category_id',
                    'label' => 'Category',
                    'value' => function($model) {
                        return $model->orcategory->category;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Orcategory::find()->asArray()->all(), 'or_category_id', 'category'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Collection Type', 'id' => 'grid-op-search-category']
                ],
                'or_series_name',
                'startor',
                'nextor',
                'endor',           
                [
                    'class' => kartik\grid\ActionColumn::className(),
                    'template' =>$Buttontemplate,
                    'buttons'=>[
					'delete'=>function ($url,$model) {
						 if ($model->nextor > ($model->startor + 1)){
							return;
						 }
						 else{
							$urls = '/finance/orseries/delete?id='.$model->or_series_id;
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete','data-pjax'=>'0']);
						}
                    },
                    'update'=>function ($url,$model) {
						 //if ($model->nextor > $model->endor || $model->nextor > ($model->startor + 1)){
						 if ($model->nextor > $model->endor){	 
							return;
						 }
						 else{
							return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/finance/orseries/update','id'=>$model->or_series_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Update <font color='Blue'></font>")]);
						}
                    },
                    
                ],
                ],
            ],
                       
    ]); ?>
</div>