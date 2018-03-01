<?php
namespace sointula\shop\common\entities;

use bl\emailTemplates\models\entities\EmailTemplate;
use bl\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_order_status".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property string $color
 * @property integer $mail_id
 *
 * @property Order[] $orders
 * @property EmailTemplate $mail
 * @property OrderStatusTranslation[] $shopOrderStatusTranslations
 */
class OrderStatus extends ActiveRecord
{

    /**
     * When user added products to cart, but the order is not completed yet.
     */
    const STATUS_INCOMPLETE = 1;
    /**
     * When order is completed already and waits for moderation.
     */
    const STATUS_CONFIRMED = 2;
    /**
     * When order has sent to user
     */
    const STATUS_SENT = 3;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => OrderStatusTranslation::className(),
                'relationColumn' => 'order_status_id'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_id'], 'integer'],
            [['color'], 'string', 'max' => 36],
            [['mail_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmailTemplate::className(), 'targetAttribute' => ['mail_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cart', 'ID'),
            'color' => Yii::t('cart', 'Color'),
            'mail_id' => Yii::t('cart', 'Mail'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMail()
    {
        return $this->hasOne(EmailTemplate::className(), ['id' => 'mail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(OrderStatusTranslation::className(), ['order_status_id' => 'id']);
    }
}