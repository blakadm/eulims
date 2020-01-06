<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\inventory\CostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Costs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cost', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'product_id',
            'lengthofuse',
            'funding_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
