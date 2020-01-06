<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\inventory\FundingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fundings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fundings-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'before'=>Html::button('<span class="glyphicon glyphicon-plus"></span> Create Fundings', ['value'=>'/inventory/funding/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Funding"),'id'=>'btnSupplier','onclick'=>'addFunding(this.value,this.title)']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
         ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'desc',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<script type="text/javascript">
    function addFunding(url,title){
        LoadModal(title,url,'true');
    }
  
</script>
