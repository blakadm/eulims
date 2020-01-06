<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Orderofpayment */

$this->title = 'Update Orderofpayment: ' . $model->orderofpayment_id;
$this->params['breadcrumbs'][] = ['label' => 'Orderofpayments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->orderofpayment_id, 'url' => ['view', 'id' => $model->orderofpayment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orderofpayment-update">
    <?php
    $Obj=$model->getCollectionStatus($model->orderofpayment_id);
    /*if($Obj[0]['payment_status'] == 'Paid'){
       echo "<script>
        alert('Cannot update this Order of payment');
        window.location.href='/finance/op';
        </script>";
       exit;
    }
    else{*/
        echo $this->render('_form', [
        'model' => $model,
        'dataProvider'=>$dataProvider,
        'request_model'=>$paymentitem_model,
        'status' => 1,
        'collection_type' => $collection_type,
        'customers' => $customers
          ]);
   // }
    
     ?>

</div>
