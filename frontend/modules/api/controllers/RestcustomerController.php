<?php

namespace frontend\modules\api\controllers;

use common\models\system\LoginForm;
use common\models\system\Profile;
use common\models\system\User;
use common\models\lab\Customer;
use common\models\lab\Customeraccount;
use common\models\lab\LogincForm;
use common\models\lab\Request;
use common\models\finance\Customerwallet;
use common\models\finance\Customertransaction;
use common\models\lab\Booking;
use common\components\Functions;
use common\models\system\Rstl;
use common\models\lab\Sample;

class RestcustomerController extends \yii\rest\Controller
{
	public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
            'except' => ['login','server','confirmaccount','mailcode'], //all the other
            'user'=> \Yii::$app->customeraccount
        ];

        return $behaviors;
    }

     protected function verbs(){
        return [
            'login' => ['POST'],
            // 'user' => ['GET'],
            // 'samplecode' => ['GET'],
           // 'server' => ['GET'],
             'mailcode' => ['GET'],
             'confirmaccount' => ['POST'],
        ];
    }

     public function actionIndex(){
        return "Index";
     }

     /**
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
        $my_var = \Yii::$app->request->post();

        $email = $my_var['email'];
        $password = $my_var['password'];

        $user = Customer::find()->where(['email'=>$email])->one();

        if($user){
            $model = new LogincForm();
            $my_var = \Yii::$app->request->post();
            $model->customer_id = $user->customer_id;
            $model->password = $password;

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
                        ->set('uid', \Yii::$app->customeraccount->identity->customer_id)// Configures a new claim, called "uid"claim,
                        //->set('username', \Yii::$app->user->identity->username)// Configures a new claim, called "uid"
                        ->sign($signer, $jwt->key)// creates a signature using [[Jwt::$key]]
                        ->getToken(); // Retrieves the generated token

                    $customer = Customer::findOne(\Yii::$app->customeraccount->identity->customer_id);

                    return $this->asJson([
                        'token' => (string)$token,
                        'user'=> (['email'=>$customer->email,
                                    'fullname' => $customer->customer_name,
                                    'type' => "customer",]),
                    ]);  
            } else {
                //check if the user account is not activated
                $chkaccount = Customeraccount::find()->where(['customer_id'=>$user->customer_id])->one();
                if($chkaccount){
                    if($chkaccount->status==0){
                        return $this->asJson([
                        'success' => false,
                        'activated'=>false,
                        'message' => 'Account not activated',
                    ]);
                    }
                }

                return $this->asJson([
                        'success' => false,
                        'message' => 'Email and Password didn\'t match',
                    ]);
            }
        }else{  
            return $this->asJson([
                    'success' => false,
                    'message' => 'Email is not a valid customer',
                ]);
        }
        
    }


    public function actionUser(){
        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        /** @var Jwt $jwt */
        $jwt = \Yii::$app->jwt;
        $token = $jwt->getBuilder()
            ->setIssuer('http://example.com')// Configures the issuer (iss claim)
            ->setAudience('http://example.org')// Configures the audience (aud claim)
            ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
            ->setExpiration(time() + 3600 * 24)// Configures the expiration time of the token (exp claim)
            ->set('uid', \Yii::$app->customeraccount->identity->customer_id)// Configures a new claim, called "uid"claim,
            //->set('username', \Yii::$app->user->identity->username)// Configures a new claim, called "uid"
            ->sign($signer, $jwt->key)// creates a signature using [[Jwt::$key]]
            ->getToken(); // Retrieves the generated token

        $customer = Customer::findOne(\Yii::$app->customeraccount->identity->customer_id);

        return $this->asJson([
            'token' => (string)$token,
            'user'=> (['email'=>$customer->email,
                        'fullname' => $customer->customer_name,
                        'type' => "customer",]),
                        'customer_id'=>\Yii::$app->customeraccount->identity->customer_id
        ]);  
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
            else{
                $data = array("status" => "online");
            }
  
        return $this->asJson($data);   
    }

    public function actionGetcustonreq(){
        $model = Request::find()->select(['request_id','request_ref_num','request_datetime'])->where(['customer_id'=>$this->getuserid(), 'status_id'=>1])->all();

        if($model){
            return $this->asJson(
                $model
            ); 
        }else{
            return $this->asJson([
                'success' => false,
                'message' => 'No Request Found',
            ]); 
        }
    }

    public function actionGetcustcomreq(){
        $model = Request::find()->select(['request_id','request_ref_num','request_datetime'])->where(['customer_id'=>$this->getuserid(), 'status_id'=>2])->all();

        if($model){
            return $this->asJson(
                $model
            ); 
        }else{
            return $this->asJson([
                'success' => false,
                'message' => 'No Request Found',
            ]); 
        }
    }

    public function actionGetcustomerwallet(){
        $transactions = Customerwallet::find()->where(['customer_id'=>$this->getuserid()])->one();
        return $this->asJson(
            $transactions
        );
    }

     public function actionGetwallettransaction($id){
        $transactions = Customertransaction::find()->where(['customerwallet_id'=>$id])->orderby('date DESC')->all();
        return $this->asJson(
            $transactions
        );
    }
    //************************************************

    public function actionSetbooking(){ //create booking for customers
        //set booking default to pending
        $my_var = \Yii::$app->request->post();


       if(!$my_var){
            return $this->asJson([
                'success' => false,
                'message' => 'POST empty',
            ]); 
       }

        //Booking
        $bookling = new Booking;
        $bookling->scheduled_date = $my_var['date'];
        $bookling->booking_reference = '34ertgdsg';
        $bookling->rstl_id = $my_var['lab'];
        $bookling->date_created = $my_var['date'];
        $bookling->qty_sample = $my_var['qty'];
        $bookling->customer_id = $this->getuserid();
        $bookling->booking_status = 0;
         // return $this->asJson($bookling); exit;

        if($bookling->save()){
            return $this->asJson([
                'success' => true,
                'message' => 'Booked Successfully',
            ]); 
        }
        else{
            return $this->asJson([
                'success' => false,
                'message' => 'Booking Failed',
            ]); 
        }
    }

    public function actionGetbookings(){
        $my_var = Booking::find()->where(['customer_id'=>$this->getuserid()])->orderby('scheduled_date DESC')->all();
        return $this->asJson(
            $my_var
        );
    }

    public function actionMailcode($email){
        //sends a code to a customer for account verification purpose

        //generate random strings
        $code = \Yii::$app->security->generateRandomString(5);
        //get the customer profile using the email
        $customer = Customer::find()->where(['email'=>$email])->one();

        if($customer){
            //check if the customer has an account already
            $account = Customeraccount::find()->where(['customer_id'=>$customer->customer_id])->one();
            if($account){
                //update the verify code
                $account->verifycode = $code;
                $account->status=0;
                $account->save();
            } else{
                //create account with the verify code
                $new = new Customeraccount;
                $new->customer_id=$customer->customer_id;
                $new->setPassword('12345');
                $new->generateAuthKey();
                $new->verifycode = $code;
                $new->save();
            }
            //contruct the html content to be mailed to the customer
            $content ="
            <h1>Hello $customer->customer_name</h1>

            <h3>Account code : $code</h3>
            <p>Thank you for choosing the Onelab, to be able to provide a quality service to our beloved customer, we are giving this account code above which you may use to activate your account if ever you want to use the mobile app version, below are the following features that you may found useful. Available for Android and Apple smart devices. </p>

            <ul>Features
                <li>Request and Result Tracking</li>
                <li>Request Transaction History</li>
                <li>Wallet Transations and History</li>
                <li>Bookings</li>
            </ul>
            <br>
            <p>Truly yours,</p>
            <h4>Onelab Team</h4>
            ";

            //email the customer now
            //send the code to the customer's email
            \Yii::$app->mailer->compose()
            ->setFrom('eulims.onelab@gmail.com')
            ->setTo($email)
            ->setSubject('Eulims Mobile App')
            ->setTextBody('Plain text content')
            ->setHtmlBody($content)
            ->send();

            return $this->asJson([
                'success' => true,
                'message' => 'Code successfully sent to customer\'s email',
            ]); 
        }
        else{
            return $this->asJson([
                'success' => false,
                'message' => 'Email is not a valid customer',
            ]); 
        }
    }

    public function actionConfirmaccount(){

        //validate the code sent by the customer
        $my_var = \Yii::$app->request->post();

        $email = $my_var['email'];
        $code = $my_var['code'];
        $password = $my_var['password'];

        $customer = Customer::find()->where(['email'=>$email])->one();

        if($customer){
            $account = Customeraccount::find()->where(['customer_id'=>$customer->customer_id])->one();
            if($account->verifycode==$code){
                $account->status=1;
                $account->setPassword($password);
                $account->generateAuthKey();
                if($account->save()){
                    return $this->asJson([
                        'success' => true,
                        'message' => 'Customer account activated and updated!',
                    ]); 
                }else{
                    return $this->asJson([
                        'success' => false,
                        'message' => 'Failed to update customer record!',
                    ]);
                }
            }else{
                return $this->asJson([
                        'success' => false,
                        'message' => 'Verification Code invalid!',
                    ]);
            }
        }else{
            return $this->asJson([
                        'success' => false,
                        'message' => 'Email is not a valid customer!',
                    ]);
        }

    }

    public function actionLogout(){
        \Yii::$app->customeraccount->logout();
        return "Logout";
    }

    public function actionGetrstl(){
        $model = Rstl::find()->all();
        if($model){
            return $this->asJson(
                $model
            ); 
        }
    }

    public function actionGetsamples($id){
        $model = Sample::find()->select(['sample_code','samplename','completed'])->where(['request_id'=>$id])->all();
        if($model){
            return $this->asJson(
                $model
            ); 
        }
    }
}
