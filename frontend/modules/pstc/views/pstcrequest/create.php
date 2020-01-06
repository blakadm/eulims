<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrequest */

$this->title = 'Create Pstcrequest';
$this->params['breadcrumbs'][] = ['label' => 'Pstcrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcrequest-create">

    <?= $this->render('_form', [
        'model' => $model,
        'customers' => $customers,
    ]) ?>

</div>
