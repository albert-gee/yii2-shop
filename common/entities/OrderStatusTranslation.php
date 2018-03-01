<?php
namespace sointula\shop\common\entities;

use bl\multilang\entities\Language;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_order_status_translation".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $order_status_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 *
 * @property Language $language
 * @property OrderStatus $orderStatus
 */
class OrderStatusTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order_status_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status_id', 'language_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cart', 'ID'),
            'order_status_id' => Yii::t('cart', 'Order Status'),
            'language_id' => Yii::t('cart', 'Language'),
            'title' => Yii::t('cart', 'Title'),
            'description' => Yii::t('cart', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status_id']);
    }
}