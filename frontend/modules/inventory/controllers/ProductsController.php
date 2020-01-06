<?php

namespace frontend\modules\inventory\controllers;

use Yii;
use common\models\inventory\Products;
use common\models\inventory\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\system\Profile;
use yii\web\UploadedFile;
use common\models\inventory\Suppliers;
use common\models\inventory\SuppliersSearch;
use yii\data\ActiveDataProvider;
use common\models\inventory\Equipmentservice;
use frontend\modules\inventory\components\_class\Schedule;
/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerInventoryEntries = new \yii\data\ArrayDataProvider([
            'allModels' => $model->inventoryEntries,
        ]);
        $providerInventoryWithdrawaldetails = new \yii\data\ArrayDataProvider([
            'allModels' => $model->inventoryWithdrawaldetails,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerInventoryEntries' => $providerInventoryEntries,
            'providerInventoryWithdrawaldetails' => $providerInventoryWithdrawaldetails,
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Products();
        $model->rstl_id=Yii::$app->user->identity->profile->rstl_id;
        $user_id=Yii::$app->user->identity->profile->user_id;
        $model->created_by=$user_id;
        $model->qty_onhand=0;
        $model->producttype_id=1;
        if ($model->load(Yii::$app->request->post())) {
            // var_dump(Yii::$app->request->post()); exit;
            $ids="";
            if($model->suppliers_ids)
                $ids= implode(',',$model->suppliers_ids);

            $model->suppliers_ids=$ids;
            $filename=$model->product_name;
            $filename2=$model->product_name."2";
            
            $image1= UploadedFile::getInstance($model, 'Image1');
            $image2= UploadedFile::getInstance($model, 'Image2');

            $sds = UploadedFile::getInstance($model, 'sds');

            if (!empty($sds) && $sds !== 0) {                
                $sds->saveAs('uploads/products/' . $model->product_name.$model->product_id.'.'.$sds->extension);
                $model->sds =$model->product_name.$model->product_id.'.'.$sds->extension;
            }


            if(!empty($image1) && $image1->size !== 0) {
                $image1->saveAs('uploads/products/'.$model->product_code.$model->product_id.'1.'.$image1->extension);
                $model->Image1='uploads/products/'.$model->product_code.$model->product_id.'1.'.$image1->extension;
            }

            if(!empty($image2) && $image2->size !== 0) {
                $image2->saveAs('uploads/products/'.$model->product_code.$model->product_id.'2.'.$image2->extension);
                $model->Image2='uploads/products/'.$model->product_code.$model->product_id.'2.'.$image2->extension;
            }


            $model->save();
            
            Yii::$app->session->setFlash('success', 'Product Successfully Added!');
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $origimg1=$model->Image1;
        $origimg2=$model->Image2;

        if ($model->load(Yii::$app->request->post())) {

            $ids="";
            if($model->suppliers_ids)
                $ids= implode(',',$model->suppliers_ids);

            $model->suppliers_ids=$ids;
            $image1= UploadedFile::getInstance($model, 'Image1');
            // var_dump($model); exit;
            $image2= UploadedFile::getInstance($model, 'Image2');

             $sds = UploadedFile::getInstance($model, 'sds');

            if (!empty($sds) && $sds !== 0) {                
                $sds->saveAs('uploads/products/' . $model->product_name.$model->product_id.'.'.$sds->extension);
                $model->sds =$model->product_name.$model->product_id.'.'.$sds->extension;
            }

            
            if(!empty($image1) && $image1->size !== 0) {
                $image1->saveAs('uploads/products/'.$model->product_code.'1.'.$image1->extension);
                $model->Image1='uploads/products/'.$model->product_code.'1.'.$image1->extension;
             
            }else{
                $model->Image1=$origimg1;
            }

            if(!empty($image2) && $image2->size !== 0) {
                $image2->saveAs('uploads/products/'.$model->product_code.'2.'.$image2->extension);
                $model->Image2='uploads/products/'.$model->product_code.'2.'.$image2->extension;
            }else{
                $model->Image2=$origimg2;
            }

            if($model->save()){
                Yii::$app->session->setFlash('success', 'Product Successfully Updated!');
            }else{
                Yii::$app->session->setFlash('error', 'Product Failed to Update!');
            }
            
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }
    
    /**
     * 
     * Export Products information into PDF format.
     * @param integer $id
     * @return mixed
     */
    public function actionPdf($id) {
        $model = $this->findModel($id);
        $providerInventoryEntries = new \yii\data\ArrayDataProvider([
            'allModels' => $model->inventoryEntries,
        ]);
        $providerInventoryWithdrawaldetails = new \yii\data\ArrayDataProvider([
            'allModels' => $model->inventoryWithdrawaldetails,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerInventoryEntries' => $providerInventoryEntries,
            'providerInventoryWithdrawaldetails' => $providerInventoryWithdrawaldetails,
        ]);

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => \Yii::$app->name],
            'methods' => [ 
                'SetHeader' => [\Yii::$app->name],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    
    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for InventoryEntries
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddInventoryEntries()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('InventoryEntries');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formInventoryEntries', ['row' => $row]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for InventoryWithdrawaldetails
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddInventoryWithdrawaldetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('InventoryWithdrawaldetails');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formInventoryWithdrawaldetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionSupplier($ids) {
        $str= explode(',', $ids);
        $searchModel = new SuppliersSearch();
        $query = Suppliers::find()->where(['IN', 'suppliers_id', $str]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);
        
        return $this->renderAjax('_supplier', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewsds($id) {

        $model = $this->findModel($id);
        
        return $this->renderAjax('_sds', [
            'model' => $model,
        ]);
    }

    public function actionEquipment($varsearch=""){
        $dataProvider = new ActiveDataProvider([
            'query' =>Products::find()->where(['producttype_id'=>2]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if($varsearch){
            //  $dataProvider = new ActiveDataProvider([
            // 'query' =>Products::find()->where(['like', 'product_name', $varsearch])->where(['producttype_id'=>2]),
            // 'pagination' => [
            //     'pageSize' => 10,
            // ],
            //  ]);

             $query = Products::find();
             $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
             $query->andFilterWhere([
                'producttype_id' => 2,
            ]);
             $query->andFilterWhere(['like', 'product_name', $varsearch]);

        }

          return $this->render('equipment',['dataProvider'=>$dataProvider,'searchkey'=>$varsearch]);
    }

    public function actionOpensched($id){

        //retrieve the schedules using the $id

        $model = new Equipmentservice(); 
        $model->inventory_transactions_id=$id;

        if ($model->load(Yii::$app->request->post())) {

            $model->requested_by = Yii::$app->user->identity->profile->user_id; 
            if( $model->save()){
                Yii::$app->session->setFlash('success', "Successfully created a new Schedule.");
                return $this->redirect(['equipment']);
            }
        } 

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_schedule', [
                'model'=>$model,
                'id'=>$id
            ]);
        }
        else {
            return $this->render('_schedule', [
              'model'=>$model,
              'id'=>$id
            ]);
        }
    }

    public function actionJsoncalendar($start=NULL,$end=NULL,$_=NULL,$id){

    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $events = array();
    
    //as of now get all the schedules
    $schedules = Equipmentservice::find()->where(['inventory_transactions_id'=>$id])->all(); 
 
    foreach ($schedules AS $schedule){
        $Event= new Schedule();
        $Event->id = $schedule->equipmentservice_id;
        $Event->title =$schedule->servicetype->servicetype;

        $Event->start =$schedule->startdate;

        $date = $schedule->enddate;
        $date1 = str_replace('-', '/', $date);
        $newdate = date('Y-m-d',strtotime($date1 . "+1 days"));
        $Event->end = $newdate;
        $events[] = $Event;
    }

    return $events;
  }

  public function actionDayClickCalendarEvent()
{
 //save date
    echo "wow"; exit;
}

}

 