<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\lab\Request;
use common\models\lab\Businessnature;
use common\models\lab\Lab;
use common\models\lab\Markettype;
use common\models\lab\Paymenttype;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\CsfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>


<div class="row">
<div class="col-sm-1">
<img src='/uploads/dost.svg' style=' width: 90; height: 120px;margin-left:60px;margin-top:20px' /> 
</div>

<div class="col-sm-11" >
<h3 style="color:#1a4c8f;font-family:verdana;font-size:150%;, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department of Science and Technology</b></h3>
<h3 style="color:#142142;font-family:verdana;font-size:150%;, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REGIONAL STANDARDS AND TESTING LABORATORY</b></h3>

<h1 style="color:#1a4c8f;font-family:Century Gothic;font-size:250%;, 
      7px 7px 0px rgba(0, 0, 0, 0.2);"><b>&nbsp;&nbsp;&nbsp;&nbsp;Customer Satisfaction Feedback Survey</b></h1><br>
</div>
</div>


   


                
              
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>   
            