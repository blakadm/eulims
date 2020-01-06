<?php

/*
 * Project Name: eulims_ * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * 06 7, 18 , 4:05:19 PM * 
 * Module: AjaxController * 
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\lab\Discount;
use common\models\lab\Businessnature;
use common\models\finance\Client;
use common\models\lab\Customer;
use common\models\lab\Request;
use common\models\finance\PostedOp;
use frontend\modules\finance\components\epayment\ePayment;
use common\models\finance\Op;
use common\models\system\ApiSettings;
use linslin\yii2\curl;

/**
 * Description of AjaxController
 *
 * @author OneLab
 */
class AjaxController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'index'  => ['GET'],
                    'view'   => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET', 'PUT', 'POST'],
                    'delete' => ['POST', 'DELETE'],
                ],
            ],
        ];
    }
    public function actionTestcurl($id){
        /*$apiUrl="https://api3.onelab.ph/lab/get-lab?id=11";
        $curl = new curl\Curl();
        $response = $curl->get($apiUrl);
        return $response;
         * 
         */
        $func=new \common\components\Functions();
        return $func->GetAccessToken(11);
    }
     public function actionSetwallet($customer_id,$amount,$source,$transactiontype){
        //$myvar = setTransaction($customer_id,$amount,$source,$transactiontype);
        return 200;
    }
    
    public function actionPostonlinepayment(){
        $post= \Yii::$app->request->post();
        $op_id=$post['op_id'];
        $PostedOp=new PostedOp();
        $ePayment=new ePayment();
        $result=[
            'status'=>'error',
            'description'=>'No Internet'
        ];
        $result=$ePayment->PostOnlinePayment($op_id);
        $response=json_decode($result);
        if($response->status=='error'){
            $posted=0;
            $success=false;
        }else{
            $posted=1;
            $PostedOp->orderofpayment_id=$op_id;
            $PostedOp->posted_datetime=date("Y-m-d H:i:s");
            $PostedOp->user_id= Yii::$app->user->id;
            $PostedOp->posted=$posted;
            $PostedOp->description=$response->description;
            $success=$PostedOp->save();
            if($success){
                $Op= Op::findOne($op_id);
                $Op->payment_mode_id=5;//Online Payment
                $Op->save(false);
            }
        } 
        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return $response;
    }
    public function actionGetdiscount(){
        $post= \Yii::$app->request->post();
        $id=$post['discountid'];
        $discount= Discount::find()->where(['discount_id'=>$id])->one();
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return $discount;
    }
    public function actionGetcustomer(){
        $post= \Yii::$app->request->post();
        $id=$post['discountid'];

        $request = Request::find()->where(['request_ref_num'=>$id])->one();
        $customer= Customer::find()->where(['customer_id'=>2])->one();
        $nob = Businessnature::find()->where(['business_nature_id'=>$customer->business_nature_id])->one();

        $customer_name = $customer->customer_name;

        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

        return ["customer_name"=> $customer_name, "nob"=>$nob->nature];
    }
    public function actionGetdiscountreferral(){
        $id=(int) \Yii::$app->request->get('discountid');
        $apiUrl='https://eulimsapi.onelab.ph/api/web/referral/listdatas/discountbyid?discount_id='.$id;
        //$apiUrl='http://localhost/eulimsapi.onelab.ph/api/web/referral/listdatas/discountbyid?discount_id='.$id;
        $curl = new curl\Curl();
        $discount = $curl->get($apiUrl);
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return json_decode($discount);
    }
    public function actionGetcustomerhead($id){
        \Yii::$app->response->format =\yii\web\Response::FORMAT_JSON;
        $Customers=Customer::find()->where(['customer_id'=>$id])->one();
        return $Customers;
    }
    public function actionTogglemenu(){
        $session = Yii::$app->session;
        $hideMenu= $session->get("hideMenu");
        if(!isset($hideMenu)){
           $hideMenu=false; 
        }
        $b=!$hideMenu;
        $session->set('hideMenu',$b);
        //return $hideMenu;
        echo $session->get("hideMenu");
    }
    public function actionGetaccountnumber(){
        $post= Yii::$app->request->post();
        $id=(int)$post['customer_id'];
        $AccNumber="<no accountnumber>";
        $Client= Client::find()->where(['customer_id'=>$id])->one();
        if($Client){
            $AccNumber=$Client->account_number;
        }else{
            $AccNumber="<no account number>";
        }
        return $AccNumber;
    }
    public function actionGetsoabalance(){
        $Connection=Yii::$app->financedb;
        $post= Yii::$app->request->post();
        $id=(int)$post['customer_id'];
        $Proc="CALL spGetSoaPreviousAccount(:mCustomerID)";
        $Command=$Connection->createCommand($Proc);
        $Command->bindValue(':mCustomerID',$id);
        $Row=$Command->queryOne();
        $Balance=(float)$Row['Balance'];
        return $Balance;
    }
}
