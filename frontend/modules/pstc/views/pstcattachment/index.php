<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\PstcattachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pstcattachments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcattachment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pstcattachment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pstc_attachment_id',
            'filename',
            'pstc_request_id',
            'uploadedby_user_id',
            'uploadedby_name',
            //'upload_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
