<?php

namespace sointula\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_attribute_type".
 *
 * @property integer $id
 * @property string $title
 *
 * @property ShopAttribute[] $shopAttributes
 */
class ShopAttributeType extends ActiveRecord
{

    const TYPE_DROP_DOWN_LIST = 1;
    const TYPE_RADIO_BUTTON = 2;
    const TYPE_COLOR = 3;
    const TYPE_TEXTURE = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_attribute_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'id'),
            'title' => Yii::t('shop', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopAttributes()
    {
        return $this->hasMany(ShopAttribute::className(), ['type_id' => 'id']);
    }

}
