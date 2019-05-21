<?php

namespace albertgeeca\shop\common\entities;

use albertgeeca\shop\common\entities\Product;
use albertgeeca\shop\common\entities\Combination;
use albertgeeca\shop\common\entities\ProductImage;
use albertgeeca\shop\common\entities\Price;
use bl\imagable\helpers\FileHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_order_product".
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $combination_id
 * @property integer $order_id
 * @property integer $count
 *
 * @property Order $order
 * @property Product $product
 * @property Price $productPrice
 * @property OrderProductAdditionalProduct $orderProductAdditionalProduct
 */
class OrderProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id', 'count'], 'required'],
            [['product_id', 'order_id', 'combination_id', 'count'], 'integer'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['combination_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Combination::className(), 'targetAttribute' => ['combination_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('shop', 'Product ID'),
            'order_id' => Yii::t('shop', 'Order ID'),
            'count' => Yii::t('shop', 'Count'),
            'combination_id' => Yii::t('shop', 'Combination'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getPrice()
    {
        $product = $this->product;

        if (!empty($this->combination_id)) {
            $price = Combination::findOne($this->combination_id)->price->discountPrice;
        }
        else if (!empty($product->price)) {
            $price = $product->price->discountPrice;
        }

        return $price ?? 0;
    }

    public function getSmallPhoto() {
        return $this->getPhoto('small');
    }
    public function getThumbPhoto() {
        return $this->getPhoto('thumb');
    }
    public function getBigPhoto() {
        return $this->getPhoto('big');
    }

    private function getPhoto($size) {
        $image = ProductImage::find()->where(['product_id' => $this->product_id])->one();

        if (!empty($image)) {
            $imageName = $image->file_name;

            $logo = \Yii::$app->shop_imagable->get('shop-product', $size, $imageName);

            return '/images/shop-product/' . FileHelper::getFullName($logo);
        }
        else return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProductAdditionalProducts() {

        return $this->hasMany(OrderProductAdditionalProduct::className(), ['order_product_id' => 'id']);
    }

    /**
     * @param $additionalProductId integer
     * @return array|bool|null|ActiveRecord
     */
    public function getOrderProductAdditionalProduct($additionalProductId) {

        if (!empty($additionalProductId)) {
            $orderProductAdditionalProduct = OrderProductAdditionalProduct::find()
                ->where(['order_product_id' => $this->id, 'additional_product_id' => $additionalProductId])->one();

            return $orderProductAdditionalProduct;
        }
        return false;
    }
}