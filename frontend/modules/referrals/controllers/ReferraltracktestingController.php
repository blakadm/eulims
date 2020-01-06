<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referraltracktesting;
use common\models\referral\ReferraltracktestingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ReferralComponent;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;
/**
 * ReferraltracktestingController implements the CRUD actions for Referraltracktesting model.
 */
class ReferraltracktestingController extends Controller
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
     * Lists all Referraltracktesting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferraltracktestingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Referraltracktesting model.
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
     * Creates a new Referraltracktesting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($referralid,$receivingid)
    {
       // echo $receivingid;exit;
        $model = new Referraltracktesting();
        $refcomponent = new ReferralComponent();
        $courier=json_decode($refcomponent->getCourierdata());
        if ($model->load(Yii::$app->request->post())) {
            $model->testing_agency_id=Yii::$app->user->identity->profile->rstl_id;
            $model->referral_id=$referralid;
            $model->date_created=date('Y-m-d H:i:s');
            $model->receiving_agency_id=$receivingid;
            
            $testingData = Json::encode(['data'=>$model]);
            //$testingUrl ='https://eulimsapi.onelab.ph/api/web/referral/referraltracktestings/insertdata';
            $testingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/referraltracktestings/insertdata';

            $curlTesting = new Curl();
            $testingResponse = $curlTesting->setRequestBody($testingData)
            ->setHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($testingData), 
            ])->post($testingUrl);
           
            if($testingResponse == 1){
                    $stat=json_decode($refcomponent->getCheckstatus($referralid,3));
                    if($stat == 0){
                        $accepted=['referralid'=>$referralid,'statusid'=>3];
                        $acceptedData = Json::encode(['data'=>$accepted]);
                        //$acceptedUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                        $acceptedUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                        $curlTesting = new Curl();
                        $acceptedResponse = $curlTesting->setRequestBody($acceptedData)
                        ->setHeaders([
                                'Content-Type' => 'application/json',
                                'Content-Length' => strlen($acceptedData), 
                        ])->post($acceptedUrl);
                    }
            
                   if($model->analysis_started <> ""){
                        $stat1=json_decode($refcomponent->getCheckstatus($referralid,4));
                        if($stat1 == 0){
                            $ongoing=['referralid'=>$referralid,'statusid'=>4];
                            $ongoingData = Json::encode(['data'=>$ongoing]);
                            //$ongoingUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                            $ongoingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                            $curlTesting = new Curl();
                            $ongoingResponse = $curlTesting->setRequestBody($ongoingData)
                            ->setHeaders([
                                    'Content-Type' => 'application/json',
                                    'Content-Length' => strlen($ongoingData), 
                            ])->post($ongoingUrl);
                        }
                    }
                    if($model->analysis_completed <> ""){
                        $stat2=json_decode($refcomponent->getCheckstatus($referralid,5));
                        if($stat2 == 0){
                            $completed=['referralid'=>$referralid,'statusid'=>5];
                            
                            $completedData = Json::encode(['data'=>$completed]);
                            //$completedUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                            $completedUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                            $curlTesting = new Curl();
                            $completedResponse = $curlTesting->setRequestBody($completedData)
                            ->setHeaders([
                                    'Content-Type' => 'application/json',
                                    'Content-Length' => strlen($completedData), 
                            ])->post($completedUrl);
                        }
                    } 

                    ///////////////
                    Yii::$app->session->setFlash('success', 'Track Testing Successfully Created!');
                    return $this->redirect(['/referrals/referral/viewreferral', 'id' => $referralid]);
            }else{
                    return "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp;$testingResponse!</div>";
            } 
          
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'courier'=>$courier
        ]);
    }

    /**
     * Updates an existing Referraltracktesting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$refid)
    {
        $model= new Referraltracktesting();
        $refcomponent = new ReferralComponent();
        $courier=json_decode($refcomponent->getCourierdata());
        $referralreceivingDetails = json_decode($refcomponent->getTracktestingdata($id));
        if($referralreceivingDetails){
            $model->date_received_courier=$referralreceivingDetails->date_received_courier;
            $model->analysis_started=$referralreceivingDetails->analysis_started;
            $model->analysis_completed=$referralreceivingDetails->analysis_completed;
            $model->cal_specimen_send_date=$referralreceivingDetails->cal_specimen_send_date;       
            $model->courier_id=$referralreceivingDetails->courier_id;
            $model->referraltracktesting_id=$referralreceivingDetails->referraltracktesting_id;
        }
            
        if ($model->load(Yii::$app->request->post())) {
            $testingData = Json::encode(['data'=>$model]);
            //$testingUrl ='https://eulimsapi.onelab.ph/api/web/referral/referraltracktestings/updatedata';
            $testingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/referraltracktestings/updatedata';

            $curlTesting = new Curl();
            $testingResponse = $curlTesting->setRequestBody($testingData)
            ->setHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($testingData),
            ])->post($testingUrl);

            if($testingResponse == 1){
                if($model->analysis_started <> ""){
                    $stat1=json_decode($refcomponent->getCheckstatus($refid,4));
                    if($stat1 == 0){
                        $ongoing=['referralid'=>$refid,'statusid'=>4];
                        $ongoingData = Json::encode(['data'=>$ongoing]);
                        //$ongoingUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                        $ongoingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                        $curlTesting = new Curl();
                        $ongoingResponse = $curlTesting->setRequestBody($ongoingData)
                        ->setHeaders([
                                'Content-Type' => 'application/json',
                                'Content-Length' => strlen($ongoingData), 
                        ])->post($ongoingUrl);
                    }
                }
                if($model->analysis_completed <> ""){
                    $stat2=json_decode($refcomponent->getCheckstatus($refid,5));
                    if($stat2 == 0){
                        $completed=['referralid'=>$refid,'statusid'=>5];

                        $completedData = Json::encode(['data'=>$completed]);
                        //$completedUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                        $completedUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                        $curlTesting = new Curl();
                        $completedResponse = $curlTesting->setRequestBody($completedData)
                        ->setHeaders([
                                'Content-Type' => 'application/json',
                                'Content-Length' => strlen($completedData), 
                        ])->post($completedUrl);
                    }
                }     
                Yii::$app->session->setFlash('success', 'Track Testing Successfully updated!');
                return $this->redirect(['/referrals/referral/viewreferral', 'id' => $refid]);
            }else{
                     return "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp;Walay data bes!</div>";
            } 
        }
        
        return $this->renderAjax('update', [
            'model' => $model,
            'courier'=>$courier
        ]);
    }

    /**
     * Deletes an existing Referraltracktesting model.
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
     * Finds the Referraltracktesting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Referraltracktesting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Referraltracktesting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
