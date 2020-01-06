<?php

namespace frontend\modules\inventory\components\_class;
/**
 * 
 */
class Depreciatedcost
{
	public $amount;
	public $lengthofuse;
	public $date_received;


	public function getDepreciation()
	{
		//(original value - (5% orignal value)) / Length of Use
		$message="";
		if(!$this->amount){
			$message.="No Cost";
			return $message;
		}

		if(!$this->lengthofuse){
			$message.="No Length of Use";
			return $message;
		}

		if(!$this->date_received){
			$message.="No Received Date";
			return $message;
		}

		

		$d1 = new \DateTime($this->date_received);
		$d2 = new \DateTime('now');


		$diff=date_diff($d1,$d2); //the difference between two dates
		$yrcost = .05 * $this->amount;
		$cost = 0 ;
		if((int)$diff->format("%y")<(int)$this->lengthofuse){
			$cost = $this->amount - ((int)$diff->format("%y") * $yrcost);
		}else{
			$cost=$yrcost;
		}
		
		return $cost;
	}

}
?>