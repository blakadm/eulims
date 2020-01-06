<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">
<br>


<!-- <div class="row">

        <div class="col-sm-3">  
                
        </div>

        <div class="col-sm-6"><center>
        <img src='/uploads/dost.svg' style=' width: 160; height: 160px' />
        <img src='/uploads/onelab.png' style=' width: 610px; height: 160px' />
        </center></div>

        <div class="col-sm-3">       
        </div>
</div>
<br> -->

<div class="row">
        <div class="col-sm-4">       
                </div>
                <div class="col-sm-4">
                <div class="panel panel-info">
                            <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>Track My Request</b></div>
                            <div class="panel-body" >
                            <h3 style="color:#142142;font-family:Century Gothic;font-size:130%;text-align:center;, 
                                7px 7px 0px rgba(0, 0, 0, 0.2);"><b>Department of Science and Technology Region IX</b></h3>
                                <h3 style="color:#1a4c8f;font-family:Century Gothic;font-size:150%;text-align:center;, 
                                7px 7px 0px rgba(0, 0, 0, 0.2);"><b>REGIONAL STANDARDS AND TESTING LABORATORY</b></h3>

                            <!-- <b style="color:#142142">Need to track your request? Enter the Request Reference Number and the Code below and we will track it for you. -->
                            </b><br>
                           
                            <?php $form = ActiveForm::begin(); ?>

                                    <?= $form->field($model, 'request_ref_num')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

                                  
                                    <div class="row" style="float: right;padding-right: 15px">
      
                                        <?= Html::submitButton('Track', ['class' => 'btn btn-success']) ?>
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>


                <div class="col-sm-4">
               
                </div>
    

    <?php ActiveForm::end(); ?>

</div>
