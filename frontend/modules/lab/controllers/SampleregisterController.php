<?php

namespace frontend\modules\lab\controllers;

use Yii;
use common\models\lab\Sample;
use frontend\modules\lab\models\Sampleextend;
use common\models\lab\SampleregisterSearch;
use common\models\lab\SampleregisterUser;
use common\models\lab\Request;
use common\models\lab\Lab;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use kartik\mpdf\Pdf;
use yii\db\ActiveQuery;
use common\components\Functions;
use yii\data\Pagination;

class SampleregisterController extends Controller
{
    public function actionIndex()
    {
        //$model = new Requestextend;
        $model = new Sampleextend;
        $rstlId = Yii::$app->user->identity->profile->rstl_id;
        
        if (Yii::$app->request->get())
        {
            $labId = (int) Yii::$app->request->get('lab_id');
            
            if($this->checkValidDate(Yii::$app->request->get('from_date')) == true)
            {
                $fromDate = Yii::$app->request->get('from_date');
            } else {
                $fromDate = date('Y-m-d');
                //Yii::$app->session->setFlash('error', "Not a valid date!");
            }

            if($this->checkValidDate(Yii::$app->request->get('to_date')) == true){
                $toDate = Yii::$app->request->get('to_date');
            } else {
                $toDate = date('Y-m-d');
                //Yii::$app->session->setFlash('error', "Not a valid date!");
            }
        } else {
            $labId = 1;
            $fromDate = date('Y-01-01'); //first day of the year
            $toDate = date('Y-m-d'); //as of today

            $_GET['lab_id'] = 1;
            $_GET['from_date'] = $fromDate;
            $_GET['to_date'] = $toDate;
        }

        $searchModel = new SampleregisterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        $dataProvider->pagination->pageSize=10;

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'laboratories' => $this->listLaboratory(),
            'analysts' => $this->listAnalyst(),
        ]);
    }

    protected function listLaboratory()
    {
        $laboratory = ArrayHelper::map(Lab::find()->all(), 'lab_id', 
            function($laboratory, $defaultValue) {
                return $laboratory->labname;
        });

        return $laboratory;
    }

    protected function listAnalyst()
    {
        $query = Yii::$app->db->createCommand('SELECT lastname, firstname, middleinitial, tagging.user_id
                            FROM eulims.tbl_profile profile INNER JOIN eulims_lab.tbl_tagging tagging
                            ON tagging.user_id = profile.user_id')
                   ->queryAll();

        $analyst = ArrayHelper::map($query, 'user_id', 
            function($query, $defaultValue) {
                return $query['firstname']." ".((empty($query['middleinitial'])) ? "" : substr($query['middleinitial'], 0, 1).".")." ".$query['lastname'];
        });

        return $analyst;
    }

    function checkValidDate($date){
        $tempdate = explode('-', $date);

        if(count($tempdate) < 3 || count($tempdate) > 3)
        {
            return false;
        } else {
            $month = (int) $tempdate[1];
            $year = (int) $tempdate[0];
            $day = (int) $tempdate[2];
            // checkdate(month, day, year)
            if(checkdate($month,$day,$year) == true){
                return true;
            } else {
                return false;
            }
        }
    }

}
