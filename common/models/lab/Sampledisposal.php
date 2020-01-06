<?php

namespace common\models\lab;;

use Yii;

/**
 * This is the model class for table "tbl_sample_disposal".
 *
 * @property int $disposal_id
 * @property string $disposal
 * @property int $status
 */
class Sampledisposal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_sample_disposal';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['disposal_id', 'disposal'], 'required'],
            [['disposal_id', 'status'], 'integer'],
            [['disposal'], 'string', 'max' => 200],
            [['disposal_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'disposal_id' => 'Disposal ID',
            'disposal' => 'Disposal',
            'status' => 'Status',
        ];
    }
}
