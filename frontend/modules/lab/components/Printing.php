<?php



namespace frontend\modules\lab\components;

use kartik\mpdf\Pdf;
use common\models\system\RstlDetails;
use common\components\Functions;
use rmrevin\yii\fontawesome\FA;
use common\models\lab\Sample;
use common\models\lab\Request;
use common\models\lab\Analysis;
/**
 * Description of RequestPrinting
 *
 * @author OneLab
 */
class Printing {


    public function actionPrintCsi(){
        $Func = new Functions();
        $Proc = "spGetRequestService(:nRequestID)";
        $Params = [':nRequestID' => $id];
        $Connection = \Yii::$app->labdb;
        $RequestRows = $Func->ExecuteStoredProcedureRows($Proc, $Params, $Connection);
        $RequestHeader = (object) $RequestRows[0];
        $rstl_id = $RequestHeader->rstl_id;
        $RstlDetails = RstlDetails::find()->where(['rstl_id' => $rstl_id])->one();
        $border=0;//Border for adjustments
        if ($RstlDetails) {
            $RequestTemplate = "<table border='$border' style='font-size: 8px' width=100%>";
            $RequestTemplate .= "<thead><tr><td colspan='7' style='height: 110px;text-align: center'>&nbsp;</td></tr></thead>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='width: 50px;height: 15px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 50px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 190px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 170px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 85px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 85px'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Nolan Sunico</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>12345</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Recodo Zamboanga City</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>11/14/2018</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Tel Fax #</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>PR #/PO #</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Project Name</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>TIN #</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Address 2</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Tel Fax 2</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Email Address</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr><td colspan='7' style='height: 45px;text-align: center'>&nbsp;</td></tr>";
            $CounterLimit=12;
            for($counter=1;$counter<=$CounterLimit;$counter++){
                $RequestTemplate .= "<tr>";
                $RequestTemplate .= "<td style='text-align: center;height: 23px'>23</td>";
                $RequestTemplate .= "<td colspan='2'>Sample Description</td>";
                $RequestTemplate .= "<td style='padding-left: 5px'>Sample Code</td>";
                $RequestTemplate .= "<td colspan='2'>Analysis/Sampling Method</td>";
                $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>11234.00</td>";
                $RequestTemplate .= "</tr>";
            }
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td valign='bottom' style='text-align: right;padding-right: 10px;height: 15px'>tot.am</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4' style='height: 10px;font-size: 7px;font-weight: bold'>";
            $RequestTemplate .= "<i class='fa fa-check'>/</i>";
            $RequestTemplate .= "</td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>vat.am</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td style='text-align: left;padding-left: 50px'>CRO</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>11/14/2018 04:32 PM</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td valign='top' style='text-align: right;padding-right: 10px'>tot.pa</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='6' valign='top' style='padding-top: 30px' rowspan='2'>Special Instructions</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px;height: 25px'>Deposit O.R</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px;height: 25px'>Bal.00</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7' style='padding-left: 5px;height: 25px'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7' style='padding-left: 5px'>This is my Remarks</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "</table>";
            $RequestTemplate .= "<table border='$border' style='border-collapse: collapse;font-size: 12px' width=100%>";
            $RequestTemplate .= "<tr><td colspan='4' style='height: 50px;text-align: center'>&nbsp;</td></tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr><td colspan='4' style='height: 50px;text-align: center'>&nbsp;</td></tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "</table>";
        }
        return $RequestTemplate;
     
    
      }
    public function PrintRequest($id) {
        
        \Yii::$app->view->registerJsFile("css/pdf.css");
        $config= \Yii::$app->components['reports'];
        $ReportNumber=(int)$config['ReportNumber'];
       
        if($ReportNumber==1){
             $mTemplate = $this->RequestTemplate($id);
        }elseif($ReportNumber==2){
            $mTemplate=$this->FastReport($id);
        }else{// in case does not matched any
            $mTemplate="<div class='col-md-12 danger'><h3>Report Configuration is not properly set.</h3></div>";
        }
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
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'R' => [
                'content' => '',
                'font-size' => 0,
                'font-style' => 'B',
                'font-family' => 'arial',
                'color' => '#333333',
            ],
            'line' => false,
        ];
        $mPDF = new Pdf(['cssFile' => 'css/pdf.css']);
        //$html = mb_convert_encoding($mTemplate, 'UTF-8', 'UTF-8');
        //$mPDF=$PDF->api;
        $mPDF->content = $mTemplate;
        $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
        $mPDF->defaultFontSize = 8;
        $mPDF->defaultFont = 'Arial';
        $mPDF->format =Pdf::FORMAT_A4;
        $mPDF->destination = Pdf::DEST_BROWSER;
        $mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
        $mPDF->render();
        exit;
    }
    public function PrintReportcsi($id) {
        
        \Yii::$app->view->registerJsFile("css/csi_styles.css");
      
        $mPDF = new Pdf(['cssFile' => 'css/csi_styles.css']);
          
        $csi = '<body>
        <div class="header">
          <p><strong>Customer Satisfaction Measurement Report</strong></p>
          <p><span>For the month of June 2019</span></p>
        </div>
        <div class="content">
          <table>
            <tbody>
              <tr>
                <td colspan="2">
                  <strong >I. Information</strong>
                </td>
              </tr>
              <tr>
                <td >No. of Customers</td>
                <td>
                  <!-- NUMBER OF CUSTOMERS -->
                  <strong>24</strong>
                </td>
              </tr>
              <tr>
                <td>Type of Industry</td>
                <td>
                  <ul>
                    <!-- LOOP THROUGH EACH TYPE OF INDUSTRIES -->
                    <li>3 Academe</li>
                    <li>7 Canned/Bottled Fish</li>
                    <li>1 Seaweeds</li>
                    <li>5 Petroleum products/haulers</li>
                    <li>3 Marine Products</li>
                    <li>3 Fishmeal</li>
                    <li>1 Rubber</li>
                    <li>2 Hospital</li>
                    <li>6 Others</li>
                  </ul>
                </td>
              </tr>
              <tr>
                <td>Type of market</td>
                <td>
                  <ul>
                    <!-- LOOP THROUGH EACH TYPE OF MARKET -->
                    <li>Local - 19</li>
                    <li>Export - 3</li>
                    <li>Both - 3</li>
                  </ul>
                </td>
              </tr>
              <tr>
                <td>
                  <strong>II. Delivery of Service</strong>
                </td>
                <td>
                  <table style="table-layout: fixed;">
                    <thead>
                      <tr>
                        <th>TOTAL SCORE</th>
                        <th>Importance Score</th>
                        <th>WF</th>
                        <th>Satisfaction Score</th>
                        <th>Weighted Score</th>
                      </tr>
                    </thead>
                    <tbody class="delivery-of-service">
                      <tr>
                        <td colspan="5">
                          <strong>Delivery Time</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>0</td><!-- : TOTAL SCORE -->
                        <td>0</td><!-- : Importance Score -->
                        <td>0</td><!-- : WF -->
                        <td>0</td><!-- : Satisfaction Score -->
                        <td>0</td><!-- : Weighted Score -->
                      </tr>
                      <tr>
                        <td colspan="5">
                          <strong>Correctness and accuracy of test results</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>0</td><!-- Delivery Time: TOTAL SCORE -->
                        <td>0</td><!-- Delivery Time: Importance Score -->
                        <td>0</td><!-- Delivery Time: WF -->
                        <td>0</td><!-- Delivery Time: Satisfaction Score -->
                        <td>0</td><!-- Delivery Time: Weighted Score -->
                      </tr>
                      <tr>
                        <td colspan="5">
                          <strong>Speed of service</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>0</td><!-- Speed of Service: TOTAL SCORE -->
                        <td>0</td><!-- Speed of Service: Importance Score -->
                        <td>0</td><!-- Speed of Service: WF -->
                        <td>0</td><!-- Speed of Service: Satisfaction Score -->
                        <td>0</td><!-- Speed of Service: Weighted Score -->
                      </tr>
                      <tr>
                        <td colspan="5">
                          <strong>Cost</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>0</td><!-- Cost: TOTAL SCORE -->
                        <td>0</td><!-- Cost: Importance Score -->
                        <td>0</td><!-- Cost: WF -->
                        <td>0</td><!-- Cost: Satisfaction Score -->
                        <td>0</td><!-- Cost: Weighted Score -->
                      </tr>
                      <tr>
                        <td colspan="5">
                          <strong>Attitude of staff</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>0</td><!-- Attitude of Staff: TOTAL SCORE -->
                        <td>0</td><!-- Attitude of Staff: Importance Score -->
                        <td>0</td><!-- Attitude of Staff: WF -->
                        <td>0</td><!-- Attitude of Staff: Satisfaction Score -->
                        <td>0</td><!-- Attitude of Staff: Weighted Score -->
                      </tr>
                      <tr>
                        <td></td>
                        <td>0</td><!-- Total Importance Score  -->
                        <td>0</td><!-- Total WF -->
                        <td></td>
                        <td>0</td><!-- Total Weighted Score -->
                      </tr>
                      <tr class="highlight">
                        <td colspan="4">
                          <strong>SATISFACTION INDEX:</strong>
                        </td>
                        <td>0</td><!-- SATISFACTION INDEX -->
                      </tr>
                      <tr class="highlight">
                        <td colspan="4">
                          <strong>OVER-ALL CUSTOMER EXPERIENCE:</strong>
                        </td>
                        <td>0</td><!-- OVER-ALL CUSTOMER EXPERIENCE -->
                      </tr>
                      <tr class="highlight">
                        <td colspan="4">
                          <strong>NET PROMOTER SCORE:</strong>
                        </td>
                        <td>0</td><!-- NET PROMOTER SCORE -->
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <strong>III. Comments/Suggestions</strong>
                </td>
                <td>
                  <ol>
                    <!-- LOOP THROUGH EACH COMMENT -->
                
                  </ol>
                </td>
              </tr>
              <tr>
                <td>
                  <strong>IV. Actions</strong>
                </td>
                <td></td><!-- ACTIONS TAKEN -->
              </tr>
            </tbody>
          </table>
        </div>
        <div class="footer">
          <div></div>
          <div></div>
          <div>
            <p>Processed by:</p>
            <div>
              <strong>ROSEMARIE S. SALAZAR</strong>
              <p>Quality Manager</p>
            </div>
          </div>
        </div>
      </body>';
        $mPDF->content = $csi;
        $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
      //  $mPDF->defaultFontSize = 80;
      //  $mPDF->defaultFont = 'Arial';
        $mPDF->format =Pdf::FORMAT_A4;
        $mPDF->destination = Pdf::DEST_BROWSER;
      //  $mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
        $mPDF->render();
        exit;
    }

    public function PrintReportmonthly($id) {
        
      \Yii::$app->view->registerJsFile("css/monthly.css");
    
      $mPDF = new Pdf(['cssFile' => 'css/monthly.css']);
        
      $csi = '<body>
      <div class="header">
        <p><strong>Customer Satisfaction Feedback</strong></p>
        <p><span>DOST Regional Office No. IX</span></p>
        <br />
      </div>
      <div class="content">
        <table>
          <thead>
            <tr>
              <th colspan="2">Technical Services: Regional Standards and Testing Laboratories</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="width: 25%;">For the period:</td>
              <td>June 2019</td><!-- PERIOD DATE-->
            </tr>
            <tr>
              <td style="width: 25%;">Total no. of Respondents:</td>
              <td>24</td><!-- No. of Respondents -->
            </tr>
          </tbody>
        </table>
        <br />
    
        <!-- PART I -->
        <table>
          <tbody>
            <tr>
              <th colspan="14">PART I: Customer Rating of Service Quality</th>
            </tr>
            <tr>
              <th>Service Quality Items</th>
              <th class="font-small">Very Satisfied</th>
              <th class="bg-grey">5</th>
              <th class="font-small">Quite Satisfied</th>
              <th class="bg-grey">4</th>
              <th class="font-small">N Sat nor D Sat</th>
              <th class="bg-grey">3</th>
              <th class="font-small">Quite Dissatisfied</th>
              <th class="bg-grey">2</th>
              <th class="font-small">Very Dissatisfied</th>
              <th class="bg-grey">1</th>
              <th class="font-small">Total Score</th>
              <th>SS</th>
              <th>GAP</th>
            </tr>
            <tr>
              <td>Delivery Time</td>
              <td>21</td><!-- Very Satisfied -->
              <td class="bg-grey color">105</td><!-- 5 -->
              <td>3</td><!-- Quite Satisfied -->
              <td class="bg-grey color">12</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">117</td><!-- TOTAL SCORE -->
              <td class="color2">4.87</td><!-- SS -->
              <td>0.04</td><!-- GAP -->
            </tr>
            <tr>
              <td>Correctness and Accuracy of Results</td>
              <td>22</td><!-- Very Satisfied -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Satisfied -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- SS -->
              <td>0.00</td><!-- GAP -->
            </tr>
            <tr>
              <td>Speed of Service</td>
              <td>21</td><!-- Very Satisfied -->
              <td class="bg-grey color">105</td><!-- 5 -->
              <td>3</td><!-- Quite Satisfied -->
              <td class="bg-grey color">12</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">117</td><!-- TOTAL SCORE -->
              <td class="color2">4.88</td><!-- SS -->
              <td>0.04</td><!-- GAP -->
            </tr>
            <tr>
              <td>Cost</td>
              <td>21</td><!-- Very Satisfied -->
              <td class="bg-grey color">105</td><!-- 5 -->
              <td>3</td><!-- Quite Satisfied -->
              <td class="bg-grey color">12</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">117</td><!-- TOTAL SCORE -->
              <td class="color2">4.88</td><!-- SS -->
              <td>0.04</td><!-- GAP -->
            </tr>
            <tr>
              <td>Attitude of Staff</td>
              <td>22</td><!-- Very Satisfied -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Satisfied -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- SS -->
              <td>0.00</td><!-- GAP -->
            </tr>
            <tr>
              <td>Over-all Customer Experience</td>
              <td>21</td><!-- Very Satisfied -->
              <td class="bg-grey color">87.50</td><!-- 5 -->
              <td>3</td><!-- Quite Satisfied -->
              <td class="bg-grey color">12.50</td><!-- 4 -->
              <td>0</td><!-- N Sat nor D Sat -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Very Dissatisfied -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">100</td><!-- TOTAL SCORE -->
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <br />
    
        <!-- PART II -->
        <table>
          <thead>
            <tr>
              <th colspan="16">PART II: Importance of these Attributes to the Customers</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Importance of Service Quality Attributes to Customers</th>
              <th class="font-small">Very Important</th>
              <th class="bg-grey">5</th>
              <th class="font-small">Quite Important</th>
              <th class="bg-grey">4</th>
              <th class="font-small">Neither Imp nor Unimp</th>
              <th class="bg-grey">3</th>
              <th class="font-small">Quite Unimportant</th>
              <th class="bg-grey">2</th>
              <th class="font-small">Not Important at All</th>
              <th class="bg-grey">1</th>
              <th class="font-small">TOTAL SCORE</th>
              <th>IS</th>
              <th>WF</th>
              <th>SS</th>
              <th>WS</th>
            </tr>
            <tr>
              <td>Delivery Time</td>
              <td>22</td><!-- Very Important -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Important -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- Neither Imp no Unimp -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Unimportant -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Not Important at all -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- IS -->
              <td>20.00</td><!-- WF -->
              <td>4.88</td><!-- SS -->
              <td>0.98</td><!-- WS -->
            </tr>
            <tr>
              <td>Correctness and Accuracy of Results</td>
              <td>22</td><!-- Very Important -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Important -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- Neither Imp no Unimp -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Unimportant -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Not Important at all -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- IS -->
              <td>20.00</td><!-- WF -->
              <td>4.92</td><!-- SS -->
              <td>0.98</td><!-- WS -->
            </tr>
            <tr>
              <td>Speed of Delivery</td>
              <td>22</td><!-- Very Important -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Important -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- Neither Imp no Unimp -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Unimportant -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Not Important at all -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- IS -->
              <td>20.00</td><!-- WF -->
              <td>4.88</td><!-- SS -->
              <td>0.98</td><!-- WS -->
            </tr>
            <tr>
              <td>Cost</td>
              <td>22</td><!-- Very Important -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Important -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- Neither Imp no Unimp -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Unimportant -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Not Important at all -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- IS -->
              <td>20.00</td><!-- WF -->
              <td>4.88</td><!-- SS -->
              <td>0.98</td><!-- WS -->
            </tr>
            <tr>
              <td>Attitude of Staff</td>
              <td>22</td><!-- Very Important -->
              <td class="bg-grey color">110</td><!-- 5 -->
              <td>2</td><!-- Quite Important -->
              <td class="bg-grey color">8</td><!-- 4 -->
              <td>0</td><!-- Neither Imp no Unimp -->
              <td class="bg-grey color">0</td><!-- 3 -->
              <td>0</td><!-- Quite Unimportant -->
              <td class="bg-grey color">0</td><!-- 2 -->
              <td>0</td><!-- Not Important at all -->
              <td class="bg-grey color">0</td><!-- 1 -->
              <td class="color">118</td><!-- TOTAL SCORE -->
              <td class="color2">4.92</td><!-- IS -->
              <td>20.00</td><!-- WF -->
              <td>4.92</td><!-- SS -->
              <td>0.98</td><!-- WS -->
            </tr>
            <tr>
              <td colspan="12"></td>
              <td>24.58</td><!-- TOTAL IS -->
              <td>80.00</td><!-- TOTAL WF -->
              <td></td>
              <td>4.89</td><!-- TOTAL WS  -->
            </tr>
            <tr class="highlight">
              <td colspan="15" style="text-align: right;">
                <strong>SATISFACTION INDEX:</strong>
              </td>
              <td>97.83</td><!-- SATISFACTION INDEX -->
            </tr>
          </tbody>
        </table>
        <br />
    
       
        
        </div>
        <br />
    
        <table style="table-layout: fixed;">
          <thead>
            <tr>
              <th>Detractors (0-6)</th>
              <th>Passives (7-8)</th>
              <th>Promoters(9-10)</th>
              <th>Net Promoter Score</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>0</td>
              <td>1</td>
              <td>7</td>
              <td>87.5</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="footer"></div>
    </body>';
      $mPDF->content = $csi;
      $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
    //  $mPDF->defaultFontSize = 80;
    //  $mPDF->defaultFont = 'Arial';
      $mPDF->format =Pdf::FORMAT_A4;
      $mPDF->destination = Pdf::DEST_BROWSER;
    //  $mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
      $mPDF->render();
      exit;
  }

  public function PrintReportdaily($id) {
        
    \Yii::$app->view->registerJsFile("css/day.css");
  
    $mPDF = new Pdf(['cssFile' => 'css/day.css']);
      
    $csi = '<body>
    <div class="header">
      <p>Department of Science and Technology</p>
      <p>REGIONAL STANDARDS AND TESTING LABORATORIES</p>
      <p>Pettit barracks, Zamboanga City</p>
      <p>TEl. No. (63) (62) 991-1024; Fax No. (63) (62) 992-1114</p>
    </div>
    <div class="content">
      <h1>Customer Satisfaction Feedback Survey</h1>
      <br />
  
      <h2>I. Information</h2>
      <table>
        <tbody>
          <tr>
            <td>
              Customer Name: <strong></strong><!-- Customer Name -->
            </td>
          </tr>
          <tr>
            <td>
              Nature of Business:
              <div class="checkbox">
                <div>
                  <div><input type="checkbox" name="" id=""> Raw and processed food</div>
                  <div><input type="checkbox" name="" id=""> Marine products</div>
                  <div><input type="checkbox" name="" id=""> Canned/Bottled Fish</div>
                  <div><input type="checkbox" name="" id=""> Fishmeal</div>
                  <div><input type="checkbox" name="" id=""> Seaweeds</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Petroleum Products/Haulers</div>
                  <div><input type="checkbox" name="" id=""> Mining</div>
                  <div><input type="checkbox" name="" id=""> Hospitals</div>
                  <div><input type="checkbox" name="" id=""> Academe/Students</div>
                  <div><input type="checkbox" name="" id=""> Beverage and juices</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Government/LGUs</div>
                  <div><input type="checkbox" name="" id=""> Construction</div>
                  <div><input type="checkbox" name="" id=""> Water Refilling/Bottled Water</div>
                  <div><input type="checkbox" name="" id=""> Others</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              Type of Market:
              <div class="checkbox">
                <div>
                  <div><input type="checkbox" name="" id=""> Local</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Export</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Both</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              What services of the RSTL have you availed?
              <div class="checkbox">
                <div>
                  <div><input type="checkbox" name="" id=""> Microbiological Testing</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Chemical Testing</div>
                </div>
                <div>
                  <div><input type="checkbox" name="" id=""> Calibration</div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <br />
  
      <h2>II. Delivery of Service</h2>
      <table class="custom-table">
        <thead>
          <tr>
            <th>Service Quality Items</th>
            <th>Very Satisfied (5)</th>
            <th>Quite Satisfied (4)</th>
            <th>Neither satisfied nor Dissatisfied (3)</th>
            <th>Quite Dissatisfied (2)</th>
            <th>Very Dissatisfied (1)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Delivery Time</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
          <tr>
            <td>Correctness and accuracy of test results</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
          <tr>
            <td>Speed of service</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
          <tr>
            <td>Cost</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
          <tr>
            <td>Attitude of staff</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
          <tr>
            <td>Over-all customer experience</td>
            <td><input type="checkbox" name="" id=""></td><!-- 5 -->
            <td><input type="checkbox" name="" id=""></td><!-- 4 -->
            <td><input type="checkbox" name="" id=""></td><!-- 3 -->
            <td><input type="checkbox" name="" id=""></td><!-- 2 -->
            <td><input type="checkbox" name="" id=""></td><!-- 1 -->
          </tr>
        </tbody>
      </table>
      <br />
  
      <h2>III. How <span>important</span> are these items to you?</h2>
      <table class="custom-table">
        <thead>
          <tr>
            <th>Service Quality Items</th>
            <th>Very important (5)</th>
            <th>Quite important (4)</th>
            <th>Neither important nor unimportant (3)</th>
            <th>Quite unimportant (2)</th>
            <th>Not at all important (1)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Delivery Time</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
          <tr>
            <td>Correctness and accuracy of test results</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
          <tr>
            <td>Speed of service</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
          <tr>
            <td>Cost</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
          <tr>
            <td>Attitude of staff</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
          <tr>
            <td>Over-all customer experience</td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
        </tbody>
      </table>
      <br />
  
      <h2>IV. How likely is it that you would <span>recommend</span> our service to others?</h2>
      <table style="table-layout: fixed;" class="thtd-center">
        <thead>
          <tr>
            <th>0<br /><span style="font-size: smaller;">Not at all likely</span></th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10<br /><span style="font-size: smaller;">Extremely likely</span></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
            <td><input type="checkbox" name="" id=""></td>
          </tr>
        </tbody>
      </table>
      <br />
  
      <h2>V. Please give us your comments/suggestions to improve our services. Also, let us know other tests you require that we are not able to provide yet.</h2>
      <hr>
      <hr>
      <hr>
    </div>
    <div class="footer">
      <div class="checkbox" style="margin: 30px 0;">
        <div style="flex: 1; display: flex;">
          Feedback given by:
          <div style="
            flex: 1;
            border-bottom: 1px solid #000;
            margin: 0 10px 0 5px;
            padding: 0 2px;
          ">
           
          </div>
        </div>
        <div style="flex: 1; display: flex;">
          Date:
          <div style="
            flex: 1;
            border-bottom: 1px solid #000;
            margin-left: 5px;
            padding: 0 2px;
          ">
            6-29-19
          </div>
        </div>
      </div>
    </div>
  </body>';
    $mPDF->content = $csi;
    $mPDF->orientation = Pdf::ORIENT_PORTRAIT;
  //  $mPDF->defaultFontSize = 80;
  //  $mPDF->defaultFont = 'Arial';
    $mPDF->format =Pdf::FORMAT_A4;
    $mPDF->destination = Pdf::DEST_BROWSER;
  //  $mPDF->methods =['SetFooter'=>['|{PAGENO}|']];
    $mPDF->render();
    exit;
}

    private function FastReport($id){
        $Func = new Functions();
        $Proc = "spGetRequestService(:nRequestID)";
        $Params = [':nRequestID' => $id];
        $Connection = \Yii::$app->labdb;
        $RequestRows = $Func->ExecuteStoredProcedureRows($Proc, $Params, $Connection);
        $RequestHeader = (object) $RequestRows[0];
        $rstl_id = $RequestHeader->rstl_id;
        $RstlDetails = RstlDetails::find()->where(['rstl_id' => $rstl_id])->one();
        $border=0;//Border for adjustments
        if ($RstlDetails) {
            $RequestTemplate = "<table border='$border' style='font-size: 8px' width=100%>";
            $RequestTemplate .= "<thead><tr><td colspan='7' style='height: 110px;text-align: center'>&nbsp;</td></tr></thead>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='width: 50px;height: 15px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 50px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 190px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 170px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 85px'>&nbsp;</td>";
            $RequestTemplate .= "<td style='width: 85px'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Nolan Sunico</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>12345</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Recodo Zamboanga City</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>11/14/2018</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Tel Fax #</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>PR #/PO #</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Project Name</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>TIN #</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Address 2</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Tel Fax 2</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td>Email Address</td>";
            $RequestTemplate .= "<td colspan='3'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr><td colspan='7' style='height: 45px;text-align: center'>&nbsp;</td></tr>";
            $CounterLimit=12;
            for($counter=1;$counter<=$CounterLimit;$counter++){
                $RequestTemplate .= "<tr>";
                $RequestTemplate .= "<td style='text-align: center;height: 23px'>23</td>";
                $RequestTemplate .= "<td colspan='2'>Sample Description</td>";
                $RequestTemplate .= "<td style='padding-left: 5px'>Sample Code</td>";
                $RequestTemplate .= "<td colspan='2'>Analysis/Sampling Method</td>";
                $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>11234.00</td>";
                $RequestTemplate .= "</tr>";
            }
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td valign='bottom' style='text-align: right;padding-right: 10px;height: 15px'>tot.am</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4' style='height: 10px;font-size: 7px;font-weight: bold'>";
            $RequestTemplate .= "<i class='fa fa-check'>/</i>";
            $RequestTemplate .= "</td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>vat.am</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td style='text-align: left;padding-left: 50px'>CRO</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'>11/14/2018 04:32 PM</td>";
            $RequestTemplate .= "<td>&nbsp;</td>";
            $RequestTemplate .= "<td></td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='4'>&nbsp;</td>";
            $RequestTemplate .= "<td colspan='2'>&nbsp;</td>";
            $RequestTemplate .= "<td valign='top' style='text-align: right;padding-right: 10px'>tot.pa</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='6' valign='top' style='padding-top: 30px' rowspan='2'>Special Instructions</td>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px;height: 25px'>Deposit O.R</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='text-align: right;padding-right: 10px;height: 25px'>Bal.00</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7' style='padding-left: 5px;height: 25px'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7' style='padding-left: 5px'>This is my Remarks</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='7'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "</table>";
            $RequestTemplate .= "<table border='$border' style='border-collapse: collapse;font-size: 12px' width=100%>";
            $RequestTemplate .= "<tr><td colspan='4' style='height: 50px;text-align: center'>&nbsp;</td></tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr><td colspan='4' style='height: 50px;text-align: center'>&nbsp;</td></tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Printed Name and Signature</td>";
            $RequestTemplate .= "<td style='padding-left: 10px'>Date and Time</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "</table>";
        }
        return $RequestTemplate;
    }
    private function RequestTemplate($id) {

        $Func = new Functions();
        $Proc = "spGetRequestServices(:nRequestID)";
        $Params = [':nRequestID' => $id];
        $Form="OP-007-F1"."<br>"."Rev. 06 | 11.04.19";
        $Connection = \Yii::$app->labdb;
        
        $request = Request::find()->where(['request_id' => $id])->one();
        $completeaddress = $request->customer->completeaddress;

        $RequestRows = $Func->ExecuteStoredProcedureRows($Proc, $Params, $Connection);
        $RequestHeader = (object) $RequestRows[0];
       
        $rstl_id = $RequestHeader->rstl_id;
       
        $RstlDetails = RstlDetails::find()->where(['rstl_id' => $rstl_id])->one();
        if ($RstlDetails) {
            
            $RequestTemplate = "<table border='0' style='border-collapse: collapse;font-size: 10px' width=100%>";
            $RequestTemplate .= "<thead>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='text-align: center;font-size: 12px'>$RstlDetails->name</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='text-align: center;font-size: 12px;font-weight: bold'>REGIONAL STANDARDS AND TESTING LABORATORIES</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            //$RequestTemplate .= "<td colspan='3' style='width: 30%'></td>";
            $RequestTemplate .= "<td colspan='10' style='width: 100%;text-align: center;font-size: 12px;word-wrap: break-word'><div style='width: 100px;'>$RstlDetails->address</div></td>";
            //$RequestTemplate .= "<td colspan='3' style='width: 30%'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            //$RequestTemplate .= "<td colspan='3'></td>";
            $RequestTemplate .= "<td colspan='10' style='text-align: center;font-size: 12px'><div style='width: 100px;'>$RstlDetails->contacts</div></td>";
            //$RequestTemplate .= "<td colspan='3'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='text-align: center;font-size: 12px'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='text-align: center;font-weight: bold;font-size: 15px'>Request for " . strtoupper($RstlDetails->shortName) . " RSTL Services</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "</thead>";
            $RequestTemplate .= "<tbody>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td>Req. Ref. No.:</td>";
            $RequestTemplate .= "<td colspan='4' style='text-align: left'>$RequestHeader->request_ref_num</td>";
            $RequestTemplate .= "<td colspan='5'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td>Date and Time:</td>";
            $RequestTemplate .= "<td colspan='4' style='text-align: left'>" . date('m/d/Y h:i A', strtotime($RequestHeader->request_datetime)) . "</td>";
            $RequestTemplate .= "<td colspan='5'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='height: 5px'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='border-top: 1px solid black;border-left: 1px solid black'>CUSTOMER:</td>";
            $RequestTemplate .= "<td colspan='6' style='border-top: 1px solid black;'>$RequestHeader->customer_name</td>";
            $RequestTemplate .= "<td style='border-top: 1px solid black;'>TEL #:</td>";
            $RequestTemplate .= "<td colspan='2' style='border-top: 1px solid black;border-right: 1px solid black'>$RequestHeader->tel</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td style='border-bottom: 1px solid black;border-left: 1px solid black'>ADDRESS:</td>";
            $RequestTemplate .= "<td colspan='6' style='border-bottom: 1px solid black;border-bottom: 1px solid black;'>$completeaddress</td>";
         //   $RequestTemplate .= "<td colspan='6' style='border-bottom: 1px solid black;border-bottom: 1px solid black;'>$RequestHeader->address</td>";
            $RequestTemplate .= "<td style='border-bottom: 1px solid black;'>FAX #:</td>";
            $RequestTemplate .= "<td colspan='2' style='border-bottom: 1px solid black;border-right: 1px solid black'>$RequestHeader->fax</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<th colspan='10' class='text-left border-bottom-line'>1.0 TESTING OR CALIBRATION SERVICE</th>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<th colspan='2' class='text-center border-center-line border-left-line border-right-line padding-left-5' style=''>SAMPLE</th>";
            $RequestTemplate .= "<th class='text-center border-bottom-line border-right-line padding-left-5' style='width: 15%;'>SAMPLE CODE</th>";
            $RequestTemplate .= "<th colspan='2' class='text-center border-bottom-line border-right-line padding-left-5' style='width: 15%;'>TEST/CALIBRATION REQUESTED</th>";
            $RequestTemplate .= "<th colspan='2' class='text-center border-bottom-line border-right-line padding-left-5' style='width: 15%;'>TEST METHOD</th>";
            $RequestTemplate .= "<th class='text-center border-bottom-line border-right-line padding-left-5' style='width: 9%;'>NO OF SAMPLES/ UNIT</th>";
            $RequestTemplate .= "<th class='text-center border-bottom-line border-right-line padding-right-5' style='width: 9%;'>UNIT COST</th>";
            $RequestTemplate .= "<th class='text-center border-bottom-line border-right-line border-right-line padding-right-5' style='width: 9%;'>TOTAL</th>";
            $RequestTemplate .= "</tr>";
            
            $CurSampleCode = "";
            $PrevSampleCode = "";
//
//             $samplesquery = Sample::find()->where(['request_id' => $id])->all();
//             foreach($samplesquery as $sample){
//             $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-bottom-line padding-left-5' colspan='2'>$sample[samplename]</td>";
//             $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-right-line border-bottom-line padding-left-5'>$sample[sample_code]</td>";
//             $analysisCount = 0;
//             $analysisquery = Analysis::find()->where(['sample_id' => $sample['sample_id']])->all();
//             foreach($analysisquery as $analysis){          
//                 $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-right-line border-bottom-line padding-left-5'>$analysis[testname]</td>";          
//             }
// }
//

            foreach ($RequestRows as $RequestRow) {
                $RequestRow = (object) $RequestRow;
                $CurSampleCode = $RequestRow->sample_code;
                $RequestTemplate .= "<tr>";
                if ($CurSampleCode != $PrevSampleCode) {
                    $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-bottom-line padding-left-5' colspan='2'>$RequestRow->samplename</td>";
                    $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-right-line border-bottom-line padding-left-5'>$RequestRow->sample_code</td>";
                } else {
                    $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-bottom-line' colspan='2'></td>";
                    $RequestTemplate .= "<td class='text-left border-right-line border-top-line border-left-line border-bottom-line'></td>";
                }
                $RequestTemplate .= "<td class='text-left border-bottom-line border-top-line border-right-line padding-left-5' colspan='2'>$RequestRow->testcalibration</td>";
                $html=$RequestRow->method;//$RequestRow->method;
                $RequestTemplate .= "<td style='word-wrap: break-word;' class='text-left border-bottom-line border-top-line border-right-line padding-left-5 padding-right-5' colspan='2'>$html</td>";
                $RequestTemplate .= "<td class='text-center border-bottom-line border-top-line border-right-line'>$RequestRow->NoSampleUnit</td>";
                $RequestTemplate .= "<td class='text-right border-bottom-line border-top-line border-right-line padding-right-5'>$RequestRow->UnitCost</td>";
                $RequestTemplate .= "<td class='text-right border-bottom-line border-top-line border-right-line padding-right-5'>$RequestRow->TotalAnalysis</td>";
                $RequestTemplate .= "</tr>";
                $PrevSampleCode = $CurSampleCode;
            }
            // $RequestTemplate .= "<tr>";
            // $RequestTemplate .= "<td colspan='8' style='border: 1px solid black;height: 0px' class='border-left-line border-bottom-line'></td>";
            // $RequestTemplate .= "<td class='border-left-line border-bottom-line'></td>";
            // $RequestTemplate .= "<td class='border-left-line border-bottom-line border-right-line'></td>";
            // $RequestTemplate .= "</tr>";
            // SUB-TOTAL
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-right border-left-line' colspan='8'></td>";
            $RequestTemplate .= "<td class='text-right border-left-line border-bottom-line padding-right-5'>Sub-Total</td>";
            $RequestTemplate .= "<td class='text-right border-left-line border-bottom-line border-right-line padding-right-5'>".number_format($RequestHeader->SubTotal,2)."</td>";
            $RequestTemplate .= "</tr>";
            // Discount
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-right border-left-line' colspan='8'></td>";
            $RequestTemplate .= "<td class='text-right border-left-line border-bottom-line padding-right-5'>Discount</td>";
            $RequestTemplate .= "<td class='text-right border-left-line border-bottom-line border-right-line padding-right-5'>".number_format($RequestHeader->DiscountPrice,2)."</td>";
            $RequestTemplate .= "</tr>";
            // TOTAL
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-right border-left-line border-bottom-line' colspan='8'></td>";
            $RequestTemplate .= "<th class='text-right border-left-line border-bottom-line padding-right-5'>TOTAL</th>";
            $GTotal=$RequestHeader->SubTotal-$RequestHeader->DiscountPrice;
            $RequestTemplate .= "<th class='text-right border-left-line border-bottom-line border-right-line padding-right-5'>".number_format($GTotal,2)."</th>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' class='text-left'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<th colspan='10' class='text-left border-bottom-line'>2. BRIEF DESCRIPTION OF SAMPLE/REMARKS</th>";
            $RequestTemplate .= "</tr>";
            //BRIEF DESCRIPTION
            $CurSampleCode2 = "";
            $PrevSampleCode2 = "";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-top-line border-right-line padding-right-5 padding-left-5' colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            foreach ($RequestRows as $RequestRow) {
                $RequestRow = (object) $RequestRow;
                $CurSampleCode2 = $RequestRow->sample_code;
                if ($CurSampleCode2 != $PrevSampleCode2) {
                    $RequestTemplate .= "<tr>";
                    $RequestTemplate .= "<td class='text-left border-left-line border-right-line padding-left-5' colspan='10'> $RequestRow->Remarks</td>";
                    $RequestTemplate .= "</tr>";
                }
                $PrevSampleCode2 = $CurSampleCode2;
            }
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-bottom-line border-right-line padding-right-5 padding-left-5' colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left' colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-right padding-right-5 padding-left-5 text-bold' colspan='8'>TOTAL</td>";
            $RequestTemplate .= "<td colspan='2' class='text-right padding-right-5 text-bold border-bottom-line'>₱ ".number_format($GTotal,2)."</td>";
            $RequestTemplate .= "</tr>";
            //Footer
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-top-line padding-left-5'>OR NO.:</td>";
            $RequestTemplate .= "<td class='text-left border-top-line padding-left-5' colspan='4'>$RequestHeader->OR_Numbers</td>";
            $RequestTemplate .= "<td class='text-right border-top-line padding-left-5' colspan='3'>AMOUNT RECEIVED:</td>";
            $RequestTemplate .= "<td colspan='2' class='text-right border-top-line padding-left-5 border-right-line padding-right-5'>".number_format($RequestHeader->TotalAmount,2)."</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-bottom-line border-left-line padding-left-5'>DATE:</td>";
            $RequestTemplate .= "<td class='text-left border-bottom-line padding-left-5' colspan='4'>$RequestHeader->ORDate</td>";
            $RequestTemplate .= "<td class='text-right border-bottom-line padding-left-5' colspan='3'>UNPAID BALANCE:</td>";
            $RequestTemplate .= "<td colspan='2' class='text-right border-bottom-line padding-left-5 border-right-line padding-right-5'>₱ ".number_format($GTotal-$RequestHeader->TotalAmount,2)."</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left' colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
             //Report Due
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-bottom-line border-left-line border-top-line padding-left-5'>REPORT DUE:</td>";
            $RequestTemplate .= "<td class='text-left border-bottom-line border-top-line padding-left-5' colspan='4'>".date('m/d/Y', strtotime($RequestHeader->report_due))."</td>";
            $RequestTemplate .= "<td class='text-right border-bottom-line border-top-line padding-left-5' colspan='3'>MODE OF RELEASE:</td>";
            $RequestTemplate .= "<td colspan='2' class='text-right border-bottom-line border-top-line padding-left-5 border-right-line padding-right-5'>$RequestHeader->ModeOfRelease</td>";
            $RequestTemplate .= "</tr>";
             //Divider
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left' colspan='10'>&nbsp;</td>";
            $RequestTemplate .= "</tr>";
             //
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-bottom-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='10'>DISCUSSED WITH CUSTOMER</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='4'>CONFORME:</td>";
            $RequestTemplate .= "<td class='text-left border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='3'></td>";
            $RequestTemplate .= "<td class='text-left border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='3'></td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-center valign-bottom border-left-line border-bottom-line padding-left-5 border-right-line padding-right-5' colspan='4' style='height: 35px'>".ucwords($RequestHeader->conforme)."</td>";
            $RequestTemplate .= "<td class='text-center valign-bottom border-left-line border-bottom-line padding-left-5 border-right-line padding-right-5' colspan='3'>".ucwords($RequestHeader->receivedBy)."</td>";
            $RequestTemplate .= "<td class='text-center valign-bottom border-left-line border-bottom-line padding-left-5 border-right-line padding-right-5' colspan='3'>".ucwords($RequestHeader->LabManager)."</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-center border-bottom-line border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='4'>Customer/Authorized Representative</td>";
            $RequestTemplate .= "<td class='text-center border-bottom-line border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='3'>Sample/s Received By:</td>";
            $RequestTemplate .= "<td class='text-center border-bottom-line border-left-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='3'>Sample/s Reviewed By:</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td class='text-left border-left-line border-bottom-line border-top-line padding-left-5 border-right-line padding-right-5' colspan='10'>REPORT NO.:</td>";
            $RequestTemplate .= "</tr>";
            
            $RequestTemplate .= "</tbody>";
            $RequestTemplate .= "<tfoot>";
            $RequestTemplate .= "<tr>";
            $RequestTemplate .= "<td colspan='10' style='text-align: right;font-size: 7px'>$Form</td>";
            $RequestTemplate .= "</tr>";
            $RequestTemplate .= "</tfoot>";
            $RequestTemplate .= "</table>";
        } else {
            $RequestTemplate = "<table border='0' width=100%>";
            $RequestTemplate .= "</table>";
        }
        return $RequestTemplate;
        
    }



}


