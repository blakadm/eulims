
<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\lab\Sampledisposal;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use common\models\system\Profile;
use yii\helpers\ArrayHelper;

$GLOBALS['user_id']=Yii::$app->user->identity->profile->user_id;
$profile = Profile::find()->where(['user_id'=> $GLOBALS['user_id']])->one();
$analyst= ArrayHelper::map(Profile::find()->where(['lab_id'=>$profile->lab_id])->all(),'user_id','fullname');
$manner= ArrayHelper::map(Sampledisposal::find()->where(['status'=>1])->all(),'disposal_id','disposal');
?>
<?php $form = ActiveForm::begin(); ?> 
                    <?= $form->field($taggingmodel,'user_id')->widget(Select2::classname(),[
                                        'data' => $analyst,
                                        'theme' => Select2::THEME_KRAJEE,
                                        'options' => ['id'=>'tagging-user_id'],
                                        'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Analyst'],
                                ])->label('Analyst')
                        ?>
                    <?= $form->field($taggingmodel, 'analysis_id')->hiddenInput(['maxlength' => true])->label(false) ?>       
                    <?php
                    echo $form->field($taggingmodel, 'start_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Select Date ...',
                    'autocomplete'=>'off'],
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                    ]);
                    ?>
                    <?php
                    echo $form->field($taggingmodel, 'end_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Select Date ...',
                    'autocomplete'=>'off'],
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                    ]);
                    ?>

                <?php
                    echo $form->field($taggingmodel, 'disposed_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Select Date ...',
                    'autocomplete'=>'off'],
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                    ]);
                    ?>
                     <?= $form->field($taggingmodel,'manner')->widget(Select2::classname(),[
                                        'data' => $manner,
                                        'theme' => Select2::THEME_KRAJEE,
                                        'options' => ['id'=>'tagging-manner'],
                                        'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select type of disposal'],
                                ])->label('Manner')
                        ?>
                    <div class="row" style="float: right;padding-right: 15px">
                    <?php  echo Html::button('<i class="glyphicon glyphicon-pencil"></i> Update Analysis', ['disabled'=>false,'value' => Url::to(['tagging/updateana']), 'onclick'=>'updateanalysis()','title'=>'Update Analysis', 'class' => 'btn btn-primary','id' => 'btn_start_analysis']); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
<script type="text/javascript">
   function updateanalysis() {

         jQuery.ajax( {
            type: 'POST',
            url: 'tagging/updateana',
            data: { start_date: $('#tagging-start_date').val(),
             end_date: $('#tagging-end_date').val(), 
             id: $('#tagging-analysis_id').val(),
             user_id: $('#select2-tagging-user_id-container').attr('title'),
             disposed_date: $('#tagging-disposed_date').val(),
             manner: $('#select2-tagging-manner-container').attr('title') },
            success: function (response) {
                 $("#xyz").html(response);
                 $(".modal").modal('hide');
               },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    }

</script>
