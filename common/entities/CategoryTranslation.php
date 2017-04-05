<?php
namespace xalberteinsteinx\shop\common\entities;

use bl\multilang\entities\Language;
use bl\seo\behaviors\SeoDataBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shop_category_translation".
 * @author Albert Gainutdinov
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 *
 * @property string $seoUrl
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 *
 * @property Category $category
 * @property Language $language
 */

class CategoryTranslation extends ActiveRecord
{

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
    public static function tableName()
    {
        return 'shop_category_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'language_id', 'title'], 'required'],
            [['category_id', 'language_id'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],

            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string']
        ];
    }

    public function fields()
    {
        return [
            'category_id',
            'language_id',
            'title',
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
            'id' => Yii::t('shop', 'ID'),
            'title' => Yii::t('shop', 'Title'),
            'description' => Yii::t('shop', 'Description'),
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}