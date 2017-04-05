<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_filter_input_type".
 *
 * @property integer $id
 * @property string $title
 *
 * @property Filter[] $shopFilters
 */
class FilterInputType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_filter_input_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopFilters()
    {
        return $this->hasMany(Filter::className(), ['input_type' => 'id']);
    }
}