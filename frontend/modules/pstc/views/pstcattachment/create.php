<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcattachment */

$this->title = 'Create Pstcattachment';
$this->params['breadcrumbs'][] = ['label' => 'Pstcattachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcattachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
