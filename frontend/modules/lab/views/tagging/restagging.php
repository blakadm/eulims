<?php

class ResttaggingController extends Controller
{
	
	public function actionGetAnalyses(){
		$data = CJSON::decode($_POST["data"]);
		$sample_code = explode(' ', $data['code']);
		$id = $sample_code[0];
	
		
		$analyses = Analysis::model()->findAllByAttributes(array('sample_id'=>$id));

		$sample = Sample::model()->findbyPK($id);
		$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$analyses->taggingId));

		$Criteria = new CDbCriteria();
        $Criteria->select="*";
        $Criteria->with = "tags";
      
        $Criteria->condition="`sample_id`=".$id;
		$analyses=Analysis::model()->findAll($Criteria);


		$sample = CJSON::encode($sample);
		$analyses = CJSON::encode($analyses);
		
		if ($analyses){

			echo CJSON::encode(array("result"=>true,"analyses"=>$analyses, "sample"=>$sample));
		}else{
			echo CJSON::encode(array("result"=>false,"message"=>"sample code not found"));
		}
		exit();
	}

 public function actionStartAnalyses(){
		 	$data = CJSON::decode($_POST["data"]);
		 	$analysis_id = explode ('-', $data['id']);
		 	$id = $analysis_id[1];
		 	$new = $id;

		 	$Criteria = new CDbCriteria;
		 	$Criteria->condition = '(status=1 OR status=2 OR user_id!='.$data['user_id'].') AND analysisId='.$id;
		 	$taggingModel = Tagging::model()->find($Criteria);

		 	if ($taggingModel){
		 		$taggingUser = Tagging::model()->findByAttributes(array('analysisId'=>$new));
		 		$analystname = Profile::model()->findbyPK($taggingUser->user_id);
		 		$name = $analystname->firstname." ".$analystname->lastname;

		 		if ($taggingUser->user_id!=$data['user_id']){
		 			$message = "Analysis already already assigned to ".$name.".";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}else{
		 			$message = "Analysis already tagged as ongoing.";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}
		 	}else{
		 		$tagging = new Tagging;
			 	$tagging->analysisId = $id;
			 	$tagging->startDate = date('Y-m-d');
			 	$tagging->status = 1;
			 	$tagging->user_id = $data['user_id'];
			 	if ($tagging->save(false)){
					//new code!!!!! insert this in the server
			 		Analysis::model()->updateByPk($new, array ("taggingId"=>$tagging->id, "user_id"=>$data['user_id']));
			 		$tag = Tagging::model()->findByAttributes(array('analysisId'=>$new));
		 			$analyst = Profile::model()->findbyPK($tag->user_id);
		 			$fullname = $analyst->firstname." ".$analyst->lastname;
		 			 $Analysis = Analysis::model()->findByPk($new);
	

					 $Request = Request::model()->findByAttributes(
						array('requestRefNum'=>$Analysis->requestId)
						);
						//for the request sample
						$val = $Request->completed;
						$ret=(int)$Request->completed;
						$frac=$val-$ret;
						if($frac>0){
						}else{
							Request::model()->updateByPk($Request->id, 
							array('completed'=>$Request->completed + .5,
							));	
						}
			 		echo CJSON::encode(array("result"=>true, "new"=>$new, "fullname"=>$fullname));
			 	}else{
			 		echo CJSON::encode(array("result"=>false, "new"=>$new, "fullname"=>$fullname));
			 	}

		 	}

 }

public function actionCompletedAnalyses(){
 	$data = CJSON::decode($_POST["data"]);
 	$analysis_id = explode ('-', $data['id']);
 	$id = $analysis_id[1];
 	$new = $id;

 	$Criteria = new CDbCriteria;
 	$Criteria->condition = '(status=2 OR status=4 OR user_id!='.$data['user_id'].') AND analysisId='.$id;
 	$taggingModel = Tagging::model()->find($Criteria);
 	if ($taggingModel){
 			$taggingUser = Tagging::model()->findByAttributes(array('analysisId'=>$new));
 			$analystname = Profile::model()->findbyPK($taggingUser->user_id);
 			$name = $analystname->firstname." ".$analystname->lastname;
 			if ($taggingUser->user_id!=$data['user_id']){
		 			$message = "Analysis already already assigned to ".$name.".";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}else{
		 			$message = "Analysis already tagged as completed.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message));
		 		}
	 	}else{
		 	$Analysis = Analysis::model()->findbyPK($id);
		 	$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$Analysis->id));
		 	if ($tagging){
		 		Tagging::model()->updateByPk($tagging->id, array ("status"=>2, "endDate"=>date('Y-m-d')));
			
				 $Analyses = Analysis::model()->findByPk($id);
				 $Samp = Sample::model()->findByPk($Analyses->sample_id);
				
				 $c = $Samp->completed; //ok
				 
				 
				 $count_analysis = Analysis::model()->findAllByAttributes(
					array('sample_id'=>$Samp->id)
					);
				$status = $tagging->status;
			

				$count = count($count_analysis);
				$c = $c + 1;
				 if ($c==$count){ 
				 			 $Request = Request::model()->findByAttributes(
							array('requestRefNum'=>$Analyses->requestId)
							);
							Request::model()->updateByPk($Request->id, 
							array('completed'=>$Request->completed + 1,
							));	
				}
					if ($status==1){
						 $Analysis = Analysis::model()->findByPk($id);
						 $Sample = Sample::model()->findByPk($Analysis->sample_id);
						 Sample::model()->updateByPk($Sample->id, 
						 array('completed'=>$Sample->completed + 1,
					 ));

					
					 $Request = Request::model()->findByAttributes(
						array('requestRefNum'=>$Analysis->requestId)
						);
						
				 }
				
				 echo CJSON::encode(array("result"=>true, "new"=>$new));
				// $message = $c."Please start analysis".$count;
				// echo CJSON::encode(array("result"=>false, "new"=>$new, "message"=>$message));
				
		 	}else{
		 		$message = "Please start analysis.";
		 		echo CJSON::encode(array("result"=>false, "new"=>$new, "message"=>$message));
	 	}
 	}
 	
 }

  public function actionTransferAnalyst(){
 	$data = CJSON::decode($_POST["data"]);
 	$analysis_id = explode ('-', $data['id']);
 	$id = $analysis_id[1];
 	//$id = 31442;
 	$new = $id;


 	$Criteria = new CDbCriteria;
	$Criteria->condition = '(status=2) AND analysisId='.$id;
	$taggingModel = Tagging::model()->find($Criteria);
	 	if ($taggingModel){

	 				$message = "Can't transfer analyst. Status is completed.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else{
	 			$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 					if ($tagging){
	 						Tagging::model()->updateByPk($tagging->id, array ("user_id"=>$data['user_id']));
	 						//Tagging::model()->updateByPk($tagging->id, array ("status"=>4, "user_id"=>1));
	 					}else{

	 					}
	 			$tag = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 			$analyst = Profile::model()->findbyPK($tag->user_id);
	 			$fullname = $analyst->firstname." ".$analyst->lastname;
	 			echo CJSON::encode(array("result"=>true, "fullname"=>$fullname, "new"=>$new));
	 		}
 }

 public function actionGetAnalyst(){
 	//transfer na wala pang tag
 		$data =CJSON::decode($_POST["data"]);
 		$analysis_id = explode ('-', $data['id']);
 		$id = $analysis_id[1];
 		//$id = 31442;

 		$tagStatus = Tagging::model()->findByAttributes(array('analysisId'=>$id));
	 		if (!$tagStatus){
	 				$message = "Please start analysis.";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else if ($tagStatus->user_id!=$data['user_id']){
	 				$analystname = Profile::model()->findbyPK($tagStatus->user_id);
 					$name = $analystname->firstname." ".$analystname->lastname;
	 				$message = "Analysis already already assigned to ".$name.".";
	 				echo CJSON::encode(array("result"=>false, "message"=>$message, "new"=>$new));
	 		}else{
	 			$Criteria = new CDbCriteria;
			 	$Criteria->condition = '(status=2) AND analysisId='.$id;
			 	$taggingModel = Tagging::model()->find($Criteria);
	 			if ($taggingModel){
	 			
		 			$message = "Can't transfer analyst. Status is completed.";
		 			echo CJSON::encode(array("result"=>false, "message"=>$message));
	 				
	 		
	 			}else{
	 				$Analysis = Analysis::model()->findbyPK($analysis_id);
 					$profile = Profile::model()->findByAttributes(array('user_id'=>$data['user_id']));
 					//$profile = Profile::model()->findByAttributes(array('user_id'=>1));
 					$code_data = Profile::model()->findAll(array("condition"=>"labId='".$profile->labId."'"));
 		
	 				if ($code_data){
	 					echo CJSON::encode(array("result"=>true, "analyst"=>$code_data, "testname"=>$Analysis->testName));
	 				}else{
	 					echo CJSON::encode(array("result"=>false));
	 				}
	 	}	
	 		}
 		
 }

  public function actionGetSampleCode(){
 		$data =CJSON::decode($_POST["data"]);

 		$profile = Profile::model()->findByAttributes(array('user_id'=>$data['user_id']));
 		//$profile = Profile::model()->findByAttributes(array('user_id'=>1));
 		$lab = Lab::model()->findbyPK($profile->labId);
 		$year = date('Y');
 		$code_data = Sample::model()->findAll(array("condition"=>'sampleCode LIKE"'.$lab->labCode.'-%" AND sampleYear='.$year.' ORDER BY sampleCode DESC'));
 		if ($code_data){
 			echo CJSON::encode(array("result"=>true, "samplecode"=>$code_data));
 		}else{
 			echo CJSON::encode(array("result"=>false));
 		}
 }

  public function actionGetTags(){
 		$data =CJSON::decode($_POST["data"]);
		$analysis_id = explode ('-', $data['id']);
 		$tagging = Tagging::model()->findByAttributes(array('analysisId'=>$analysis_id));
 		$analyst = Profile::model()->findbyPK($tagging->user_id);
 		$fullname = $analyst->firstname." ".$analyst->lastname;
 		$status = $tagging->status;
 		if ($status==1){
 			echo CJSON::encode(array("result"=>true, "status"=>"Ongoing", "fullname"=>$fullname));
 		}else if ($status==2){
 			echo CJSON::encode(array("result"=>true, "status"=>"Completed", "fullname"=>$fullname));
 		}else if ($status==4){
 			echo CJSON::encode(array("result"=>true, "status"=>"Assigned", "fullname"=>$fullname));
 		}else if (!$status){
 			echo CJSON::encode(array("result"=>true, "status"=>"Pending", "fullname"=>$fullname));
 		}
 }

 public function actionCheckdisposal(){

	$data = CJSON::decode($_POST["data"]);
	$id = $data['id'];

		 $Analysis = Analysis::model()->findByPk($id);
		 $tagging = Tagging::model()->findByAttributes(array('analysisId'=>$Analysis->id));
		 $Samp = Sample::model()->findByPk($Analysis->sample_id);
		 $c = $Samp->completed; 
		 $count_analysis = Analysis::model()->findAllByAttributes(
			array('sample_id'=>$Samp->id)
			);

		// $count = count($count_analysis);
		// $c = $c + 1;
		//  if ($c==$count){ 
		// 	echo CJSON::encode(array("result"=>true));
		// }else{
		// 	$message = ;
		// 	echo CJSON::encode(array("result"=>false, "message"=>$message));
		// }
 }

 public function actionSavedisposal(){
 		echo CJSON::encode(array("result"=>true, "message"=>$message));
 }
}

?>