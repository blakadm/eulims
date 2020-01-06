<?php

namespace common\models\lab;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lab\Analysis;
use common\models\lab\Sample;
use common\models\lab\Tagging;
use frontend\modules\lab\models\Sampleextend;

/**
 * SampleregisterSearch extended model of `common\models\lab\Analysis`.
 */
class SampleregisterSearch extends Sampleextend
{
    /**
     * {@inheritdoc}
     */
    //public $samplename,$start_date,$end_date;
    public function rules()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
       $rstlId = Yii::$app->user->identity->profile->rstl_id;

       $query = Sampleextend::find();
       $query->joinWith(['request']);

        if (count($params) == 0)
        {
            $labId = 1;
            $fromDate = date('Y-01-01'); //first day of the year
            $toDate = date('Y-m-d'); //as of today

            $query->where('status_id < :statusId AND lab_id = :labId AND active =:active AND request_ref_num != "" AND DATE_FORMAT(`request_datetime`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate',[':statusId'=>2,':labId'=>$labId,':active'=>1,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate]);
            $query->orderBy([
                'tbl_sample.request_id' => SORT_ASC,
            ]);

        } else {
           $labId = (int) Yii::$app->request->get('lab_id');
            
            if($this->checkValidDate(Yii::$app->request->get('from_date')) == true)
            {
                $fromDate = Yii::$app->request->get('from_date');
            } else {
                $fromDate = date('Y-m-d');
            }

            if($this->checkValidDate(Yii::$app->request->get('to_date')) == true){
                $toDate = Yii::$app->request->get('to_date');
            } else {
                $toDate = date('Y-m-d');
            }

            $query->where('status_id < :statusId AND lab_id = :labId AND active =:active AND request_ref_num != "" AND DATE_FORMAT(`request_datetime`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate',[':statusId'=>2,':labId'=>$labId,':active'=>1,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate]);
            $query->orderBy([
                'tbl_sample.request_id' => SORT_DESC,
            ]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       // $dataProvider->sort->defaultOrder = ['sample_id' => SORT_DESC];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_request.rstl_id' => $rstlId,
            'tbl_sample.rstl_id' => $rstlId,
        ]);

        return $dataProvider;
    }

    public function checkValidDate($date){
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
