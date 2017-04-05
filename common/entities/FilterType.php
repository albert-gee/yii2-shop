<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_filter_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $class_name
 * @property string $column
 * @property string $displaying_column
 *
 * @property Filter[] $shopFilters
 */
class FilterType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_filter_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'class_name', 'column', 'displaying_column'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'title' => Yii::t('shop', 'Title'),
            'class_name' => \Yii::t('shop', 'Class name with namespace'),
            'column' => Yii::t('shop', 'Column'),
            'displaying_column' => Yii::t('shop', 'Displaying column'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopFilters()
    {
        return $this->hasMany(Filter::className(), ['filter_type' => 'id']);
    }
}