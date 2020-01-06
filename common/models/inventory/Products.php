<?php

namespace common\models\inventory;

use Yii;

/**
 * This is the model class for table "tbl_products".
 *
 * @property int $product_id
 * @property string $product_code
 * @property string $product_name
 * @property int $producttype_id
 * @property int $categorytype_id
 * @property string $description   //can be alcohol content or specify the unit of the product
 * @property string $price    //original price of the item
 * @property string $srp      //the price of the item based on the formula
 * @property int $qty_reorder     //how many item will be reordered
 * @property int $qty_onhand       
 * @property int $qty_min_reorder   //threshold, if a certain item reaches its threshold reorder begins
 * @property int $unit       //number , how many item is in the unit can be box or etc //see desc
 * @property int $discontinued
 * @property string $suppliers_ids
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $Image1
 * @property string $Image2
 * @property string $sds
 * @property int $rstl_id
 *
 * @property InventoryEntries[] $inventoryEntries
 * @property InventoryWithdrawaldetails[] $inventoryWithdrawaldetails
 * @property Categorytype $categorytype
 * @property Producttype $producttype
 */
class Products extends \yii\db\ActiveRecord
{
 

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_products';
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
            [['rstl_id','product_name', 'categorytype_id', 'qty_reorder', 'qty_onhand', 'qty_min_reorder', 'unit', 'created_by'], 'required'],
            [['rstl_id','producttype_id', 'categorytype_id', 'qty_reorder', 'qty_onhand', 'qty_min_reorder', 'discontinued', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['price', 'srp'], 'number'],
            [['suppliers_ids','Image1','Image2'], 'safe'],
            [['product_code', 'Image1', 'Image2', 'sds'], 'string', 'max' => 100],
            [['product_name', 'unit'], 'string', 'max' => 50],
            [['categorytype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorytype::className(), 'targetAttribute' => ['categorytype_id' => 'categorytype_id']],
            [['producttype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producttype::className(), 'targetAttribute' => ['producttype_id' => 'producttype_id']],
             [['Image1', 'Image2', 'sds'], 'file', 'extensions'=>'jpg, gif, png, jpeg, pdf', 'skipOnEmpty' => true] //experiment only
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'producttype_id' => 'Product Type',
            'categorytype_id' => 'Category Type',
            'description' => 'Description',
            'price' => 'Price',
            'srp' => 'Srp',
            'qty_reorder' => 'Qty Reorder',
            'qty_onhand' => 'Qty Onhand',
            'qty_min_reorder' => 'Qty Min Reorder',
            'unit' => 'Unit',
            'discontinued' => 'Discontinued',
            'suppliers_ids' => 'Suppliers Ids',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'Image1' => 'Image1',
            'Image2' => 'Image2',
            'sds' => 'Safety Data Sheet',
            'rstl_id' => 'Rstl ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventoryEntries()
    {
        return $this->hasMany(InventoryEntries::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getInventoryWithdrawaldetails()
    // {
    //     return $this->hasMany(InventoryWithdrawaldetails::className(), ['product_id' => 'product_id']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategorytype()
    {
        return $this->hasOne(Categorytype::className(), ['categorytype_id' => 'categorytype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducttype()
    {
        return $this->hasOne(Producttype::className(), ['producttype_id' => 'producttype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnittype()
    {
        return $this->hasOne(Units::className(), ['unitid' => 'unit']);
    }


    public function getTotalqty()
    {
        $myvar = InventoryEntries::find()->where(['product_id'=>$this->product_id])->all();
        $total = 0;
        foreach ($myvar as $var) {
            # code...
            $total += $var->getTotalcontent();
        }

        return $total;
    }
}
