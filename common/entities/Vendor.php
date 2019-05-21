<?php
namespace albertgeeca\shop\common\entities;

use bl\imagable\helpers\FileHelper;
use bl\multilang\behaviors\TranslationBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_vendor".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_name
 * @property string $description
 *
 * @property Product[] $products
 * @property VendorTranslation[] $translations
 */
class Vendor extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_vendor';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => VendorTranslation::className(),
                'relationColumn' => 'vendor_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'image_name'], 'string', 'max' => 255],
            [['description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'image_name',
            'translations'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'Id'),
            'title' => Yii::t('shop','Title'),
            'image_name' => Yii::t('shop','Image filename'),
            'description' => \Yii::t('shop', 'Description')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['vendor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModeratedProducts() {
        return $this->hasMany(Product::className(), ['vendor_id' => 'id'])->where(['status' => Product::STATUS_SUCCESS]);
    }

    /**
     * @param string $size
     * @return mixed
     */
    public function getImage(string $size = 'big') {
        $image = \Yii::$app->shop_imagable->get('shop-vendors', $size, $this->image_name);
        $image = str_replace('\\', '/', $image);
        $image = str_replace(\Yii::getAlias('@webroot'), '', $image);

        return $image;
    }

    public function getTranslations()
    {
        return $this->hasMany(VendorTranslation::className(), ['vendor_id' => 'id']);
    }

    /**
     * Adds title, meta-description and meta-keywords to category page using bl\cms\seo\StaticPageBehavior.
     */
    public function registerMetaData()
    {
        $currentView = Yii::$app->controller->view;

        $currentView->title = html_entity_decode($this->translation->seoTitle ?? $this->translation->title ?? '');
        $currentView->registerMetaTag([
            'name' => 'description',
            'content' => html_entity_decode($this->translation->seoDescription ?? '')
        ]);
        $currentView->registerMetaTag([
            'name' => 'keywords',
            'content' => html_entity_decode($this->translation->seoKeywords ?? '')
        ]);
    }
}
