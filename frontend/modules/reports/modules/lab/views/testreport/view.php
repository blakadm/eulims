<?php
use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\lab\Analysis;
use yii\data\ActiveDataProvider;
use common\models\lab\Batchtestreport;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Testreport */

$this->title = $model->report_num;
$this->params['breadcrumbs'][] = ['label' => 'Testreports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="testreport-view">
    <div class="form-row">
        <div class="container table-responsive">
             <?php
             if($model->previous_id){
                $t = 'view?id='.$model->previous_id;
            echo Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Go to Old Report',$t,['target'=>'_blank','class'=>'btn btn-success pull-right']);

             }else{
                $t = 'reissue?id='.$model->testreport_id;
            echo Html::a('<i class="glyphicon glyphicon-check"></i> Reissue',$t,['target'=>'_blank','class'=>'btn btn-danger pull-right','data' => [
                   'confirm' => 'Are you sure you want to Reissue this Report ?',
                ]]);
             }

            //checks if the testreport is from a batch generated reports
            $chkbatch = Batchtestreport::find()->where(['request_id'=>$model->request_id])->one();
            if($chkbatch){
                    $t = 'viewmultiple?id='.$chkbatch->batchtestreport_id;
                echo Html::a('<i class="glyphicon glyphicon-arrow-up"></i> View Batch',$t,['target'=>'_blank','class'=>'btn btn-primary pull-right']);
            }
            ?>
            <!-- <button class="btn btn-primary pull-right"><i class="glyphicon glyphicon-file"></i> View Batch</button> -->
        </div>
    </div>
    <br>
    <div class="container table-responsive">
          <?= DetailView::widget([
            'model' => $model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-book"></i> Test Report # ' . $model->report_num,
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'attributes' => [
                'report_num',
                [
                    'label'=>'Request Reference Number',
                    'value'=>$model->request->request_ref_num,
                    'displayOnly'=>true,
                 
                ],
                'report_date',
              //  'status_id',
                'release_date',
                ],
            ]) ?>
    </div>
    <div class="form-row">
    <div class="container table-responsive">
        <?php
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        //query on analysis to get those records with sample_ids involve

                        $query = Analysis::find()->where(['sample_id'=>$model->sample_id]);
                        $analysisdataProvider = new ActiveDataProvider([
                            'query' => $query,
                        ]);
                 
                         return $this->render('_testresults.php', [
                             'model'=>$analysisdataProvider,
                         ]);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true
                ],
                [
                    'attribute'=>'sample.sample_code',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'sample.samplename',
                    'enableSorting' => false,
                ],
                
            ];
          
            echo GridView::widget([
                'id' => 'sample-grid',
                'dataProvider'=> $trsamples,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title"> <i class="glyphicon glyphicon-file"></i> Samples and Test Results</h3>',
                    'type'=>'primary',
                ],
                'columns' => $gridColumns,
            
            ]);
        ?>
    </div>  
    </div>
    <div class="form-row">
        <div class="container table-responsive">
             <?= Html::button('<span class="glyphicon glyphicon-download"></span> Print', ['value'=>'/customer/info/create', 'class' => 'btn btn-small btn-primary','title' => Yii::t('app', "Print Report"),'onclick'=>"ShowGModal('Select Template',true,'300px')"]); ?>
        </div>
    </div>
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
</div>


<?php
    Modal::begin([
    'options' => [
        'id' => 'gmodal',
        'tabindex' => false, // important for Select2 to work properly
        //'class' => 'modal draggable fade',
    ],
    'header' => '<h4 class="modal-title">Select Test Report</h4>'
    ]);
    echo "<div>";
    //echo "<div id='modalContent' style='margin-left: 5px; padding-bottom:10px;'><img src='/images/ajax-loader.gif' alt=''/></div>";
   ?>
    <div class="container table-responsive">
         <?= Html::a('<span class="glyphicon glyphicon-download"></span> ISO', ['/reports/lab/testreport/printview?id='.$model->testreport_id.'&template=1'], ['class'=>'btn btn-primary']) ?>
         <?= Html::a('<span class="glyphicon glyphicon-download"></span> NON-ISO', ['/reports/lab/testreport/printview?id='.$model->testreport_id.'&template=2'], ['class'=>'btn btn-primary']) ?>
         <?= Html::a('<span class="glyphicon glyphicon-download"></span> EMB', ['/reports/lab/testreport/printview?id='.$model->testreport_id.'&template=3'], ['class'=>'btn btn-primary']) ?>
    </div>
   <?php
    echo "<div>&nbsp;</div>";
    echo "</div>";
    Modal::end();
    ?>

    <script type="text/javascript">
        function ShowGModal(header,closebutton,width){
    if(closebutton==undefined){
        closebutton=true;
    }
    if(width==undefined){
       width='600px'; 
    }
    $(".close").prop('disabled',!closebutton);
    //$('#searchTextField').val()="";
    var dialog=$("#gmodal").modal({
        backdrop: true,
        show: true,
        draggable: true
    });
    dialog.init(function(){
        setTimeout(function(){
            dialog.find('.modal-title').html(header);
            dialog.find('.modal-dialog ').css({
               width: width
            });
            //dialog.find('#modalContent').load(url);
        }, 5);
    });
}
    </script>