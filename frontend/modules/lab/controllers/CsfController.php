<?php

namespace frontend\modules\lab\controllers;

use common\models\lab\Sample;
use common\models\lab\Analysis;
use frontend\modules\lab\components\Printing;
use Yii;
use common\models\lab\Csf;
use common\models\lab\CsfSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\lab\Request;
use common\models\lab\Customer;
use yii\web\Response;

/**
 * CsfController implements the CRUD actions for Csf model.
 */
class CsfController extends Controller
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
     * Lists all Csf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Csf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCsf()
    {  
        $csf = Csf::find()->all();
        return $this->asJson([$csf]);             
    }

    public function actionReports()
    {
        $model = new Csf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('reports', [
            'model' => $model,
        ]);
    }

    public function actionMonthlyreport()
    {
        $model = new Csf();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('day', [
            'model' => $model,
        ]);
    }

    public function actionCustomer()
    {
        $model = new Csf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('day', [
            'model' => $model,
        ]);
    }

    public function actionResult()
    {
        $model = new Csf();

        $searchModel = new CsfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('results', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }

    public function actionCsireport()
    {
        $searchModel = new CsfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('csireport', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCsi()
    {
        $searchModel = new CsfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $csf = Csf::find()->all();
        $count = count($csf);
        return $this->render('csi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'csf'=>$csf,
            'count'=>$count
        ]);
    }

    public function actionResultmodal($id)
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('results_modal', [
                    'model' => $this->findModel($id),
                ]);
        }
    }

    /**
     * Displays a single Csf model.
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
     * Creates a new Csf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Csf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->service=0;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionGetcustomer()
	{
        
        $ref_num = $_GET['ref_num'];

         if(isset($_GET['ref_num'])){
            $id = $_GET['ref_num'];

            $request = Request::find()->where(['request_ref_num'=>$id])->all();
            $customer = Customer::find()->where(['customer_id'=>$request->customer_id])->all();
            $customer_name = $customer->customer_name;
        } else {
            $customer_name = "Error getting customer name";
         }
        return Json::encode([
            'custo'=>$title,
        ]);

	
     }

    /**
     * Updates an existing Csf model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Csf model.
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
     * Finds the Csf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Csf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Csf::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    

     public function actionPrintreport(){
      $Printing=new Printing();
      $Printing->PrintReportcsi(20);
  }

  public function actionPrintmonthly(){
    $Printing=new Printing();
    $Printing->PrintReportmonthly(20);
}

public function actionPrintcustomer(){
    $Printing=new Printing();
    $Printing->PrintReportdaily(20);
}

    

}
