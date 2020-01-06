<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_cancelled_or".
 *
 * @property int $cancelled_or_id
 * @property int $receipt_id
 * @property string $reason
 * @property string $cancel_date
 * @property int $cancelledby
 */
class CancelledOr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_cancelled_or';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('financedb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_id', 'cancelledby'], 'integer'],
            [['cancel_date'], 'safe'],
            [['reason'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cancelled_or_id' => 'Cancelled Or ID',
            'receipt_id' => 'Receipt ID',
            'reason' => 'Reason',
            'cancel_date' => 'Cancel Date',
            'cancelledby' => 'Cancelledby',
        ];
    }
}
