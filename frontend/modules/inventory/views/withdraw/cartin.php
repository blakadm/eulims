<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\inventory\InventoryWithdrawaldetails;
?>
	<?= "<h2>Product Code: ".$product->product_code." - Unit: ".$product->unittype->unit." </h2>"?>
	<?php $form = ActiveForm::begin(); ?>
	<div class="view">
	     <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'columns' => [
	            ['class' => 'yii\grid\SerialColumn'],
	            'expiration_date',
	            'suppliers.suppliers',
	            'po_number',
	            'description',
	            [
	            	'header'=>'Content',
	            	'value'=>function($model){
	            		return $model->content." ".$model->product->unittype->unit;
	            	}
	            ],
	            // 'quantity',
	            'amount',
	             [
				    'header' => 'Onhand',
				    'value' => function($model){
				    	$withdrawn = InventoryWithdrawaldetails::find()->where(['inventory_transactions_id'=>$model->inventory_transactions_id])->sum('quantity');

				        return $model->quantity-$withdrawn;
				    },
				    'format' => 'raw'
				],
	            [
				    'header' => 'Order',
				    'value' => function($model){
				    	$withdrawn = InventoryWithdrawaldetails::find()->where(['inventory_transactions_id'=>$model->inventory_transactions_id])->sum('quantity');
				    	$total = $model->quantity-$withdrawn;
				        return Html::textInput($model->inventory_transactions_id."_name",0,['id'=>$model->inventory_transactions_id.'_id','type'=>'number','min'=>0,'max'=>$total,'onkeypress'=>"return false;", 'onchange'=>"setcontent(".$model->inventory_transactions_id.",".$model->content.",this)"]);
				    },
				    'format' => 'raw'
				],
				[
				    'header' => 'Total ( '.$product->unittype->unit.')',
				    'value' => function($model){
				    	return "<i id='tc-$model->inventory_transactions_id'>0</i>";
				    },
				    'format' => 'raw'
				],
	        ],
	    ]); ?>
	</div>

	<?php if(Yii::$app->request->isAjax){ ?>
	    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
	<?php } ?>
	 <?= Html::submitButton('Add to Cart', ['class' => 'btn btn-success pull-right']) ?>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
	function setcontent(id,content,txtinput){
		qty = txtinput.value;
		amount=parseFloat(content) * parseInt(qty);
		$('#tc-'+id).html(amount);
	}
</script>