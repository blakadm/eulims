<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;
use linslin\yii2\curl;
use common\models\referral\Referral;
use common\models\referral\Sample;
use common\models\referral\Analysis;
use common\models\referral\Notification;
use common\models\referral\Service;
use common\models\referral\Testbid;
use common\models\referral\Bid;
use common\models\referral\Bidnotification;
use common\models\referral\Agency;

/**
 * Referral User Defined Functions
 * @author OneLab
 */
class ReferralFunctions extends Component
{
	//public $source = 'https://eulimsapi.onelab.ph';
	public $source = 'http://localhost/eulimsapi.onelab.ph';

	//check if the agency is notified
	function checkNotified($referralId,$recipientId)
	{
		if($referralId > 0 && $recipientId > 0){
			$check = Notification::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('recipient_id =:recipientId', [':recipientId'=>$recipientId])
				->andWhere('notification_type_id =:notice', [':notice'=>1])
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

	//check if the agency is the testing lab of the referral request
	function checkTestingLab($referralId,$recipientId)
	{
		if($referralId > 0 && $recipientId > 0){
			$check = Notification::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('recipient_id =:recipientId', [':recipientId'=>$recipientId])
				->andWhere('notification_type_id =:notice', [':notice'=>3])
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

	//check if the agency is the owner of the referral request
	function checkOwner($referralId,$senderId)
	{
		if($referralId > 0 && $senderId > 0){
			$check = Referral::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('receiving_agency_id =:senderId', [':senderId'=>$senderId])
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

	//check if service is already offered
    function checkOffered($methodrefId,$rstlId)
    {
        if($rstlId > 0 && $methodrefId > 0) {
			$service = Service::find()->where(['method_ref_id'=>$methodrefId,'agency_id'=>$rstlId])->count();
			
			if($service > 0){
				$return = 1;
			} else {
				$return = 0;
			}
		} else {
			$return = 0;
		}
		return $return;
    }

    //return method reference offered by
    function offeredBy($methodrefId)
    {
        if($methodrefId > 0){
			$service = Service::find()
				->joinWith('agency',true)
				->where(['method_ref_id'=>$methodrefId])
				->orderBy('tbl_agency.agency_id');
			
			$query = $service->asArray()->all();
			$count = $service->count();
			
			if($count > 0){
				return $query;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
    }

    //get attachments
    function getAttachment($referralId,$rstlId,$type)
    {
        if($referralId > 0 && $rstlId > 0 && $type > 0) {
            $apiUrl=$this->source.'/api/web/referral/attachments/show_upload?referral_id='.$referralId.'&rstl_id='.$rstlId.'&type='.$type;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, 120);
            $curl->setOption(CURLOPT_TIMEOUT, 120);
            $list = $curl->get($apiUrl);
            return $list;
        } else {
            return 'Invalid referral!';
        }
    }

    //download attachment
    function downloadAttachment($referralId,$rstlId,$fileId)
    {
        if($referralId > 0 && $rstlId > 0 && $fileId > 0) {
            $apiUrl=$this->source.'/api/web/referral/attachments/download?referral_id='.$referralId.'&rstl_id='.$rstlId.'&file='.$fileId;
            return $apiUrl;
        } else {
            return 'false';
        }
    }

    //check if test bid added
    function checkTestbid($referralId,$analysisId,$bidderAgencyId)
    {
    	if($referralId > 0 && $analysisId > 0 && $bidderAgencyId > 0) {
    		$testBid = Testbid::find()->where('referral_id =:referralId AND analysis_id =:analysisId AND bidder_agency_id =:bidderAgencyId',[':referralId'=>$referralId,':analysisId'=>$analysisId,':bidderAgencyId'=>$bidderAgencyId])->count();
    		return $testBid;
    	} else {
    		return 'false';
    	}
    }

    //function count all both unresponded referral and unseen bid notifications
    function countAllNotification($agencyId)
    {
    	if($agencyId > 0){
    		$countBidNotification = Bidnotification::find()->where('recipient_agency_id =:recipientAgencyId AND seen =:seen', [':recipientAgencyId'=>$agencyId,':seen'=>0])->count();

			$countReferralNotification = Notification::find()->where('recipient_id =:recipientId AND responded =:responded', [':recipientId'=>$agencyId,':responded'=>0])->count();
			$allNotification = $countReferralNotification + $countBidNotification;

			return $allNotification;
    	} else {
    		return 'false';
    	}
    }

    //check bidder
    function checkBidder($referralId,$agencyId)
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
    }
}