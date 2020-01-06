<?php

namespace frontend\modules\reports\modules\lab\controllers;

use Yii;
use yii\web\Controller;
use common\models\lab\Sample;
use common\models\lab\SampleSearch;
use common\models\lab\Request;
use frontend\modules\reports\modules\models\Requestextend;
use common\models\lab\Lab;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

class AccomplishmentController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = new Requestextend;
    	$rstlId = Yii::$app->user->identity->profile->rstl_id;
    	
		if (Yii::$app->request->get())
		{
			$labId = (int) Yii::$app->request->get('lab_id');
			
			if($this->checkValidDate(Yii::$app->request->get('from_date')) == true)
			{
		        $fromDate = Yii::$app->request->get('from_date');
			} else {
				$fromDate = date('Y-m-d');
				Yii::$app->session->setFlash('error', "Not a valid date!");
			}

			if($this->checkValidDate(Yii::$app->request->get('to_date')) == true){
				$toDate = Yii::$app->request->get('to_date');
			} else {
				$toDate = date('Y-m-d');
				Yii::$app->session->setFlash('error', "Not a valid date!");
			}
		} else {
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
		}

		$modelRequest = Requestextend::find()
					->where('rstl_id =:rstlId AND status_id > :statusId AND lab_id = :labId AND DATE_FORMAT(`request_datetime`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate', [':rstlId'=>$rstlId,':statusId'=>0,':labId'=>$labId,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate])
					->groupBy(['DATE_FORMAT(request_datetime, "%Y-%m")'])
					->orderBy('request_datetime DESC');

		$dataProvider = new ActiveDataProvider([
            'query' => $modelRequest,
            'pagination' => false,
            // 'pagination' => [
            //     'pagesize' => 10,
            // ],
        ]);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
                'lab_id' => $labId,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'model'=>$modelRequest,
	            'laboratories' => $this->listLaboratory(),
            ]);
        } else {
			return $this->render('index', [
	            'dataProvider' => $dataProvider,
	            'lab_id' => $labId,
	            'model'=>$modelRequest,
                'from_date' => $fromDate,
                'to_date' => $toDate,
	            'laboratories' => $this->listLaboratory(),
	        ]);
		}

        //return $this->render('index');
	}
	
	public function actionMontly($id)
    {
        // $taggingmodel = Tagging::find()->where(['analysis_id'=>$id])->one();
        // $analysis = Analysis::find()->where(['analysis_id'=>$id])->one();      
        // $model = new Tagging();
        
        // if ($taggingmodel){
            return $this->renderAjax('monthly');
        // }else{
        //     return $this->renderAjax('monthly', [
        //         'taggingmodel' => $taggingmodel,
        //     ]);
        // }

      
    }

    protected function listLaboratory()
    {
        $laboratory = ArrayHelper::map(Lab::find()->all(), 'lab_id', 
            function($laboratory, $defaultValue) {
                return $laboratory->labname;
        });

        return $laboratory;
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
