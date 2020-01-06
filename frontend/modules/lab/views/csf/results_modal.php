
<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\lab\Csf;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Csf */


?>
<div class="csf-view">

<?php
 $csf = Csf::find()->where(['id'=>$model->id])->one();

 $delivery = $csf->d_deliverytime + $csf->d_accuracy + $csf->d_speed + $csf->d_cost + $csf->d_attitude + $csf->d_overall;

 $d = $delivery / 6;

 $importance = $csf->i_deliverytime + $csf->i_accuracy + $csf->i_speed + $csf->i_cost + $csf->i_attitude + $csf->i_overall;
 $i = $importance / 6;
?>

<div class="row" style="float: right;padding-right: 30px">
<?php echo Html::button("<span class='glyphicon glyphicon-print'></span> Print CSF",['value' => '/lab/csf/printcustomer','onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View CSF")]); ?>


</div>
    <h1><?= Html::encode($this->title) ?></h1>

   
    
    <div class="panel panel-info">
                            <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:100%;"><b>Customer Satisfaction Feedback Results</b></div>
                            <div class="panel-body">
                           
       <h1 style="color:black;font-family:Century Gothic;text-align:center;font-size:200%;text-shadow: 
      4px 4px 0px #d5d5d5, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b><?php echo $model->name?></b></h1>

                    
                                      <h4 style="text-align:center;">SUMMARY</h4><br>Delivery of Service: <?php echo round($d) ?>/5<br>Importance: <?php echo round($i) ?>/5<br>Recommendations: <?php echo $csf->recommend?>/10

                                    


                        
                        </div>
                </div>
              

        <?php
        
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> Information',
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'attributes'=>[
                [
                    'columns' => [
                        [
                            'label'=>'Request Reference Number',
                            'value'=>$model->ref_num,
                            'displayOnly'=>true,
                         
                        ],
                      
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Date',
                            'value'=>$model->r_date,
                            'displayOnly'=>true,
                         
                        ],
                      
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Customer Name',
                            'format'=>'raw',
                            'value'=>$model->name,
                            'displayOnly'=>true
                        ],
                    ],
                    
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Nature of Business',
                            'value'=>$model->nob,
                            'displayOnly'=>true,
                        ],
                      
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Type of Market',
                            'value'=> $model->markettype ? $model->markettype->type : "",
                            'displayOnly'=>true,
                        ],
                      
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'What services of the RSTL have you availed?',
                            'format'=>'raw',
                            'value'=> $model->lab ? $model->lab->labname : "",
                            'displayOnly'=>true
                        ],
                    ],
                ],
            ],


        ]);
        ?>

<?php
        
        echo DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> Delivery of Service',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            [
                'columns' => [
                    [
                        'label'=>'Delivery Time',
                        'value'=>$model->d_deliverytime."/5",
                        'displayOnly'=>true,
                     
                    ],
                    [
                        'label'=>'Correctness and accuracy of test results',
                        'format'=>'raw',
                        'value'=>$model->d_accuracy."/5",
                        'displayOnly'=>true
                    ],
                  
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Speed of service',
                        'value'=>$model->d_speed."/5",
                        'displayOnly'=>true,
                    ],
                    [
                        'label'=>'Cost',
                        'value'=>$model->d_cost."/5",
                        'displayOnly'=>true,
                    ],
                  
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Attitude of Staff',
                        'value'=>$model->d_attitude."/5",
                        'displayOnly'=>true,
                    ],
                    [
                        'label'=>'Over-all customer experience',
                        'value'=>$model->d_overall."/5",
                        'displayOnly'=>true,
                    ],
                  
                ],
            ],
          
        ],


    ]);
    ?>

<?php
        
        echo DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> How important are these items to you?',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            [
                'columns' => [
                    [
                        'label'=>'Delivery Time',
                        'value'=>$model->i_deliverytime."/5",
                        'displayOnly'=>true,
                     
                    ],
                    [
                        'label'=>'Correctness and accuracy of test results',
                        'format'=>'raw',
                        'value'=>$model->i_accuracy."/5",
                        'displayOnly'=>true
                    ],
                  
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Speed of service',
                        'value'=>$model->i_speed."/5",
                        'displayOnly'=>true,
                    ],
                    [
                        'label'=>'Cost',
                        'value'=>$model->i_cost."/5",
                        'displayOnly'=>true,
                    ],
                  
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Attitude of Staff',
                        'value'=>$model->i_attitude."/5",
                        'displayOnly'=>true,
                    ],
                    [
                        'label'=>'Over-all customer experience',
                        'value'=>$model->i_overall."/5",
                        'displayOnly'=>true,
                    ],
                  
                ],
            ],
          
        ],


    ]);
    ?>

<?php
        
        echo DetailView::widget([
        'model'=>$model,
        'responsive'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-info-sign"></i> Recommendations',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            [
                'columns' => [
                    [
                        'label'=>'How likely is it that you would recommend our service to others',
                        'value'=>$model->recommend."/10",
                        'displayOnly'=>true,
                     
                    ],
                  
                ],
            ],
            [
                'columns' => [
                    [
                        'label'=>'Comments and Suggestions',
                        'format'=>'raw',
                        'value'=>$model->essay,
                        'displayOnly'=>true
                    ],
                ],
                
            ],
        ],


    ]);
    ?>


