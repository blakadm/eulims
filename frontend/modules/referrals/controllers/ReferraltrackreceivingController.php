<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referraltrackreceiving;
use common\models\referral\ReferraltrackreceivingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use common\models\referral\Referral;
use common\components\ReferralComponent;
use linslin\yii2\curl\Curl;
use yii\helpers\Json;

/**
 * ReferraltrackreceivingController implements the CRUD actions for Referraltrackreceiving model.
 */
class ReferraltrackreceivingController extends Controller
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
     * Lists all Referraltrackreceiving models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferraltrackreceivingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Referraltrackreceiving model.
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
     * Creates a new Referraltrackreceiving model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate($referralid,$testingid,$receiveddate)
    public function actionCreate($referralid)
    {
        
        $model = new Referraltrackreceiving();
        $refcomponent = new ReferralComponent();
        $courier=json_decode($refcomponent->getCourierdata());
        $rstlId=Yii::$app->user->identity->profile->rstl_id;
        $referral=json_decode($refcomponent->getReferralOne($referralid, $rstlId));
       
        if ($model->load(Yii::$app->request->post())) {
            
            $model->referral_id=$referralid;
            $model->date_created=date('Y-m-d H:i:s');
            $model->testing_agency_id=$referral->testing_agency_id;
            $model->receiving_agency_id=$rstlId;
            $model->sample_received_date=$referral->sample_received_date;
            
            $receivingData = Json::encode(['data'=>$model]);
            $testingUrl ='https://eulimsapi.onelab.ph/api/web/referral/referraltrackreceivings/insertdata';
            //$testingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/referraltrackreceivings/insertdata';

            $curlReceiving = new Curl();
            $receivingResponse = $curlReceiving->setRequestBody($receivingData)
            ->setHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($receivingData), 
            ])->post($testingUrl);

            if($receivingResponse == 1){
                    $stat=json_decode($refcomponent->getCheckstatus($referralid,2));
                    if($stat == 0){
                        $shipped=['referralid'=>$referralid,'statusid'=>2];
                        $shippedData = Json::encode(['data'=>$shipped]);
                        $shippedUrl ='https://eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';
                        //$shippedUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/statuslogs/insertdata';

                        $curlTesting = new Curl();
                        $shippedResponse = $curlTesting->setRequestBody($shippedData)
                        ->setHeaders([
                                'Content-Type' => 'application/json',
                                'Content-Length' => strlen($shippedData), 
                        ])->post($shippedUrl);
                    }
                    Yii::$app->session->setFlash('success', 'Track Receiving Successfully Created!');
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
     * Updates an existing Referraltrackreceiving model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$refid)
    {
        $model = new Referraltrackreceiving();
        $refcomponent = new ReferralComponent();
        $courier=json_decode($refcomponent->getCourierdata());
        $referralreceivingDetails = json_decode($refcomponent->getTrackreceiving($refid));

        if($referralreceivingDetails){
            $model->shipping_date=$referralreceivingDetails->shipping_date;
            $model->cal_specimen_received_date=$referralreceivingDetails->cal_specimen_received_date;       
            $model->courier_id=$referralreceivingDetails->courier_id;
            $model->referral_id=$referralreceivingDetails->referral_id;
            
        }
        if ($model->load(Yii::$app->request->post())) {
            $receivingData = Json::encode(['data'=>$model]);
            $receivingUrl ='https://eulimsapi.onelab.ph/api/web/referral/referraltrackreceivings/updatedata';
            //$receivingUrl ='http://localhost/eulimsapi.onelab.ph/api/web/referral/referraltrackreceivings/updatedata';

            $curlReceiving = new Curl();
            $receivingResponse = $curlReceiving->setRequestBody($receivingData)
            ->setHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($receivingData),
            ])->post($receivingUrl);

            if($receivingResponse == 1){
                    Yii::$app->session->setFlash('success', 'Track Receiving Successfully updated!');
                    return $this->redirect(['/referrals/referral/viewreferral', 'id' => $refid]);
            }else{
                return "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp;$receivingResponse!</div>";
            } 
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'courier'=>$courier
        ]);
    }

    /**
     * Deletes an existing Referraltrackreceiving model.
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
     * Finds the Referraltrackreceiving model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Referraltrackreceiving the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Referraltrackreceiving::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
