<?php
namespace sointula\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_product_availability_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $availability_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 */
class ProductAvailabilityTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_availability_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['availability_id', 'language_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 256],
        ];
    }

    public function fields() {
        return [
            // 'availability_id',
            'language_id',
            'title',
            'description',
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
            'description' => Yii::t('shop', 'Description'),
        ];
    }
}