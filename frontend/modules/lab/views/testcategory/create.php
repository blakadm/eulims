<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\lab\Testcategory */


?>
<div class="testcategory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div> 