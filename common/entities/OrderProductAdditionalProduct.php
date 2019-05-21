<?php
namespace albertgeeca\shop\common\entities;

use albertgeeca\shop\common\entities\Product;
use Yii;

/**
 * This is the model class for table "shop_order_product_additional_product".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $order_product_id
 * @property integer $additional_product_id
 * @property integer $number
 *
 * @property OrderProduct $orderProduct
 * @property Product $additionalProduct
 */
class OrderProductAdditionalProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order_product_additional_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_product_id', 'additional_product_id', 'number'], 'integer'],
            [['order_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProduct::className(), 'targetAttribute' => ['order_product_id' => 'id']],
            [['additional_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['additional_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cart', 'ID'),
            'order_product_id' => Yii::t('cart', 'Order Product ID'),
            'additional_product_id' => Yii::t('cart', 'Additional Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProduct()
    {
        return $this->hasOne(OrderProduct::className(), ['id' => 'order_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'additional_product_id']);
    }
}