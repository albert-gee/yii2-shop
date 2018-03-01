<?php
namespace sointula\shop\common\entities;
use sointula\shop\common\components\user\models\UserGroup;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;


/**
 * This is the model class for table "shop_combination_price".
 *
 * @property integer $id
 * @property integer $combination_id
 * @property integer $price_id
 * @property integer $user_group_id
 * @property string $creation_time
 * @property string $update_time
 *
 * @property Price $price
 * @property Combination $combination
 * @property UserGroup $userGroup
 */
class CombinationPrice extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_combination_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['combination_id', 'price_id', 'user_group_id'], 'integer'],
            [['creation_time', 'update_time'], 'safe'],

            [['price_id'], 'exist', 'skipOnError' => true, 'targetClass' => Price::className(), 'targetAttribute' => ['price_id' => 'id']],
            [['combination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Combination::className(), 'targetAttribute' => ['combination_id' => 'id']],
            [['user_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserGroup::className(), 'targetAttribute' => ['user_group_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'combination_id',
            'price_id',
            'user_group_id',
            'creation_time',
            'update_time',
            'price'
        ];
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creation_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('shop', 'ID'),
            'combination_id' => \Yii::t('shop', 'Combination'),
            'price_id' => \Yii::t('shop', 'Price'),
            'user_group_id' => \Yii::t('shop', 'User group'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrice() {
        return $this->hasOne(Price::className(), ['id' => 'price_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombination()
    {
        return $this->hasOne(Combination::className(), ['id' => 'combination_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'user_group_id']);
    }
}
