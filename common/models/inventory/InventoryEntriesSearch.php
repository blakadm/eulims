<?php

namespace common\models\inventory;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\inventory\InventoryEntries;

/**
 * InventoryEntriesSearch represents the model behind the search form of `common\models\inventory\InventoryEntries`.
 */
class InventoryEntriesSearch extends InventoryEntries
{
    /**
     * {@inheritdoc}
     */
    
    public $createTimeRange;
    public $createDateStart;
    public $createDateEnd;
    
    public $createTimeRange2;
    public $createDateStart2;
    public $createDateEnd2;
    
    public function rules()
    {
        return [
            [['inventory_transactions_id', 'rstl_id', 'product_id', 'created_by', 'suppliers_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['manufacturing_date', 'expiration_date', 'po_number','createDateStart','createDateEnd','createDateStart2','createDateEnd2'], 'safe'],
            [['amount', 'total_amount'], 'number'],
            [['createTimeRange','createTimeRange2'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $query = InventoryEntries::find()->where(['rstl_id'=>Yii::$app->user->identity->profile->rstl_id]);

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
            'inventory_transactions_id' => $this->inventory_transactions_id,
            // 'transaction_type_id' => $this->transaction_type_id,
            'rstl_id' => $this->rstl_id,
            'product_id' => $this->product_id,
            'created_by' => $this->created_by,
            'suppliers_id' => $this->suppliers_id,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'total_amount' => $this->total_amount,
        ]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['between', 'manufacturing_date', $this->createDateStart, $this->createDateEnd])
            ->andFilterWhere(['between', 'expiration_date', $this->createDateStart2, $this->createDateEnd2]);
        return $dataProvider;
    }
}
