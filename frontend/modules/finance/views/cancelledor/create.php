<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\finance\CancelledOr */

$this->title = 'Create Cancelled Or';
$this->params['breadcrumbs'][] = ['label' => 'Cancelled Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cancelled-or-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
