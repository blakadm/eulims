<?php

namespace common\models\inventory;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\inventory\Products;

/**
 * ProductsSearch represents the model behind the search form about `common\models\inventory\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'qty_reorder','categorytype_id', 'qty_onhand', 'qty_min_reorder', 'discontinued', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['product_code', 'product_name', 'description', 'unit', 'suppliers_ids'], 'safe'],
            [['price', 'srp'], 'number'],
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
        $query = Products::find()->orderBy(['product_code' => SORT_ASC])->where(['rstl_id'=>Yii::$app->user->identity->profile->rstl_id]);

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
            'product_id' => $this->product_id,
            'price' => $this->price,
            'srp' => $this->srp,
            'categorytype_id' => $this->categorytype_id,
            'qty_reorder' => $this->qty_reorder,
            'qty_onhand' => $this->qty_onhand,
            'qty_min_reorder' => $this->qty_min_reorder,
            'discontinued' => $this->discontinued,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'suppliers_ids', $this->suppliers_ids]);

        return $dataProvider;
    }
}
