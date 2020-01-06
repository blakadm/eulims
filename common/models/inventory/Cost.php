<?php

namespace common\models\inventory;

use Yii;

/**
 * This is the model class for table "tbl_cost".
 *
 * @property int $id
 * @property int $product_id
 * @property int $lengthofuse
 * @property int $funding_id
 * @property string $date_received
 */
class Cost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_cost';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('inventorydb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'lengthofuse', 'funding_id', 'date_received'], 'required'],
            [['product_id', 'lengthofuse', 'funding_id'], 'integer'],
            [['date_received'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'lengthofuse' => 'Length of use (years)',
            'funding_id' => 'Funding ID',
            'date_received' => 'Date Received', 
        ];
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getFundings()
    {
        return $this->hasOne(Fundings::className(), ['id' => 'funding_id']);
    }
}
