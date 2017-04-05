<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "shop_attribute_value_color_texture".
 *
 * @property integer $id
 * @property string $color
 * @property string $texture
 * @property string $title
 *
 * @property ShopAttributeValueTranslation $translation
 */
class ShopAttributeValueColorTexture extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_attribute_value_color_texture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color', 'texture', 'title'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'color',
            'texture',
            'title'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'color' => Yii::t('shop', 'Color'),
            'texture' => Yii::t('shop', 'Texture'),
            'title' => Yii::t('shop', 'Title')
        ];
    }

    public function getTextureFile()
    {
        return '/images/shop/attribute-texture/' . $this->texture;
    }

    public function getAttributeTexture() {
        return Html::img('/images/shop/attribute-texture/' . self::findOne($this->id)->texture, ['class' => 'texture']);
    }

    public function getAttributeColor() {

        return Html::tag('div', '', [
            'style' => 'background-color:' . self::findOne($this->id)->color . ';',
            'class' => 'color']);
    }
}
