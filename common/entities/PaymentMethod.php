<?php
namespace sointula\shop\common\entities;

use bl\imagable\helpers\FileHelper;
use bl\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment_method".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $image
 *
 * @property PaymentMethodTranslation[] $paymentMethodTranslations
 */
class PaymentMethod extends ActiveRecord
{
    /**
     * @var string
     */
    private $imagePath = '/images/payment/';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => PaymentMethodTranslation::className(),
                'relationColumn' => 'payment_method_id'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_method';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image' => Yii::t('payment', 'Logo'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PaymentMethodTranslation::className(), ['payment_method_id' => 'id']);
    }
    public function getBigLogo() {
        $logo = $this->getLogo('big');
        return $logo;
    }
    public function getThumbLogo() {
        $logo = $this->getLogo('thumb');
        return $logo;
    }
    public function getSmallLogo() {
        $logo = $this->getLogo('small');
        return $logo;
    }
    private function getLogo($size) {
        if (!empty($this->image)) {
            return $this->imagePath . FileHelper::getFullName(
                    \Yii::$app->shop_imagable->get('payment', $size, $this->image));
        }
        else return '';
    }
}