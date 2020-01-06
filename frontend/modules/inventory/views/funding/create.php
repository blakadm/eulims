<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\inventory\Fundings */

$this->title = 'Create Fundings';
$this->params['breadcrumbs'][] = ['label' => 'Fundings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fundings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
