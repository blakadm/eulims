<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\Op;
use common\models\finance\Paymentitem;
use common\models\finance\Collection;
use yii\data\ActiveDataProvider;
use frontend\modules\finance\components\models\OpSearchNoneLab;
use common\models\finance\OpSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\Query;

use frontend\modules\finance\components\models\Ext_Request as Request;
use yii\helpers\Json;
use common\components\Functions;
use frontend\modules\finance\components\models\Model;


class AccountingController extends Controller
{
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
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionOp()
    {
        $model =new Op();
        $searchModel = new OpSearchNoneLab();
        $Op_Query = Op::find()->where(['>', 'collectiontype_id',2])->andWhere(['rstl_id'=>Yii::$app->user->identity->profile->rstl_id]);;
        $dataProvider = new ActiveDataProvider([
                'query' => $Op_Query,
                'sort'=> ['defaultOrder' => ['transactionnum'=>SORT_DESC]],
                'pagination' => [
                    'pageSize' => 10,
                ],
        ]);
        return $this->render('op/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    public function actionOpLab()
    {
        $model =new Op();
        $searchModel = new OpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;
        return $this->render('op_lab/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    public function actionCreateOplab()
    {
        $model = new Op();
        $paymentitem = new Paymentitem();
  
        $searchModel = new OpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;
        
        if ($model->load(Yii::$app->request->post())) {
             $transaction = Yii::$app->financedb->beginTransaction();
             $session = Yii::$app->session;
             try  {
                $request_ids=$model->RequestIds;
                $str_request = explode(',', $request_ids);
                $arr_length = count($str_request); 
                $model->total_amount=0;
                $total_amount=0;
                $model->rstl_id=$GLOBALS['rstl_id'];
                $model->transactionnum= $this->Gettransactionnum();
                $model->payment_status_id=1; //unpaid
                if ($model->payment_mode_id == 6){
                    $model->on_account=1;
                }else{
                    $model->on_account=0;
                }

                $model->save();
                //Saving for Paymentitem
                for($i=0;$i<$arr_length;$i++){
                    $request =$this->findRequest($str_request[$i]);
                    $paymentitem = new Paymentitem();
                    $paymentitem->rstl_id =$GLOBALS['rstl_id'];
                    $paymentitem->request_id = $str_request[$i];
                    $paymentitem->orderofpayment_id = $model->orderofpayment_id;
                    $paymentitem->details =$request->request_ref_num;
                    $total=$request->total;
                    $amount=$request->getBalance($str_request[$i],$total);
                    $total_amount+=$amount;
                    $paymentitem->amount = $amount;
                    $paymentitem->request_type_id =$request->request_type_id;
                    $paymentitem->status=1;//Unpaid
                    $paymentitem->save(false); 
                    
                }
                //----------------------//
                $transaction->commit();
                //$this->postRequest($request_ids);
                $this->updateTotalOP($model->orderofpayment_id, $total_amount);
                $session->set('savepopup',"executed");
                 return $this->redirect(['/finance/op']); 
                   
                } catch (Exception $e) {
                    $transaction->rollBack();
                   $session->set('errorpopup',"executed");
                   return $this->redirect(['finance/accounting/op-lab']);
                }
                //-------------------------------------------------------------//
        } 
        $model->order_date=date('Y-m-d');
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('op_lab/create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('op_lab/create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
    }
    //-----------------Order of Payment None Lab
     public function actionCreateOp()
    {
        $model = new Op();
        $searchModel = new OpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;
      
         if ($model->load(Yii::$app->request->post())) {
             $transaction = Yii::$app->financedb->beginTransaction();
             $session = Yii::$app->session;
             try  {
                        $model->RequestIds='0';
                        $model->rstl_id= Yii::$app->user->identity->profile->rstl_id;//$GLOBALS['rstl_id'];
                        $model->transactionnum= $this->Gettransactionnum();
                        $model->payment_status_id=1; //unpaid
                        if ($model->payment_mode_id == 6){
                            $model->on_account=1;
                        }else{
                            $model->on_account=0;
                        }
                        $model->save();
                        $transaction->commit();
                      return $this->redirect(['/finance/accounting/view-op', 'id' => $model->orderofpayment_id]);                        
                    
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
              //-------------------------------------------------------------//
        } 
        $model->order_date=date('Y-m-d');
		$model->collectiontype_id=3; //SETUP
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('op/create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('op/create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
    }
    
     public function actionAddCollection($opid)
    {
        $op_model=$this->findModel($opid);
        $paymentitem = [new Paymentitem()];
        $op_amount=$op_model->total_amount;
         if ($op_model->load(Yii::$app->request->post())) {
            Model::loadMultiple($paymentitem, Yii::$app->request->post());
              
            $paymentitem = Model::createMultiple(Paymentitem::classname());
            
            $valid = Model::validateMultiple($paymentitem);
            
           // if ($valid) {
                $transaction = \Yii::$app->financedb->beginTransaction();
                try {
                    $posts=Yii::$app->request->post();
                
                     $total=0;
                       $posts=$posts["Paymentitem"];
                        foreach ($posts as $post) {
                            $paymentitems = new Paymentitem();
                            $paymentitems->rstl_id =$GLOBALS['rstl_id'];
                            $paymentitems->request_id = 0;
                            $paymentitems->orderofpayment_id = $opid;
                            $paymentitems->details=$post['details'];
                            $paymentitems->amount=$post['amount'];
                            $paymentitems->status=1; //Unpaid
                            $paymentitems->save(false);
                            $total+=$post['amount'];
                        }
                      $total_op_amount=$op_amount+$total;
                      $this->updateTotalOP($opid, $total_op_amount);
                  //  }
                    //if ($flag) {
                       $transaction->commit();
                       Yii::$app->session->setFlash('success','Successfully Saved');
                       return $this->redirect(['/finance/accounting/view-op', 'id' => $opid]);
                   // }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('danger','Transaction failed');
                    $transaction->rollBack();
                }
            //}
       }
       else{
           $op_model->orderofpayment_id=$opid;
           return $this->renderAjax('op/_form_paymentitem', [
            'op_model' => $op_model,
            'paymentitem' => (empty($paymentitem)) ? [new Paymentitem()] : $paymentitem
         ]);
       } 
    }
    //--------------------------------------------------------------------------
    public function actionViewOplab($id)
    { 
         $paymentitem_Query = Paymentitem::find()->where(['orderofpayment_id' => $id]);
         $paymentitemDataProvider = new ActiveDataProvider([
                'query' => $paymentitem_Query,
                'pagination' => [
                    'pageSize' => 10,
                ],
        ]);
         
         return $this->render('op_lab/view', [
            'model' => $this->findModel($id),
            'paymentitemDataProvider' => $paymentitemDataProvider,
        ]);

    }
    
    public function actionViewOp($id)
    { 
         $paymentitem_Query = Paymentitem::find()->where(['orderofpayment_id' => $id]);
         $paymentitemDataProvider = new ActiveDataProvider([
                'query' => $paymentitem_Query,
                'pagination' => [
                    'pageSize' => 10,
                ],
        ]);
         
         return $this->render('op/view', [
            'model' => $this->findModel($id),
            'paymentitemDataProvider' => $paymentitemDataProvider,
        ]);

    }
    
    public function actionUpdateOplab($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['op_lab/view', 'id' => $model->orderofpayment_id]);
        } else {
            return $this->render('op_lab/update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionGetlistrequest($id)
    {
         $model= new Request();
        $query = Request::find()->where(['customer_id' => $id,'posted' => 0])->andWhere(['not', ['request_ref_num' => null]]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       // $dataProvider->pagination->pageSize=3;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('op_lab/_request', ['dataProvider'=>$dataProvider,'model'=>$model]);
        }
        else{
            return $this->render('op_lab/_request', ['dataProvider'=>$dataProvider,'model'=>$model]);
        }

    }
    
     public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['op_lab/index']);
    }

    /**
     * Finds the Op model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Op the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Op::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findRequest($requestId)
    {
        if (($model = Request::findOne($requestId)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     public function Gettransactionnum(){
          $lastyear=(new Query)
            ->select('MAX(transactionnum) AS lastnumber')
            ->from('eulims_finance.tbl_orderofpayment')
            ->one();
          $lastyear=substr($lastyear["lastnumber"],0,4);
          $year=date('Y');
          $year_month = date('Y-m');
          $last_trans_num=(new Query)
            ->select(['count(transactionnum)+ 1 AS lastnumber'])
            ->from('eulims_finance.tbl_orderofpayment')
            ->one();
          $str_trans_num=0;
          if($last_trans_num != ''){
              if($lastyear < $year){
                 $str_trans_num='0001'; 
              }
              else if($lastyear == $year){
                  $str_trans_num=str_pad($last_trans_num["lastnumber"], 4, "0", STR_PAD_LEFT);
              }
              
          }
          else{
               $str_trans_num='0001';
          }
        
         $next_transnumber=$year_month."-".$str_trans_num;
         return $next_transnumber;
        
     }
     
     public function actionCalculateTotal($id) {
        $total = 0;
        if($id == '' ){
            echo $total;
        }
        else{
            $str_total = explode(',', $id);
            $arr_length = count($str_total); 
            for($i=0;$i<$arr_length;$i++){
                 $request =$this->findRequest($str_total[$i]);
                 $total+=$request->total;
            }
            echo $total;
        }
     }
     
     public function getCollectionname($collectionid) {
         $collection_name=(new Query)
            ->select('natureofcollection')
            ->from('eulims_finance.tbl_collectiontype')
            ->where(['collectiontype_id' => $collectionid])
            ->one();
         return $collection_name;
     }
     
       // updating request as posted upon saving order of payment
     public function postRequest($reqID){
         $str_total = explode(',', $reqID);
          $arr_length = count($str_total); 
            for($i=0;$i<$arr_length;$i++){
               Yii::$app->labdb->createCommand()
             ->update('tbl_request', ['posted' => 1], 'request_id= '.$str_total[$i])
             ->execute(); 
            }
     }
     
     public function updateTotalOP($id,$total){
        
        Yii::$app->financedb->createCommand()
      ->update('tbl_orderofpayment', ['total_amount' => $total], 'orderofpayment_id= '.$id)
      ->execute(); 
            
     }
     
      public function actionCheckCustomerWallet($customerid) {
         $wallet=(new Query)
            ->select('balance')
            ->from('eulims_finance.tbl_customerwallet')
            ->where(['customer_id' => $customerid])
            ->one();
         echo $wallet["balance"];
     }
     /*public function actionListpaymentmode($customerid){
         $func=new Functions();
         $paymentlist=$func->GetPaymentModeList($customerid);
         return $paymentlist;
     }*/
    
      public function actionListpaymentmode() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $func=new Functions();
            $list = $func->GetPaymentModeList($id);
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $paymentlist) {
                    $out[] = ['id' => $paymentlist['payment_mode_id'], 'name' => $paymentlist['payment_mode']];
                    if ($i == 0) {
                        $selected = $paymentlist['payment_mode_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
    
    public function actionUpdateamount(){
        if(Yii::$app->request->post('hasEditable'))
        {
            $id= Yii::$app->request->post('editableKey');
            $paymentitem= Paymentitem::findOne($id);
            $op_id=$paymentitem->orderofpayment_id;
            $out= Json::encode(['output'=> '','message'=> '']);
            $post=[];
            $posted=current($_POST['Paymentitem']);
            $post['Paymentitem']=$posted;
            if($paymentitem->load($post))
            {
                $paymentitem->save();
            }
            $sum = Paymentitem::find()->where(['orderofpayment_id' => $op_id])
                 ->andWhere(['status' => 1])
                 ->sum('amount');
            $this->updateTotalOP($op_id, $sum);
            echo $out;
            return;
        }
    }
}
