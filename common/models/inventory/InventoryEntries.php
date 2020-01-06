<?php

namespace common\models\inventory;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "tbl_inventory_entries".
 *
 * @property int $inventory_transactions_id
 * @property int $rstl_id
 * @property int $product_id
 * @property string $manufacturing_date
 * @property string $expiration_date
 * @property int $created_by
 * @property int $suppliers_id
 * @property string $po_number
 * @property int $quantity_onhand //tracking currently onhand  item 
 * @property int $quantity //the item qty at the start of the entries
 * @property double $content 
 * @property string $amount
 * @property string $total_unit 
 * @property string $total_amount
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Equipmentservice[] $equipmentservices
 * @property EquipmentstatusEntry[] $equipmentstatusEntries
 * @property Suppliers $suppliers
 * @property Products $product
 * @property Transactiontype $Withdrawdetails
 */
class InventoryEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_inventory_entries';
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
            [['rstl_id', 'product_id', 'created_by', 'suppliers_id', 'quantity','amount', 'content'], 'required'],
            [[ 'rstl_id', 'product_id', 'created_by', 'suppliers_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['manufacturing_date', 'expiration_date'], 'safe'],
            [['content', 'amount', 'total_unit', 'total_amount'], 'number'],
            [['description'], 'string'],
            [['po_number'], 'string', 'max' => 50],
            [['suppliers_id'], 'exist', 'skipOnError' => true, 'targetClass' => Suppliers::className(), 'targetAttribute' => ['suppliers_id' => 'suppliers_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'inventory_transactions_id' => 'Inventory Transactions ID',
            'transaction_type_id' => 'Transaction Type ID',
            'rstl_id' => 'Rstl ID',
            'product_id' => 'Product ID',
            'manufacturing_date' => 'Manufacturing Date',
            'expiration_date' => 'Expiration Date',
            'created_by' => 'Created By',
            'suppliers_id' => 'Suppliers ID',
            'po_number' => 'PO/Lot Number',
            'quantity_onhand'=>'Quantity Onhand',
            'quantity' => 'Quantity',
            'content' => 'Content',
            'amount' => 'Price',
            'total_unit' => 'Total Volume/Mass',
            'total_amount' => 'Total Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentservices()
    {
        return $this->hasMany(Equipmentservice::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentstatusEntries()
    {
        return $this->hasMany(EquipmentstatusEntry::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuppliers()
    {
        return $this->hasOne(Suppliers::className(), ['suppliers_id' => 'suppliers_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWithdrawdetails()
    {
        return $this->hasMany(InventoryWithdrawaldetails::className(), ['inventory_transactions_id' => 'inventory_transactions_id']);
    }

    public function getTotalcontent()
    {
        return ($this->quantity_onhand * $this->content);
    }
}
