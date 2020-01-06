<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\lab\Tagging */

$this->title = 'Create Tagging';
$this->params['breadcrumbs'][] = ['label' => 'Taggings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tagging-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
