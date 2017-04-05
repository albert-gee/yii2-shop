<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;

/**
 * This is the model class for table "shop_product_video".
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * 
 * @property integer $id
 * @property integer $product_id
 * @property string $resource
 * @property string $file_name
 *
 * @property Product $product
 */

class ProductVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'integer'],
            [['resource'], 'string', 'max' => 255],
            [['file_name'], 'string', 'max' => 500],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'resource' => 'Resource',
            'file_name' => 'File Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}