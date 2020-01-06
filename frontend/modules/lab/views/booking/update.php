<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Booking */

$this->title = 'Update Booking: ' . $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->booking_id, 'url' => ['view', 'id' => $model->booking_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="booking-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
