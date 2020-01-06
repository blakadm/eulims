<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\inventory\Cost */

$this->title = 'Create Cost';
$this->params['breadcrumbs'][] = ['label' => 'Costs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
