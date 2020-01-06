<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_excess_payment".
 *
 * @property int $excess_id
 * @property int $receipt_id
 * @property int $check_id
 * @property string $amount
 *
 * @property Receipt $receipt
 * @property Check $check
 */
class ExcessPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_excess_payment';
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
            [['receipt_id', 'check_id', 'amount'], 'required'],
            [['receipt_id', 'check_id'], 'integer'],
            [['amount'], 'number'],
            [['receipt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Receipt::className(), 'targetAttribute' => ['receipt_id' => 'receipt_id']],
            [['check_id'], 'exist', 'skipOnError' => true, 'targetClass' => Check::className(), 'targetAttribute' => ['check_id' => 'check_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'excess_id' => 'Excess ID',
            'receipt_id' => 'Receipt ID',
            'check_id' => 'Check ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['receipt_id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheck()
    {
        return $this->hasOne(Check::className(), ['check_id' => 'check_id']);
    }
}
