<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "tbl_receipt".
 *
 * @property int $receipt_id
 * @property int $rstl_id
 * @property int $terminal_id
 * @property int $collection_id
 * @property int $project_id
 * @property string $or_number
 * @property string $receiptDate
 * @property int $payment_mode_id
 * @property string $payor
 * @property int $collectiontype_id
 * @property string $total
 * @property int $cancelled
 * @property int $or
 * @property Billing[] $billings
 * @property Check[] $checks
 * @property Project $project
 * @property Paymentmode $paymentMode
 * @property Collectiontype $collectiontype
 * @property Collection $collection
 */
class Receipt extends \yii\db\ActiveRecord
{
    public $or;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_receipt';
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

            [['rstl_id', 'terminal_id', 'collection_id', 'project_id', 'or_number', 'receiptDate', 'payment_mode_id', 'collectiontype_id', 'total', 'cancelled','payor'], 'required'],
            [['rstl_id', 'terminal_id', 'collection_id', 'project_id', 'payment_mode_id', 'collectiontype_id', 'cancelled'], 'integer'],
            [['receiptDate','payor','or'], 'safe'],
            [['total'], 'number'],
            [['or_number'], 'string', 'max' => 50],
            [['payor'], 'string', 'max' => 100],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'project_id']],
            [['payment_mode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymentmode::className(), 'targetAttribute' => ['payment_mode_id' => 'payment_mode_id']],
            [['collectiontype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectiontype::className(), 'targetAttribute' => ['collectiontype_id' => 'collectiontype_id']],
            [['collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collection::className(), 'targetAttribute' => ['collection_id' => 'collection_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'receipt_id' => 'Receipt ID',
            'rstl_id' => 'Rstl ID',
            'terminal_id' => 'Terminal ID',
            'collection_id' => 'Collection ID',
            'project_id' => 'Project ID',
            'or_number' => 'Or Number',
            'receiptDate' => 'Receipt Date',
            'payment_mode_id' => 'Payment Mode ID',
            'payor' => 'Payor',
            'collectiontype_id' => 'Collectiontype ID',
            'total' => 'Total',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillings()
    {
        return $this->hasMany(Billing::className(), ['receipt_id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheck()
    {
        return $this->hasMany(Check::className(), ['receipt_id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['project_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentmode()
    {
        return $this->hasOne(Paymentmode::className(), ['payment_mode_id' => 'payment_mode_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectiontype()
    {
        return $this->hasOne(Collectiontype::className(), ['collectiontype_id' => 'collectiontype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['collection_id' => 'collection_id']);
    }
}
