<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Orderofpayment */

//$this->title = 'Create Orderofpayment';
$this->params['breadcrumbs'][] = ['label' => 'Order of payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orderofpayment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'status' => 0,
        'collection_type' => $collection_type,
        'customers' => $customers
    ]) ?>

</div>
