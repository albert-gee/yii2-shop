<?php

namespace albertgeeca\shop\common\entities;

use dektrium\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "partner_request".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property string $contact_person
 * @property string $company_name
 * @property string $website
 * @property string $message
 * @property string $created_at
 * @property integer $moderation_status
 * @property integer $moderated_by
 * @property string $moderated_at
 *
 * @property User $moderatedBy
 * @property User $sender
 */
class PartnerRequest extends \yii\db\ActiveRecord
{
    /**
     * Constants for status_moderation column
     */
    const STATUS_ON_MODERATION = 1;
    const STATUS_DECLINED = 2;
    const STATUS_SUCCESS = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_request';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'moderation_status', 'moderated_by'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'moderated_at'], 'safe'],
            [['company_name', 'website', 'contact_person'], 'string', 'max' => 255],
            [['moderated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['moderated_by' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModeratedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'moderated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
}
