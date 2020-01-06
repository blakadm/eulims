<?php
namespace frontend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\system\Profile;
use kartik\mpdf\Pdf;
use yii\helpers\Json;
use common\models\lab\CustomerMigration;
use common\models\lab\RequestMigration;
use common\models\lab\SampleMigration;
use common\models\lab\AnalysisMigration;
use common\models\lab\Customer;
use common\models\finance\CheckMigration;
use common\models\finance\ReceiptMigration;
use common\models\finance\PaymentitemMigration;
use common\models\finance\OrderofpaymentMigration;
use common\models\finance\DepositMigration;
use common\models\finance\CollectionMigration;

use common\models\finance\Restore_op;
use common\models\finance\Restore_paymentitem;
use common\models\finance\Restore_deposit;
use common\models\finance\Restore_receipt;
use common\models\finance\Restore_check;
use common\models\finance\Deposit;

use common\models\rental\Application;
use common\models\rental\Billing;
//use common\models\rental\Customer;
use common\models\rental\Item_type;
use common\models\rental\Item;
//use common\models\rental\Profile;

/* @property Customer $customer */
class ApiController extends ActiveController
{
    public $modelClass = 'common\models\system\Profile';
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function($action) {
            return new \yii\data\ActiveDataProvider([
                'query' => Profile::find()->where(['user_id' => Yii::$app->user->id]),
            ]);
        };
        return $actions;
    }

   public function actionSync_customer(){ 
     
            $post = Yii::$app->request->post();
            $toreturn = false;
            if(isset($post)){
                $myvar = Json::decode($post['data']);
                //$toreturn = $myvar['email']; //use this email to track if the user record already exist in ulimsportal or in any other cloud storage
                //lets check the record if it exist
                $model = Customer::find()->where(['email'=>$myvar['email']])->one();
                if($model){
                    //the customer already exist
                    // email already exist proceed to confirmation
                    $toreturn=2; //this mean that the record's email already exist and that the client accessing the API will know what to do -> compare the records for confirmation
                }else{
                   
                    // save the customer info
                    $newmodel = new Customer;
                    $newmodel->rstl_id = $myvar['rstl_id'];
                    $newmodel->customer_name = $myvar['customer_name'];
                    $newmodel->classification_id = $myvar['classification_id'];
                    $newmodel->latitude = $myvar['latitude'];
                    $newmodel->longitude = $myvar['longitude'];
                    $newmodel->head = $myvar['head'];
                    $newmodel->barangay_id = $myvar['barangay_id'];
                    $newmodel->address = $myvar['address'];
                    $newmodel->tel = $myvar['tel'];
                    $newmodel->fax = $myvar['fax'];
                    $newmodel->email = $myvar['email'];
                    $newmodel->customer_type_id = $myvar['customer_type_id'];
                    $newmodel->business_nature_id = $myvar['business_nature_id'];
                    $newmodel->industrytype_id = $myvar['industrytype_id'];
                    $newmodel->is_sync_up = $myvar['is_sync_up'];
                    $newmodel->is_updated = $myvar['is_updated'];
                    $newmodel->is_deleted = $myvar['is_deleted'];
                    if($newmodel->save()){
                        $newmodel->customer_code = $newmodel->rstl_id."-".$newmodel->customer_id;
                        $newmodel->save();
                        $toreturn = $newmodel->customer_code;
                    }
                }
            }
             
            return $this->asJson(
                        $toreturn
                    );
        
    }


    public function actionConfirm($email){
        $model = Customer::find()->where(['email'=>$email])->one();

        return $this->asJson(
                        $model
                    );
    }
}
