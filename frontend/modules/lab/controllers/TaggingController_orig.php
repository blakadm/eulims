<?php
namespace frontend\modules\lab\controllers;

use Yii;
use common\models\lab\Tagging;
use common\models\lab\Tagginganalysis;
use common\models\lab\Analysis;
use common\models\lab\Workflow;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Request;
use common\models\lab\Sample;
use common\models\lab\Procedure;
use common\models\TaggingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use common\models\system\Profile;
//ini_set("memory_limit", "200M");dfgf
/**
 * TaggingController implements the CRUD actions for Tagging model.
 */
class TaggingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tagging models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaggingSearch();
     
        $model = new Sample();
        
        $samplesQuery = Sample::find()->where(['sample_id' =>0]);
        $dataProvider = new ActiveDataProvider([
                'query' => $samplesQuery,
                'pagination' => [
                    'pageSize' => 10,
                ],
             
        ]);

        return $this->render('_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }

    /**
     * Displays a single Tagging model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tagging model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tagging();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tagging_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tagging model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tagging_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tagging model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tagging model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tagging the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tagging::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetsamplecode($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('sample_id as id, sample_code AS text')
                    ->from('tbl_sample')
                    ->where(['like', 'sample_code', $q])
                    
                    ->limit(20);
            $command = $query->createCommand();
            $command->db= \Yii::$app->labdb;
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' =>Sample::find()->where(['sample_id'=>$id])->sample_code];
        }
        return $out;
    }

    public function actionStartanalysis()
    {           
        if(isset($_POST['id'])){
           
     
            $ids = $_POST['id'];
            $analysis = $_POST['analysis_id'];
            $analysisID = explode(",", $ids);  
            $analysiss= Analysis::find()->where(['analysis_id'=> $_POST['analysis_id']])->one();
            $samplesq = Sample::find()->where(['sample_id' =>$analysiss->sample_id])->one();             
            $samcount = $analysiss->completed;  

			if ($ids){
				foreach ($analysisID as $aid){
                    
                    $taggingmodel = Tagging::find()->where(['analysis_id'=>$aid])->one();
                  
                        $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
                        $now = date('Y-m-d');
                        $Connection= Yii::$app->labdb;
    
                        $sql="UPDATE `tbl_tagging` SET `start_date`='$now', `tagging_status_id`='1', `user_id`='$profile->user_id'  WHERE `tagging_id`=".$aid;
                        $Command=$Connection->createCommand($sql);
                        $Command->execute();                      
                        $sample= Sample::find()->where(['sample_id'=> $aid])->one();


                        $tagginganalysismodel = Tagginganalysis::find()->where(['cancelled_by'=> $analysis])->one();

                        if ($tagginganalysismodel)
                        {

                        }else{
                            $tagginganalysis = new Tagginganalysis();
                            $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
                            $tagginganalysis->user_id = $profile->user_id;
                            $tagginganalysis->analysis_id = $aid;
                            $tagginganalysis->start_date = date("Y-m-d");
                            $tagginganalysis->tagging_status_id = 1;
                            $tagginganalysis->cancelled_by = $_POST['analysis_id'];
                            $tagginganalysis->save(false);   
                        }
                    
                
            }
        }
       
            $analysis_id = $_POST['analysis_id'];    
            $samplesQuery = Sample::find()->where(['sample_id' =>$analysis_id]);
            $sampleDataProvider = new ActiveDataProvider([
                    'query' => $samplesQuery,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                 
            ]);

            
            $procedure = Procedure::find()->where(['testname_id' => 1]);

         

            $analysisQuery = Analysis::findOne(['analysis_id' => $analysis]);
            $samcount = $analysisQuery->completed;
            $modelmethod=  Methodreference::findOne(['method'=>$analysisQuery->method]);
            
            $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysisQuery->test_id, 'method_id'=>$analysisQuery->testcategory_id]);
          
            $tagging= Tagging::find()->where(['cancelled_by'=> $analysis_id]);        
            $workflow = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id]);
            $count = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();

            $analysisdataprovider = new ActiveDataProvider([
                    'query' => $tagging,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                 
            ]);
            return $this->renderAjax('tag', [
                'analysisdataprovider'=> $analysisdataprovider,
                'analysis_id'=>$analysis_id,
                'count'=>$count,
                'samcount'=>$samcount,
             ]);
            
        }
            
     }

     public function actionSampleregister()
     {
        //  $searchModel = new TestnamemethodSearch();
        //  $model = new Testnamemethod();
        //  $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sample = Sample::find()->all();   
        $sampledataprovider = new ActiveDataProvider([
            'query' => $sample,
            'pagination' => [
                'pageSize' => 10,
            ],
         
        ]);
 
        return $this->render('sampleregister', [
            // 'analysisdataprovider'=> $analysisdataprovider,
            // 'analysis_id'=>$analysis_id,
            // 'count'=>$count,
            // 'samcount'=>$samcount,
         ]);
     }
     public function actionTag($id)
     {
                $analysisQuery = Analysis::findOne(['analysis_id' => $id]);
                $modelmethod=  Methodreference::findOne(['method'=>$analysisQuery->method]);   
             
                //method id == testcategory_id
                $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysisQuery->test_id, 'method_id'=>$analysisQuery->testcategory_id]);
                if ( $testnamemethod){
                $workflow = Workflow::find()->where(['testname_method_id' => $testnamemethod->testname_method_id])->all();
             
                            $w = '';
                            foreach ($workflow as $w_id){
                                $w .= $w_id->workflow_id.",";
                            }
                            $w = substr($w, 0, strlen($w)-1);
                        
                        
                        $ids = explode(",", $w);   
                        
                        $tagging= Tagging::find()->where(['cancelled_by'=> $id])->one();

                            if ($tagging){
                            
                            }else{
                                foreach ($ids as $workflow){  
                                    $workflows = Workflow::find()->where(['workflow_id' => $workflow])->one();

                                    if ($workflow){
                                        $tagging = new Tagging();
                                        $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
                                        $tagging->user_id = null;
                                        $tagging->analysis_id = $workflows->workflow_id;
                                        $tagging->start_date = null;
                                        $tagging->tagging_status_id = 0;
                                        $tagging->reason = 1;
                                        $tagging->cancelled_by = $id;
                                        $tagging->iso_accredited = 1;
                                        $tagging->save(false);  
                                    }else{
                                        
                                    }
                                  
                                }
                            }
                                    
                    
                            $workflow = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id]);
                            $tagging= Tagging::find()->where(['cancelled_by'=> $id]);

                            $analysis= Analysis::find()->where(['analysis_id'=> $id])->one();
                            $samplesq = Sample::find()->where(['sample_id' =>$analysis->sample_id])->one();    
                            
                            $count = Sample::find()->where(['request_id' =>$samplesq->request_id])->count(); 

                            $requestcount= Sample::find()
                            ->leftJoin('tbl_request', 'tbl_sample.request_id=tbl_request.request_id')   
                            ->all();  

                            $rcount= 2;

                                         if ($samplesq->completed==$count){
                                             
                                            $Connection= Yii::$app->labdb;
                                            $sql="UPDATE `tbl_request` SET `completed`='$rcount' WHERE `request_id`=".$samplesq->request_id;
                                            $Command=$Connection->createCommand($sql);
                                            $Command->execute(); 
                                         }

                            $samcount = $analysis->completed;

                            $procedure = Procedure::find()->where(['testname_id' => 1]);
                            $count =  Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();
                            $analysis_id = $id;
                    
                            $analysisdataprovider = new ActiveDataProvider([
                                    'query' => $tagging,
                                    'pagination' => [
                                        'pageSize' => false,
                                            ],                 
                            ]);
                                
                            if(Yii::$app->request->isAjax){
                                return $this->renderAjax('tag', [
                                    'analysis_id'=>$analysis_id,
                                    'analysisdataprovider'=>$analysisdataprovider,
                                    'count'=>$count,
                                    'samcount'=>$samcount,
                                    ]);
                            }
            }else{

                if(Yii::$app->request->isAjax){
                    return $this->renderAjax('_noworkflow', [
                        ]);
                }
            }
    
     }

     public function actionStatus($id)
     {
                $analysisQuery = Analysis::findOne(['analysis_id' => $id]);
                $modelmethod=  Methodreference::findOne(['method'=>$analysisQuery->method]);   
             
                $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysisQuery->test_id, 'method_id'=>$analysisQuery->testcategory_id]);
                if ($testnamemethod){
                $workflow = Workflow::find()->where(['testname_method_id' => $testnamemethod->testname_method_id])->all();
               
                        $tagging= Tagging::find()->where(['cancelled_by'=> $id]);
                        $analysis= Analysis::find()->where(['analysis_id'=> $id])->one();
                        $samcount = $analysis->completed;
                        $count =  Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();
                        $analysis_id = $id;
                
                            $analysisdataprovider = new ActiveDataProvider([
                                    'query' => $tagging,
                                    'pagination' => [
                                        'pageSize' => false,
                                            ],                 
                            ]);
                                
                            if(Yii::$app->request->isAjax){
                                return $this->renderAjax('_status', [
                                    'analysis_id'=>$analysis_id,
                                    'analysisdataprovider'=>$analysisdataprovider,
                                    'count'=>$count,
                                    'samcount'=>$samcount,
                                    ]);
                            }
            }else{
                if(Yii::$app->request->isAjax){
                    return $this->renderAjax('_noworkflow', [
                        ]);
                }
            }
    
     }

     public function actionSamplestatus($id)
     {
        $id = $_GET['id'];

        $request = Request::find()->where(['request_id' => $id])->one();
        $sample = Sample::find()->where(['request_id' => $id]);

       // $samplesQuery = Sample::find()->where(['sample_id' =>$analysis_id]);

        $sampledataprovider = new ActiveDataProvider([
            'query' => $sample,
            'pagination' => [
                'pageSize' => false,
                    ],                 
        ]);

        if(Yii::$app->request->isAjax){
                 return $this->renderAjax('_samplestatus', [
                'sampledataprovider'=>$sampledataprovider,
                'request'=>$request,
                ]);
        }
           
     }

     public function actionCompletedanalysis()
     {
         
         if(isset($_POST['id'])){
             $ids = $_POST['id'];
             $analysis_id = $_POST['analysis_id'];
             $analysisID = explode(",", $ids);
             $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
             if ($ids){
                 foreach ($analysisID as $aid){
                    $tagging = Tagging::find()->where(['tagging_id'=>$aid])->one();             
                    $analysis= Analysis::find()->where(['analysis_id'=> $_POST['analysis_id']])->one();

                    if ($tagging->tagging_status_id==0 ){

                    }else{
                                if ($tagging){
                                    $now = date('Y-m-d');
                                    $Connection= Yii::$app->labdb;
                                    $sql="UPDATE `tbl_tagging` SET `end_date`='$now', `tagging_status_id`='2' WHERE `tagging_id`=".$tagging->tagging_id;
                                    $Command=$Connection->createCommand($sql);
                                    $Command->execute();                      
                                    $sample= Sample::find()->where(['sample_id'=> $aid])->one();


                                }else{
            
                                }    
                    }
                                    
             } 
            
         }
            
             $samplesQuery = Sample::find()->where(['sample_id' =>$analysis_id]);

           

             $sampleDataProvider = new ActiveDataProvider([
                     'query' => $samplesQuery,
                     'pagination' => [
                         'pageSize' => 10,
                     ],
                  
             ]);
           
            
             $analysisQuery = Analysis::findOne(['analysis_id' => $analysis_id]);
             $modelmethod=  Methodreference::findOne(['method'=>$analysisQuery->method]);
             
             $testnamemethod = Testnamemethod::findOne(['testname_id'=>$analysisQuery->test_id, 'method_id'=>$analysisQuery->testcategory_id]);
           
      
             $workflow = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id]);
             $count = Workflow::find()->where(['testname_method_id'=>$testnamemethod->testname_method_id])->count();     
             $samplesq = Sample::find()->where(['sample_id' =>$analysisQuery->sample_id])->one();             
             $samcount = $analysisQuery->completed;
             $tagging= Tagging::find()->where(['cancelled_by'=> $analysis_id]);   
             $analysisdataprovider = new ActiveDataProvider([
                     'query' => $tagging,
                     'pagination' => [
                         'pageSize' => 10,
                     ],
                  
             ]);

            
 
             return $this->renderAjax('tag', [
                 'sampleDataProvider' => $sampleDataProvider,
                 'analysisdataprovider'=> $analysisdataprovider,
                 'analysis_id'=>$analysis_id,
                 'count'=>$count,
                 'samcount'=>$samcount,
              ]);
          
             
         }
             
      }

    public function actionGetanalysis()
	{
        $id = $_GET['analysis_id'];
        $analysis_id = $id;
        $model = new Tagging();

       

         $samplesQuery = Sample::find()->where(['sample_id' => $id]);
         $sampleDataProvider = new ActiveDataProvider([
                 'query' => $samplesQuery,
                 'pagination' => [
                     'pageSize' => 10,
                 ],       
         ]);

         $analysisQuery = Analysis::find()->where(['sample_id' => $id]);
         $request = Request::find()->where(['request_id' =>42]);
         $analysisdataprovider = new ActiveDataProvider([
                 'query' => $analysisQuery,
                 'pagination' => [
                     'pageSize' => false,
                 ],
              
         ]);
         
         return $this->renderAjax('_viewAnalysis', [
            'request'=>$request,
            'model'=>$model,
            'samplesQuery'=>$samplesQuery,
            'analysisQuery'=>$analysisQuery,
            'sampleDataProvider' => $sampleDataProvider,
            'analysisdataprovider'=> $analysisdataprovider,
            'analysis_id'=>$analysis_id,
            'id'=>$id,
         ]);
	
	 }
}
