<?php

namespace common\models\lab;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lab\Testreport;

/**
 * TestreportSearch represents the model behind the search form of `common\models\lab\Testreport`.
 */
class TestreportSearch extends Testreport
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['testreport_id', 'request_id', 'lab_id', 'status_id', 'reissue', 'previous_id'], 'integer'],
            [['report_num', 'report_date', 'release_date'], 'safe'],
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
        $GLOBALS['lab_id']=Yii::$app->user->identity->profile->lab_id;
        $query = Testreport::find()->Where(['lab_id'=>$GLOBALS['lab_id']]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'testreport_id' => $this->testreport_id,
            'request_id' => $this->request_id,
            'lab_id' => $this->lab_id,
            'report_date' => $this->report_date,
            'status_id' => $this->status_id,
            'release_date' => $this->release_date,
            'reissue' => $this->reissue,
            'previous_id' => $this->previous_id,
        ]);

        $query->andFilterWhere(['like', 'report_num', $this->report_num]);

        return $dataProvider;
    }
}
