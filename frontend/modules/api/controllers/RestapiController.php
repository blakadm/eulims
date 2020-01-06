<?php

namespace frontend\modules\api\controllers;

use Yii;
use common\models\lab\Sample;
use common\models\lab\Analysis;
use common\models\lab\Workflow;
use common\models\lab\Request;
use common\models\lab\Tagging;
use common\models\lab\Procedure;
//use common\models\lab\Tagging;
use common\models\lab\Customer;
use common\models\lab\Customeraccount;
use common\models\lab\LogincForm;
use common\models\system\LoginForm;
use common\models\system\Profile;
use common\models\system\User;
use common\models\inventory\Products;
use common\models\inventory\InventoryEntries;
use common\models\inventory\Equipmentservice;
use common\models\inventory\InventoryWithdrawal;
use common\models\inventory\InventoryWithdrawaldetails;
use common\models\finance\CustomerWallet;
use common\models\finance\CustomerTransaction;
use common\models\lab\Booking;
use common\models\system\Rstl;
use common\models\auth\AuthAssignment;
use common\components\Functions;
use yii\web\UploadedFile;

use common\models\rental\Application;
use common\models\rental\Billing;
//use common\models\rental\Customer;
use common\models\rental\Item_type;
use common\models\rental\Item;
//use common\models\rental\Profile;


class RestapiController extends \yii\rest\Controller
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
            'except' => ['login', 'server'],
            // 'user'=> [\Yii::$app->customeraccount,\Yii::$app->user]
        ];

        return $behaviors;
    }

    protected function verbs(){
        return [
            'login' => ['POST'],
            'logout' => ['POST'],
            'user' => ['GET'],
            'samplecode' => ['GET'],
            'analysis' => ['GET'],
           // 'server' => ['GET'],
             'data' => ['GET'],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
            $model = new LoginForm();
            $my_var = \Yii::$app->request->post();
            $model->email = $my_var['email'];
            $model->password = $my_var['password'];
           
            if ($model->login()) {      
                $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
                /** @var Jwt $jwt */
                $jwt = \Yii::$app->jwt;
                $token = $jwt->getBuilder()
                    ->setIssuer('http://example.com')// Configures the issuer (iss claim)
                    ->setAudience('http://example.org')// Configures the audience (aud claim)
                    ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
                    ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
                    ->setExpiration(time() + 3600 * 24)// Configures the expiration time of the token (exp claim)
                    ->set('uid', \Yii::$app->user->identity->user_id)// Configures a new claim, called "uid"
                    //->set('username', \Yii::$app->user->identity->username)// Configures a new claim, called "uid"
                    ->sign($signer, $jwt->key)// creates a signature using [[Jwt::$key]]
                    ->getToken(); // Retrieves the generated token
    
                    $users = User::find()->where(['LIKE', 'email', $my_var['email']])->one();
                    $profile = Profile::find()->where(['user_id'=>$users->user_id])->one();
                    $role = AuthAssignment::find()->where(['user_id'=>$users->user_id])->one();
        
                    return $this->asJson([
                        'token' => (string)$token,
                        'user'=> (['email'=>$users->email,
                                    'firstName'=>$profile->firstname,
                                    'middleInitial' => $profile->middleinitial,
                                    'lastname' => $profile->lastname,
                                    'type' => $role->item_name,]),
                    ]);
                } else {
                    return $this->asJson([
                        'success' => false,
                        'message' => 'Email and Password didn\'t match',
                    ]);
                }
    }

    public function actionUser()
    {  
        $user_id =\Yii::$app->user->identity->profile->user_id;
        $users = User::find()->where(['LIKE', 'user_id', $user_id])->one();
        $profile = Profile::find()->where(['user_id'=>$user_id])->one();
        $role = AuthAssignment::find()->where(['user_id'=>$users->user_id])->one();
        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        /** @var Jwt $jwt */
        $jwt = \Yii::$app->jwt;
        $token = $jwt->getBuilder()
            ->setIssuer('http://example.com')// Configures the issuer (iss claim)
            ->setAudience('http://example.org')// Configures the audience (aud claim)
            ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
            ->setExpiration(time() + 3600 * 24)// Configures the expiration time of the token (exp claim)
            ->set('uid', \Yii::$app->user->identity->user_id)// Configures a new claim, called "uid"
            //->set('username', \Yii::$app->user->identity->username)// Configures a new claim, called "uid"
            ->sign($signer, $jwt->key)// creates a signature using [[Jwt::$key]]
            ->getToken(); // Retrieves the generated token
        return $this->asJson([
                'token' => (string)$token,
                'user'=> (['email'=>$users->email,
                'firstName'=>$profile->firstname,
                'middleInitial' => $profile->middleinitial,
                'lastname' => $profile->lastname,
                'type' => $role->item_name]),
                'user_id'=> $users->user_id
            ]);               
    }

    public function actionChangestatus()
    {  
     $year = date("Y");
     if (isset($_GET['samplecode'])) {
        //limit for this year only
        $samplecode = Sample::find()->select(['sample_id','sample_code'])
        ->where(['LIKE', 'tbl_sample.sample_code', $_GET['samplecode']])
        ->AndWhere(['LIKE', 'sample_year', $year])
        ->all();
        return $this->asJson(['sampleCodes'=>$samplecode]);            
        }
    }

    public function actionSamplecode()
    {  
     $year = date("Y");
     if (isset($_GET['samplecode'])) {
        //limit for this year only
        $samplecode = Sample::find()->select(['sample_id','sample_code'])
       // ->Where(['tbl_sample.sample_code'=>$_GET['samplecode']])
        ->where(['LIKE', 'tbl_sample.sample_code', $_GET['samplecode']])
        ->AndWhere(['LIKE', 'sample_year', $year])
        ->all();
        return $this->asJson(['sampleCodes'=>$samplecode]);            
        }
    }

    public function actionAnalysis()
    {  
        if (isset($_GET['id'])) {

            $code = $_GET['id'];
            $year = date("Y");

            $sample = Sample::find()->select(['sample_id','sample_code', 'samplename', 'description'])
            ->where(['tbl_sample.sample_code'=> $_GET['id']])
            ->AndWhere(['sample_year'=>$year])->one();
 
            $analysis = Analysis::find()->select(['tbl_analysis.analysis_id','tbl_analysis.testname', 'tbl_analysis.method', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_tagging', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            $tagging = Tagging::find()->select(['tbl_tagging.analysis_id', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_analysis', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            if ($sample){
                return $this->asJson(['sampleCode'=>$sample->sample_code, 
                'samples'=>$sample, 
                'tests'=>$analysis,
                'tagging'=>$tagging]);           
            }                    
        }
    }

    public function actionStartanalysis()
    {  
        if (isset($_GET['id'])) {

            $date = $_GET['date'];

            $analysis_id = $_GET['id'];
            $year = date("Y");

            $analysis = Analysis::find()->where(['analysis_id'=>$analysis_id])->one();

            $sample = Sample::find()->select(['sample_id','sample_code', 'samplename', 'description'])
            ->where(['tbl_sample.sample_id'=> $analysis->sample_id])
            ->AndWhere(['sample_year'=>$year])->one();

            $now = date("Y-m-d");
            $Connection= Yii::$app->labdb;
            $sql="UPDATE `tbl_tagging` SET `tagging_status_id`=1, `start_date`='$date' WHERE `analysis_id`=".$analysis_id;
            $Command=$Connection->createCommand($sql);
            $Command->execute();
      
            $analysis = Analysis::find()->select(['tbl_analysis.analysis_id','tbl_analysis.testname', 'tbl_analysis.method', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_tagging', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            $tagging = Tagging::find()->select(['tbl_tagging.analysis_id', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_analysis', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            if ($sample){
                return $this->asJson(['sampleCode'=>$sample->sample_code, 
                'samples'=>$sample, 
                'tests'=>$analysis,
                'tagging'=>$tagging]);           
            }                    
        }
    }

    public function actionCompletedanalysis()
    {  
        if (isset($_GET['id'])) {

            $analysis_id = $_GET['id'];
            $year = date("Y");
            $date = $_GET['date'];
            $analysis = Analysis::find()->where(['analysis_id'=>$analysis_id])->one();


            $sample = Sample::find()->select(['sample_id','sample_code', 'samplename', 'description'])
            ->where(['tbl_sample.sample_id'=> $analysis->sample_id])
            ->AndWhere(['sample_year'=>$year])->one();

            $now = date("Y-m-d");
            $Connection= Yii::$app->labdb;
            $sql="UPDATE `tbl_tagging` SET `tagging_status_id`=2, `end_date`='$date' WHERE `analysis_id`=".$analysis_id;
            $Command=$Connection->createCommand($sql);
            $Command->execute();

            //tracking
            $taggingcount= Tagging::find()
            ->leftJoin('tbl_analysis', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->leftJoin('tbl_sample', 'tbl_analysis.sample_id=tbl_sample.sample_id')    
            ->where(['tbl_tagging.tagging_status_id'=>2, 'tbl_sample.sample_id'=>$analysis->sample_id ])
            ->all();   

            if ($taggingcount){
                $counttag = count($taggingcount); 
             } 
              $sql="UPDATE `tbl_sample` SET `completed`='$counttag' WHERE `sample_id`=".$analysis->sample_id;
              $Command=$Connection->createCommand($sql);
              $Command->execute();                 
              $samplesq = Sample::find()->where(['sample_id' =>$analysis->sample_id])->one();             
              $samcount = $samplesq->completed;

              $sampletagged= Sample::find()
              ->leftJoin('tbl_analysis', 'tbl_sample.sample_id=tbl_analysis.sample_id')
              ->leftJoin('tbl_tagging', 'tbl_analysis.analysis_id=tbl_tagging.analysis_id') 
              ->leftJoin('tbl_request', 'tbl_request.request_id=tbl_analysis.request_id')    
              ->where(['tbl_tagging.tagging_status_id'=>2, 'tbl_request.request_id'=>$samplesq->request_id ])
              ->all();  

              $st = count($sampletagged);

              if ($samcount==$counttag){
                $sql="UPDATE `tbl_request` SET `completed`='$st' WHERE `request_id`=".$samplesq->request_id;
                $Command=$Connection->createCommand($sql);
                $Command->execute(); 
              }

              //end of tracking
      
            $analysis = Analysis::find()->select(['tbl_analysis.analysis_id','tbl_analysis.testname', 'tbl_analysis.method', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_tagging', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            $tagging = Tagging::find()->select(['tbl_tagging.analysis_id', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_analysis', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            if ($sample){
                return $this->asJson(['sampleCode'=>$sample->sample_code, 
                'samples'=>$sample, 
                'tests'=>$analysis,
                'tagging'=>$tagging]);           
            }                    
        }
    }

    public function actionUpdateanalysis()
    {  
        if (isset($_GET['id'])) {

            $analysis_id = $_GET['id'];
            $start = $_GET['start_date'];
            $end = $_GET['end_date'];

            $year = date("Y");

            $analysis = Analysis::find()->where(['analysis_id'=>$analysis_id])->one();


            $sample = Sample::find()->select(['sample_id','sample_code', 'samplename', 'description'])
            ->where(['tbl_sample.sample_id'=> $analysis->sample_id])
            ->AndWhere(['sample_year'=>$year])->one();

            $Connection= Yii::$app->labdb;
            $sql="UPDATE `tbl_tagging` SET `start_date`='$start', `end_date`='$end' WHERE `analysis_id`=".$analysis_id;
            $Command=$Connection->createCommand($sql);
            $Command->execute();
      
            $analysis = Analysis::find()->select(['tbl_analysis.analysis_id','tbl_analysis.testname', 'tbl_analysis.method', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_tagging', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            $tagging = Tagging::find()->select(['tbl_tagging.analysis_id', 'tbl_tagging.tagging_status_id', 'tbl_tagging.start_date', 'tbl_tagging.end_date'])
            ->leftJoin('tbl_analysis', 'tbl_tagging.analysis_id=tbl_analysis.analysis_id')
            ->where([ 'tbl_analysis.sample_id'=>$sample->sample_id])->all();

            if ($sample){
                return $this->asJson(['sampleCode'=>$sample->sample_code, 
                'samples'=>$sample, 
                'tests'=>$analysis,
                'tagging'=>$tagging]);           
            }                    
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogout()
    {
        return $this->render('index');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionData()
    {
        return $this->getuserid();
    }

    function getuserid(){
        $myvar = \Yii::$app->request->headers->get('Authorization');

        $rawToken = explode("Bearer ", $myvar);
        $rawToken = $rawToken[1];
        $token = \Yii::$app->jwt->getParser()->parse((string) $rawToken);
        return $token->getClaim('uid');
    }

     //************************************************
     public function actionServer(){

        $server = $_SERVER['SERVER_NAME'];
        if(!$sock = @fsockopen($server, 80))
            {
                $data = array("status" => "offline");
            }
            else
            {
                $data = array("status" => "online");
            }

           
        return $this->asJson($data);   
    }


    //************************************************
    public function actionGetproducts($keyword = ""){
        $products = Products::find()->where(['LIKE', 'product_name', $keyword])->all();

        //product type 1 = consumables and 2 = non consumable
        return $this->asJson(
            $products
        );
    }

    public function actionGetproduct($productcode){

        $product = Products::find()->where(['product_code' => $productcode])->one();
        //product type 1 = consumables and 2 = non consumable
        if($product){
             return $this->asJson(
                $product
            );
        }else{
            return $this->asJson([
                'success' => false,
                'message' => 'no product code found',
            ]);  
        } 
    }

    public function actionUpdatethumbnail(){
        $my_var = \Yii::$app->request->post();
        if($my_var){
            $product = Products::find()->where(['product_id' => $my_var['product_id']])->one(); //find product using the primarykey
            if($product){
                //fetch and save the picture, if (success) update the product
                //$product->Image1 = my_var/** productname + product code + extension */

                $image1 = UploadedFile::getInstanceByName('image');

                

                if(!empty($image1) && $image1->size !== 0) {
                    $image1->saveAs('uploads/products/'.$product->product_code.$product->product_id.'1.'.$image1->extension);
                    $product->Image1='uploads/products/'.$product->product_code.$product->product_id.'1.'.$image1->extension;
                }

                if($product->save(false)){
                    return $this->asJson([
                        'success' => true,
                        'message' => 'Product ('.$product->product_code.') updated!',
                    ]);
                }else{
                    return $this->asJson([
                        'success' => false,
                        'message' => 'Product ('.$product->product_code.') failed to update!',
                    ]); 
                }
                          
            }else{
                return $this->asJson([
                    'success' => false,
                    'message' => 'Product not found using code '.$my_var['product_id'],
                ]); 
            }
        }else{
            return $this->asJson([
                    'success' => false,
                    'message' => 'No Submission',
                ]); 
        }
        
    }


    public function actionSetschedule(){
        $my_var = \Yii::$app->request->post();
        $product = Products::findOne($my_var['product_id']); //find product using the primarykey
        if($product){
            //create schedule
            $model = new Equipmentservice;
            $model->inventory_transactions_id=$my_var['product_id'];
            $model->servicetype_id=$my_var['servicetype_id'];
            $model->requested_by=$this->getuserid();
            $model->startdate=$my_var['startdate'];
            $model->enddate=$my_var['enddate'];
            $model->request_status=0;
            $model->save();

            return $this->asJson([
                'success' => true,
                'message' => 'Schedule created for product code '.$product->product_name,
            ]); 
        }else{
            return $this->asJson([
                'success' => false,
                'message' => 'Product not found using id '.$my_var['product_id'],
            ]); 
        }
    }

    public function actionGetschedules(){
         return $this->asJson([
                'success' => false,
                'message' => 'charchar',
            ]); 
    }

    public function actionGetsamples($id){
        $model = Sample::find()->select(['sample_code','samplename','completed'])->where(['request_id'=>$id])->all();
        if($model){
            return $this->asJson(
                $model
            ); 
        }
    }

    public function actionGetrstl(){
        $model = Rstl::find()->all();
        if($model){
            return $this->asJson(
                $model
            ); 
        }
    }

    public function actionGetentries($product_id){
        $model = InventoryEntries::find()->where(['product_id'=>$product_id])->all();
       
        return $this->asJson(
            $model
        ); 
        
    }

    public function actionWithdraw(){
        //gets the data sent by the mobile app, items to be withdrawn
        $my_var = \Yii::$app->request->post();

        //playing safe
        $session = \Yii::$app->session;
        try{
            //begin transaction
            $connection = \Yii::$app->inventorydb;
            $transaction = $connection->beginTransaction();

            if($my_var){//condition to check if there are items to be withdraw

                $model = new InventoryWithdrawal;
                $model->created_by=$this->getuserid();
                $model->withdrawal_datetime=date('Y-m-d');
                $model->lab_id=1; //check if the user has lab_id
                $model->total_qty=0;
                $model->total_cost=0;
                $model->remarks="Transaction made from mobile";
                if(!$model->save()){ //if the header failed to save
                    $transaction->rollBack();
                    throw new \Exception("Cannot save header of Withdrawal Items!", 1);
                }

                // the format is objects inside an array
                foreach($my_var as $key) {
                    $entry = InventoryEntries::findOne($key['id']); //get the entries record
                    
                    if($key['quantity']>$entry->quantity_onhand){ // cart qty > withdrawable ~> throw ERR
                        $transaction->rollBack();
                        throw new \Exception("Withdrawable Quantity is less than the desired Quantity!", 1);
                     }

                     //subtract qty in Entries tbl
                     $entry->quantity_onhand = (int)$entry->quantity_onhand - (int)$key['quantity']; 
                     if($entry->save()){
                        $func = new Functions();
                        $func->checkreorderpoint($entry->product_id);
                        //create record of withdrawaldetails item
                        $item = new InventoryWithdrawaldetails();
                        $item->inventory_withdrawal_id =$model->inventory_withdrawal_id;
                        $item->inventory_transactions_id=$key['id'];
                        $item->quantity=$key['quantity'];
                        $item->price=$entry->amount*(int)$key['quantity'];
                        $item->withdarawal_status_id=2;
                        $item->save();
                      }
                }

                $transaction->commit();
                return $this->asJson([
                    'success' => true,
                    'message' => 'Processed Successfully!',
                ]);
            }else{
                return $this->asJson([
                    'success' => false,
                    'message' => 'Cart Empty',
                ]); 
            }

        }catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

    }
}
