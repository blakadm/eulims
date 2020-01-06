<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\JsExpression;
//use yii\helpers\ArrayHelper;
//use common\models\lab\Request;
//use kartik\grid\GridView;
use yii\web\NotFoundHttpException;
//use common\models\lab\Analysisextend;
//use common\models\system\LogSync;
//use common\models\system\ApiSettings;
use linslin\yii2\curl;
use common\models\lab\exRequestreferral;
use common\models\lab\Analysis;
use common\models\lab\Sample;

/**
 * Description of Referral Component
 * Get Data from Referral API for local eULIMS
 * @author OneLab
 */
class ReferralComponent extends Component {

    //public $source = 'https://eulimsapi.onelab.ph';
    public $source = 'http://localhost/eulimsapi.onelab.ph';
    /**
     * FindOne testname
     * @param integer $testnameId
     * @return array
     */
    function getTestnameOne($testnameId){
        if($testnameId > 0){
            $apiUrl=$this->source.'/api/web/referral/listdatas/testnameone?testname_id='.$testnameId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return json_decode($list);
        } else {
            return "Not valid testname";
        }
    }
    /**
     * FindOne Method reference
     * @param integer $methodrefId
     * @return array
     */
    function getMethodrefOne($methodrefId){
        if($methodrefId > 0){
            $apiUrl=$this->source.'/api/web/referral/listdatas/methodreferenceone?methodref_id='.$methodrefId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return json_decode($list);
        } else {
            return "Not valid method reference";
        }
    }
    /**
     * FindOne Discount
     * @param integer $discountId
     * @return array
     */
    function getDiscountOne($discountId){
        //if($discountId > 0){
        if($discountId >= 0){
            $apiUrl=$this->source.'/api/web/referral/listdatas/discountbyid?discount_id='.$discountId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return json_decode($list);
        } else {
            return "Not valid discount";
        }
    }
    /**
     * FindOne Customer
     * @param integer $customerId
     * @return array
     */
    function getCustomerOne($customerId){
        if($customerId > 0){
            $apiUrl=$this->source.'/api/web/referral/customers/customerone?customer_id='.$customerId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return "Not valid customer";
        }
    }
    //get referral sample type by lab
    function getSampletype($labId)
    {
        if($labId > 0){
            $apiUrl=$this->source.'/api/web/referral/listdatas/sampletypebylab?lab_id='.$labId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return "Not valid lab";
        }
    }
    //get referral testname by sampletype
    function getTestnames($labId,$sampletypeId){
        if($labId > 0 && $sampletypeId > 0){
            $apiUrl=$this->source.'/api/web/referral/listdatas/testnamebylab_sampletype?lab_id='.$labId.'&sampletype_id='.$sampletypeId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return "Not valid lab or sampletype";
        }
    }
    //get referral methodref by testname
    function getMethodrefs($labId,$sampletypeId,$testnameId){
        if($labId > 0 && $sampletypeId > 0 && $testnameId > 0){
            //$apiUrl=$this->source.'/api/web/referral/listdatas/testnamemethodref?testname_id='.$testnameId.'&sampletype_id='.$sampletypeId.'&lab_id='.$labId;
            $apiUrl=$this->source.'/api/web/referral/services/methodrefs?testname_id='.$testnameId.'&sampletype_id='.$sampletypeId.'&lab_id='.$labId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return "Not valid testname";
        }
    }

    //get referral laboratory list
    function listLabreferral()
    {
        $apiUrl=$this->source.'/api/web/referral/listdatas/lab';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $list = $curl->get($apiUrl);

        //$data = ArrayHelper::map(json_decode($list), 'lab_id', 'labname');
        
        return $list;
    }
    //get referral discount list
    function listDiscountreferral()
    {
        $apiUrl=$this->source.'/api/web/referral/listdatas/discount';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $list = $curl->get($apiUrl);

        //$data = ArrayHelper::map(json_decode($list), 'discount_id', 'type');
        
        return $list;
    }
    //get referral purpose list
    function listPurposereferral()
    {
        $apiUrl=$this->source.'/api/web/referral/listdatas/purpose';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $list = $curl->get($apiUrl);

        //$data = ArrayHelper::map(json_decode($list), 'purpose_id', 'name');
        
        return $list;
    }
    //get referral mode of release list
    function listModereleasereferral()
    {
        $apiUrl=$this->source.'/api/web/referral/listdatas/moderelease';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $list = $curl->get($apiUrl);

        //$data = ArrayHelper::map(json_decode($list), 'modeofrelease_id', 'mode');
        
        return $list;
    }
    //get matching services
    function listMatchAgency($requestId){

        $request = exRequestreferral::findOne($requestId);

        $sample = Sample::find()
            ->select('sampletype_id')
            ->where('request_id =:requestId', [':requestId' => $requestId])
            ->groupBy('sampletype_id')
            ->asArray()->all();

        $analysis = Analysis::find()
            ->joinWith('sample')
            ->where('tbl_sample.request_id =:requestId AND is_package_name =:packageName',[':requestId'=>$requestId,':packageName'=>0])
            ->groupBy(['testname_id','methodref_id'])
            ->asArray()->all();

        $package = Analysis::find()
            ->joinWith('sample')
            ->where('tbl_sample.request_id =:requestId AND is_package_name =:packageName AND type_fee_id =:typeFee',[':requestId'=>$requestId,':packageName'=>1,':typeFee'=>2])
            ->groupBy(['package_id'])
            ->asArray()->all();

        $sampletypeId = implode(',', array_map(function ($data) {
            return $data['sampletype_id'];
        }, $sample));

        $testnameId = implode(',', array_map(function ($data) {
            return $data['test_id'];
        }, $analysis));

        $methodrefId = implode(',', array_map(function ($data) {
            return $data['methodref_id'];
        }, $analysis));

        $packageId = implode(',', array_map(function ($data) {
            return $data['package_id'];
        }, $package));

        $apiUrl=$this->source.'/api/web/referral/services/listmatchagency?rstl_id='.$request->rstl_id.'&lab_id='.$request->lab_id.'&sampletype_id='.$sampletypeId.'&testname_id='.$testnameId.'&methodref_id='.$methodrefId.'&package_id='.$packageId;

        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $list = $curl->get($apiUrl);

        if($list == 'false'){
            return null;
        } else {
            $agencyId = implode(',', array_map(function ($data) {
                return $data['agency_id'];
            }, json_decode($list,true)));
            
            $list_agency = $this->listAgency($agencyId);
            return $list_agency;
        }
    }
    //get list agencies
    function listAgency($agencyId)
    {   
        if(!empty($agencyId)){
            $agencies = rtrim($agencyId);

            $apiUrl=$this->source.'/api/web/referral/listdatas/listagency?agency_id='.$agencies;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return null;
        }
    }
    //check if notified
    function checkNotify($requestId,$agencyId)
    {
        if($requestId > 0 && $agencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/checknotify?request_id='.$requestId.'&agency_id='.$agencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //check if bid notified
    /*function checkBidNotify($requestId,$agencyId)
    {
        if($requestId > 0 && $agencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/checknotify?request_id='.$requestId.'&agency_id='.$agencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }*/
    //check if confirmed
    function checkConfirm($requestId,$rstlId,$testingAgencyId)
    {
        if($requestId > 0 && $rstlId > 0 && $testingAgencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/checkconfirm?request_id='.$requestId.'&receiving_id='.$rstlId.'&testing_id='.$testingAgencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //check if agency participated the bidding
    /*function checkBidder($referralId,$agencyId)
    {
        if($agencyId > 0 && $referralId > 0){
            $bid = Bid::find()->where('bidder_agency_id =:bidderAgencyId AND referral_id =:referralId',[':bidderAgencyId'=>$agencyId,':referralId'=>$referralId])->count();
            if($bid > 0){
                return 1; 
            } else {
                return 0;
            }
        } else {
            return 'false';
        }
    }*/
    //check if active lab
    function checkActiveLab($labId, $agencyId)
    {
        if($labId > 0 && $agencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/checkactivelab?lab_id='.$labId.'&agency_id='.$agencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //check if agency is active
    function checkActiveAgency($agencyId)
    {
        if($agencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/checkactiveagency?agency_id='.$agencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //post delete request
    function removeReferral($agencyId,$requestId)
    {
        if($agencyId > 0 && $requestId > 0){
            $apiUrl=$this->source.'/api/web/referral/referrals/deletereferral';
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $data = Json::encode(['request_id'=>$requestId,'rstl_id'=>$agencyId],JSON_NUMERIC_CHECK);
            $response = $curl->setRequestBody($data)
            ->setHeaders([
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($data),
            ])->post($apiUrl);

            return $response;
        } else {
            return 0;
        }
    }
    //get referral notifications
    function listUnrespondedNofication($rstlId)
    {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/countnotification?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return false;
        }
    }
    //get bid notifications
    function listUnseenBidNofication($rstlId)
    {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/countbidnotification?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return false;
        }
    }
    //get bidder agency
    function getBidderAgency($requestId,$rstlId)
    {
        if($requestId > 0 && $rstlId > 0){
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/bidderagency?request_id='.$requestId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return false;
        }
    }
    //list bidders
    function listBidders($agencyId){
        if($agencyId > 0){
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/listbidder?agency_id='.$agencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return false;
        }
    }
    //count bid notices
    function countBidnotice($requestId,$rstlId){
        if($requestId > 0 && $rstlId > 0){
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/bidnotice?request_id='.$requestId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return false;
        }
    }
    //get bid estimated due date
    function getBidDuedate($requestId,$rstlId,$senderId)
    {
        if($rstlId > 0 && $requestId > 0 && $senderId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/showdue?request_id='.$requestId.'&rstl_id='.$rstlId.'&sender_id='.$senderId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get referral details via referral_id
    function getReferraldetails($referralId,$rstlId)
    {
        if($referralId > 0 && $rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/viewdetail?referral_id='.$referralId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get referral,sample,analysis details for saving in eulims local
    function getReferralRequestDetails($referralId,$rstlId)
    {
        if($referralId > 0 && $rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/get_referral_detail?referral_id='.$referralId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get all notifications of rstl
    function getNotificationAll($rstlId)
    {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/listall?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get all bid notifications of rstl
    function getBidNotificationAll($rstlId)
    {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bidnotifications/listall?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get notification
    function getNotificationOne($notificationId,$rstlId)
    {
        if($rstlId > 0 && $notificationId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/notification_one?notification_id='.$notificationId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get estimated due date
    function getDuedate($requestId,$rstlId,$senderId)
    {
        if($rstlId > 0 && $requestId > 0 && $senderId > 0) {
            $apiUrl=$this->source.'/api/web/referral/notifications/showdue?request_id='.$requestId.'&rstl_id='.$rstlId.'&sender_id='.$senderId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get checkowner
    function checkOwner($referralId,$rstlId)
    {
        if($rstlId > 0 && $referralId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/checkowner?referral_id='.$referralId.'&sender_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get only referral
    function getReferralOne($referralId,$rstlId)
    {
        if($referralId > 0){
            $apiUrl=$this->source.'/api/web/referral/referrals/referral_one?referral_id='.$referralId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Invalid referral!';
        }
    }
    //get details for sample code
    function getSamplecode_details($requestId,$rstlId){
        if($requestId > 0 && $rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/get_samplecode?request_id='.$requestId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Invalid request!';
        }
    }
    //get attachment
    function getAttachment($referralId,$rstlId,$type){
        if($referralId > 0 && $rstlId > 0 && $type > 0) {
            $apiUrl=$this->source.'/api/web/referral/attachments/show_upload?referral_id='.$referralId.'&rstl_id='.$rstlId.'&type='.$type;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Invalid referral!';
        }
    }
    function downloadAttachment($referralId,$rstlId,$fileId){
        if($referralId > 0 && $rstlId > 0 && $fileId > 0) {
            $apiUrl=$this->source.'/api/web/referral/attachments/download?referral_id='.$referralId.'&rstl_id='.$rstlId.'&file='.$fileId;
            //$curl = new curl\Curl();
            //$file = $curl->get($apiUrl);
            //return $apiUrl;
            //if($file == 0){
            //    return 'false';
            //} else {
            //    return $apiUrl;
            //}
            return $apiUrl;
        } else {
            return 'false';
        }
    }
    function getReferredAgency($referralId,$rstlId){
        if($referralId > 0 && $rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/referred_agency?referral_id='.$referralId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    function getReferralAll($rstlId){
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/referral_all?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    function getIncomingReferral($rstlId) {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/incoming_referral?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    function getSentReferral($rstlId) {
        if($rstlId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/sent_referral?rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    //offer service
    function offerService($data){
        $referralUrl=$this->source.'/api/web/referral/services/offer';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $referralreturn = $curl->setRequestBody($data)
        ->setHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ])->post($referralUrl);

        return $referralreturn;
    }
    //remove service
    function removeService($data){
        $referralUrl=$this->source.'/api/web/referral/services/remove';
        $curl = new curl\Curl();
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
        $curl->setOption(CURLOPT_TIMEOUT, 180);
        $referralreturn = $curl->setRequestBody($data)
        ->setHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ])->post($referralUrl);

        return $referralreturn;
    }
    //check if service is already offered
    function checkOffered($methodrefId,$rstlId){
        if($rstlId > 0 && $methodrefId > 0) {
            $apiUrl=$this->source.'/api/web/referral/services/check_offered?methodref_id='.$methodrefId.'&rstl_id='.$rstlId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $check = $curl->get($apiUrl);
            return $check;
        } else {
            return 0;
        }
    }
    //return method reference offered by
    function offeredBy($methodrefId){
        if($methodrefId > 0){
            $apiUrl=$this->source.'/api/web/referral/services/offeredby?methodref_id='.$methodrefId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $offeredby = $curl->get($apiUrl);
            return $offeredby;
        } else {
            return 0;
        }
    }
    //get package one
    function getPackageOne($packageId)
    {
        if($packageId > 0){
            $apiUrl=$this->source.'/api/web/referral/packages/package_detail?package_id='.$packageId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 0;
        }
    }
    //get list of packages
    function getPackages($labId,$sampletypeId)
    {
        if($labId > 0 && $sampletypeId > 0){
            $apiUrl=$this->source.'/api/web/referral/packages/listpackage?lab_id='.$labId.'&sampletype_id='.$sampletypeId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 0;
        }
    }
    //check if test bid added
    /*function checkTestbid($referralId,$analysisId,$bidderAgencyId)
    {
        if($referralId > 0 && $analysisId > 0 && $bidderAgencyId > 0) {
            $testBid = Testbid::find()->where('referral_id =:referralId AND analysis_id =:analysisId AND bidder_agency_id =:bidderAgencyId',[':referralId'=>$referralId,':analysisId'=>$analysisId,':bidderAgencyId'=>$bidderAgencyId])->count();
            return $testBid;
        } else {
            return 'false';
        }
    }*/
    //function count all both unresponded referral and unseen bid notifications
    function countAllNotification($rstlId)
    {
        if($rstlId > 0){
            $bid =  json_decode($this->listUnseenBidNofication($rstlId),true);
            $referral = json_decode($this->listUnrespondedNofication($rstlId),true);
            $bid_notification = $bid['count_bidnotification'];
            $referral_notification = $referral['count_notification'];
            $allNotification = $bid_notification + $referral_notification;

            return $allNotification;
        } else {
            return 'false';
        }
    }
    //function to get agency bid details for update to local ulims
    function getBidDetails($referralId,$rstlId,$bidderAgencyId,$bidId)
    {
        if($referralId > 0 && $rstlId > 0 && $bidderAgencyId > 0 && $bidId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bids/bid_details?referral_id='.$referralId.'&rstl_id='.$rstlId.'&bidder_id='.$bidderAgencyId.'&bid_id='.$bidId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    //function to get agency bid details for update to local ulims
    function getTestbidDetails($referralId,$rstlId,$bidderAgencyId)
    {
        if($referralId > 0 && $rstlId > 0 && $bidderAgencyId > 0) {
            $apiUrl=$this->source.'/api/web/referral/bids/testbid_details?referral_id='.$referralId.'&rstl_id='.$rstlId.'&bidder_id='.$bidderAgencyId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }

    //function bid notification details
    function getBidNoticeDetails($referralId,$rstlId,$noticeId,$seen)
    {
        if($referralId > 0 && $rstlId > 0 && $noticeId > 0 && $seen == 1) {
            $apiUrl=$this->source.'/api/web/referral/bids/notice_details?referral_id='.$referralId.'&agency_id='.$rstlId.'&notice_id='.$noticeId.'&seen='.$seen;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    //get referral track receiving

    //get referral track receiving by referral id

    function getTrackreceiving($referralId)
    {
       
        if($referralId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referraltrackreceivings/detail?referral_id='.$referralId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get referral track testing by referral id
    function getTracktesting($referralId)
    {
       
        if($referralId > 0) {
            $apiUrl=$this->source.'/api/web/referral/referraltracktestings/detail?referral_id='.$referralId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    
    //get Status Logs
    function getStatuslogs($Id)
    {
       
        if($Id > 0) {
            $apiUrl=$this->source.'/api/web/referral/referrals/logs?id='.$Id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get referral track testing by referral id
    function getTracktestingdata($Id)
    {
       
        if($Id > 0) {
            $apiUrl=$this->source.'/api/web/referral/referraltracktestings/getdata?id='.$Id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Not valid request!';
        }
    }
    //get Courier
    function getCourierdata()
    {
       
            $apiUrl=$this->source.'/api/web/referral/couriers/getdata';
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            if($list){
                return $list;
            }else {
                return 'Error in connection!';
            }
    }
    function getCourierone($id)
    {
       
            $apiUrl=$this->source.'/api/web/referral/couriers/getone?id='.$id;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            if($list){
                return $list;
            }else {
                return 'Error in connection!';
            }
    }
    
    function getCheckstatus($referralId,$statusId)
    {
        if($referralId > 0 && $statusId > 0) {
            //$apiUrl=$this->source.'/api/web/referral/bids/notice_details?referral_id='.$referralId.'&agency_id='.$rstlId.'&notice_id='.$noticeId.'&seen='.$seen;
            $apiUrl=$this->source.'/api/web/referral/statuslogs/checkstatuslogs?referralId='.$referralId.'&statusId='.$statusId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'false';
        }
    }
    
}

