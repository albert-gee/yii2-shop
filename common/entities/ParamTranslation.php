<?php
namespace xalberteinsteinx\shop\common\entities;

use bl\seo\behaviors\SeoDataBehavior;
use yii\db\ActiveRecord;

/**
 * @author Albert Gainutdinov
 *
 * @property integer $param_id
 * @property string $name
 * @property string $value
 */
class ParamTranslation extends ActiveRecord
{

    public function rules()
    {
        return [
            [['param_id'], 'number'],
            [['name', 'value'], 'string'],
        ];
    }

    public function fields() {
        return [
            'name',
            'value',
            'language_id'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_param_translation';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParam()
    {
        return $this->hasOne(Param::className(), ['id' => 'param_id']);
    }
}