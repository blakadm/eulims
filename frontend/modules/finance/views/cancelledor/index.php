<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\CancelledOrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cancelled Ors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cancelled-or-index">
<div class="panel panel-default col-xs-12">
        <div class="panel-heading"><i class="fa fa-adn"></i> </div>
        <div class="panel-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cancelled Or', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cancelled_or_id',
            'receipt_id',
            'reason',
            'cancel_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
        </div>
</div>
</div>
