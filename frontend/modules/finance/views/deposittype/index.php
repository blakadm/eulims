<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\DepositTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deposit Types';
$this->params['breadcrumbs'][] = $this->title;
$Buttontemplate='{update}{delete}'; 
?>
<div class="deposit-type-index">
<div class="table-responsive">

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
            'type'=>'primary', 'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Deposit Type', ['value' => Url::to(['/finance/deposittype/create']),'title'=>'Create Deposit Type', 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','id' => 'modalBtn']),
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'deposit_type',
            
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Buttontemplate,
                'buttons'=>[
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/finance/deposittype/update','id'=>$model->deposit_type_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Update ")]);
                },
                ]
            ],
        ],
    ]); ?>
     
</div>
</div>
