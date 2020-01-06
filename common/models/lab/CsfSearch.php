<?php

namespace common\models\lab;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lab\Csf;

/**
 * CsfSearch represents the model behind the search form of `common\models\lab\Csf`.
 */
class CsfSearch extends Csf
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'd_deliverytime', 'd_accuracy', 'd_speed', 'd_cost', 'd_attitude', 'd_overall', 'i_deliverytime', 'i_accuracy', 'i_speed', 'i_cost', 'i_attitude', 'i_overall', 'recommend'], 'integer'],
            [['name', 'nob', 'tom', 'service', 'essay', 'r_date', 'ref_num'], 'safe'],
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
        $query = Csf::find();

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
            'ref_num' => $this->ref_num,
            'd_deliverytime' => $this->d_deliverytime,
            'd_accuracy' => $this->d_accuracy,
            'd_speed' => $this->d_speed,
            'd_cost' => $this->d_cost,
            'd_attitude' => $this->d_attitude,
            'd_overall' => $this->d_overall,
            'i_deliverytime' => $this->i_deliverytime,
            'i_accuracy' => $this->i_accuracy,
            'i_speed' => $this->i_speed,
            'i_cost' => $this->i_cost,
            'i_attitude' => $this->i_attitude,
            'i_overall' => $this->i_overall,
            'recommend' => $this->recommend,
            'r_date' => $this->r_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'nob', $this->nob])
            ->andFilterWhere(['like', 'tom', $this->tom])
            ->andFilterWhere(['like', 'service', $this->service])
            ->andFilterWhere(['like', 'essay', $this->essay]);

        return $dataProvider;
    }
}
