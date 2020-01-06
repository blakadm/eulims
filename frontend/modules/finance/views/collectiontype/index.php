<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\CollectiontypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Collection Type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectiontype-index">

    <div class="table-responsive">
        <?php 
        $Buttontemplate='{update}{delete}'; 
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'pjaxSettings' => [
                'options' => [
                    'enablePushState' => false,
                ]
            ],
           
            'panel' => [
                'type'=>'primary', 'before'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Create Collection Type', ['value' => Url::to(['/finance/collectiontype/create']),'title'=>'Create Collection Type', 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','id' => 'modalBtn']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'natureofcollection',
                [
                    'class' => kartik\grid\ActionColumn::className(),
                    'template' => $Buttontemplate,
                    'buttons'=>[
                    'update'=>function ($url, $model) {
						return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/finance/collectiontype/update','id'=>$model->collectiontype_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "Update ")]);
					},
                    
                ],
                ],
            ],
        ]); ?>
    </div>
</div>
