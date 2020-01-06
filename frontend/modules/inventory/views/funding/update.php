<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\inventory\Fundings */

$this->title = 'Update Fundings: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fundings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fundings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
