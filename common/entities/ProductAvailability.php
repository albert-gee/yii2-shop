<?php
namespace sointula\shop\common\entities;

use bl\multilang\behaviors\TranslationBehavior;
use bl\multilang\entities\Language;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_product_availability".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property ProductAvailabilityTranslation[] $translations
 * @property ProductAvailabilityTranslation $translation
 * @property Product[] $products
 */
class ProductAvailability extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_availability';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => ProductAvailabilityTranslation::className(),
                'relationColumn' => 'availability_id'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    public function fields() {
        return [
            'id',
            'translations'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['status' => 'id']);
    }

    /**
     * @param int|null $language_id
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation($language_id = null)
    {
        return $this->hasOne(ProductAvailabilityTranslation::className(), ['availability_id' => 'id'])
            ->andOnCondition(['language_id' => $language_id ?? Language::getCurrent()->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ProductAvailabilityTranslation::className(), ['availability_id' => 'id']);
    }
}