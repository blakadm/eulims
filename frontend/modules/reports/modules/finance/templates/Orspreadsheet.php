<?php

namespace frontend\modules\reports\modules\finance\templates;

use yii2tech\spreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use common\components\NumbersToWords;
use common\models\system\Rstl;
use common\models\finance\Paymentitem;
use common\models\finance\Check;
use common\models\finance\ExcessPayment;
 /**
* 
*/
class Orspreadsheet extends Spreadsheet
{

	/**
     * @var location the data provider for the view. This property is required.
     */
    public $location="";
    public $model; // model used for targeting specific cell for data placements
     //public $LOCATION =\Yii::$app->basePath.'\\modules\\reports\\modules\\lab\\templates\\';
    /**

	/**
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet|null $document spreadsheet document representation instance.
     */

	public function init(){
		$this->location = \Yii::$app->basePath.'/modules/reports/modules/finance/templates/';
                $this->loaddoc();
              //  $exporter->loaddoc()
                $this->send($this->model->or_number.'.xls');
        }

    public function loaddoc()
    {
		$paymentmode=$this->model->paymentMode->payment_mode;
		if($paymentmode == 'Cash'){
			$this->setDocument(IOFactory::load($this->location."cash.xls"));
		} else if($paymentmode == 'Check'){
			$this->setDocument(IOFactory::load($this->location."check.xls"));
		}
            
        $numbertowords=new NumbersToWords();
        
        $paymentitem_Query = Paymentitem::find()->where(['receipt_id' => $this->model->receipt_id])->all();
		
		
        $row=10;
        $count=0;
        
        $this->getDocument()->getActiveSheet()->setCellValue('C3', date('F d, Y',strtotime($this->model->receiptDate)));
        $rstl= Rstl::find()->where(['rstl_id'=>$this->model->rstl_id])->one();
        $this->getDocument()->getActiveSheet()->setCellValue('B5', $rstl->name);
        $this->getDocument()->getActiveSheet()->setCellValue('B6', $this->model->payor);
        $this->getDocument()->getActiveSheet()->setCellValue('A9', $this->model->collectiontype ? $this->model->collectiontype->natureofcollection : "");
        foreach ($paymentitem_Query as $i => $or) {
           $this->getDocument()->getActiveSheet()->setCellValue('A'.$row, $or['details']);
           $this->getDocument()->getActiveSheet()->setCellValue('D'.$row, number_format($or['amount'],2));
           $row++; 
        }
		//for excess payment from check to customer wallet
		$excess_amount=0;
		$excesspayment_Query = ExcessPayment::find()->where(['receipt_id' => $this->model->receipt_id])->all();
		foreach ($excesspayment_Query as $i => $excess) {
		   $this->getDocument()->getActiveSheet()->setCellValue('A'.$row, "Credited to Customer Wallet");
           $this->getDocument()->getActiveSheet()->setCellValue('D'.$row, number_format($excess['amount'],2));
		   $excess_amount=$excess['amount'];
		}
		//////////////////////
		$gtotal=$this->model->total + $excess_amount;
		
		$amountinwords=$numbertowords->convert($gtotal);
        $whole_number=(int)$gtotal;
        $remainder=$gtotal - $whole_number;
        if (!$remainder){
            $amountinwords.=" And 00/100";
        }
		
        $this->getDocument()->getActiveSheet()->setCellValue('D19', number_format($gtotal,2));
        $this->getDocument()->getActiveSheet()->setCellValue('A20','                                        '.$amountinwords);
        
        
        $cash='';
        $check='';
        $mo='';
        $space="";
        for($x=0;$x<12;$x++){
            $space.="&nbsp;";
        }
        if($paymentmode == 'Cash'){
            $this->getDocument()->getActiveSheet()->setCellValue('A23', "/");
        }
        else if($paymentmode == 'Check'){
            $row1=24;
            $this->getDocument()->getActiveSheet()->setCellValue('A24', "/");
            $check_Query = Check::find()->where(['receipt_id' => $this->model->receipt_id])->all();
            foreach ($check_Query as $i => $check) {
                $bnk=$check['bank'].'     '.$check['checknumber'] .'     '.$check['checkdate'];
                $this->getDocument()->getActiveSheet()->setCellValue('B'.$row1, $bnk);				
                $row1++;
            }
        }
        else if($paymentmode == 'Money Order'){
            $this->getDocument()->getActiveSheet()->setCellValue('A29', "/");
        }

        
         #set password
         /*$this->getDocument()->getActiveSheet()->getProtection()->setSheet(true);
         $this->getDocument()->getSecurity()->setLockWindows(true);
         $this->getDocument()->getSecurity()->setLockStructure(true);
         $this->getDocument()->getSecurity()->setWorkbookPassword("PhpSpreadsheet"); */

        // Parent::setDocument($document);
    }

     public function render()
    {
        //overrides the render so that it would do nothing with cdataactiveprovider
        return $this;
    }

   
}

?>