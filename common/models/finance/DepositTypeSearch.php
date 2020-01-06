<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\DepositType;

/**
 * DepositTypeSearch represents the model behind the search form about `common\models\finance\DepositType`.
 */
class DepositTypeSearch extends DepositType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deposit_type_id'], 'integer'],
            [['deposit_type'], 'safe'],
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
        $query = DepositType::find();

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
            'deposit_type_id' => $this->deposit_type_id,
        ]);

        $query->andFilterWhere(['like', 'deposit_type', $this->deposit_type]);

        return $dataProvider;
    }
}
