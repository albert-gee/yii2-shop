<?php
namespace albertgeeca\shop\common\entities;
use bl\multilang\behaviors\TranslationBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * @author Albert Gainutdinov
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $position
 *
 * @property Product $product
 * @property ParamTranslation $translation
 * @property ParamTranslation[] $translations
 *
 * @method ParamTranslation getTranslation($languageId = null)
 */



class Param extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => ParamTranslation::className(),
                'relationColumn' => 'param_id'
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

    public function rules()
    {
        return [
            [['product_id', 'position'], 'number']
        ];
    }

    public function fields() {
        return [
            'id',
            'position',
            'product_id',
            'translations'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_param';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ParamTranslation::className(), ['param_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}