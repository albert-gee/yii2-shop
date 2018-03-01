<?php
namespace sointula\shop\common\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_product_discount_type".
 *
 * @property integer $id
 * @property string $title
 *
 * @property Price[] $prices
 */

class PriceDiscountType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_price_discount_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('shop', 'ID'),
            'title' => \Yii::t('shop', 'Title'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['discount_type_id' => 'id']);
    }
}
