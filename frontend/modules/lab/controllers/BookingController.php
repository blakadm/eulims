<?php

namespace frontend\modules\lab\controllers;

use Yii;
use common\models\lab\Booking;
use common\models\lab\BookingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\inventory\components\_class\Schedule;
use common\models\lab\Customer;
use yii\db\Query;
/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller
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
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Booking model.
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
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Booking();
        $model->rstl_id= Yii::$app->user->identity->profile->rstl_id;
        if ($model->load(Yii::$app->request->post())) {
            $model->booking_reference=$this->Createreferencenum();
            $model->scheduled_date;
            $model->description;echo "<br>";
            $model->rstl_id;
            $model->date_created=date("Y-m-d");
            if(isset($_POST['qty_sample'])){
                $quantity = (int) $_POST['qty_sample'];
            } else {
                $quantity = 1;
            }
            $model->qty_sample=$quantity;
            $model->customer_id;
            
            $model->save();
            Yii::$app->session->setFlash('success','Successfully Saved');
            return $this->redirect(['index']);
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }
    
    public function actionJsoncalendar($start=NULL,$end=NULL,$_=NULL,$id){

    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $events = array();
    
    //as of now get all the schedules
    $schedules = Booking::find()->where(['rstl_id'=>$id,'booking_status'=>0])->all(); 
 
    foreach ($schedules AS $schedule){
        $customer_id= $schedule->customer_id;
        $customer =Customer::find()->where(['customer_id'=>$customer_id])->one();
        $Event= new Schedule();
        $Event->id = $schedule->booking_id;
        $Event->title =$customer->customer_name.": ".$schedule->description."\n Sample Qty:".$schedule->qty_sample;

        $Event->start =$schedule->scheduled_date;

        $date = $schedule->scheduled_date;
        $date1 = str_replace('-', '/', $date);
        $newdate = date('Y-m-d',strtotime($date1 . "+1 days"));
        $Event->end  = $newdate;
        $events[] = $Event;
    }

    return $events;
  }

    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Successfully Updated');
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success','Successfully Removed!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function Createreferencenum(){
          $lastid=(new Query)
            ->select('MAX(booking_id) AS lastnumber')
            ->from('eulims_lab.tbl_booking')
            ->one();
          $lastnum=$lastid["lastnumber"]+1;
          $rstl_id=Yii::$app->user->identity->profile->rstl_id;
           
          $string = Yii::$app->security->generateRandomString(9);
        
          $next_refnumber=$rstl_id.$string.$lastnum;//rstl_id+random strings+(lastid+1)
          return $next_refnumber;
     }
     
     public function actionManage()
     {
        $model = new Booking();
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;
        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
         
     }
}
