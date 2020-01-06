<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\inventory\Fundings;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\inventory\Cost */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cost-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'lengthofuse')->textInput()->hint("Item's Life Span") ?>
	<?=Html::a('<span class="glyphicon glyphicon-plus"></span> Add Fundings','/inventory/funding',['class' => 'btn btn-success','title' => Yii::t('app', "Add New Fundings"),'target'=>'_blank']); ?>
    <?=
    $form->field($model, 'funding_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => ArrayHelper::map(Fundings::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Choose Source'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Source of Funds');
    ?>
	
    <?php
     echo "<b>Date Received</b>";
    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'date_received',
        // 'readonly' => true,
        'options' => ['placeholder' => 'Enter Date'],
        'value' => function($model) {
            return date("m/d/Y", $model->date_received);
        },
        'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'format' => 'yyyy-mm-dd'
        ],
    ]);
    ?>
	<br/>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
