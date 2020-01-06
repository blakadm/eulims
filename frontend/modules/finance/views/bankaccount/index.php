<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\Functions;

$func= new Functions();
$this->title = 'Bank Account';
$this->params['breadcrumbs'][] = ['label' => 'Finance', 'url' => ['/finance']];
$this->params['breadcrumbs'][] = 'Bankaccount';
$this->registerJsFile("/js/finance/finance.js");

$Header="Department of Science and Technology<br>";
$Header.="Bank Account";
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\BankaccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                'before'=>Html::button('<span class="glyphicon glyphicon-plus"></span> Create Bank Account', ['value'=>'/finance/bankaccount/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Bank account"),'id'=>'btnBankaccount','onclick'=>'LoadModal(this.title, this.value);']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
        'exportConfig'=>$func->exportConfig("Bank Account", "ba", $Header),
        
        'columns' => [
            'bank_name',
            'account_number',
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Buttontemplate,
                'buttons'=>[
                    'update'=>function ($url,$model) {
						return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/finance/bankaccount/update','id'=>$model->bankaccount_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Update")]);

                    },
                ]
            ],
        ],
       
    ]); ?>
</div>
