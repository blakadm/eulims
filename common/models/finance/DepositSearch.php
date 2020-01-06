<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Deposit;

/**
 * OrderofpaymentSearch represents the model behind the search form about `common\models\finance\Orderofpayment`.
 */
class DepositSearch extends Deposit
{
    public $createTimeRange;
    public $createDateStart;
    public $createDateEnd;
    /**
     * @inheritdoc
     */
   
    public function rules()
    {
        return [
            [['rstl_id', 'or_series_id', 'start_or', 'end_or', 'deposit_type_id'], 'integer'],
            [['deposit_date'], 'safe'],
            [['amount'], 'number'],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $query = Deposit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['deposit_id'=>SORT_DESC]],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'deposit_id' => $this->deposit_id,
            'amount' => $this->amount,
            'deposit_type_id' => $this->deposit_type_id,
            'deposit_date' => $this->deposit_date,
            'rstl_id' => Yii::$app->user->identity->profile->rstl_id,
            
        ]);

        $query->andFilterWhere(['like', 'start_or', $this->start_or])
            ->andFilterWhere(['like', 'end_or', $this->end_or])
            ->andFilterWhere(['between', 'deposit_date', $this->createDateStart, $this->createDateEnd]);
        return $dataProvider;
    }
    
}
