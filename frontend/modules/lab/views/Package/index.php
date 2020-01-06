<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Lab;
use common\models\lab\Sampletype;
use common\models\lab\Testnamemethod;
use common\models\lab\Testcategory;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\PackageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sampetypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');
$testcategorylist= ArrayHelper::map(Testcategory::find()->all(),'testcategory_id','category');
$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="package-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  
    <?php $this->registerJsFile("/js/services/services.js"); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                'before'=> Html::button("<span class='glyphicon glyphicon-plus'></span> Create Package", ["value"=>"/lab/package/create", "class" => "btn btn-success modal_method","title" => Yii::t("app", "Create Package")]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],  
            [
                'attribute' => 'testcategory_id',
                'label' => 'Test Category',
                'width'=>'20%',
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
                'contentOptions' => ['style' => 'max-width:100px; white-space: normal;'],
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
            [
                'attribute' => 'name',
                'label' => 'Name',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:5%; white-space: normal;'],
                'value' => function($model) {    
                        return $model->name;
                            },
                'filterInputOptions' => ['placeholder' => 'Sample Type', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'rate',
                'label' => 'Rate',
                'format' => 'raw',
                'value' => function($model) {    
                        return number_format($model->rate, 2);
                            },
             
            ],
            // [
            //     'attribute' => 'tests',
            //     'label' => 'Tests',
            //     'format' => 'raw',
            //    // 'width'=>'20%',
            //     'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
            //     'value' => function($model) {

                        // if ($model->tests){
                        //     $tet = $model->tests;  
                        //     $sql = "SELECT GROUP_CONCAT(testname_id) FROM tbl_testname_method WHERE testname_method_id IN ($tet)";     
                 
                        //     $Connection = Yii::$app->labdb;
                        //     $command = $Connection->createCommand($sql);
                        //     $row = $command->queryOne();    
                        //     $tests = $row['GROUP_CONCAT(testname_id)'];  
    
                        //     $space = explode(',', $tests);
                        //     $d = '';
                        //     $newline = ", ";
                        //     foreach ($space as $s){
                        //         $d.= $s.$newline;
                        //     }

                        //     $len = strlen($d);

                        //     $x = $len-2;

                        //      $testname_id = substr($d ,0,$x);
                        //     $sql_testname = "SELECT GROUP_CONCAT(testName) FROM tbl_testname WHERE testname_id IN ($testname_id)";     
                 
                        //     $Connection = Yii::$app->labdb;
                        //     $command_testname = $Connection->createCommand($sql_testname);
                        //     $row_testname = $command_testname->queryOne();    
                        //     $testname = $row_testname['GROUP_CONCAT(testName)'];  

                        //     return $testname;
                            
                            
                        // }else{
                         //   return "";
                      //  }
                      

                        
            //             },
            //    'filterInputOptions' => ['placeholder' => 'Sample Type', 'testcategory_id' => 'grid-products-search-category_type_id']
         //   ],
            ['class' => 'kartik\grid\ActionColumn',
          //  'contentOptions' => ['style' => 'width: 8.7%'],
            'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/package/view','id'=>$model->id]), 'onclick'=>'LoadModal(this.title, this.value, true, 500);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Packages")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/lab/package/update','id'=>$model->id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Packages")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/lab/package/delete?id='.$model->id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Packages','data-pjax'=>'0']);
                },
            ],
        ],
        ],
    ]); ?>
</div>
