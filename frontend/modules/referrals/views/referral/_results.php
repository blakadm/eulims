<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$rstl_id=$rstl_id=(int) Yii::$app->user->identity->profile->rstl_id;

$gridColumnResults="<div class='row'><div class='col-md-12'>". GridView::widget([
    'dataProvider' => $testresult,
     'id'=>'Grid',
     'tableOptions'=>['class'=>'table table-hover table-stripe table-hand'],
     'pjax'=>true,
     'pjaxSettings' => [
             'options' => [
                 'enablePushState' => false,
             ],
     ],
     'toolbar'=>[],
     'panel' => [
         'type' => GridView::TYPE_PRIMARY,
         'heading' => '<i class="fa fa-columns"></i> List',
      ],
     'columns' => [
         [
             'label' => 'Result',
             'format'=>'raw',
             'value' =>  function($testresult){
                 return Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$testresult['filename'],'/referrals/attachment/download?referral_id='.$testresult['referral_id'].'&file='.$testresult['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Result','target'=>'_self'])."<br>";
             }
         ],

     ],
 ])."</div></div>";
                
if($model['receiving_agency_id'] == $rstl_id){
    $gridColumnResult=$gridColumnResults;
}else{
    //$Uploadbtn=Html::button('<span class="glyphicon glyphicon-upload"></span> Upload Result', ['value'=>"/referrals/attachment/upload_result?referralid=$model[referral_id]", 'class' => 'btn btn-success','title' => Yii::t('app', "Upload Result"),'id'=>'btnuploadresult','onclick'=>'addresult(this.value,this.title)']).'<br><br>'; 
    $Uploadbtn =  Html::button('<span class="glyphicon glyphicon-upload"></span> Upload Result', ['value'=>Url::to(['/referrals/attachment/upload_result','referralid'=>$model['referral_id'],'request_id'=>$model['local_request_id']]), 'onclick'=>'addresult(this.value,this.title)', 'class' => 'btn btn-primary','title' => 'Upload Result']);
    $gridColumnResult=$Uploadbtn.$gridColumnResults;
}
echo $gridColumnResult;

?>


<script type="text/javascript">
   function addresult(url,title){
       LoadModal(title,url,'true','600px');
   } 
</script>

