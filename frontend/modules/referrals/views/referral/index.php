<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use \yii\helpers\ArrayHelper;
use common\components\Functions;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use common\components\ReferralComponent;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\ReferralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referral Request';
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['/referrals']];
$this->params['breadcrumbs'][] = $this->title;
$func=new Functions();
$refcomp = new ReferralComponent();

?>
<div class="referral-index">
    <?php
        // echo $func->GenerateStatusLegend("Legend/Status",false);
    ?>

    <h1><?php //echo Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--<?= Html::a('Create Referral', ['create'], ['class' => 'btn btn-success']) ?>-->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'referral-grid',
        //'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Referral</h3>',
            'type'=>'primary',
            'after'=>false,
            //'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Referral Request', ['value' => Url::to(['referral/create']),'title'=>'Create Referral Request', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'referralcreate']),
            //'before'=>"<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/referrals/referral/create\")' class=\"btn btn-success\"><i class=\"glyphicon glyphicon-plus\"></i> Create Referral Request</button>",
            'before'=>null,
        ],
        'columns' => [
            [
                'header' => 'Referral Code',
                'attribute' => 'referral_code',
                'format' => 'raw',
                //'value' => function($data){ return $data->referral_code;},
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Referral Date',
                'attribute' => 'referral_date_time',
                'format' => 'raw',
                'value' => function($data){ return ($data['referral_date_time'] != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($data['referral_date_time'], 'php:F j, Y h:i a') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>";},
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Customer',
                'attribute' => 'customer_id',
                'format' => 'raw',
                'value' => function($data){ return $data['customer_name'];},
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Referred By',
                'attribute' => 'receiving_agency_id',
                'format' => 'raw',
                'value' => function($data) use ($refcomp){
                    //$referred_agency = json_decode($refcomp->listAgency($data['receiving_agency_id']),true);
                    //$receiving_agency = !empty($referred_agency) ? $referred_agency[0]['name'] : null;
                    //return $receiving_agency;
                    return $data['receiving_agency'];
                },
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Referred To',
                'attribute' => 'testing_agency_id',
                'format' => 'raw',
                'value' => function($data) use ($refcomp){
                    //$referred_agency = json_decode($refcomp->listAgency($data['testing_agency_id']),true);
                    //$testing_agency = !empty($referred_agency) ? $referred_agency[0]['name'] : null;
                    //return $testing_agency;
                    return $data['testing_agency'];
                },
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'dropdown' => false,
                'dropdownOptions' => ['class' => 'pull-right'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'buttons' => [
                    'view' => function ($url, $data) {

                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['referral/viewreferral','id'=>$data['referral_id']]),'onclick'=>'window.open(this.value)','class' => 'btn btn-primary','title' => 'View '.$data['referral_code']]);

                        /*$checkActive = $referralcomp->checkActiveLab($model->lab_id,$data['agency_id']);
                        $checkNotify = $referralcomp->checkNotify($model->request_id,$data['agency_id']);
                        $checkConfirm = $referralcomp->checkConfirm($model->request_id,$rstlId,$data['agency_id']);

                        //return $checkConfirm; 
                        //exit;

                        if($model->status_id > 0) {
                            switch ($checkNotify) {
                                case 0:
                                    alert('Not valid request!');
                                    if($checkActive != 1)
                                    {
                                        return 'Lab not active.';
                                    }
                                case 1:
                                    if($checkActive == 1){
                                        return Html::button('<span class="glyphicon glyphicon-bell"></span> Notify', ['value'=>Url::to(['/referrals/referral/notify','request_id'=>$model->request_id,'agency_id'=>$data['agency_id']]),'onclick'=>'sendNotification(this.value,this.title)','class' => 'btn btn-primary','title' => 'Notify '.$data['name']]);
                                    } else {
                                        return '<span class="label label-danger">LAB NOT ACTIVE</span>';
                                    }
                                    break;
                                case 2: 
                                    //return '<span class="text-success">Notice sent.</span>';
                                    return $checkConfirm == 1 ? Html::button('<span class="glyphicon glyphicon-send"></span>&nbsp;&nbsp;Send', ['value'=>Url::to(['/referrals/referral/send','request_id'=>$model->request_id,'agency_id'=>$data['agency_id']]),'onclick'=>'sendReferral(this.value,this.title)','class' => 'btn btn-primary','title' => 'Send Referral '.$data['name']]) : '<span class="text-success">Notice sent.</span>';
                                    break;
                            }
                        } else {
                            return "<span class='label label-danger'>Referral ".$model->status->status."</span>";
                        }*/
                    },
                ],
            ],
        ],
        'toolbar' => [
            'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/referrals/referral'])], [
                        'class' => 'btn btn-default', 
                        'title' => 'Refresh Grid'
                    ]),
        ],
]); ?>

    <?php /*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'referral_id',
            'referral_code',
            'referral_date',
            'referral_time',
            'receiving_agency_id',
            //'testing_agency_id',
            //'lab_id',
            //'sample_received_date',
            //'customer_id',
            //'payment_type_id',
            //'modeofrelease_id',
            //'purpose_id',
            //'discount_id',
            //'discount_amt',
            //'total_fee',
            //'report_due',
            //'conforme',
            //'received_by',
            //'bid',
            //'cancelled',
            //'create_time',
            //'update_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);*/ ?>
</div>