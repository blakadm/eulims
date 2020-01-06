
<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\lab\Markettype;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\lab\Csf */


?>
<div class="csf-view">

    <h1><?= Html::encode($this->title) ?></h1>

   

    <div class="panel panel-info">
                            <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:200%;"><b>Customer Satisfaction Feedback</b></div>
                            <div class="panel-body">
                            <h1 style="color:#1a4c8f;font-family:Century Gothic;text-align:center;font-size:1000%;text-shadow: 
      4px 4px 0px #d5d5d5, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b>Thank you!</b></h1>
       <h1 style="color:black;font-family:Century Gothic;text-align:center;font-size:200%;text-shadow: 
      4px 4px 0px #d5d5d5, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b><?php echo $model->name?></b></h1>

                    
                                    
                                      <center><h1>We really appreciate the time you took to answer the survey</h1></center><br></h4>

                                    


                        
                        </div>
                </div>

       

</div>
<?php 
echo Html::button("<span class='glyphicon glyphicon-plus'></span> Submit New Customer Satisfaction Index",['value' => '/lab/csf/index','onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);

?>