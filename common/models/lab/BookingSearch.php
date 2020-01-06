<?php

namespace common\models\lab;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\lab\Booking;

/**
 * BookingSearch represents the model behind the search form of `common\models\lab\Booking`.
 */
class BookingSearch extends Booking
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_id', 'rstl_id', 'qty_sample', 'customer_id'], 'integer'],
            [['scheduled_date', 'booking_reference', 'description', 'date_created'], 'safe'],
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
        $query = Booking::find()->where(['rstl_id'=>Yii::$app->user->identity->profile->rstl_id]);

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
            'booking_id' => $this->booking_id,
            'scheduled_date' => $this->scheduled_date,
            'rstl_id' => $this->rstl_id,
            'date_created' => $this->date_created,
            'qty_sample' => $this->qty_sample,
            'customer_id' => $this->customer_id,
        ]);

        $query->andFilterWhere(['like', 'booking_reference', $this->booking_reference])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
