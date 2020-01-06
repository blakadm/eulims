<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\lab\Sampletype;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\TestcategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Test Categories';
$this->params['breadcrumbs'][] = $this->title;
$sampletypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');
?>
<div class="testcategory-index">

<?php $this->registerJsFile("/js/services/services.js"); ?>
   
   
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
              //  'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Test Category', ['value'=>'/lab/testcategory/create', 'class' => 'btn btn-success modal_services','title' => Yii::t('app', "Create New Test Category")]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'category',
         

        //           ['class' => 'kartik\grid\ActionColumn',
        //     'contentOptions' => ['style' => 'width: 8.7%'],
        //   //  'template' => $button,
        //   'template' => '{view}{update}{delete}',
        //     'buttons'=>[
        //         'view'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/testcategory/view','id'=>$model->testcategory_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Test Name")]);
        //         },
        //         'update'=>function ($url, $model) {
        //             return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/testcategory/update','id'=>$model->testcategory_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Test Name")]);
        //         },
        //         'delete'=>function ($url, $model) {
        //             $urls = '/lab/testcategory/delete?id='.$model->testcategory_id;
        //             return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Test Name','data-pjax'=>'0']);
        //         },
        //         ],
        //     ],
        ],
    ]); ?>
</div> 