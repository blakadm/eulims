<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Orseries;

/**
 * OrseriesSearch represents the model behind the search form of `common\models\finance\Orseries`.
 */
class OrseriesSearch extends Orseries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['or_series_id', 'or_category_id', 'terminal_id', 'rstl_id', 'startor', 'nextor', 'endor'], 'integer'],
            [['or_series_name'], 'safe'],
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
        $query = Orseries::find()->where(['rstl_id'=>Yii::$app->user->identity->profile->rstl_id]);

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
            'or_series_id' => $this->or_series_id,
            'or_category_id' => $this->or_category_id,
            'terminal_id' => $this->terminal_id,
            'rstl_id' => $this->rstl_id,
            'startor' => $this->startor,
            'nextor' => $this->nextor,
            'endor' => $this->endor,
        ]);

        $query->andFilterWhere(['like', 'or_series_name', $this->or_series_name]);

        return $dataProvider;
    }
}
