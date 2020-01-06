<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_tagging".
 *
 * @property integer $tagging_id
 * @property integer $user_id
 * @property integer $analysis_id
 * @property string $start_date
 * @property string $end_date
 * @property integer $tagging_status_id
 * @property string $cancel_date
 * @property string $reason
 * @property integer $cancelled_by
 * @property string $disposed_date
 * @property string $manner
 * @property integer $iso_accredited
 *
 * @property Analysis $analysis
 * @property TaggingStatus $taggingStatus
 */
class Tagging extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tagging';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'analysis_id', 'reason', 'cancelled_by', 'iso_accredited'], 'required'],
            [['user_id', 'analysis_id', 'tagging_status_id', 'cancelled_by', 'iso_accredited'], 'integer'],
            [['start_date', 'end_date', 'cancel_date', 'disposed_date'], 'safe'],
            [['reason'], 'string', 'max' => 100],
            [['analysis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Analysis::className(), 'targetAttribute' => ['analysis_id' => 'analysis_id']],
            [['tagging_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaggingStatus::className(), 'targetAttribute' => ['tagging_status_id' => 'tagging_status_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tagging_id' => 'Tagging ID',
            'user_id' => 'User ID',
            'analysis_id' => 'Analysis ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'tagging_status_id' => 'Tagging Status ID',
            'cancel_date' => 'Cancel Date',
            'reason' => 'Reason',
            'cancelled_by' => 'Cancelled By',
            'disposed_date' => 'Disposed Date',
            'iso_accredited' => 'Iso Accredited',
            'manner' => 'Manner of Disposal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysis()
    {
        return $this->hasOne(Analysis::className(), ['analysis_id' => 'analysis_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaggingStatus()
    {
        return $this->hasOne(TaggingStatus::className(), ['tagging_status_id' => 'tagging_status_id']);
    }
}
