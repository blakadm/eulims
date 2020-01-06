<?php

namespace frontend\modules\lab\controllers;

use Yii;
use common\models\lab\Package;
use common\models\lab\PackageSearch;
use common\models\lab\Testnamemethod;
use common\models\lab\Sampletypetestname;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
use common\models\lab\Testname;
/**
 * PackageController implements the CRUD actions for Package model.
 */
class PackageController extends Controller
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
     * Lists all Package models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Package model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]);
        }
    }

    /**
     * Creates a new Package model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Package();
        $GLOBALS['rstl_id']=Yii::$app->user->identity->profile->rstl_id;
        $post= Yii::$app->request->post();
        $sampletype = [];
        if ($model->load(Yii::$app->request->post())) {
                   $model = new Package();
                    $model->rstl_id= $GLOBALS['rstl_id'];
                    $model->sampletype_id= $post['Package']['sampletype_id'];
                    $model->name= $post['Package']['name'];
                    $model->testcategory_id= $post['Package']['testcategory_id'];
                    $model->rate= $post['Package']['rate'];
                    $model->tests= $post['Package']['tests'];
                    $model->save(false);  
                    return $this->runAction('index');
                }          
                if(Yii::$app->request->isAjax){
                    $GLOBALS['rstl_id']=Yii::$app->user->identity->profile->rstl_id;
                    $model->rstl_id= $GLOBALS['rstl_id'];
                    
                    $model->testcategory_id=1;
                    return $this->renderAjax('_form', [
                        'model' => $model,
                        'sampletype'=>$sampletype
                    ]);
               }
    }
    /**
     * Updates an existing Package model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sampletype = [];
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                 //   Yii::$app->session->setFlash('success', 'Package Successfully Updated'); 
                    return $this->redirect(['index']);

                } else if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_form', [
                        'model' => $model,
                        'sampletype'=>$sampletype
                    ]);
                 }
    }

    /**
     * Deletes an existing Package model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id); 
        if($model->delete()) {            
          //  Yii::$app->session->setFlash('success', 'Package Successfully Deleted'); 
            return $this->redirect(['index']);
        } else {
            return $model->error();
        }
    }

    public function actionGetmethod()
	{
        $model = new Testnamemethod();
        $labid = $_GET['lab_id'];
        $sampletype_id = $_GET['sampletype_id'];

         $sampletypetestname = Sampletypetestname::find()->where(['sampletype_id'=>$sampletype_id])->all();

        //  $testnamemethod = Testnamemethod::find()
        //  ->leftJoin('tbl_sampletype_testname', 'tbl_sampletype_testname.testname_id=tbl_testname_method.testname_id')
        //  ->where(['tbl_sampletype_testname.sampletype_id'=>$sampletype_id]);

        $testnamemethod = Testnamemethod::find();

         $testnamedataprovider = new ActiveDataProvider([
                 'query' => $testnamemethod,
                 'pagination' => [
                     'pageSize' => false,
                 ],
              
         ]);

         $package = Package::find()->where(['id'=>0])->all();
         $packagedataprovider = new ArrayDataProvider([
                 'allModels' => $package,
                 'pagination' => [
                     'pageSize' => false,
                 ],       
         ]);

         return $this->renderAjax('_method', [
            'testnamedataprovider' => $testnamedataprovider,
            'packagedataprovider'=>$packagedataprovider,
            'model'=>$model
         ]);
	
     }

     public function actionAddpackage()
     {
         $testname_method_id = $_POST['mid'];
        //  $testname_id = $_POST['testname_id'];
        //  $searchModel = new ProcedureSearch();
        //  $model = new Procedure();
        //  $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        // // $procedure = Procedure::find()->where(['procedure_id' => $id])->one();

      
         $Package = new Package();
         $procedure->procedure_name = $procedure_name;
         $procedure->procedure_code = "1";
         $procedure->testname_id = "1";
         $procedure->testname_method_id = "1";
         $procedure->save();
  
         $workflow = Workflow::find()->where(['testname_method_id' => $testname_id]);
         
                 $workflowdataprovider = new ActiveDataProvider([
                         'query' => $workflow,
                         'pagination' => [
                             'pageSize' => false,
                         ],
                      
                 ]);
  
         if(Yii::$app->request->isAjax){
             return $this->renderAjax('_workflow', [
                 'searchModel' => $searchModel,
                 'dataProvider' => $dataProvider,
                 'workflowdataprovider'=>$workflowdataprovider,
                 'testname_id'=>$testname_id,
                 'model'=>$model,
             ]);
         }
     
         
    }

    /**
     * Finds the Package model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Package the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Package::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetpackage() {
        if(isset($_GET['packagelist_id'])){
            $id = (int) $_GET['packagelist_id'];
            $modelpackagelist =  Package::findOne(['id'=>$id]);
            if(count($modelpackagelist)>0){
                $rate = $modelpackagelist->rate;
                $tet = $modelpackagelist->tests;
                $sql = "SELECT GROUP_CONCAT(testName) FROM tbl_testname WHERE testname_id IN ($tet)";             
                $Connection = Yii::$app->labdb;
                $command = $Connection->createCommand($sql);
                $row = $command->queryOne();    
                $tests = $row['GROUP_CONCAT(testName)'];                
            } else {
                $rate = "";
                $tests = "";         
            }
        } else {
            $rate = "Error getting rate";
            $tests = "Error getting tests";
        }
        return Json::encode([
            'rate'=>$rate,
            'tests'=>$tests,
            'ids'=>$tet,
        ]);
    }
}
