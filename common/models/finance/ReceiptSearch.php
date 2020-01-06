<?php

namespace common\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\Receipt;
use kartik\daterange\DateRangeBehavior;

/**
 * OrderofpaymentSearch represents the model behind the search form about `common\models\finance\Orderofpayment`.
 */
class ReceiptSearch extends Receipt
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
            [['receipt_id', 'rstl_id', 'terminal_id', 'orderofpayment_id', 'deposit_type_id', 'or_number', 'collectiontype_id', 'payment_mode_id', 'cancelled'], 'integer'],
            [['receiptDate','total','createDateStart','createDateEnd'], 'safe'],
            [['payor'], 'string'],
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
        $query = Receipt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['receipt_id'=>SORT_DESC]],
        ]);

        
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'receipt_id' => $this->receipt_id,
            'rstl_id' => Yii::$app->user->identity->profile->rstl_id,
            'terminal_id' => $this->terminal_id,
            'orderofpayment_id' => $this->orderofpayment_id,
            'deposit_type_id' => $this->deposit_type_id,
            'payment_mode_id' => $this->payment_mode_id,
            'collectiontype_id' => $this->collectiontype_id,
            'total' => $this->total,
            'cancelled' => $this->cancelled,
        ]);

        $query->andFilterWhere(['like', 'or_number', $this->or_number])
            ->andFilterWhere(['like', 'payor', $this->payor])
            ->andFilterWhere(['between', 'receiptDate', $this->createDateStart, $this->createDateEnd]);
        return $dataProvider;
    }
    
}
