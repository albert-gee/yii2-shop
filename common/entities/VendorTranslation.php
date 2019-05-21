<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

namespace albertgeeca\shop\common\entities;
use bl\multilang\entities\Language;
use bl\seo\behaviors\SeoDataBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "shop_vendor_translation".
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $language_id
 * @property string $description
 *
 * @property Language $language
 * @property Vendor $vendor
 */
class VendorTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_vendor_translation';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'seoData' => [
                'class' => SeoDataBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'language_id'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'id']],
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
        ];
    }

    public function fields()
    {
        return [
            'vendor_id',
            'language_id',
            'description',
            'seoUrl',
            'seoTitle',
            'seoDescription',
            'seoKeywords'
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('shop', 'ID'),
            'vendor_id' => \Yii::t('shop', 'Vendor'),
            'language_id' => \Yii::t('shop', 'Language'),
            'description' => \Yii::t('shop', 'Description'),
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
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }
}