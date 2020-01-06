<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use Yii;
use yii\base\Component;
use yii2mod\alert\Alert;
use common\models\lab\Status;
use common\models\finance\PaymentStatus;
use common\models\lab\Customer;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\system\Profile;
use common\models\lab\Sample;
use common\models\lab\Samplecode;
use common\models\lab\Lab;
use yii\helpers\ArrayHelper;
use common\models\lab\Request;
use kartik\grid\GridView;
use common\models\finance\Customertransaction;
use common\models\finance\Customerwallet;
use common\models\inventory\Products;
use common\models\inventory\Suppliers;
use common\models\inventory\Reorder;
use yii\web\NotFoundHttpException;
use common\models\system\LogSync;
use common\models\system\ApiSettings;
use linslin\yii2\curl;


/**
 * Description of Functions
 * @property string $pesoSign
 * @author OneLab
 */
class Functions extends Component{
    /**
     * 
     * @param string $Proc
     * @param array $Params
     * @param CDBConnection $Connection
     * @return array
     */
    function ExecuteStoredProcedureRows($Proc,array $Params,$Connection){
        if(!isset($Connection)){
           $Connection=Yii::$app->db;
        }
        $Command=$Connection->createCommand("CALL $Proc");
        //Iterate through arrays of parameters
        foreach($Params as $Key=>$Value){
           $Command->bindValue($Key, $Value); 
        }
        $Rows=$Command->queryAll();
        return $Rows;
    }
    function getPesoSign(){
        return "₱";
    }
    
    /**
     * Get the corresponding access token using rstl_id 
     * @param int $id The RSTL_ID for each RSTL
     * @return JSON
     */
    public function GetAccessToken($id){
        $ApiSettings= ApiSettings::find()->where(['rstl_id'=>$id])->one();
        $apiUrl= $ApiSettings->get_token_url."?tk=".$ApiSettings->request_token."&id=".$id;
        $curl = new curl\Curl();
        $response = $curl->get($apiUrl);
        return $response;
    }
    public function DisplayImageFromFolder(){
        $files = glob("../../frontend/web/images/icons/*.png");
        $list=[];
        for ($i=1; $i<count($files); $i++)
        {
                $num = $files[$i];
                $Filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($num));
                $listval=['icon'=>$Filename,'text'=>$Filename];
                array_push($list, $listval);
        }
        return ArrayHelper::map($list, 'icon', 'icon');
    }
    /**
     * @description Checks if url (Image, Files, Page) does exist
     * @param string $url
     * @return boolean
     */
    public function CheckUrlExist($url){
        return false;
        //$file = 'http://www.port-management.com/assets/41a8fcb2/photo/c1f44f4d32ce6b10fcb6ec71f292cfa43323ee6c1.jpg';
        $ServerName=Yii::$app->getRequest()->serverName;
        if (strpos($url, 'http') == false) {
            if(Yii::$app->getRequest()->isSecureConnection){//SSL
                $url="https://$ServerName".$url;
            }else{
                $url="http://$ServerName".$url;
            }
        }
        $file_headers = @get_headers($url);
        if($file_headers[0] == 'HTTP/1.1 200 OK') {
            $exists = true;
        }
        else {
            $exists = false;
        }
        return $exists;
    }
    public function CheckRSTLProfile(){
        if(!Yii::$app->user->identity->profile){
            throw new NotFoundHttpException("Warning: The requested profile does not exist, Please add Profile.");
        }
    }
    /**
     * 
     * @param string $Proc
     * @param array $Params
     * @param CDBConnection $Connection
     * @return array
     */
    public function ExecuteStoredProcedureOne($Proc,array $Params,$Connection){
        if(!isset($Connection)){
           $Connection=Yii::$app->db;
        }
        $Command=$Connection->createCommand("CALL $Proc");
        //Iterate through arrays of parameters
        foreach($Params as $Key=>$Value){
           $Command->bindValue($Key, $Value); 
        }
        $Row=$Command->queryOne();
        return $Row;
    }
    /**
     * @param description Executes the SQL statement. This method should only be used for executing non-query SQL statement, such as `INSERT`, `DELETE`, `UPDATE` SQLs. No result set will be returned.
     * @param type $Proc
     * @param array $Params the Parameter for Stored Procedure
     * @param type $Connection the Active Connection
     * @return Integer
     */
    function ExecuteStoredProcedure($Proc,array $Params,$Connection){
        if(!isset($Connection)){
           $Connection=Yii::$app->db;
        }
        $Command=$Connection->createCommand("CALL $Proc");
        //Iterate through arrays of parameters
        foreach($Params as $Key=>$Value){
           $Command->bindValue($Key, $Value); 
        }
        $ret=$Command->execute();
        return $ret;
    }
    function GetProfileName($UserID){
        $Profile=Profile::find()->where(['user_id'=>$UserID])->one();
        return $Profile->fullname;      
    }
    function GetCustomerName($CustomerID){
        $Customer= Customer::find()->where(['customer_id'=>$CustomerID])->one();
        return $Customer->customer_name;
    }
    /**
     * 
     * @param integer $CustomerID
     * @return array
     */
    function GetPaymentModeList($CustomerID){
        $Connection=Yii::$app->financedb;
        $Proc="CALL spGeneratePaymentModeList(:mCustomerID)";
        $Command=$Connection->createCommand($Proc);
        $Command->bindValue(':mCustomerID',$CustomerID);
        $list = $Command->queryAll();
        return $list;
    }
    function GetClientList($rstl_id){
        $Connection=Yii::$app->financedb;
        $Proc="CALL spGetClientList(:mrstl_id)";
        $Command=$Connection->createCommand($Proc);
        $Command->bindValue(':mrstl_id',$rstl_id);
        $list = $Command->queryAll();
        return $list;
    }
    /**
     * 
     * @param integer $CustomerID
     * @return array
     */
    function GetCustomerClientList($rstl_id){
        $Connection=Yii::$app->financedb;
        $Proc="CALL spGetCustomerClient(:mrstl_id)";
        $Command=$Connection->createCommand($Proc);
        $Command->bindValue(':mrstl_id',$rstl_id);
        $list = $Command->queryAll();
        return $list;
    }

    function GenerateStatusTagging($Legend, $Ispayment){
        $StatusLegend="<fieldset>";
        $StatusLegend.="<legend>$Legend</legend>";
        $StatusLegend.="<div style='padding: 0 10px'>";
      
                $StatusLegend.="<span class='badge btn-default legend-font' ><span class= 'glyphicon glyphicon-check'></span> PENDING</span>&nbsp;";
                $StatusLegend.="<span class='badge btn-primary legend-font' ><span class= 'glyphicon glyphicon-check'></span> ONGOING</span>&nbsp;";
                $StatusLegend.="<span class='badge btn-success legend-font' ><span class= 'glyphicon glyphicon-check'></span> COMPLETED</span>&nbsp;";
                $StatusLegend.="<span class='badge btn-danger legend-font' ><span class= 'glyphicon glyphicon-check'></span> CANCELLED</span>&nbsp;";
                $StatusLegend.="<span class='badge btn-warning legend-font' ><span class= 'glyphicon glyphicon-check'></span> ASSIGNED</span>&nbsp;";
         
        $StatusLegend.="</div>";
        $StatusLegend.="</fieldset>";
        return $StatusLegend;
    }

    function GenerateStatusServices($Legend, $Ispayment){
        $StatusLegend="<fieldset>";
        $StatusLegend.="<legend>$Legend</legend>";
        $StatusLegend.="<div style='padding: 0 10px'>";
    
                $StatusLegend.="<span class='badge btn-success legend-font' ><span class= 'glyphicon glyphicon-check'></span> UNOFFER</span>&nbsp;";
                $StatusLegend.="<span class='badge btn-danger legend-font' ><span class= 'glyphicon glyphicon-check'></span> OFFER</span>&nbsp;";
                
        $StatusLegend.="</div>";
        $StatusLegend.="</fieldset>";
        return $StatusLegend;
    }

    function GenerateSampleCode($request_id){
        $request =Request::find()->where(['request_id'=>$request_id])->one();
        //$lab = Lab::findOne($request->lab_id);
        $year = date('Y', strtotime($request->request_datetime));
        $connection= Yii::$app->labdb;
        $rstlId = Yii::$app->user->identity->profile->rstl_id;
        
        foreach ($request->samples as $samp){
            $transaction = $connection->beginTransaction();
            $return="false";
            try {
                $proc = 'spGetNextGenerateSampleCode(:rstlId,:labId,:requestId)';
                $params = [':rstlId'=>$rstlId,':labId'=>$request->lab_id,':requestId'=>$request_id];
                $row = $this->ExecuteStoredProcedureOne($proc, $params, $connection);
                $samplecodeGenerated = $row['GeneratedSampleCode'];
                $samplecodeIncrement = $row['SampleIncrement'];

                $sampleId = $samp->sample_id;
                $sample= Sample::find()->where(['sample_id'=>$sampleId])->one();
                
                //insert to tbl_samplecode
                $samplecode = new Samplecode();
                $samplecode->rstl_id = $rstlId;
                $samplecode->reference_num = $request->request_ref_num;
                $samplecode->sample_id = $sampleId;
                $samplecode->lab_id = $request->lab_id;
                $samplecode->number = $samplecodeIncrement;
                $samplecode->year = $year;
                
                if($samplecode->save())
                {
                    //update samplecode to tbl_sample
                    $sample->sample_code = $samplecodeGenerated;
					$sample->sample_month = date('m', strtotime($request->request_datetime));
					$sample->sample_year = date('Y', strtotime($request->request_datetime));
                    $sample->save(false); //skip validation since only update of sample code is performed
                    $transaction->commit();
                    $return="true";
                } else {
                    //error
                    $transaction->rollBack();
                    $samplecode->getErrors();
                    $return="false";
                }
                
                //$transaction->commit();

            } catch (\Exception $e) {
               $transaction->rollBack();
               echo $e->getMessage();
               $return="false";
            } catch (\Throwable $e) {
               $transaction->rollBack();
               $return="false";
               echo $e->getMessage();
            }
            
        }
        return $return;
    }

    function GetSampleCode($form,$model,$disabled=false,$Label=false){
        //isali sa query na year 2018(this year) ang ioutput na sample codes para hindi malito 
        //pag multi year na gamitin

       

$dataExp = <<< SCRIPT
         function (params, page) {
                return {
                    q: params.term, // search term
                };
            }
SCRIPT;
        $dataResults = <<< SCRIPT
            function (data, page) {
                return {
                  results: data.results
                };
            }
SCRIPT;



                $url = \yii\helpers\Url::to(['/lab/tagging/getsamplecode']);
              //  $url = '';
                // Get the initial city description
               $sample_code = empty($model->sample_code) ? '' : Sample::findOne($model->sample_code)->sample_code;
             // $sample_code = "ssdf";
                return $form->field($model, 'sample_code')->widget(Select2::classname(), [
                    'initValueText' => $sample_code, // set the initial display text
                    'options' => ['placeholder' => 'Search or Scan Sample Code ...','disabled'=>$disabled,'class'=>'.input-group.input-group-sm'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression($dataExp),
                            'results' => new JsExpression($dataResults)
                        ]
                    ],
                ])->label($Label);
            }

        

function GetCustomerList($form,$model,$disabled=false,$Label=false){
$dataExp = <<< SCRIPT
    function (params, page) {
        return {
            q: params.term, // search term
        };
    }
SCRIPT;
$dataResults = <<< SCRIPT
    function (data, page) {
        return {
          results: data.results
        };
    }
SCRIPT;
        $url = \yii\helpers\Url::to(['/lab/request/customerlist']);
        // Get the initial city description
        $cust_name = empty($model->customer) ? '' : Customer::findOne($model->customer_id)->customer_name;
        return $form->field($model, 'customer_id')->widget(Select2::classname(), [
            'initValueText' => $cust_name, // set the initial display text
            'options' => ['placeholder' => 'Search for a customer ...','disabled'=>$disabled,'class'=>'.input-group.input-group-sm'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression($dataExp),
                    'results' => new JsExpression($dataResults)
                ]
            ],
        ])->label($Label);
    }

//referral customer list
function GetReferralCustomerList($form,$model,$disabled=false,$Label=false){
$dataExp = <<< SCRIPT
    function (params, page) {
        return {
            query: params.term, // search term
        };
    }
SCRIPT;
$dataResults = <<< SCRIPT
    function (data, page) {
        return {
          results: data.results
        };
    }
SCRIPT;
        $url = \yii\helpers\Url::to(['/lab/request/referralcustomerlist']);
        // Get the initial city description
        $cust_name = empty($model->customer) ? '' : Customer::findOne($model->customer_id)->customer_name;
        return $form->field($model, 'customer_id')->widget(Select2::classname(), [
            'initValueText' => $cust_name, // set the initial display text
            'options' => ['placeholder' => 'Search for a customer ...','disabled'=>$disabled,'class'=>'.input-group.input-group-sm'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression($dataExp),
                    'results' => new JsExpression($dataResults)
                ]
            ],
        ])->label($Label);
    }


//bergel cutara
function GetSupplierList($form,$model,$disabled=false,$Label=false){
$dataExp = <<< SCRIPT
    function (params, page) {
        return {
            q: params.term, // search term
        };
    }
SCRIPT;
$dataResults = <<< SCRIPT
    function (data, page) {
        return {
          results: data.results
        };
    }
SCRIPT;
        $url = \yii\helpers\Url::to(['/inventory/inventoryentries/supplierlist']);
        // Get the initial city description
        $supplier_name = empty($model->suppliers_id) ? '' : Suppliers::findOne($model->suppliers_id)->suppliers;
        return $form->field($model, 'suppliers_id')->widget(Select2::classname(), [
            'initValueText' => $supplier_name, // set the initial display text
            'options' => ['placeholder' => 'Search for a supplier ...','disabled'=>$disabled,'class'=>'.input-group.input-group-sm'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression($dataExp),
                    'results' => new JsExpression($dataResults)
                ]
            ],
        ])->label($Label);
    }


//bergel cutara
function GetProductList($form,$model,$disabled=false,$Label=false){
$dataExp = <<< SCRIPT
    function (params, page) {
        return {
            q: params.term, // search term
        };
    }
SCRIPT;
$dataResults = <<< SCRIPT
    function (data, page) {
        return {
          results: data.results
        };
    }
SCRIPT;
        $url = \yii\helpers\Url::to(['/inventory/inventoryentries/productlist']);
        // Get the initial city description
        $prod_name = empty($model->product_id) ? '' : Products::findOne($model->product_id)->product_name;
        return $form->field($model, 'product_id')->widget(Select2::classname(), [
            'initValueText' => $prod_name, // set the initial display text
            'options' => ['placeholder' => 'Search for a product ...','disabled'=>$disabled,'class'=>'.input-group.input-group-sm'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression($dataExp),
                    'results' => new JsExpression($dataResults)
                ]
            ],
        ])->label($Label);
    }

    //bergel cutara
    function GetRequestList($form,$model,$disabled=false,$Label=false){
$dataExp = <<< SCRIPT
    function (params, page) {
        return {
            q: params.term, // search term
        };
    }
SCRIPT;

$dataResults = <<< SCRIPT
    function (data, page) {
        return {
          results: data.results
        };
    }
SCRIPT;
        $url = \yii\helpers\Url::to(['/lab/request/requestlist']);
        // Get the initial city description
        $req_name = empty($model->request_id) ? '' : Request::findOne($model->request_id)->request_ref_num;
        return $form->field($model, 'request_id')->widget(Select2::classname(), [
            'initValueText' => $req_name, // set the initial display text
            'options' => ['placeholder' => 'Search for a request ...','disabled'=>$disabled,'class'=>'form-control'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression($dataExp),
                    'results' => new JsExpression($dataResults)
                ]
            ],
        ])->label($Label);
    }



    function GenerateStatusLegend($Legend, $Ispayment){
        $StatusLegend="<fieldset>";
        $StatusLegend.="<legend>$Legend</legend>";
        $StatusLegend.="<div style='padding: 0 10px'>";
        if($Ispayment == true){
            $Stats= PaymentStatus::find()->orderBy('payment_status_id')->all();
        }else{
            $Stats= Status::find()->orderBy('status')->all();
        }
        foreach ($Stats as $Stat){
            if($Ispayment){
                $StatusLegend.="<span class='badge $Stat->class legend-font' ><span class='$Stat->icon'></span> $Stat->payment_status</span>";
            }else{
                $StatusLegend.="<span class='badge $Stat->class legend-font' ><span class='$Stat->icon'></span> $Stat->status</span>";
            }
        }
        $StatusLegend.="</div>";
        $StatusLegend.="</fieldset>";
        return $StatusLegend;
    }
    
    function CrudAlert($title="Saved Successfully",$type="SUCCESS",$showclose=true,$showcancel=false,$callback=true) {
        switch($type) {
            case "SUCCESS":
                $dialog = Alert::TYPE_SUCCESS;
                break;
            case "ERROR":
                $dialog = Alert::TYPE_ERROR;
                break;
            case "INFO":
                $dialog = Alert::TYPE_INFO;
                break;
            case "WARNING":
                $dialog = Alert::TYPE_WARNING;
                break;
            case "INPUT":
                $dialog = Alert::TYPE_INPUT;
                break;
        }
        
        if($callback==true) {
        
        return  Alert::widget([
            'options' => [
                'showCloseButton' => $showclose,
                'showCancelButton' => $showcancel,
                'title' => $title,
                'type' => $dialog ,
                //'timer' => 1000
            ],
            'callback' => new \yii\web\JsExpression("
                        function () {
                        $('.sweet-overlay').css('display','none');
                        $('.sweet-alert').removeClass( \"showSweetAlert \" );
                        $('.sweet-alert').removeClass( \"visible \" );
                        $('.sweet-alert').addClass( \"hideSweetAlert \" );
                        $('.sweet-alert').css('display','none');
                        $('body').removeClass( \"stop-scrolling\" );
                        }"),
        ]);
     }else{
          return  Alert::widget([
            'options' => [
                'showCloseButton' => $showclose,
                'showCancelButton' => $showcancel,
                'title' => $title,
                'type' => $dialog ,
                //'timer' => 1000
            ]
              ]);
     }
    }
    /**
     * 
     * @param type $mString
     * @param type $length
     * @return typeLeft Function
     */
    public function left($mString, $length){
        return substr($mString,0,$length);
    }

    /**
     * 
     * @return type
     */
    public function exportConfig($title,$filename,$Header){
        $pdfHeader = [
            'L' => [
                'content' => "",
                'font-size' => 0,
                'color' => '#333333',
            ],
            'C' => [
                'content' => $Header,
                'font-size' => 20,
                'margin-top'=>60,
                'color' => '#333333',
            ],
            'R' => [
                'content' =>'',
                'font-size' => 0,
                'color' => '#333333',
            ],
            'line'=>false
        ];
        $pdfFooter = [
            'L' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'color' => '#999999',
            ],
            'C' => [
                'content' => '{PAGENO}',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333',
            ],
            'R' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333',
            ],
            'line' => false,
        ];
        return [GridView::PDF => [
                'filename' => $filename,
                'alertMsg'        => 'The PDF export file will be generated for download.',
                'config' => [
                    'methods' => [
                        //'SetHeader' => [$pdfHeader,'line'=>0],
                        //'SetFooter' => [$pdfFooter]
                        'SetHeader' => [
                            ['odd' => $pdfHeader, 'even' => $pdfHeader],
                        ],
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter],
                        ],
                    ],
                    'options' => [
                        'title' => $title,
                        'subject' => 'SOA',
                        'keywords' => 'pdf, preceptors, export, other, keywords, here',
                        'destination'=>'I'
                    ],
                ]
            ],
            GridView::EXCEL => [
                'label'           => 'Excel',
                //'icon'            => 'file-excel-o',
                'methods' => [
                    'SetHeader' => [$pdfHeader],
                    'SetFooter' => [$pdfFooter]
                ],
                'iconOptions'     => ['class' => 'text-success'],
                'showHeader'      => TRUE,
                'showPageSummary' => TRUE,
                'showFooter'      => TRUE,
                'showCaption'     => TRUE,
                'filename'        => $filename,
                'alertMsg'        => 'The EXCEL export file will be generated for download.',
                'options'         => ['title' => $title],
                'mime'            => 'application/vnd.ms-excel',
                'config'          => [
                    'worksheet' =>$title,
                    'cssFile'   => ''
                ]
            ],
        ];
    }
    public function IsRequestHasReceipt($request_id){
        $Connection=Yii::$app->labdb;
        $SQL="SELECT COUNT(*)>0 HasReceipt FROM `tbl_request` WHERE `request_id`=:requestID AND `payment_status_id`>1";
        $Command=$Connection->createCommand($SQL);
        $Command->bindValue(':requestID', $request_id);
        $Row=$Command->queryOne();
        return $Row['HasReceipt'];
    }
    public function SetWallet($customer_id,$amount,$source,$transactiontype){
//        echo $customer_id;
//        echo "<br>";
//        echo $amount;
//        echo "<br>";
//        echo $source;
//        echo "<br>";
//        echo $transactiontype;
//        exit;
        //$transactiontype 0 = credit, 1= debit ,2= initial
        $transac = new Customertransaction();
        $transac->transactiontype=$transactiontype;
        $transac->updated_by=Yii::$app->user->id;
        $transac->amount=$amount;
        $transac->date=date('Y-m-d');
        $transac->source=$source;
        $wallet = Customerwallet::find()->where(['customer_id' => $customer_id])->one();
        if($wallet){
           
             switch($transactiontype){
                case 0:
                    //credit transactiontype
                    $wallet->balance = $wallet->balance + $transac->amount;

                break;
                case 1:
                    //debit transaction type
                    $wallet->balance = $wallet->balance - $transac->amount;
                break;
                case 2:
                    //initial transaction
                    //compiler cant go  here
                break;
                default:
                    return false;
             }

             //save the wallet information
             if($wallet->save(false)){
                $transac->balance=$wallet->balance;
                $transac->customerwallet_id=$wallet->customerwallet_id;
                if($transac->save(false)){
                    return true;
                }else{
                    return false;
                }
             }else{
                
                return false;
             }
        }else{
            //make wallet
            $newwallet = New Customerwallet();
            $newwallet->rstl_id=Yii::$app->user->identity->profile->rstl_id;
            $newwallet->date = date('Y-m-d h:i:s');
            $newwallet->last_update = date('Y-m-d h:i:s');
            $newwallet->balance=$amount;
            $newwallet->customer_id=$customer_id;
            if($newwallet->save()){
                $transac = new Customertransaction();
                $transac->updated_by=Yii::$app->user->id;
                $transac->date=date('Y-m-d h:i:s');
                $transac->transactiontype=$transactiontype;
                $transac->amount=$amount;
                $transac->balance=$amount;
                $transac->customerwallet_id=$newwallet->customerwallet_id;
                $transac->source=$source;
                if($transac->save(false)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            return false;
        }
    }

    public function checkreorderpoint($id){
        $product = Products::findOne($id);
        
        if($product->qty_reorder >= $product->getTotalqty()){
            $checkvar = Reorder::find()->where(['product_id'=>$id])->one();
            if($checkvar)
                return false; //dont proceed to add into reorder table

            //add the product to the reorder points table
            $reorder = new Reorder();
            $reorder->product_id=$id;
            $reorder->date_created=date("Y-m-d");
            $reorder->save();
            //return true;
        }
        //return false;
    }
}
