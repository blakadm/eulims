<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\DepositType */

$this->title = 'Create Deposit Type';
$this->params['breadcrumbs'][] = ['label' => 'Deposit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
