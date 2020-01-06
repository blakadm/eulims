<?php

namespace common\models\lab;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lab\Loginlogs;

/**
 * LoginlogsSearch represents the model behind the search form of `common\models\referral\Loginlogs`.
 */
class LoginlogsSearch extends Loginlogs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loginlogs_id', 'user_id', 'rstl_id'], 'integer'],
            [['login_date'], 'safe'],
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
        $query = Loginlogs::find();

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
            'loginlogs_id' => $this->loginlogs_id,
            'user_id' => $this->user_id,
            'rstl_id' => $this->rstl_id,
            'login_date' => $this->login_date,
        ]);

        return $dataProvider;
    }
}
