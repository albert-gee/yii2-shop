<?php
namespace sointula\shop\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_delivery_method_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $language_id
 * @property integer $delivery_method_id
 * @property string $title
 * @property string $description
 *
 * @property DeliveryMethod $deliveryMethod
 */
class DeliveryMethodTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_delivery_method_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_method_id', 'language_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [['delivery_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryMethod::className(), 'targetAttribute' => ['delivery_method_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('shop', 'Title'),
            'description' => Yii::t('shop', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryMethod()
    {
        return $this->hasOne(DeliveryMethod::className(), ['id' => 'delivery_method_id']);
    }
}