<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\lab\Lab;
use common\models\lab\Markettype;
use common\models\lab\Sampletype;
use common\models\lab\Documentcontrolconfig;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;



use kartik\grid\Module;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;
use kartik\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\DocumentcontrolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer Satisfaction Feedback Survey';
$this->params['breadcrumbs'][] = $this->title;
$lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
$tomlist= ArrayHelper::map(Markettype::find()->all(),'id','type');
$month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$year = ['2019'];
?>

<?php $form = ActiveForm::begin(); ?>  
   <div>
       <?php 
     echo   $sampletype = "<div class='row'><div class='col-md-2'  style='margin-left:15px'>".$form->field($model,'r_date')->widget(Select2::classname(),[
                       'data' => $month,
                       'id'=>'month',
                       'theme' => Select2::THEME_KRAJEE,
                       'options' => ['id'=>'month'],
                       'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Month'],
               ])->label("Month")."</div>"."<div class='col-md-2'>".$form->field($model,'essay')->widget(Select2::classname(),[
                   'data' => $year,
                   'id'=>'year',
                   'theme' => Select2::THEME_KRAJEE,
                   'options' => ['id'=>'year'],
                   'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Year'],
           ])->label("Year")."</div><div><br><span class='btn btn-success'onclick='monthlyreport()'>Generate</span></div>";

          
       ?>
   </div>
<?php ActiveForm::end(); ?>	
		  
 
<?php $this->registerJsFile("/js/services/services.js"); ?>
<div class="customer_satisfaction_feedback-index">

   
    <?php echo  GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ref_num',
            'name',
            [
                'attribute' => 'service',
                'label' => 'Laboratory',
                'value' => function($model) {
                    $lab = Lab::find()->where(['lab_id' => $model->service])->one();

                        if ($lab){
                            return $lab->labname;
                        }else{
                           return "None";
                        }
                       
                    
                    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $lablist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Select Lab', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'r_date',
                'label' => 'Date and Time',
                'value' => function($model) {
                    if ($model){
                        return $model->r_date;
                    }else{
                       return "None";
                    }
                    
                },
            ],
            'nob',
            [
                'attribute' => 'service',
                'label' => 'Type of Market',
                'value' => function($model) {
                    $tom = Markettype::find()->where(['id' => $model->tom])->one();
                    if ($tom){
                        return $tom->type;
                    }else{
                       return "None";
                    }
                    
                    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $tomlist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Select Market', 'id' => 'grid-products-search-category_type_id']
            ],
            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
            'template' => '{view}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/lab/csf/resultmodal','id'=>$model->id]), 'class' => 'btn btn-primary','onclick'=>'LoadModal(this.title, this.value);','title' => Yii::t('app', "View Results")]);
                },
               
            ],
        ],
        ],
    ]); ?>
</div>


<script type="text/javascript">
   function monthlyreport() {

         jQuery.ajax( {
            type: 'GET',
            url: 'monthlyreport',
            data: { month: $('#select2-month-container').attr('title'), year: $('#select2-year-container').attr('title')},
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

