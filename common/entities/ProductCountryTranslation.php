<?php
namespace albertgeeca\shop\common\entities;
use bl\multilang\entities\Language;
use bl\seo\behaviors\SeoDataBehavior;
use bl\seo\entities\SeoData;
use Yii;
use yii\db\ActiveRecord;

/**
 * @author Albert Gainutdinov
 * 
 * @property integer $id
 * @property integer $country_id
 * @property integer $language_id
 * @property string $title
 */

class ProductCountryTranslation extends ActiveRecord
{

    public function rules()
    {
        return [
            [['id', 'country_id', 'language_id'], 'number'],
            [['title'], 'string']
        ];
    }

    public function fields() {
        return [
            'country_id',
            'language_id',
            'title'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_country_translation';
    }
}
