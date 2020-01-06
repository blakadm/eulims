<?php

namespace frontend\modules\lab\controllers;

use Yii;
use common\models\lab\Services;
use common\models\lab\Sample;
use common\models\lab\Testname;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Sampletype;
use common\models\lab\Sampletypetestname;
use common\models\lab\ServicesSearch;
use common\models\lab\Labsampletype;
use common\models\lab\Testcategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
set_time_limit(5000);
/**
 * ServicesController implements the CRUD actions for Services model.
 */
class ServicesController extends Controller
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
     * Lists all Services models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Services();
        $modelmethod = new Methodreference();
        $searchModel = new ServicesSearch();
        $sampletype = [];
        $testcategory = [];
        $test = [];
     
        $samplesQuery = Sample::find()->where(['sample_id' =>0]);
        $dataProvider = new ActiveDataProvider([
                'query' => $samplesQuery,
                'pagination' => [
                    'pageSize' => false,
                ],       
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'testcategory'=>$testcategory,
            'sampletype'=>$sampletype,
            'model'=>$model,
            'test'=>$test,
            'modelmethod'=>$modelmethod,
        ]);
    }

    /**
     * Displays a single Services model.
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
     * Creates a new Services model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Services();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->services_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Services model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->services_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Services model.
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
     * Finds the Services model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Services the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Services::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListsampletype() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);

           // $apiUrl="https://eulimsapi.onelab.ph/api/web/v1/sampletypes/restore?id=".$id;
            $apiUrl="https://192.168.1.102/api/web/v1/sampletypes/restore?id=".$id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $curl->get($apiUrl);
            $decode=Json::decode($response);

            $selected  = null;
            if ($id != null && count($decode) > 0) {
                $selected = '';
                foreach ($decode as $i => $sampletype) {
                    $out[] = ['id' => $sampletype['sampletype_id'], 'name' => $sampletype['type']];
                    if ($i == 0) {
                        $selected = $sampletype['sampletype_id'];
                    }
                }
               \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
    }

    public function actionListtestcategory() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);

            $apiUrl="https://eulimsapi.onelab.ph/api/web/v1/testcategories/restore?id=".$id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $curl->get($apiUrl);
            $decode=Json::decode($response);

            $selected  = null;
            if ($id != null && count($decode) > 0) {
                $selected = '';
                foreach ($decode as $i => $testcategory) {
                    $out[] = ['id' => $testcategory['testcategory_id'], 'name' => $testcategory['category']];
                    if ($i == 0) {
                        $selected = $testcategory['testcategory_id'];
                    }
                }
               \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
    }

    public function actionListtest() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);

            $apiUrl="https://eulimsapi.onelab.ph/api/web/v1/testnames/restore?id=".$id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response = $curl->get($apiUrl);
            $decode=Json::decode($response);
                   
            $selected  = null;
            if ($id != null && count($decode) > 0) {
                $selected = '';
                foreach ($decode as $i => $sampletype) {
                    $out[] = ['id' => $sampletype['testname_id'], 'name' => $sampletype['testName']];
                    if ($i == 0) {
                        $selected = $sampletype['testname_id'];
                    }
                }
                \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        \Yii::$app->response->data = Json::encode(['output'=>$out, 'selected'=>'']);
    }

    public function actionGetmethod()
	{
        $id = $_GET['id'];
        $labid = $_GET['lab_id'];
        $sampletypeid = $_GET['sample_type_id'];
        $testcategory = $_GET['testcategory'];
        $methodreferenceid = $id;
        $model = new Methodreference();

        $apiUrl="https://eulimsapi.onelab.ph/api/web/v1/labsampletypes/restore?lab_id=".$labid."&sampletype_id=".$sampletypeid;
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response_labsampletype = $curl->get($apiUrl);
        $decode_labsampletype=Json::decode($response_labsampletype);

        foreach ($decode_labsampletype as $labsampletype) {          
          //  $labsampletypeid = $labsampletype['lab_sampletype_id'];  
            
            $labsampletypeid = 2;
        }

        $apiUrl_sampletypetestnames="https://eulimsapi.onelab.ph/api/web/v1/sampletypetestnames/search?testname_id=".$methodreferenceid;
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response_sampletypetestnames = $curl->get($apiUrl_sampletypetestnames);
        $decode_sampletypetestnames=Json::decode($response_sampletypetestnames);

        foreach ($decode_sampletypetestnames as $sampletypetestnames) {        
            $sampletypetestname = $sampletypetestnames['sampletype_testname_id'];        
        }

        $testnameQuery = Methodreference::find()
        ->leftJoin('tbl_testname_method', 'tbl_testname_method.method_id=tbl_methodreference.method_reference_id')
        ->Where(['tbl_testname_method.testname_id'=>$id])->all();

        $apiUrl="https://eulimsapi.onelab.ph/api/web/v1/methodreferences/restore?id=".$id;
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $response = $curl->get($apiUrl);
        $decode=Json::decode($response,TRUE);


        $count = Testnamemethod::find()->all();

        $testnamemethodcount = count($count);

         $testnameDataProvider = new ArrayDataProvider([
                 'allModels' => $decode,
                 'pagination' => [
                     'pageSize' => false,
                 ],
              
         ]);
         $testnamemethod = "";
         $testname = "";
         $searchModel = new ServicesSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $sampletype = [];
         $test = [];

         return $this->renderAjax('_method', [
            'model'=>$model,
            'testnameDataProvider' => $testnameDataProvider,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sampletype'=>$sampletype,
            'test'=>$test,
            'methodreferenceid'=>$methodreferenceid,
            'labid'=>$labid,
            'sampletypeid'=>$sampletypeid,
            'labsampletypeid'=>$labsampletypeid,
            'sampletypetestname'=>$sampletypetestname,
            'testnamemethod'=>$testnamemethod,
            'testname'=>$testname,
            'testnamemethodcount'=>$testnamemethodcount,
            'testcategory'=>$testcategory
         ]);
	
     }
     
     public function actionOffer()
     {
          $id = $_POST['id'];
          $labid = $_POST['labid'];
          $sampletypeid = $_POST['sampletypeid'];
          $methodreferenceid = $_POST['methodreferenceid'];
          $testcategory = $_POST['testcategory'];
          $labsampletypeid = $_POST['labsampletypeid'];
          $sampletypetestname = $_POST['sampletypetestname'];
          $GLOBALS['rstl_id']=Yii::$app->user->identity->profile->rstl_id;

          $apiUrl_testnamemethod="https://eulimsapi.onelab.ph/api/web/v1/testnamemethods/search?testname_id=".$methodreferenceid;
          $curl = new curl\Curl();
          $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
          $response_testnamemethod = $curl->get($apiUrl_testnamemethod);
          $decode_testnamemethod=Json::decode($response_testnamemethod);
  
          foreach ($decode_testnamemethod as $testnamemethods) {         
              $testnamemethod = $testnamemethods['testname_method_id'];
          }

           $apiUrl_sampletypetestnames="https://eulimsapi.onelab.ph/api/web/v1/sampletypetestnames/restore?sampletype_id=".$sampletypeid."&testname_id=".$methodreferenceid;
        
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response_sampletypetestnames = $curl->get($apiUrl_sampletypetestnames);
            $decode_sampletypetestnames=Json::decode($response_sampletypetestnames);
    
            foreach ($decode_sampletypetestnames as $sampletypetestnames) {       
                $sampletypetestname = $sampletypetestnames['sampletype_testname_id'];      
            }
  
            $services = new Services();
            $services->rstl_id =   $GLOBALS['rstl_id'];
            $services->method_reference_id = $id;
            $services->sampletype_id = $sampletypeid;
            $services->testname_method_id = $sampletypetestname;
            $services->save();
            $services_model = Services::find()->where(['services_id' => $services->services_id])->one();

          $apiUrl_sampletype="https://eulimsapi.onelab.ph/api/web/v1/sampletypes/search?sampletype_id=".$sampletypeid;
          $curl = new curl\Curl();
          $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
          $response_sampletype = $curl->get($apiUrl_sampletype);
          $decode_sampletype=Json::decode($response_sampletype,TRUE);

          foreach ($decode_sampletype as $var)
          {      
              $sampletype = Sampletype::find()->where(['sampletype_id'=>$sampletypeid])->one();
                if ($sampletype){
                }else{
                    $sampletype = new Sampletype();
                    $sampletype->sampletype_id = $var['sampletype_id'];  
                    $sampletype->type = $var['type'];
                    $sampletype->status_id = $var['status_id'];
                    $sampletype->save();
                }              
          }
       
         $apiUrl_labtype="https://eulimsapi.onelab.ph/api/web/v1/labsampletypes/search?lab_sampletype_id=".$labsampletypeid;
         $curl = new curl\Curl();
         $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
         $response_labtype = $curl->get($apiUrl_labtype);
         $decode_labtype=Json::decode($response_labtype,TRUE);

         foreach ($decode_labtype as $var)
         { 
            $labsampletype = Labsampletype::find()->where(['lab_sampletype_id'=>$labsampletypeid])->one();
            if ($labsampletype){
            }else{
                $labsampletype = new Labsampletype();
                $labsampletype->lab_sampletype_id = $var['lab_sampletype_id'];  
                $labsampletype->lab_id = $var['lab_id'];
                $labsampletype->sampletype_id = $var['sampletype_id'];
                $labsampletype->effective_date = $var['effective_date'];
                $labsampletype->added_by = $var['added_by'];
                $labsampletype->testcategory_id = $var['testcategory_id'];
                $labsampletype->save(false);
            }  
         }
     
         //ADD TESTCATEGORY

         $labsampletype = Labsampletype::find()->where(['lab_sampletype_id'=>$labsampletypeid])->one();
         $apiUrl_testcategory="https://eulimsapi.onelab.ph/api/web/v1/testcategories/search?testcategory_id=".$testcategory;
         $curl = new curl\Curl();
         $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
         $response_testcategory = $curl->get($apiUrl_testcategory);
         $decode_testcategory=Json::decode($response_testcategory,TRUE);

         foreach ($decode_testcategory as $var)
         { 
            $testcategory = Testcategory::find()->where(['testcategory_id'=>$labsampletype->testcategory_id])->one();
            if ($testcategory){
            }else{
                $testcategory = new Testcategory();
                $testcategory->testcategory_id = $var['testcategory_id'];  
                $testcategory->category = $var['category'];
                $testcategory->save(false);
            }  
         }

         $apiUrl_testnames="https://eulimsapi.onelab.ph/api/web/v1/testnames/search?testname_id=".$methodreferenceid;
         $curl = new curl\Curl();
         $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
         $response_testnames = $curl->get($apiUrl_testnames);
         $decode_testnames=Json::decode($response_testnames,TRUE);

         foreach ($decode_testnames as $var)
         { 
            $testname = Testname::find()->where(['testname_id'=>$methodreferenceid])->one();
            if ($testname){
            }else{
                $testname = new Testname();
                $testname->testname_id = $var['testname_id'];
                $testname->testName = $var['testName'];
                $testname->status_id = $var['status_id'];
                $testname->create_time = $var['create_time'];
                $testname->update_time = $var['update_time'];
                $testname->save();
            }
         }
         $apiUrl_testnamemethod="https://eulimsapi.onelab.ph/api/web/v1/testnamemethods/restore?testname_id=".$methodreferenceid."&method_id=".$id;
         $curl = new curl\Curl();
         $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
         $response_testnamemethod = $curl->get($apiUrl_testnamemethod);
         $decode_testnamemethod =Json::decode($response_testnamemethod,TRUE);

         foreach ($decode_testnamemethod as $var)
         { 
            $testnamemethod = Testnamemethod::find()->where(['testname_method_id'=>$var['testname_method_id']])->one();
            if ($testnamemethod){

            }else{
                $testnamemethod = new Testnamemethod();
                $testnamemethod->testname_method_id =  $var['testname_method_id'];
                $testnamemethod->testname_id = $var['testname_id'];
                $testnamemethod->method_id = $var['method_id'];
                $testnamemethod->create_time = $var['create_time'];
                $testnamemethod->update_time = $var['update_time'];
                $testnamemethod->save();
            }
         }  
         $apiUrl_sampletypetestnames="https://eulimsapi.onelab.ph/api/web/v1/sampletypetestnames/search?sampletype_testname_id=".$sampletypetestname;
         $curl = new curl\Curl();
         $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
         $response_sampletypetestnames = $curl->get($apiUrl_sampletypetestnames);
         $decode_sampletypetestnames=Json::decode($response_sampletypetestnames,TRUE);

         foreach ($decode_sampletypetestnames as $var)
         { 
            $sampletypetestname = Sampletypetestname::find()->where(['sampletype_testname_id'=>$sampletypetestname])->one();
            if ($sampletypetestname){
            }else{
                $sampletypetestname = new Sampletypetestname();
                $sampletypetestname->sampletype_testname_id = $var['sampletype_testname_id'];
                $sampletypetestname->sampletype_id = $var['sampletype_id'];
                $sampletypetestname->testname_id = $var['testname_id'];
                $sampletypetestname->added_by = $var['added_by'];
                $sampletypetestname->save();
            }
         }
          $apiUrl_methodreference="https://eulimsapi.onelab.ph/api/web/v1/methodreferences/search?method_reference_id=".$id;
          $curl = new curl\Curl();
          $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
          $response_methodreference = $curl->get($apiUrl_methodreference);
          $decode_methodreference =Json::decode($response_methodreference,TRUE);

          foreach ($decode_methodreference as $var)
          { 
                  $methodreference = Methodreference::find()->where(['method_reference_id'=>$id])->one();
                  if ($methodreference){
                      
                  }else{
                    $methodreference = new Methodreference();
                    $methodreference->method_reference_id = $var['method_reference_id'];  
                    $methodreference->testname_id = $methodreferenceid;
                    $methodreference->method = $var['method'];
                    $methodreference->reference = $var['reference'];
                    $methodreference->fee = $var['fee'];
                    $methodreference->create_time = $var['create_time'];
                    $methodreference->update_time = $var['update_time'];
                    $methodreference->save();
                  }              
          }
          
     }

     public function actionUnoffer()
     {
        
          $id = $_POST['id'];
          $labid = $_POST['labid'];
          $sampletypeid = $_POST['sampletypeid'];
          $methodreferenceid = $_POST['methodreferenceid'];
          $labsampletypeid = $_POST['labsampletypeid'];
          $sampletypetestname = $_POST['sampletypetestname'];
        
          $apiUrl_testnamemethod="https://eulimsapi.onelab.ph/api/web/v1/testnamemethods/restore?testname_id=".$methodreferenceid."&method_id=".$id;
          $curl = new curl\Curl();
          $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
          $response_testnamemethod = $curl->get($apiUrl_testnamemethod);
          $decode_testnamemethod =Json::decode($response_testnamemethod,TRUE);
 
          foreach ($decode_testnamemethod as $var)
          { 
             $testnamemethodid = $var['testname_method_id'];
          }       

        $apiUrl_sampletypetestnames="https://eulimsapi.onelab.ph/api/web/v1/sampletypetestnames/restore?sampletype_id=".$sampletypeid."&testname_id=".$methodreferenceid;
        
            $curl = new curl\Curl(


            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $response_sampletypetestnames = $curl->get($apiUrl_sampletypetestnames);
            $decode_sampletypetestnames=Json::decode($response_sampletypetestnames);
    
            foreach ($decode_sampletypetestnames as $sampletypetestnames) {       
                $sampletypetestnameid = $sampletypetestnames['sampletype_testname_id'];      
            }

           //DON'T DELETE RECORD IF IT'S CONNECTED IN OTHER SAMPLETYPES.. DELETE ONLY IF ITS DEPENDENT IN THAT SPECIFIC SAMPLECODE

           $connection= Yii::$app->labdb;
           $connection->createCommand('set foreign_key_checks=0')->execute();
        //   $connection->createCommand("DELETE FROM tbl_sampletype WHERE sampletype_id=$sampletypeid")->execute();
        //    $connection->createCommand("DELETE FROM tbl_lab_sampletype WHERE lab_sampletype_id=$labsampletypeid")->execute();
        //    $connection->createCommand("DELETE FROM tbl_methodreference WHERE method_reference_id=$id")->execute();
       //   $connection->createCommand("DELETE FROM tbl_testname WHERE testname_id=$methodreferenceid")->execute();
        //    $connection->createCommand("DELETE FROM tbl_sampletype_testname WHERE sampletype_testname_id=$sampletypetestnameid")->execute();
        //  $connection->createCommand("DELETE FROM tbl_testname_method WHERE testname_method_id=$testnamemethodid")->execute();
           $connection->createCommand("DELETE FROM tbl_services WHERE method_reference_id=$id AND rstl_id=$GLOBALS[rstl_id]")->execute();
           $connection->createCommand('set foreign_key_checks=1')->execute();  
     }

     public function actionSync()
     {
        $servicesquery = Services::find()->Where(['rstl_id'=>$GLOBALS['rstl_id']])->all();  
        $servicecount = count($servicesquery);
        $services = Services::find()->all();    

        $post = Yii::$app->request->post();
        if(isset($post)){

            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {

            $services = new Services();
            $service->rstl_id =   $GLOBALS['rstl_id'];
            $service->method_reference_id = $id;
            $service->sampletype_id = $sampletypeid;
            $service->testname_method_id = $testnamemethod->testname_method_id;
            if($newCustomer->save(true)){
            }else{
                $ids=$ids.$var['id'].',';
            }
            $ctr++;
        }      
    }
     \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

     return [
        'num'=>$ctr,
        'ids'=>$ids
     ];   
     
     }
}
