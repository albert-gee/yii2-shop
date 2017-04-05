<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;

/**
 * This is the model class for table "shop_filters".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $filter_type
 * @property integer $input_type
 *
 * @property Category $category
 * @property FilterType $filterType
 * @property FilterInputType $inputType
 */
class Filter extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_filters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'filter_type', 'input_type'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['filter_type'], 'exist', 'skipOnError' => true, 'targetClass' => FilterType::className(), 'targetAttribute' => ['filter_type' => 'id']],
            [['input_type'], 'exist', 'skipOnError' => true, 'targetClass' => FilterInputType::className(), 'targetAttribute' => ['input_type' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'category_id' => Yii::t('shop', 'Category ID'),
            'filter_type' => Yii::t('shop', 'Filter type'),
            'input_type' => Yii::t('shop', 'Field type')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FilterType::className(), ['id' => 'filter_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInputType()
    {
        return $this->hasOne(FilterInputType::className(), ['id' => 'input_type']);
    }

    /**
     * This method return only these rows which product_id complies existing product.
     * @param $filter
     * @param $categoryId
     */
    public static function getCategoryFilterValues($filter, $categoryId) {
        /* @var $class ActiveRecordInterface */

        $class = $filter->type->class_name;
        $objects = $class::find()
            ->joinWith('products p')
            ->where(['p.category_id' => $categoryId])
            ->all();

        return $objects;
    }
}