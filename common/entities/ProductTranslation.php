<?php
namespace albertgeeca\shop\common\entities;
use bl\multilang\entities\Language;
use bl\seo\behaviors\SeoDataBehavior;
use bl\seo\entities\SeoData;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * @author Albert Gainutdinov
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 * @property string $full_text
 * @property string $creation_time
 * @property string $update_time
 *
 * @property string $seoUrl
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 *
 * @property Language $language
 * @property Product $product
 */

class ProductTranslation extends ActiveRecord
{
    public static function tableName()
    {
        return 'shop_product_translation';
    }

    public function behaviors()
    {
        return [
            'seoData' => [
                'class' => SeoDataBehavior::className()
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creation_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['product_id', 'language_id'], 'integer'],
            [['description', 'full_text'], 'string'],
            [['creation_time', 'update_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],

            /*SEO params*/
            [['seoUrl', 'seoTitle', 'seoDescription', 'seoKeywords'], 'string'],
            [['seoUrl'], 'uniqueForEntity'],
        ];
    }

    public function fields()
    {
        return [
            // 'id',
            // 'product_id',
            'language_id',
            'title',
            'description',
            'full_text',
            'creation_time',
            // 'update_time',

            'seoUrl',
            'seoTitle',
            'seoDescription',
            'seoKeywords'
        ];
    }


    /**
     * Validation rule which checks if such seo url is already exist in current entity
     * @param $attribute
     * @param $params
     */
    public function uniqueForEntity($attribute, $params)
    {
        $translationsIds = ProductTranslation::find()
            ->asArray()->where(['product_id' => $this->product_id])->select('id')->all();

        $seoUrl = SeoData::find()
            ->where(['entity_name' => ProductTranslation::className(), 'seo_url' => \Yii::$app->request->post()['ProductTranslation']['seoUrl']])
            ->andWhere(['not in', 'entity_id', ArrayHelper::getColumn($translationsIds, 'id')])
            ->one();

        if (!empty($seoUrl)) {
            $this->addError($attribute, \Yii::t('shop', 'Such url already exists'));
        }
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}