<?php
namespace sointula\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_product_additional_product".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $additional_product_id
 *
 * @property Product $additionalProduct
 * @property Product $product
 */
class ProductAdditionalProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_additional_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'additional_product_id'], 'integer'],
            [['additional_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['additional_product_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'product_id' => Yii::t('shop', 'Product'),
            'additional_product_id' => Yii::t('shop', 'Additional product'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'additional_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}