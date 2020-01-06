<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Op;
use kartik\daterange\DateRangeBehavior;
/**
 * OrderofpaymentSearch represents the model behind the search form about `common\models\finance\Orderofpayment`.
 */
class OpSearch extends Op
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
            [['orderofpayment_id', 'rstl_id', 'collectiontype_id', 'customer_id', 'receipt_id'], 'integer'],
            [['transactionnum', 'order_date', 'purpose','createDateStart','createDateEnd','on_account'], 'safe'],
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
        $query = Op::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['orderofpayment_id'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'orderofpayment_id' => $this->orderofpayment_id,
            'rstl_id' => Yii::$app->user->identity->profile->rstl_id,
            'collectiontype_id' => $this->collectiontype_id,
            'customer_id' => $this->customer_id,
            'receipt_id' => $this->receipt_id,
            'on_account'=>0
        ]);

        $query->andFilterWhere(['like', 'transactionnum', $this->transactionnum])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['between', 'order_date', $this->createDateStart, $this->createDateEnd]);
        return $dataProvider;
    }
    
}
