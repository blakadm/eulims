<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\finance\DepositType */

$this->title = 'Update Deposit Type: ' . $model->deposit_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Deposit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->deposit_type_id, 'url' => ['view', 'id' => $model->deposit_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deposit-type-update">
    <?= $this->render('_form', [
        'model' => $model,
        'op_model'=> $op_model,
    ]) ?>

</div>
