<?php

namespace xalberteinsteinx\shop\common\entities;

use bl\multilang\behaviors\TranslationBehavior;
use bl\multilang\entities\Language;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_attribute_value".
 *
 * @property integer $id
 * @property integer $attribute_id
 *
 * @property ShopAttribute $attribute
 * @property ShopAttributeValueTranslation[] $shopAttributeValueTranslations
 * @property ShopAttributeValueTranslation $translation
 */
class ShopAttributeValue extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => ShopAttributeValueTranslation::className(),
                'relationColumn' => 'value_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_attribute_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'integer'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'shopAttributeValueTranslations',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('shop', 'Id'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopAttribute() {
        return $this->hasOne(ShopAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @param int|null $language_id
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation($language_id = null)
    {
        return $this->hasOne(ShopAttributeValueTranslation::className(), ['value_id' => 'id'])
            ->andOnCondition(['language_id' => $language_id ?? Language::getCurrent()->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopAttributeValueTranslations()
    {
        return $this->hasMany(ShopAttributeValueTranslation::className(), ['value_id' => 'id']);
    }

}
