<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\CancelledOr;

/**
 * CancelledOrSearch represents the model behind the search form about `common\models\finance\CancelledOr`.
 */
class CancelledOrSearch extends CancelledOr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cancelled_or_id', 'receipt_id', 'cancelledby'], 'integer'],
            [['reason', 'cancel_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = CancelledOr::find();

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
            'cancelled_or_id' => $this->cancelled_or_id,
            'receipt_id' => $this->receipt_id,
            'cancel_date' => $this->cancel_date,
            'cancelledby' => $this->cancelledby,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
