<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\lab\Csf */

$this->title = 'Create Csf';
$this->params['breadcrumbs'][] = ['label' => 'Csfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
