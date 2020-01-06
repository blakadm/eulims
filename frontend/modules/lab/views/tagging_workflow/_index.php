<?php
use common\models\TaggingSearch;
use common\models\lab\Sample;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\lab\Sampletype;
use common\components\Functions;
use yii\widgets\ActiveForm;
?>

<?php $searchModel = new TaggingSearch();
     
     $model = new Sample();
     
     $samplesQuery = Sample::find()->where(['sample_id' =>0]);
     $dataProvider = new ActiveDataProvider([
             'query' => $samplesQuery,
             'pagination' => [
                 'pageSize' => 10,
             ],
          
     ]);

     ?>
<?php
$lablist= ArrayHelper::map(Sample::find()->all(),'sample_id','sample_code');
$sampletypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');

$this->title = 'Tagging';
$this->params['breadcrumbs'][] = ['label' => 'Tagging', 'url' => ['/lab']];
$this->params['breadcrumbs'][] = 'Sample Tagging';

$this->registerJsFile("/js/services/services.js");
$func=new Functions();


?>

<!-- <blockquote class="imgur-embed-pub" lang="en" data-id="a/lc3D4"><a href="//imgur.com/lc3D4">Tadpole Lessons</a></blockquote><script async src="//s.imgur.com/min/embed.js" charset="utf-8"></script> -->

<div class="tagging-index">
    <?php
        echo $func->GenerateStatusTagging("Legend/Status",true);
    ?>

<div class="alert alert-info" style="background: #d4f7e8 !important;margin-top: 1px !important;">
     <a href="#" class="close" data-dismiss="alert" >×</a>
    <p class="note" style="color:#265e8d"><b>Note:</b> Please scan barcode in the dropdown list below. .</p>
     
    </div>
    <div class="row">
    <div class="col-md-4">
       <?php $form = ActiveForm::begin(); ?>
       <?php
               $disabled=false; 
               echo $func->GetSampleCode($form,$model,$disabled,"");
       ?>    
       <?php ActiveForm::end(); ?>
           </div>
              </div>
<?=
                $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                   
                ]) ?>   