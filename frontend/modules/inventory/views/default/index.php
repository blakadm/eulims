<?php

use yii\helpers\Html;
use kartik\grid\GridView;
$Button="{ok}";
?>
<div class="Lab-default-index">
    <div class="alert alert-warning">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h2><i class="icon fa fa-warning"></i>Reorder Point !</h2>
         Products need to reorder.
    </div>
     <div class="reorder-index">
       <?= GridView::widget([
            'dataProvider' => $dataProvider2,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'product_id',
                 [
                    'header'=>'Product',
                    'value'=>function($model){
                        return $model->product->product_name;
                    }
                ],
                [
                    'header'=>'Reorder Point',
                    'value'=>function($model){
                        return $model->product->qty_reorder." ".$model->product->unittype->unit;
                    }
                ],
                [
                    'header'=>'Total onHand',
                    'value'=>function($model){
                        return $model->product->getTotalqty()." ".$model->product->unittype->unit;
                    }
                ],
                'date_created',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => $Button,
                    'buttons' => [
                        'ok' => function($url, $model){
                             
                             return Html::a('Solve <span class="glyphicon glyphicon-question-sign"></span>',
                                ['default/solve/?id='.$model->reorder_id],
                                [
                                    'data-confirm' => "Have you reordered more?",
                                    'class'=>'pull-left btn btn-success',
                                ]
                            ); 
                        }
                    ],
                    
                ],
                // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <div class="alert alert-warning">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h2><i class="icon fa fa-warning"></i>Expired !</h2>
         Products need to dispose.
    </div>
    <div class="expired-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'inventory_transactions_id',
                [
                    'header'=>'Product',
                    'value'=>function($model){
                        return $model->product->product_name;
                    }
                ],
                'expiration_date',
                'po_number',
                'content',
                'quantity',
                'description',
                [
                    'header'=>'Created_at',
                    'value'=>function($model){
                        return date('Y-m-d',$model->created_at);
                    }
                ],

                // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

</div>
