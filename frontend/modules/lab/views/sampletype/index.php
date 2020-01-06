<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Lab;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\SampletypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sample Types';
$this->params['breadcrumbs'][] = $this->title;

$lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
?>
<div class="sampletype-index">

<?php $this->registerJsFile("/js/services/services.js"); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                //'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Sample Type', ['value'=>'/lab/sampletype/create', 'class' => 'btn btn-success modal_services','title' => Yii::t('app', "Create New Sample Type")]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'type',
            [
                'attribute' => 'status_id',
                'label' => 'Status',
                'hAlign'=>'center',
                'format'=>'raw',
                'value' => function($model) {
                    if ($model->status_id==1)
                    {   
                        return "<span class='badge badge-success' style='width:80px!important;height:20px!important;'>Active</span>";
                    }else{
                        return "<span class='badge badge-default' style='width:80px!important;height:20px!important;'>Inactive</span>";
                    }
                    
                },
            ],
        //     ['class' => 'kartik\grid\ActionColumn',
        //     'contentOptions' => ['style' => 'width: 8.7%'],
        //     'template' => '{view}{update}{delete}',
        //     'buttons'=>[
        //         'view'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/sampletype/view','id'=>$model->sampletype_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Sample Type")]);
        //         },
        //         'update'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/sampletype/update','id'=>$model->sampletype_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Sample Type")]);
        //         },
        //         'delete'=>function ($url, $model) {
        //             $urls = '/lab/sampletype/delete?id='.$model->sampletype_id;
        //             return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Sample Type','data-pjax'=>'0']);
        //         },
        //     ],
        // ],
        ],
    ]); ?>
</div>
