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
use yii\web\NotFoundHttpException;
use linslin\yii2\curl;
//use common\models\lab\exRequestreferral;
//use common\models\lab\Analysis;
//use common\models\lab\Sample;

/**
 * Description of Pstc Component
 * Get Data from Referral API for local eULIMS
 * @author OneLab
 */
class PstcComponent extends Component {

    public $source = 'https://eulimsapi.onelab.ph';
    //public $source = 'http://localhost/eulimsapi.onelab.ph';
    
	//list to view
	function getRequest($rstlId,$accepted)
	{
		if($rstlId > 0 && isset($accepted)) {
            $apiUrl=$this->source.'/api/web/referral/pstcrequests/request?rstl_id='.$rstlId.'&accepted='.$accepted;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
			return $list;
        } else {
            return 0;
        }
	}
	
    //for viewing single pstc request
    function getViewRequest($requestId,$rstlId,$pstcId)
    {
        if($requestId > 0 && $rstlId > 0 && $pstcId > 0) {
            $apiUrl=$this->source.'/api/web/referral/pstcrequests/viewrequest?request_id='.$requestId.'&rstl_id='.$rstlId.'&pstc_id='.$pstcId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 0;
        }
    }

    //pstc request details for saving
    function getRequestDetails($requestId,$rstlId,$pstcId)
    {
        if($requestId > 0 && $rstlId > 0 && $pstcId > 0) {
            $apiUrl=$this->source.'/api/web/referral/pstcrequests/request_details?request_id='.$requestId.'&rstl_id='.$rstlId.'&pstc_id='.$pstcId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 0;
        }
    }

    //get sample by sample ID
    function getSampleOne($sampleId,$requestId,$rstlId,$pstcId) {
        if($sampleId > 0 && $requestId > 0 && $rstlId > 0 && $pstcId > 0) {
            $apiUrl=$this->source.'/api/web/referral/pstcrequests/get_pstcsample?sample_id='.$sampleId.'&request_id='.$requestId.'&rstl_id='.$rstlId.'&pstc_id='.$pstcId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 180);
            $curl->setOption(CURLOPT_TIMEOUT, 180);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return null;
        }
    }

    //check if the agency is the owner of the pstc request
    function checkOwner($requestId,$rstlId,$pstcId)
    {
        if($requestId > 0 && $rstlId > 0 && $pstcId > 0){
            $check = Pstcrequest::find()
                ->where('pstc_request_id =:requestId', [':requestId' => $requestId])
                ->andWhere('rstl_id =:rstlId', [':rstlId' => $rstlId])
                ->andWhere('pstc_id =:pstcId', [':pstcId' => $pstcId])
                ->count();
            
            if($check > 0){
                $status = 1;
            } else {
                $status = 0;
            }
            return $status;
        } else {
            return 0;
        }
    }

    //download request form
    function downloadRequest($requestId,$rstlId,$fileId)
    {
        if($requestId > 0 && $rstlId > 0 && $fileId > 0) {
            $apiUrl=$this->source.'/api/web/referral/pstcattachments/download?request_id='.$requestId.'&rstl_id='.$rstlId.'&file='.$fileId;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);

            if($list == 'false') {
                return $list;
            } else {
                return $apiUrl;
            }
        } else {
            return false;
        }
    }
}

