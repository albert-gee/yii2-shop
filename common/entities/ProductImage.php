<?php
namespace xalberteinsteinx\shop\common\entities;

use bl\imagable\helpers\FileHelper;
use bl\multilang\behaviors\TranslationBehavior;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "shop_product_image".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $position
 * @property string $file_name
 * @property string $extension
 *
 * @property Product $product
 * @property ProductImageTranslation $translation
 *
 * @method ProductImageTranslation getTranslation($languageId = null)
 */

class ProductImage extends ActiveRecord
{
    const SIZE_BIG = 'big';
    const SIZE_SMALL = 'small';
    const SIZE_THUMB = 'thumb';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_image';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => ProductImageTranslation::className(),
                'relationColumn' => 'image_id'
            ],
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'position',
                'groupAttributes' => [
                    'product_id'
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'position'], 'integer'],
            [['file_name'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>'3000000'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function fields() {
        return [
            'id',
            'product_id',
            'position',
            'file_name',
            'translations'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     */
    public function removeImage() {
        $this->delete();
        \Yii::$app->shop_imagable->delete('shop-product', $this->file_name);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ProductImageTranslation::className(), ['image_id' => 'id']);
    }

    /**
     * @return string path to big product image.
     */
    public function getBig() {
        return $this->getImage(self::SIZE_BIG);
    }

    /**
     * @return string path to thumb product image.
     */
    public function getThumb() {
        return $this->getImage(self::SIZE_THUMB);
    }

    /**
     * @return string path to small product image.
     */
    public function getSmall() {
        return $this->getImage(self::SIZE_SMALL);
    }

    /**
     * @param string $size image size.
     * @return string path to product image.
     */
    public function getImage($size = self::SIZE_BIG) {
        $image = \Yii::$app->shop_imagable->get('shop-product', $size, $this->file_name);

        return '/images/shop-product/' . FileHelper::getFullName($image);
    }
}
