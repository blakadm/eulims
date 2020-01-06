<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Lab;
use common\models\lab\Sampletype;
use common\models\lab\Testcategory;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;


$lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
$sampetypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');
$testcategorylist= ArrayHelper::map(Testcategory::find()->all(),'testcategory_id','category');

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\LabsampletypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lab Sample Type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labsampletype-index">

    <?php $this->registerJsFile("/js/services/services.js"); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'hover'=>true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                //'before'=> Html::button("<span class='glyphicon glyphicon-plus'></span> Create Lab Sample Type", ["value"=>"/lab/labsampletype/create", "class" => "btn btn-success modal_services","title" => Yii::t("app", "Create New Lab Sample Type")]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'lab_id',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'label' => 'Lab',
                'format' => 'raw',
                'width'=>'20%',
                'value' => function($model) {
                   if ($model->lab){
                      return $model->lab->labname;
                   }else{
                        return "";
                   }
                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $lablist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Lab', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'testcategory_id',
                'label' => 'Test Category',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'value' => function($model) {
                    if ($model->testcategory){
                        return $model->testcategory->category;
                    }else{
                        return "";
                    }          
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $testcategorylist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Test Category', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'sampletype_id',
                'label' => 'Sample Type',
                'format' => 'raw',
                'width'=>'20%',
                'value' => function($model) {

                    if ($model->sampletype){
                        return $model->sampletype->type;
                    }else{
                        return "";
                    }        
                },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => $sampetypelist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Sample Type', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            'effective_date',
           // 'added_by',

        //     ['class' => 'kartik\grid\ActionColumn',
        //     'contentOptions' => ['style' => 'width: 8.7%'],
        //     'template' => '{view}{update}{delete}',
        //     'buttons'=>[
        //         'view'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/labsampletype/view','id'=>$model->lab_sampletype_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Lab Sample Type")]);
        //         },
        //         'update'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/labsampletype/update','id'=>$model->lab_sampletype_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Lab Sample Type")]);
        //         },
        //         'delete'=>function ($url, $model) {
        //             $urls = '/lab/labsampletype/delete?id='.$model->lab_sampletype_id;
        //             return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Lab Sample Type','data-pjax'=>'0']);
        //         },
        //     ],
        // ],
        ],
    ]); ?>
</div>
