<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Tagging */

$this->title = 'Update Tagging: ' . $model->tagging_id;
$this->params['breadcrumbs'][] = ['label' => 'Taggings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tagging_id, 'url' => ['view', 'id' => $model->tagging_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tagging-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
