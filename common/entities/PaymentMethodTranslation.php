<?php
namespace sointula\shop\common\entities;

use bl\multilang\entities\Language;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment_method_translation".
 *
 * @property integer $id
 * @property integer $payment_method_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 *
 * @property Language $language
 * @property PaymentMethod $paymentMethod
 */
class PaymentMethodTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_method_translation';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_method_id', 'language_id'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('payment', 'Title'),
            'description' => Yii::t('payment', 'Description'),
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
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }
}