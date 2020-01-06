<?php

namespace frontend\modules\finance\controllers;


use mysqli;
use Yii;
use Yii\db\Query;
use Yii\helpers\Json;
use yii\data\SqlDataProvider;
use Yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
use Yii\data\ActiveDataProvider;

use yii\base\Model;

class FinancialreportsController extends \yii\web\Controller
{
    public function actionCollectionsummary($iyear,$imonth)
    {
        
        $monthName = date("F", mktime(0, 0, 0, $imonth, 10));
        $moduleTitle = "Collection Summary for " . $monthName . ', ' . $iyear;
       // $queryA = new yii\db\Query;
        $queryNew =  'Call eulims_finance_migration.spGetCollectionSummary('. $iyear .','. $imonth . ');';
         $columnArray = array();
          $columnArrayNew = array();
          
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM eulims_finance.tbl_collectiontype')->queryScalar();
        $columnHeaders = Yii::$app->db->createCommand('SELECT accountcode FROM eulims_finance.tbl_accountingcode')->queryAll();
        
        $columnArray[] = 'natureofcollection';
        foreach ($columnHeaders as $col)
            {
            $columnArray[]=$col['accountcode'];
           
            }
            
        $dataProviderCollectionSummary = new SqlDataProvider([
            'sql' => $queryNew,
            'totalCount' => $count,
        ]);
        $totalWidth = $count *150;
        $finalWidth = 1500;
        if($totalWidth>$finalWidth)
        {
            $finalWidth = $totalWidth;
        }
        
        $gridOptions='text-align:left;font-size:12px;width:'. $finalWidth .'px';
        
        
        
        return $this->render('collectionsummary',['moduleTitle'=>$moduleTitle,'dataProviderCollectionSummary' => $dataProviderCollectionSummary,
            'columnArray'=>$columnArray,
            'columnHeaders' =>$columnHeaders,
            'columnArrayNew'=>$columnArrayNew,
            'gridOptions'=>$gridOptions,
         
           
            ]);
    }
    
    public function actionCashreceiptjournal($iyear,$imonth)
    {
     $monthName = date("F", mktime(0, 0, 0, $imonth, 10));
     $moduleTitle = "Cash Receipt Journal for " . $monthName . ', ' . $iyear;
        
     $sqlQueryString = 'Call eulims_finance_migration.spGetCollectionSummary('. $iyear .','. $imonth . ');';    
     $dataHeader = Yii::$app->db->createCommand($sqlQueryString);
     $stringTable="<table>";
     $arrayUACS =['2-03-01-020','1-01-01-010','1-01-02-020','1-01-04-010','4-03-01-010','1-01-01-010(TF)','','',];
     
     $mysqli = new mysqli('localhost', 'eulims', 'eulims', 'eulims_finance');
     
     $sql = $sqlQueryString; //'Call eulims_finance.spGetCollectionSummary()';
        $res = $mysqli->query($sql);

        $values = $res->fetch_all(MYSQLI_ASSOC);
        $columns = array();

        if(!empty($values)){
            $columns = array_keys($values[0]);
        }
        $tableWidth = ((count($columns)-1) * 100) + 500;
        $rowcount=mysqli_num_rows($res);
 
        $stringTable = '<table id="tblCashreceipt" style="width:' .$tableWidth . 'px;"> <tr>
        <td  style="width:397px">Cell</td>
        <td style="width:97px">Debit</td>
        <td style="width:' .((count($columns)-1) * 100) . 'px">Credit</td>
        <td style="width:297px">Sundry</td></tr></table>';

        $stringTable = $stringTable . '<table style="width:' .$tableWidth . 'px;">
        <tr>
            <td style="width:100px">Date</td>
            <td style="width:100px">Reference</td>
            <td style="width:100px">Jev No</td>
            <td style="width:100px">Payor</td>
            <td style="width:100px">1-01-014-010</td>';
        
           
            for ($i =1;$i<count($columns);$i++) 
                {
                $stringTable = $stringTable .' <td style="width:100px">' . $columns[$i] . '</td>';
            }
            $stringTable = $stringTable . '<td style="width:100px">UACS Code</td><td style="width:100px">Debit</td><td style="width:100px">Credit</td></tr>';
                       
            $stringTD = '';
            $total = 0;

            for ($i =1;$i<count($columns);$i++) 
            {
                $tmpValue=0;
               
                for($j=0;$j<$rowcount;$j++)
                    {
                   // $tmpValue = $tmpValue + $values[$j][$columns[$i]] ;
                   if($values[$j][$columns[$i]] !="")
                        {
                            $tmpValue = $tmpValue +  $values[$j][$columns[$i]];
                        }
                    }
                    $total = $total + $tmpValue;
                    $stringTD = $stringTD . '<td class="tdValue" style="width:100px">'. number_format($tmpValue, 2, '.', ',') . '</td>';
                //    $stringTD = $stringTD . '<td class="tdValue" style="width:100px">' .$tmpValue. '</td>';
            }

            $stringTable = $stringTable . '<tr><td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px">Various</td>
            <td class="tdValue" style="width:150px">'. number_format($total, 2, '.', ',') . '</td>';

            $stringTable = $stringTable . $stringTD;   
            
            $stringTable = $stringTable .'<td style="width:100px"></td><td style="width:100px"></td><td style="width:100px"></td></tr>';
            
            foreach($arrayUACS as $uacs)
            {

            $stringTable = $stringTable . '<tr style="height:20px"><td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:150px"></td>';
            
                    for ($i =1;$i<count($columns);$i++) 
                    {
                        $stringTable = $stringTable . '<td style="width:100px"></td>';
                    }
                    $stringTable = $stringTable . '<td style="width:150px">' . $uacs . '</td><td style="width:100px"></td><td style="width:100px"></td></tr>';
            }

            $stringTable = $stringTable . '<tr><td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px"></td>
            <td style="width:100px">Total</td>
            <td class="tdValue" style="width:150px">'. number_format($total, 2, '.', ',') . '</td>';

            $stringTable = $stringTable . $stringTD;   
            
            $stringTable = $stringTable .'<td style="width:100px"></td><td style="width:100px"></td><td style="width:100px"></td></tr>';
            
          
            $test=count($columns);

            $stringTable = $stringTable . '</table>';
   
    
     return $this->render('cashreceiptjournal',['moduleTitle'=>$moduleTitle,'stringTable'=>$stringTable,'tableWidth'=>$tableWidth,'values'=>$test]);
    }
    
    public function actionCollectionreport($iyear,$imonth)
    {
        $monthName = date("F", mktime(0, 0, 0, $imonth, 10));
         $moduleTitle = "Collection Report for " . $monthName . ', ' . $iyear;
      //  $searchModel = new AccountingcodeSearch();
     //   $dataProvider2 = $searchModel->search(Yii::$app->request->queryParams);
        
//        $stringQuery ='SELECT Count(*)
//                        FROM eulims_finance.tbl_receipt 
//                        INNER JOIN eulims_finance.tbl_collection  ON eulims_finance.tbl_receipt.or_number = eulims_finance.tbl_collection.oldColumn_receiptId 
//                        INNER JOIN eulims_finance.tbl_deposit ON eulims_finance.tbl_receipt.deposit_id = eulims_finance.tbl_deposit.deposit_id 
//                        WHERE YEAR(eulims_finance.tbl_receipt.receiptDate)=' .$iyear. ' AND MONTH(eulims_finance.tbl_receipt.receiptDate)=' .$imonth;
        
        $stringQuery = "Call eulims_finance.spGetCollectionReportNewCount('" . $iyear . "','" . $imonth ."');";  
        $count = Yii::$app->db->createCommand($stringQuery)->queryScalar();
        
        
       // $count = Yii::$app->db->createCommand($stringQuery)->queryScalar();
      //  $queryNew = new Yii\db\Query;

        $stringWhere = 'YEAR(eulims_finance.tbl_receipt.receiptDate)=' .$iyear. ' AND MONTH(eulims_finance.tbl_receipt.receiptDate)=' .$imonth;
        
                               
                               
                               
        $queryNewest =  'Call eulims_finance.spGetCollectionReportNew('. $iyear .','. $imonth . ');';    //'Call eulims_finance.spGetCollectionReportNew(2018,5);';
    //     $queryNewest ='Call spGetCollectionReport(2018,5)';                      
        $queryDaw =Yii::$app->db->createCommand('Call eulims_finance.spGetCollectionReportNew('. $iyear .','. $imonth . ');')->queryScalar(); 
          
      //  $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM eulims_finance.tbl_collectiontype')->queryScalar();
      //  $columnHeaders = Yii::$app->db->createCommand('SELECT accountcode FROM eulims_finance.tbl_accountingcode')->queryAll();
        
       
           
            
        $dataProvider = new SqlDataProvider([
            'sql' => $queryNewest,
            'totalCount' => $count,
          //  'pagination' => false
            'sort' =>false,
        'pagination' => [
            'pageSize' => 8,
        ],
        ]);
        
       
        
         return $this->render('collectionreport', [
                  // 'count' => $counter,
                    'dataProvider' => $dataProvider,
                    'moduleTitle'=>$moduleTitle
        ]);
    }
    
    public function actionCollectionreportdaily($datefrom,$dateto)
    {
        
        
    //    $monthName = date("F", mktime(0, 0, 0, $imonth, 10));
         $moduleTitle = "Collection Report for " . $datefrom . ' to ' . $dateto;
//        
//        $stringQuery ="SELECT COUNT(*)
//                        FROM eulims_finance_migration.tbl_receipt 
//                        INNER JOIN eulims_finance_migration.tbl_collection  ON eulims_finance_migration.tbl_receipt.or_number = eulims_finance_migration.tbl_collection.oldColumn_receiptId 
//                        INNER JOIN eulims_finance_migration.tbl_deposit ON eulims_finance_migration.tbl_receipt.deposit_id = eulims_finance_migration.tbl_deposit.deposit_id 
//                        WHERE eulims_finance_migration.tbl_receipt.receiptDate  BETWEEN '" .$datefrom.   "' AND '" . $dateto. "'";
        $stringQuery = "Call eulims_finance.spGetCollectiomReportDailyCount('" . $datefrom . "','" . $dateto ."');";  
        $count = Yii::$app->db->createCommand($stringQuery)->queryScalar();
      //  $count = Yii::$app->db->createCommand($stringQuery)->queryScalar();
       // $stringWhere = 'YEAR(eulims_finance.tbl_receipt.receiptDate)=' .$iyear. ' AND MONTH(eulims_finance.tbl_receipt.receiptDate)=' .$imonth;
     //   $queryNewest =  'Call eulims_finance_migration.spGetCollectionReportNew('. $iyear .','. $imonth . ');';    //'Call eulims_finance.spGetCollectionReportNew(2018,5);';
      //  $queryDaw =Yii::$app->db->createCommand('Call eulims_finance_migration.spGetCollectionReportDaily('. $iyear .','. $imonth . ');')->queryScalar(); 
        
         $queryNewest =  "Call eulims_finance.spGetCollectiomReportDaily('" . $datefrom . "','" . $dateto ."');";  
          
        $dataProvider = new SqlDataProvider([
            'sql' => $queryNewest,
            'totalCount' => $count,
          //  'pagination' => false
            'sort' =>false,
        'pagination' => [
            'pageSize' => 8,
        ],
        ]);
        
         return $this->render('collectionreport', [
                  // 'count' => $counter,
                    'dataProvider' => $dataProvider,
                    'moduleTitle'=>$moduleTitle
        ]);
    }
    
    
    public function actionIndex()
    {
        $model = new YearMonth();
        $listYear =[]; //['2018','2017','2016','2015'];
        
        $arrayYear = Yii::$app->db->createCommand('CALL spGetYearArrayforReceipt();')->queryAll();
        $index = 0;
        foreach ($arrayYear as $colYear)
            {
                array_push($listYear,$colYear['iyear']);
            }
            
            
        $listMonth = ["0" => "All",
                    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
                    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
                    "9" => "September", "10" => "October", "11" => "November", "12" => "December"];
        
        
        return $this->render('index',['model'=>$model,'listYear'=>$listYear,'listMonth'=>$listMonth]);
    }
    
     public function actionSelectpage()
    {
     //   $model = new YearMonth();
      //  $listYear =['2011','2012','2013','2014'];
        return $this->render('index');
    }
    
    public function actionTest()
    {
        $columnHeaders =Yii::$app->db->createCommand('SELECT accountcode FROM eulims_finance.tbl_accountingcode')->queryAll();
                        
        echo \yii\helpers\Json::decode(['output'=>$columnHeaders, 'selected'=>'']);
            return;
            
       // return $this->render('index');
    }
    
    

}







class YearMonth extends Model
{
    public $intYear;
    public $intMonth;
    
    public function attributeLabels()
    {
        return [
             'intYear' => 'Year',
            'intMonth' => 'Month'
           
          
        ];
    }
    
}
