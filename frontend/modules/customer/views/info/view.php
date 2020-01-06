<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use common\components\Functions;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Customer */
$this->title = $model->customer_name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'customer_id',
            // 'rstl_id',
            'customer_code',
            'customer_name',
            'head',
            'tel',
            'fax',
            // 'email:email',
            'Completeaddress'
            // 'latitude',
            // 'longitude',
        ],
    ]) ?>

</div>

<div class="view">
     <?= GridView::widget([
        'dataProvider' => $transactions,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode('Wallet Transactions'),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            [
                'attribute'=>'transactiontype',
                'format'=>'html',
                'value'=>function($data){
                    if($data->transactiontype==0)
                        return "<font class='pull-left' color='green'><i class='fa fa-plus' title='credit'></i> credit</font>";
                    elseif ($data->transactiontype==2) {
                        return "<font class='pull-left' color='green'><i class='fa fa-book' title='initial'></i> initial</font>";
                    }
                    else
                        return "<font class='pull-right' color='red'><i class='fa fa-minus'title='debit'></i> debit</font>";
                },
            ],
            'amount',
            'balance',
            'source'
        ],
    ]); ?>
</div>


<div class="view">
     <?= GridView::widget([
        'dataProvider' => $reqtransactions,
        // 'pjax' => true,
        // 'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-file"></span>  ' . Html::encode('Requests Made'),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'request_ref_num',
            'request_datetime',
            'total'
        ],
    ]); ?>
</div>

<?php if(Yii::$app->request->isAjax){ ?>
    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
<?php } ?>