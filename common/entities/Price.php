<?php
namespace xalberteinsteinx\shop\common\entities;

use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_price".
 *
 * @property integer $id
 * @property double $price
 * @property double $discount
 * @property integer $discount_type_id
 *
 * @property PriceDiscountType $discountType
 * @property float $oldPrice
 * @property float $discountPrice
 * @property ProductPrice $productPrice
 * @property CombinationPrice $combinationPrice
 */
class Price extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_type_id'], 'integer'],
            [['price', 'discount'], 'number'],
            [['discount_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceDiscountType::className(), 'targetAttribute' => ['discount_type_id' => 'id']],
        ];
    }

    public function fields() {
        return [
            'price',
            'discount',
            'discount_type_id'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('shop', 'ID'),
            'price' => \Yii::t('shop', 'Price'),
            'discount' => \Yii::t('shop', 'Discount'),
            'discount_type_id' => \Yii::t('shop', 'Discount Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountType()
    {
        return $this->hasOne(PriceDiscountType::className(), ['id' => 'discount_type_id']);
    }

    /**
     * Gets price
     * @return float|int
     */
    public function getOldPrice()
    {
        $price = $this->price;
        if (\Yii::$app->getModule('shop')->enableCurrencyConversion) {
            $price = $price * Currency::currentCurrency();
        }
        if (\Yii::$app->getModule('shop')->enablePriceRounding) {
            $price = floor($price);
        }

        return $price;
    }

    /**
     * Gets price with discount
     * @return float|int
     * @throws Exception
     */
    public function getDiscountPrice()
    {
        $price = $this->price;

        if (!empty($this->discount) && !empty($this->discount_type_id)) {
            if ($this->discountType->title == "money") {
                $price = $this->price - $this->discount;
            } else if ($this->discountType->title == "percent") {
                $price = $this->price - ($this->price / 100) * $this->discount;
            } else throw new Exception(\Yii::t('shop', 'Such discount type does not exist.'));
        }

        if (\Yii::$app->getModule('shop')->enableCurrencyConversion) {
            $price = $price * Currency::currentCurrency();
        }
        if (\Yii::$app->getModule('shop')->enablePriceRounding) {
            $price = floor($price);
        }

        return $price;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPrice()
    {
        return $this->hasOne(ProductPrice::className(), ['price_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombinationPrice()
    {
        return $this->hasOne(CombinationPrice::className(), ['price_id' => 'id']);
    }
}
