<?php

namespace common\models\inventory;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\inventory\Cost;

/**
 * CostSearch represents the model behind the search form of `common\models\inventory\Cost`.
 */
class CostSearch extends Cost
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'lengthofuse', 'funding_id'], 'integer'],
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
        $query = Cost::find();

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
            'id' => $this->id,
            'product_id' => $this->product_id,
            'lengthofuse' => $this->lengthofuse,
            'funding_id' => $this->funding_id,
        ]);

        return $dataProvider;
    }
}
