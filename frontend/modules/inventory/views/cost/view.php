<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\inventory\Fundings;
use common\models\inventory\Products;
use frontend\modules\inventory\components\_class\Depreciatedcost;
/* @var $this yii\web\View */
/* @var $model common\models\inventory\Cost */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Costs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'product_id',
                'label'=>'Product',
                'value'=>function($model){
                    $prod = Products::find()->where(['product_id'=>$model->product_id])->one();
                    if($prod){
                        return $prod->product_name;
                    }else{
                        return "No Product Found. Please report this to admin as BUG";
                    }
                }
            ],
            'lengthofuse',
            [
                'attribute' => 'funding_id',
                'label'=>'Funding Source',
                'value'=>function($model){
                    $fund = Fundings::find()->where(['id'=>$model->funding_id])->one();
                    if($fund){
                        return $fund->name;
                    }else{
                        return "No Funding Source Found. Please report this to admin as BUG";
                    }
                }
            ],
            [
                'label'=>'Original Cost',
                'value'=>function($model){
                    $prod = Products::find()->where(['product_id'=>$model->product_id])->one();
                    
                    if($prod){
                        return $prod->price;
                    }else{
                        return "No cost found";
                    }
                }
            ],
            [
                'label'=>'Depreciated Cost',
                'value'=>function($model){
                    $prod = Products::find()->where(['product_id'=>$model->product_id])->one();
                    
                    if($prod){
                        $dep = new Depreciatedcost;
                        $dep->amount=$prod->price;
                        $dep->lengthofuse=$model->lengthofuse; 
                        $dep->date_received = $model->date_received;
                        return $dep->getDepreciation();
                    }else{
                        return "No cost found";
                    }
                }
            ],

        ],
    ]) ?>

</div>
