<?php

namespace albertgeeca\shop\common\entities;

use bl\multilang\entities\Language;
use Yii;

/**
 * This is the model class for table "shop_attribute_translation".
 *
 * @property integer $id
 * @property string $title
 * @property integer $language_id
 * @property integer $attr_id
 *
 * @property Language $language
 */
class ShopAttributeTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_attribute_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'attr_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('shop', 'Title')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
