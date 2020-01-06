<?php

use common\models\lab\Sample;
use common\models\lab\Testreport;
use common\models\lab\Analysis;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use common\models\finance\Paymentitem;
use common\models\finance\Op;
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



</span>

<?= GridView::widget([
    'dataProvider' => $paymentstatusdataprovider,
    'summary' => '',
    'panel' => [
        'heading'=>'<h3 class="panel-title"> <i class="glyphicon glyphicon-file"></i>'. $request->request_ref_num.'</h3>',
        'type'=>'primary',
        'items'=>false,
    ],
    'toolbar' => false,
    'columns' => [
        [
            'header'=>'O.R. #',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
               $request = Request::find()->where(['request_ref_num' => $model->request_ref_num])->one();
               $paymentitem = Paymentitem::find()->where(['details' => $request->request_ref_num])->one(); 
                if ($paymentitem){
                    $orderofpayment = Op::find()->where(['orderofpayment_id' => $paymentitem->orderofpayment_id])->one();
                    return $orderofpayment->transactionnum;
                }else{
                    return "No payment record";
                }

           // return $request->request_ref_num;
              
              
              
            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],
        [
            'header'=>'Date',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
                $request = Request::find()->where(['request_ref_num' => $model->request_ref_num])->one();
               $paymentitem = Paymentitem::find()->where(['details' => $request->request_ref_num])->one(); 
                
                if ($paymentitem){
                    $orderofpayment = Op::find()->where(['orderofpayment_id' => $paymentitem->orderofpayment_id])->one();
                    $orderofpayment = Op::find()->where(['orderofpayment_id' => $paymentitem->orderofpayment_id])->one();
                    return $orderofpayment->order_date;
                }else{
                    return "";
                }
                             

            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],
        [
            'header'=>'Amount',
            'hAlign'=>'center',
            'format' => 'raw',
            'enableSorting' => false,
            'value'=> function ($model){
                $request = Request::find()->where(['request_ref_num' => $model->request_ref_num])->one();
                $paymentitem = Paymentitem::find()->where(['details' => $request->request_ref_num])->one(); 

                if ($paymentitem){
                    $orderofpayment = Op::find()->where(['orderofpayment_id' => $paymentitem->orderofpayment_id])->one();
                    return $paymentitem->amount;
                }else{
                    return "";
                }
               
            },
            'contentOptions' => ['style' => 'width:30%; white-space: normal;'],                   
        ],

],
]); 


?>