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
use common\models\lab\Csf;


?>

<?php
$js=<<<SCRIPT
SCRIPT;
?>


<?php

$extremely_satisfied = "<img src='/uploads/csf/5-laugh-regular.svg' />";
$satisfied = "<img src='/uploads/csf/4-smile-regular.svg' />";
$neutral = "<img src='/uploads/csf/3-meh-regular.svg' />";
$unsatisfied = "<img src='/uploads/csf/2-frown.svg' />";
$extremely_unsatisfied = "<img src='/uploads/csf/1-angry-regular.svg' />";

$legend_extremely_satisfied = "<img src='/uploads/csf/5-laugh-regular.svg' /><div style='font-size:140%;text-align:center;'><b>(5)<br>Extremely Satisfied</b></div>";
$legend_satisfied = "<img src='/uploads/csf/4-smile-regular.svg' /><div style='font-size:140%;text-align:center;'><b>(4)<br><br>Satisfied</b></div>";
$legend_neutral = "<img src='/uploads/csf/3-meh-regular.svg' /><div style='font-size:140%;text-align:center;'><b>(3)<br><br>Neutral</b></div>";
$legend_unsatisfied = "<img src='/uploads/csf/2-frown.svg' /><div style='font-size:140%;text-align:center;'><b>(2)<br><br>Unsatisfied</b></div>";
$legend_extremely_unsatisfied = "<img src='/uploads/csf/1-angry-regular.svg' /><div style='font-size:140%;text-align:center;'><b>(1)<br>Extremely Unsatisfied</b></div>";

$requestlist= ArrayHelper::map(Businessnature::find()->orderBy(['nature' => SORT_DESC])->all(),'nature','nature');

                    //$btn_style = ';height: 45px;width: 45px; border-radius: 50%;display: inline-block;color:#0f096d;box-shadow: inset 0px 25px 0 rgba(255,255,255,0.3), 0 5px 5px rgba(0, 0, 0, 0.3);';
                    
                    $btn_style = ';width: 64px;height: 64px;background-color: transparent;border: none;border-radius: 50%;background-repeat: no-repeat;background-position: center center;font-size: 11px;padding: 1px;box-shadow: 0 0 3px #000;margin: 0 5px;';
                    
                    $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    ?>
                    
   <div class="csf-form" style="margin: 0 70px;">
   <?php //echo Html::button("<span class='glyphicon glyphicon-refresh'></span> Reset",['value' => '/lab/csf/index','onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]); ?>
                     <br>
                     <br>
                   
    <?php $form = ActiveForm::begin(); ?>         
    <div class="row">
        <div class="col-sm-6">
              
               
                <div class="panel panel-info">
                <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>Information</b></div>
                    <div class="panel-body">

                    <?= $form->field($model, 'ref_num')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model,'nob')->widget(Select2::classname(),[
                        'data' => $requestlist,
                        'id'=>'name',
                        'theme' => Select2::THEME_KRAJEE,
                        'options' => ['id'=>'sample-type_id'],
                        'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Nature of Business'],
                        
                        ])
                        ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                      
                        <?php echo $form->field($model, 'tom')->radioList(
                            ArrayHelper::map(Markettype::find()->all(),'id','type'),
                            ['itemOptions' => ['onchange'=>$js]]
                        ); ?>
                        <?php echo $form->field($model, 'service')->radioList(
                                                    ArrayHelper::map(Lab::find()->where(['active'=> 1])->all(),'lab_id','labname'),
                                                    ['itemOptions' => ['onchange'=>$js]]
                        ); ?>
                      
                    </div>
                </div>

                <div class="panel panel-info">
                <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>Legends</b></div>
                    <div class="panel-body">
                    <?php echo Html::button($legend_extremely_satisfied, [ 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($legend_satisfied, [ 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                 
                    <?php echo Html::button($legend_neutral, [  'style'=>'background-color: #F5DEB3 !important'.$btn_style]).$space ?>
                    <?php echo Html::button($legend_unsatisfied, ['style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($legend_extremely_unsatisfied, ['style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>           
                   
                       
                      
                    </div>
                </div>

                    <div class="panel panel-info">
                    <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>Delivery of Service</b></div>
                    <div class="panel-body">
                 
                    <?= $form->field($model, 'd_deliverytime')->hiddenInput()->label("Delivery Time") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_deliverytime', 'value'=>'5', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_deliverytime', 'value'=>'4', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                 
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class' => 'd_deliverytime', 'value'=>'3', 'style'=>'background-color: #F5DEB3 !important'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_deliverytime', 'value'=>'2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_deliverytime','value'=>'1', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>           
                    <br>
                    <br>     
                    <?= $form->field($model, 'd_accuracy')->hiddenInput()->label("Correctness and accuracy of test results") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_accuracy', 'value'=>'5', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_accuracy', 'value'=>'4', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class' => 'd_accuracy', 'value'=>'3', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_accuracy', 'value'=>'2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class' => 'd_accuracy','value'=>'1', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>   
                    <br>
                    <br>
                    <?= $form->field($model, 'd_speed')->hiddenInput()->label("Speed of Service") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_speed', 'value'=>'5', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_speed', 'value'=>'4', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'d_speed', 'value'=>'3', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_speed', 'value'=>'2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)','class'=>'d_speed', 'value'=>'1', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>        
                   <br>
                    <br>
                     <?= $form->field($model, 'd_cost')->hiddenInput()->label("Cost") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_cost', 'value'=> '5', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_cost', 'value'=> '4', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'d_cost', 'value'=> '3', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_cost', 'value'=> '2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_cost', 'value'=> '1', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                
                    <br>
                    <br>
                    <?= $form->field($model, 'd_attitude')->hiddenInput()->label("Attitude of staff") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_attitude', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_attitude', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'d_attitude', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_attitude', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_attitude', 'value'=>'1', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>          
                   <br>
                    <br>  <?= $form->field($model, 'd_overall')->hiddenInput()->label("Over-all customer experience") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_overall', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_overall', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'d_overall', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_overall', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'d_overall', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    </div>
                </div>
            </div>
            <div class="row">
        <div class="col-sm-6">
        <div class="panel panel-info">
        <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>How <font color="red">Important</font> are these items to you?</b></div>
                <div class="panel-body">               
                <?= $form->field($model, 'i_deliverytime')->hiddenInput()->label("Delivery Time") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_deliverytime', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>   
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_deliverytime', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_deliverytime', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_deliverytime',  'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_deliverytime', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>  
                    <br>
                    <br>
                    <?= $form->field($model, 'i_accuracy')->hiddenInput()->label("Correctness and accuracy of test results") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_accuracy', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>  
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_accuracy', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>    
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_accuracy', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_accuracy', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>             
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_accuracy', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>        
                    <br>
                    <br>
                    <?= $form->field($model, 'i_speed')->hiddenInput()->label("Speed of Service") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_speed', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_speed', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_speed', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?> 
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_speed', 'value'=>'2','style'=>'background-color: #F5DEB3 !important'.$btn_style]).$space ?> 
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_speed', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>          
                    <br>
                    <br>
                    <?= $form->field($model, 'i_cost')->hiddenInput()->label("Cost") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_cost', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>         
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_cost', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>        
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_cost', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button( $unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_cost', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_cost', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                  
                    <br>
                    <br>
                    <?= $form->field($model, 'i_attitude')->hiddenInput()->label("Attitude of Staff") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_attitude', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>             
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_attitude', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_attitude', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_attitude', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_attitude', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                   
                    <br>
                    <br>
                    <?= $form->field($model, 'i_overall')->hiddenInput()->label("Over-all customer experience") ?>
                    <?php echo Html::button($extremely_satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_overall', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($satisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_overall', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>
                    <?php echo Html::button($neutral, ['onclick'=>'changeColor(this)', 'class'=>'i_overall', 'value'=>'3','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>                  
                    <?php echo Html::button($unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_overall', 'value'=>'2','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>     
                    <?php echo Html::button($extremely_unsatisfied, ['onclick'=>'changeColor(this)', 'class'=>'i_overall', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style]).$space ?>          
                                     
                    <br>
                    <br>
                </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>How likely is it that you would <font color="red">recommend</font> our service to others? 1-10</b></div>

                <div class="panel-body">
                    <?= $form->field($model, 'recommend')->hiddenInput()->label("Recommend")->label(false) ?>
                    <?php echo Html::button('<b><font size="4">10</b>', ['onclick'=>'changeColor_rec(this)','class'=>'recommend', 'value'=>'10','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">9</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'9','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">8</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'8','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">7</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'7','value' => '2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">6</b>', ['onclick'=>'changeColor_rec(this)',' class'=>'recommend', 'value'=>'6','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">5</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'5','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">4</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'4','style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>
                    <?php echo Html::button('<b><font size="4">3</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'2','value' => '2', 'style'=>'background-color: #F5DEB3 !important;'.$btn_style]) ?>     
                    <?php echo Html::button('<b><font size="4">2</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style])?>    
                    <?php echo Html::button('<b><font size="4">1</b>', ['onclick'=>'changeColor_rec(this)', 'class'=>'recommend', 'value'=>'1','style'=>'background-color: #F5DEB3 !important;'.$btn_style])?>            
                 </div>     
        </div>
        <div class="panel panel-info">
        <div class="panel-heading" style="color:#142142;font-family:Century Gothic;font-size:130%;"><b>Please give us your comments/ suggestions to improve our services. Also, let us know other test you require that we are not able to provide yet.</b></div>
            <div class="panel-body">
                 <?= $form->field($model, 'essay')->textArea(['rows' => '3'])->label(false) ?>
            </div>  
        </div>    
       
            <?php $form->field($model, 'r_date')->hiddenInput()->label(false) ?>
        </div>
        </div>
        <div class="row" style="float: right;padding-right: 30px">
      

         <?php echo Html::submitButton('Submit Feedback', ['class' => 'btn btn-primary']) ?>
        <?php if($model->isNewRecord){ ?>
        <?php } ?>
    <?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
    function changeColor (btn, txtBoxId) {
        document.querySelectorAll('.' + btn.className).forEach(function (btn) {
            btn.style.backgroundColor = '#bbb';
        })
        let colors = ['#DC143C', '#FFA07A', '#F5DEB3', '#98FB98', '#3CB371' ]
        btn.style.backgroundColor = colors[btn.value - 1];
        document.getElementById('csf-' + btn.className).value = btn.value;
    }
    function changeColor_rec (btn, txtBoxId) {
        document.querySelectorAll('.' + btn.className).forEach(function (btn) {
            btn.style.backgroundColor = '#bbb';
        })
        let colors = ['#DC143C', '#CD5C5C', '#F08080', '#E9967A', '#FFA07A', '#FFEFD5', '#98FB98','#32CD32','#008000','#006400' ]
        btn.style.backgroundColor = colors[btn.value - 1];
        document.getElementById('csf-' + btn.className).value = btn.value;
    }
</script>
<?php ini_set("memory_limit", "10000M");?>