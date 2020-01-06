<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Methodreference;
use common\models\lab\Testname;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\TestnamemethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$methodlist= ArrayHelper::map(Methodreference::find()->all(),'method_reference_id','method');
$testnamelist= ArrayHelper::map(Testname::find()->all(),'testname_id','testName');

$this->title = 'Test Name Methods';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="testnamemethod-index">

<?php $this->registerJsFile("/js/services/services.js"); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
              //  'before'=>  Html::button('<span class="glyphicon glyphicon-plus"></span> Create Test Name Method', ['value'=>'/lab/testnamemethod/create', 'class' => 'btn btn-success modal_method','title' => Yii::t('app', "Create New Test Name Method")]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'attribute' => 'testname_id',
            //     'label' => 'Test Name',
            //     'value' => function($model) {

            //         if ($model->testname){
            //             return $model->testname_method_id;
            //         }else{
            //             return "";
            //         }
                    
            // ],
            [
                'attribute' => 'testname_id',
                'label' => 'Test Name',
                'value' => function($model) {

                    if ($model->testname){
                        return $model->testname->testName;
                    }else{
                        return "";
                    }
                    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $testnamelist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Test Name', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'method_id',
                'label' => 'Method',
                'value' => function($model) {
                     if($model->method){
                      return $model->method->method;
                    }else{
                        return "";
                 }    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $methodlist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Method', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            'create_time',
            'update_time',

        //     ['class' => 'kartik\grid\ActionColumn',
        //     'contentOptions' => ['style' => 'width: 8.7%'],
        //    'template' => '{view}{update}{delete}{workflow}',
        //     'buttons'=>[
        //         'view'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/testnamemethod/view','id'=>$model->testname_method_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Test Name Method")]);
        //         },
        //         'update'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/testnamemethod/update','id'=>$model->testname_method_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Test Name Method")]);
        //         },
        //         'delete'=>function ($url, $model) {
        //             $urls = '/lab/testnamemethod/delete?id='.$model->testname_method_id;
        //             return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Test Name Method','data-pjax'=>'0']);
        //         },
        //         // 'workflow'=>function ($url, $model) {
        //         //   $t = '/lab/testnamemethod/createworkflow?test_id='.$model->testname_method_id;
        //         //     return Html::button('<span class="glyphicon glyphicon-plus"></span><span class="glyphicon glyphicon-file"></span>', ['value'=>$t, 'class' => 'btn btn-warning btn-modal','onclick'=>'LoadModal(this.title, this.value, true, 900);','name' => Yii::t('app', "Manage Workflow"),'title' => Yii::t('app', "Create Workflow")]);
        //         // },
        //         ],
        //     ],
          
        ],
    ]); ?>
</div>
