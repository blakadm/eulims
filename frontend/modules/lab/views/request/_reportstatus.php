
<?php

use common\models\lab\Sample;
use common\models\lab\Testreport;
use common\models\lab\Analysis;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use common\models\lab\Tagging;
use common\models\lab\Tagginganalysis;
use common\models\lab\Workflow;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Request;
use common\models\lab\Procedure;
use common\models\TaggingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use common\models\system\Profile;

?>

<span style="display:inline-block;">



<br>
</span>
<?= GridView::widget([
    'dataProvider' => $testreportdataprovider,
    'summary' => '',
    'panel' => [
        'heading'=>'<h3 class="panel-title"> <i class="glyphicon glyphicon-file"></i>'. $request->request_ref_num.'</h3>',
        'type'=>'primary',
        'items'=>false,
    ],
    'toolbar' => false,
    'columns' => [
        [
            'header'=>'Report #',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
               $testreport = Testreport::find()->where(['request_id' => $model->request_id])->one();

               if ($testreport){
                    return $testreport->report_num;
               }else{
                    return "No Test Report";
               }
              
            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],
        [
            'header'=>'Report Date',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
                $testreport = Testreport::find()->where(['request_id' => $model->request_id])->one();
                
                               if ($testreport){
                                    return $testreport->report_date;
                               }else{
                                    return "";
                               }
            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],
        [
            'header'=>'Date Released',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
               return "";
            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],

],
]); 


?>