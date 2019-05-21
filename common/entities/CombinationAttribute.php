<?php
namespace albertgeeca\shop\common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "shop_combination_attribute".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $combination_id
 * @property integer $attribute_id
 * @property integer $attribute_value_id
 * @property string $creation_time
 * @property string $update_time
 *
 * @property ShopAttribute $productAttribute
 * @property ShopAttributeValue $productAttributeValue
 * @property Combination $combination
 */
class CombinationAttribute extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_combination_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['combination_id', 'attribute_id', 'attribute_value_id'], 'integer'],
            [['creation_time', 'update_time'], 'safe'],

            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['attribute_value_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopAttributeValue::className(), 'targetAttribute' => ['attribute_value_id' => 'id']],
            [['combination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Combination::className(), 'targetAttribute' => ['combination_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'combination_id',
            'attribute_id',
            'attribute_value_id',
            'creation_time',
            'update_time'
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
            'id' => Yii::t('shop', 'ID'),
            'combination_id' => Yii::t('shop', 'Combination'),
            'attribute_id' => Yii::t('shop', 'Attribute'),
            'attribute_value_id' => Yii::t('shop', 'Attribute value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        return $this->hasOne(ShopAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributeValue()
    {
        return $this->hasOne(ShopAttributeValue::className(), ['id' => 'attribute_value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombination()
    {
        return $this->hasOne(Combination::className(), ['id' => 'combination_id']);
    }
}