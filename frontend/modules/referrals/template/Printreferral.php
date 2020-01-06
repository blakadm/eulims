<?php

/*
 * Project Name: referral * 
 * Copyright(C)2019 Department of Science & Technology -IX * 
 * Developer: Egg  * 
 * 09 16, 19 , 10:50:25 AM * 
 * Module: Printreferral * 
 */

namespace frontend\modules\referrals\template;

use Yii;
use kartik\mpdf\Pdf;
use common\components\Functions;
use rmrevin\yii\fontawesome\FA;
use common\models\system\RstlDetails;
use common\models\referral\Referral;
use common\models\referral\Customer;
use Mpdf\Mpdf;
use common\models\referral\Formrequest;


/**
 * Description of Printreferral
 *
 * @author ts-ict5
 */
class Printreferral {
 var $referral;   
 var $mpdf;  
 var $total; 
 var $rstl_id;
 var $samples;
 var $analyses;
 var $customer;
 
 public function setReferral($referral) {
    $this->referral = $referral;
 }   
 public function setSamples($samples) {
    $this->samples = $samples;
 } 
 public function setAnalyses($analyses) {
    $this->analyses = $analyses;
 } 
 public function setCustomer($customer) {
    $this->customer = $customer;
 }

 public function Printing($id) {
    \Yii::$app->view->registerJsFile("css/pdf.css");
    $this->mpdf=new Mpdf();
    $this->mpdf->format =Pdf::FORMAT_A4;
    $this->mpdf->destination = Pdf::DEST_BROWSER;
    $this->mpdf->orientation = Pdf::ORIENT_PORTRAIT;
    $this->mpdf->defaultFontSize = 9;
    $this->mpdf->defaultFont = 'Arial';
    $html='<style>@page {
        margin-top: 1cm;
        margin-bottom: 1cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
    }</style>';
    $referral=$this->referral;
    $referral_code=$referral['referral_code'];
    $this->mpdf->WriteHTML($html);
    $this->mpdf->SetTitle($referral_code);
    $this->mpdf->SetHTMLHeader($this->Header());
    $this->printRows();
    $this->mpdf->SetHTMLFooter($this->Footer());  



    $this->mpdf->Output();
 }
 public function Header() {
    $this->rstl_id=Yii::$app->user->identity->profile->rstl_id;
    $RstlDetails = RstlDetails::find()->where(['rstl_id' => $this->rstl_id])->one();
    $referral=$this->referral;
   // $customer_id=$referral->customer_id;
    $customer=$this->customer;
    $class1 = '
    <style>
      table {
        border-collapse: collapse;
        font-size: 12px;
      }
    </style>
    '; 
    $ReferralTemplate = "<table border='0' width=100%>";
    $ReferralTemplate .= "<thead>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='text-align: center;font-size: 12px'>$RstlDetails->name</td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='text-align: center;font-size: 12px;font-weight: bold'>REGIONAL STANDARDS AND TESTING LABORATORIES</td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='width: 100%;text-align: center;font-size: 12px;word-wrap: break-word'><div style='width: 100px;'>$RstlDetails->address</div></td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='text-align: center;font-size: 12px'><div style='width: 100px;'>$RstlDetails->contacts</div></td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='text-align: center;font-size: 12px'>&nbsp;</td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12' style='text-align: center;font-weight: bold;font-size: 15px'>Request for " . strtoupper($RstlDetails->shortName) . " RSTL Services</td>";
    $ReferralTemplate .= "</tr>";
    $ReferralTemplate .= "<tr>";
    $ReferralTemplate .= "<td colspan='12'>&nbsp;</td>";
    $ReferralTemplate .= "</tr>"; 
    $ReferralTemplate .= "</thead>";
    $ReferralTemplate .= "</table>";

    $this->mpdf->WriteHTML($class1.$ReferralTemplate);
    $class = '
        <style>
          table {
            font-style: arial;
            border: 1px solid black;
            width: 46%;
            border-collapse: collapse;
          }
          table tr{
            border: 1px solid;
          }
          table tr td{
            text-align: left; 
            font-size: 12px;
          }
        </style>
    ';
                
    $referralNum = "Referral Code:";
                

    $html = '
        <br>
        <table>
            <tr>
                <td width="80">'.$referralNum.'</td>
                <td width="190">'.$referral['referral_code'].'</td>
            </tr>
            <tr>
                <td width="80">Date and Time:</td>
                <td width="190">'.$referral['referral_date_time'].'</td>
            </tr>
        </table>
    ';
    $this->mpdf->WriteHTML($class.$html);
    
    $classCustomer = '
                <style>
                  table {
                    font-style: arial;
                    border: 0.5px solid #000;
                    width:100%
                  }
                  td{
                    text-align: left;
                    valign: middle;
                    border-bottom: none;
                  }
                 
                </style>
            ';
			
    //$customerAddress = $referral->customer->houseNumber.' '.$referral->customer->barangay->name.' '.$referral->customer->municipality->name.', '.$referral->customer->province->name;
    $customerAddress="Address here";
    $customerDetails = '
        <br>
        <table>
            <tr>
                <td width="80">CUSTOMER:</td>
                <td width="380" style="border-right: 0.5px solid #000;font-size:10px">'.substr($customer['customer_name'],0,80).'</td>
                <td width="52">TEL NO.:</td>
                <td width="104" style="font-size:10px;">'.substr($customer['tel'],0,23).'</td>
            </tr>
            <tr>
                <td>ADDRESS:</td>
                <td style="border-right: 0.5px solid #000;font-size:10px;white-space:nowrap;">'.substr($customer['address'],0,80).'</td>
                <td>FAX NO.:</td>
                <td style="font-size:10px;">'.$customer['fax'].'</td>
            </tr>
        </table>
        </br>
    ';
    $this->mpdf->WriteHTML($classCustomer.$customerDetails);
    
    $testingTitle = '
        <b> <h5>1. TESTING OR CALIBRATION SERVICE </h5></b>
    ';

   
    $this->mpdf->WriteHTML($testingTitle); 
   
    
      

   
 }
 
 public function printRows() {
        $referral = $this->referral;

        $classRows = '
                <style>
                  table {
                    font-style: arial;
                    border-top: 0.5px solid #000;
                    border-left: 0.5px solid #000;
                  }
                  td{
                    border-right: 0.5px solid #000;
                    border-bottom: 0.5px solid #000;
                    
                  }
                  th{
                    border: 0.5px solid #000;
                    text-align: center;
                    vertical-align: bottom;
                  }
                </style>
            ';

        if($referral['payment_type_id'] == 2){
            $gratis = 'TOTAL <i style="font-size:7px;">(Gratis)</i>';
        } else {
            $gratis = 'TOTAL';
        }

        $rows = '<table>';
                $sampleCount = 0;
                $subTotal = 0;
                //foreach($request->samps as $sample){
        $rows .='<tr>
            <th>SAMPLE</th>
            <th>SAMPLE<br/>CODE</th>
            <th>TEST/<br/>CALIBRATION<br/>REQUESTED</th>
            <th>TEST <br/>METHOD</th>
            <th>NO. OF<br/>SAMPLES/<br/>UNITS</th>
            <th>UNIT<br/>COST</th>
            <th>TOTAL</th>
            </tr>';
            foreach($this->samples as $sample){

                $rows .='
                    <tr>
                        <td>'.$sample['sample_name'].'<br/></td>
                        <td style="text-align: center;hyphens: auto !important">'.$sample['sample_code'].'</td>
                        ';
                        $analysisCount = 0;
                        foreach($this->analyses as $analysis){
                            if($sample['sample_id'] == $analysis['sample_id']){
                                $checkpackageName = ($analysis['is_package_name'] == 2) ? $analysis['packages']['name'] : ($analysis['is_package_name'] == 1 ? "&nbsp;&nbsp;".$analysis['test_name'] : $analysis['test_name']);


                                $checkpackageFee = ($analysis['is_package_name'] == 1) ? "-" : number_format($analysis['analysis_fee'],2);
                                $checkUnit = ($analysis['is_package_name'] == 1) ? "" : "1";
                                $checkMethodRef = $analysis['methodreference_id'] == 0 ? "-" : $analysis['method'];

                                if($analysisCount != 0){
                                    $rows .='
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        <td>'.$checkpackageName.'</td>
                                        <td>'.$checkMethodRef.'</td>
                                        <td style="text-align: center">'.$checkUnit.'</td>
                                        <td style="text-align: right">'.$checkpackageFee.'</td>
                                        <td style="text-align: right">'.$checkpackageFee.'</td>
                                        ';
                                }else{
                                    $rows .= '
                                        <td>'.$checkpackageName.'</td>
                                        <td>'.$checkMethodRef.'</td>
                                        <td style="text-align: center">'.$checkUnit.'</td>
                                        <td style="text-align: right">'.$checkpackageFee.'</td>
                                        <td style="text-align: right">'.$checkpackageFee.'</td>
                                        ';
                                }
                                $rows .='</tr>';
                                $analysisCount = $analysisCount + 1;
                                $subTotal = $subTotal + $analysis['analysis_fee'];
                            }
                            
                        }

            }
         //$discount = $subTotal * $request->disc->rate/100;
         //$discount = $referral->discount->rate/100;
        // $discount = $subTotal * ($referral->discount->rate/100);
        $discount=0;
        $total = $subTotal - $discount;



         $rows .='
                                    <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr>
                                            <td></td><td></td><td></td><td></td>
                                            <td style="width: 52px; text-align: center"></td>
                                            <td style="width: 52px; text-align: right">Sub-Total</td>
                                            <td style="width: 52px; text-align: right">'.number_format($subTotal,2).'</td>
                                    </tr>
                                    <tr>
                                            <td></td><td></td><td></td><td></td>
                                            <td style="width: 52px; text-align: center"></td>
                                            <td style="width: 52px; text-align: right">Discount</td>
                                            <td style="width: 52px; text-align: right">'.number_format($discount,2).'</td>
                                    </tr>
                                    <tr>
                                            <td></td><td></td><td></td><td></td>
                                            <td style="width: 52px; text-align: right"></td>
                                            <td style="text-align: right; font-weight: bold;">'.$gratis.'</td>
                                            <td style="text-align: right; font-weight: bold;">'.number_format($total,2).'</td>
                                    </tr>';
        $rows .= '</table>';
       // $rows .= '<table><tr><td>&nbsp;</td></tr></table>';

        $this->mpdf->WriteHTML($classRows.$rows);

        $classDescription = '
            <style>
              table {
                font-style: arial;
                border: 0.5px solid #000;
              }
            </style>
        ';
                    $classDescriptionTitle = '
            <style>
              div {
                    font-size: 15;
                      }
            </style>
        ';

                    /** Sample Description **/

        $descriptionTitle = '
            <div><b><h5> 2. BRIEF DESCRIPTION OF SAMPLE/REMARKS</b> </h5></div>
        ';

       $this->mpdf->WriteHTML($classDescriptionTitle.$descriptionTitle);
			
       $classSampleDescription ='<style>
            table {
              font-style: arial;
              width: 100%;
            }

            td{
              text-align: left;
              valign: middle;
            }
            </style>';

        $sampleDescription = '<table>';

        foreach($this->samples as $sample)
        {
            //$sampleDescription .= '<tr><td>'.$sample->barcode.': '.$sample->description.'</td></tr>';
            $sampleDescription .= '<tr style="border:0px"><td>'.$sample['sample_code'].': '.$sample['description'].'</td></tr>';
        }
        $sampleDescription .= '</table>';

        $this->mpdf->WriteHTML($classSampleDescription.$sampleDescription);     
        
       /* $totalTemplate = "<table style='border:0px;'>";
        $totalTemplate .= "<tr><td height='100px'>&nbsp;</td></tr>";
        $totalTemplate .='<tr style="border:0px"><td style="text-align: right;font-size:13px"><u>'.number_format($total,2).'</u></td></tr>';
        $totalTemplate .= "</table>";*/
        $this->total=$total;
        
    }
    
    public function Footer() {
     $referral = $this->referral; 
     $totalTemplate= '<table width="100%" style="border:0px;vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;font-style: italic;">';
        if($referral['payment_type_id'] == 2){
            $totalTemplate .= '<tr style="border:0px"><td>TOTAL (Gratis)</td><td class="currency">P</td><td>'.number_format($this->total).'</td></tr>';
        } else {
           $totalTemplate .= '<tr style="border:0px"><td style="text-align: right;font-size:13px">TOTAL    &nbsp;     P&nbsp;<b><u>'.number_format($this->total,2).'</u></b></td></tr>';
        }
        $totalTemplate .= '</table> <br>';

        
     $ORdetails = '
          <table>
                
                <tr style="border-bottom:0px">
                    <td width="50" style="border-right:0px;border-bottom:0px">OR. NO.:</td>
                    <td width="249" style="border-right: 0.5px solid #000;border-bottom:0px"></td>
                    <td width="112" style="border-right:0px;border-bottom:0px">AMOUNT RECEIVED:</td>
                    <td width="156"></td>
                </tr>
                <tr style="border-top:0px">
                    <td style="border-right:0px;border-top:0px">DATE:</td>
                    <td style="border-right: 0.5px solid #000;border-top:0px"></td>
                    <td style="border-right:0px;border-top:0px">UNPAID BALANCE:</td>
                    <td></td>
                </tr>
            </table>
      ';
      
      $reportDue = '
      <br>
      <table>
          <tr>
              <td width="142">REPORT DUE ON:&nbsp;&nbsp;&nbsp; '.date("M. j, Y", strtotime($referral['report_due'])).'</td>
          </tr>
      </table>
     ';

     $signatories = '
     <br>
     <table>
         <tr>
             <td colspan="3" style="border-bottom: 0.5px solid #000;">DISCUSSED WITH CUSTOMER</td>
         </tr>
         <tr style="border-bottom:0px">
             <td width="189" style="border-right: 0.5px solid #000;border-bottom:0px">CONFORME:</td>
             <td width="189" style="border-right: 0.5px solid #000;border-bottom:0px"></td>
             <td width="189" style="border-right: 0.5px solid #000;border-bottom:0px"></td>
         </tr>
         <tr style="border-bottom:0px;border-top:0px"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
         <tr style="border-top:0px">
             <td style="text-align: center;">'.$referral['conforme'].'</td>
             <td style="text-align: center;">'.$referral['cro_receiving'].'</td>
             <td style="text-align: center;">'.''.'</td>
         </tr>
         <tr>
             <td style="text-align: center;">Customer/Authorized Representative</td>
             <td style="text-align: center;">Sample/s Received by:</td>
             <td style="text-align: center;">Sample/s Reviewed by:</td>
         </tr>
         <tr>
             <td colspan="3">REPORT NO.:</td>
         </tr>
     </table>
    ';

    $formrequest=Formrequest::find()->where(['agency_id' => $this->rstl_id])->one();    

    $paging ='{PAGENO}';

    $formDetails = '
        <table style="border:0px;padding-left: 2px; font-style: arial;">
            <tr style="border:0px;">
                <td style="text-align: left;border-right:0px;font-size:10px">Page '.$paging.'</td>
                <td style="text-align: right;valign: middle;font-size:10px">'.$formrequest->number.'</td>
            </tr>
            <tr style="border:0px">
                <td style="border-right:0px;">&nbsp;</td>
                <td style="text-align: right;valign: middle;font-size:10px">Rev. '.$formrequest->rev_num.' | '.$formrequest->rev_date.'</td>
            </tr>
        </table>
    '; 
     $footer=$totalTemplate;
     $footer .=$ORdetails;
     $footer .=$reportDue;
     $footer .=$signatories;
     $footer .=$formDetails;
      

     return $footer;
     
      
       
    }
}
