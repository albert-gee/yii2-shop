<?php

namespace albertgeeca\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;
use bl\multilang\entities\Language;

/**
 * This is the model class for table "shop_product_image_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $image_id
 * @property integer $language_id
 * @property string $alt
 *
 * @property Language $language
 * @property ProductImage $image
 */
class ProductImageTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_image_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'language_id'], 'integer'],
            [['alt'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductImage::className(), 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    public function fields() {
        return [
            'alt',
            'language_id'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'image_id' => Yii::t('shop', 'Image ID'),
            'language_id' => Yii::t('shop', 'Language ID'),
            'alt' => Yii::t('shop', 'Alt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(ProductImage::className(), ['id' => 'image_id']);
    }
}