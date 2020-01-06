<?php
use yii\widgets\ListView;
use yii\helpers\Html;
$columns =2;
?>
<div class="container" >
<div class="row">
	<div class="col-md-7" >
		<!-- <i>Press Enter to Find</i> -->
		<div id="srcbox">
			<div class="search-wrapper">
			    <div class="input-holder">
			    	<form>
			        <input id="searchbox" type="text" class="search-input" placeholder="Type to search then press 'Enter' to Find" name="varsearch" />
			        <!-- <button idd="btnsub" class="search-icon" onclick="searchToggle(this, event);" href><div></div></button> -->
			        <?php
			        echo Html::button("<span></span>",
	                    [
	                    	'class'=>'search-icon',
	                    	'id'=>'btnsub',
	                        'onclick'=>"searchToggle(this, event);",
	                    ]
	                ); 
			        ?>
			       
			        </form>
			    </div>
			    <div  id='btnx' class="close" onclick="searchToggle(this, event);"></div>
			</div>
		</div>
		<div class="row" style="margin-top:80px;">
			<h4><b><?= $searchkey ?></b></h4>
			<?= 
				ListView::widget([
				    'dataProvider' => $dataProvider,
				    'options' => [
				        'tag' => 'div',
				        'class' => 'list-wrapper',
				        'id' => 'list-wrapper',
				    ],
				    'itemView'=>'_view',
				    'beforeItem'   => function ($model, $key, $index, $widget) use ($columns) {
			            // if ( $index % $columns == 0 ) {
			                return "<div class='box-wrapper'>";
			            // }
			        },

			        'afterItem' => function ($model, $key, $index, $widget) use ($columns) {
			            // if ( $index != 0 && $index % ($columns - 1) == 0 ) {
			                return "</div>";
			            // }
			        }
				]);
			?>
		</div>
	</div>
	<div class="col-md-3"  style="border-left: 2px solid #565656;">
		<div id="cart">
			<div class="items"><img src="/images/icons/cart.png" height="40px" width="40px" class="pull-left" /><font size="5"><b>CART</b></font>
				
				</div>
			
			<div class="items">
				
				<?php 
					
					$total = 0;
					 if($session->has('cart')){
					 	$cart = unserialize($session['cart']);
						foreach ($cart as $key) {
							echo "<div class='col-md-12 item'>";
							echo "<div class='col-md-12'>".Html::a('<b>x</b>', ['destroyitem','itemid'=>$key['ID']], ['class'=>'btn btn-primary pull-right','title'=>'clear this order','style'=>'height:auto; color:red; background-color:#fafafa; border-color:#fafafa;'])."</div>";
							echo "<div class='col-md-1'><span class='fa fa-ellipsis-v'></span></div>";
							echo "<div class='iteminfo col-md-4'>";
							echo "<div class='itemtitle'>".$key['Name']."</div>";
							echo "<div class='itemcode'>".$key['Item']."</div>";
							echo "</div>";

							echo "<div class='iteminfo col-md-2'>";
							echo "<div class='itemqty'>".$key['Quantity']."</div>";
							echo "<div class='itemcost pull-right'>".$key['Cost']." php</div>";
							echo "</div>";


							
							echo "<div class='itemfee col-md-5'>".$key['Subtotal']." php</div>";
							echo "</div>";
						}
						if(sizeof($cart)>0){
							echo Html::a("Withdraw",
					        	['outcart'],
			                    [
			                    	'data-confirm' => "Are you sure you want to withdraw the cart?",
			                    	'class'=>'pull-left btn btn-success',
			                    ]
			                ); 
				      
							echo Html::a("Empty Cart",
								['destroyall'],
			                    [
			                    	'data-confirm' =>"Are you sure you want to clear the cart?",
			                    	'class'=>'pull-right btn btn-danger',
			                    ]
			                ); 	
						}
						 else{
						 	echo "<pre align='center'>~EMPTY~</pre>";
						 }
				        
		               
					 }
					 else{
					 	echo "<pre align='center'>~EMPTY~</pre>";
					 }
				?>
				
			</div>
			
		</div>
	</div>
</div>
</div>
<?php 
$this->registerCssFile("/css/modcss/inventorymod.css", [
    // 'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    // 'media' => 'print',
], 'css-search-bar');

$this->registerJsFile("/js/inventory/searchtoggle.js", [
    // 'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    // 'media' => 'print',
], 'js-search-bar');
$this->registerJsFile("/js/inventory/float.js", [
    // 'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    // 'media' => 'print',
], 'js-cart');
?>


<script type="text/javascript">


	$( document ).ready(function() {
	    $(btnsub).click();
	});


</script>