<?php
namespace xalberteinsteinx\shop\common\entities;

use xalberteinsteinx\shop\common\components\user\models\Profile;
use xalberteinsteinx\shop\models\Order;
use xalberteinsteinx\shop\common\components\user\models\UserGroup;
use xalberteinsteinx\shop\helpers\ShopArrayHelper;
use bl\multilang\behaviors\TranslationBehavior;
use bl\multilang\entities\Language;
use dektrium\user\models\User;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\{
    ActiveRecord, Expression
};
use yii\helpers\Url;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "shop_product".
 * @author Albert Gainutdinov
 *
 * @property integer $id
 * @property integer $position
 * @property integer $category_id
 * @property integer $vendor_id
 * @property integer $country_id
 * @property string $sku
 * @property string $creation_time
 * @property string $update_time
 * @property integer $status
 * @property integer $availability
 * @property integer $owner
 * @property boolean $sale
 * @property integer $views
 * @property boolean $popular
 * @property boolean $new
 * @property integer $price_id
 * @property integer $number
 * @property boolean $show
 * @property integer $shows
 *
 * @property Category $category
 * @property RelatedProduct $relatedProducts
 * @property Param[] $params
 * @property ProductCountry $productCountry
 * @property Vendor $vendor
 * @property ProductAvailability $productAvailability
 * @property Order[] $orders Gets orders, which have this product
 * @property integer $orderedNumber Gets number of units that have already bought.
 * @property User $productOwner
 * @property ProductPrice $productPrice
 * @property Profile $ownerProfile
 * @property Price $price Gets price for current user group.
 * @property Price[] $prices Gets all product prices
 * @property float $oldPrice
 * @property float $discountPrice
 * @property ProductFile[] $files
 * @property ProductImage[] $images Gets all product images
 * @property ProductImage $image Gets one product image
 * @property ProductVideo[] $videos
 * @property ProductTranslation $translation
 * @property ProductTranslation[] $translations
 * @property Combination[] $shopCombinations
 * @property Combination $defaultCombination
 * @property Combination[] $combinations
 * @property ShopAttribute[] $productAttributes
 * @property boolean $isFavorite
 * @property ProductAdditionalProduct[] $productAdditionalProducts
 *
 */
class Product extends ActiveRecord
{
    /**
     * Constants for status column
     */
    const STATUS_ON_MODERATION = 1;
    const STATUS_DECLINED = 2;
    const STATUS_SUCCESS = 10;

    /**
     * This properties are used for the Cart component.
     */
    public $count;
    public $combinationId;
    public $additionalProducts;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position', 'category_id', 'vendor_id', 'country_id', 'status', 'availability', 'owner', 'views', 'price_id', 'number', 'shows'], 'integer'],
            [['category_id', 'vendor_id', 'country_id', 'availability', 'number'], 'filter', 'filter' => function($value) {
                $value = (!empty($value)) ? (int)$value : NULL;
                return $value;
            }],
            [['sale', 'popular'], 'filter', 'filter' => function($value) {
                $value = (int)$value;
                return $value;
            }],

            [['sale', 'popular', 'new', 'show'], 'boolean'],
            [['creation_time', 'update_time'], 'safe'],
            [['sku'], 'string', 'max' => 255],
            [['sku'], 'trim'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'id']],
            [['availability'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAvailability::className(), 'targetAttribute' => ['availability' => 'id']],
            [['owner'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner' => 'id']],
            [['price_id'], 'exist', 'skipOnError' => true, 'targetClass' => Price::className(), 'targetAttribute' => ['price_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'position',
            'category_id',
            'vendor_id',
            'country_id',
            'sku',
            'creation_time',
            // 'update_time',
            'status',
            'availability',
            // 'owner',
            'views',
            'new',
            'sale',
            'popular',
            //'price_id',
            'number',
            'show',

            'translations',
            'params',
            // 'productAvailability',
            'productPrices',
            'images',
            'combinations',
            'productAdditionalProducts'
        ];
    }


    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::className(),
                'translationClass' => ProductTranslation::className(),
                'relationColumn' => 'product_id'
            ],
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'position',
                'groupAttributes' => [
                    'category_id'
                ],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'position' => Yii::t('shop', 'Position'),
            'category_id' => Yii::t('shop', 'Category'),
            'vendor_id' => Yii::t('shop', 'Vendor'),
            'country_id' => Yii::t('shop', 'Country'),
            'sku' => Yii::t('shop', 'Sku'),
            'creation_time' => Yii::t('shop', 'Creation Time'),
            'update_time' => Yii::t('shop', 'Update Time'),
            'status' => Yii::t('shop', 'Status'),
            'availability' => Yii::t('shop', 'Availability'),
            'owner' => Yii::t('shop', 'Owner'),
            'sale' => Yii::t('shop', 'Sale'),
            'new' => Yii::t('shop', 'New'),
            'views' => Yii::t('shop', 'Views'),
            'popular' => Yii::t('shop', 'Popular'),
            'price_id' => Yii::t('shop', 'Price'),
            'number' => Yii::t('shop', 'Number'),
            'show' => Yii::t('shop', 'Show'),
            'shows' => Yii::t('shop', 'Shows'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParams()
    {
        return $this->hasMany(Param::className(), ['product_id' => 'id'])->orderBy('position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCountry()
    {
        return $this->hasOne(ProductCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAvailability()
    {
        return $this->hasOne(ProductAvailability::className(), ['id' => 'availability']);
    }

    /**
     * Gets orders, which have this product
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        $orders = Order::find()->joinWith('orderProducts')->where(['product_id' => $this->id])->all();
        return $orders;
    }

    /**
     * Gets number of units that have already bought.
     * @return int|mixed
     */
    public function getOrderedNumber()
    {
        $orderedProductsNumber = Order::find()->select('count')->joinWith('orderProducts')->where(['product_id' => $this->id])->sum('count');
        return $orderedProductsNumber ?? 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts()
    {
        return $this->hasMany(RelatedProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProductsWhereItRelated()
    {
        return $this->hasMany(RelatedProduct::className(), ['related_product_id' => 'id']);
    }

    /**
     * Gets user profile, which created product.
     * @return \yii\db\ActiveQuery
     */
    public function getOwnerProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPrices() {
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id']);
    }

    /**
     * Gets prices for current user group
     * @return mixed
     */
    public function getPrice()
    {
        $user_group_id = (\Yii::$app->user->isGuest) ? UserGroup::USER_GROUP_ALL_USERS :
            \Yii::$app->user->identity->user_group_id;
        $productPrice = ProductPrice::find()
            ->where(['product_id' => $this->id, 'user_group_id' => $user_group_id])->one();

        return $productPrice->price ?? 0;
    }

    /**
     * @param $userGroupId
     * @return mixed
     * @throws Exception
     */
    public function getPriceByUserGroup($userGroupId)
    {
        if (!empty($userGroupId)) {
            $productPrice = ProductPrice::find()
                ->where(['product_id' => $this->id, 'user_group_id' => $userGroupId])
                ->one();
            if (!empty($productPrice)) return $productPrice->price;
            else return false;
        }
        else throw new Exception('User group id is empty');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        $productPrice = ProductPrice::findOne($this->price_id);
        $prices = $productPrice->prices;
        return $prices;
    }

    /**
     * @return float|int
     */
    public function getOldPrice()
    {
        $price = $this->price;
        if (!empty($price)) {
            $oldPrice = $price->oldPrice;
        }
        else $oldPrice = 0;

        return $oldPrice;
    }

    /**
     * Gets price with discount
     * @return float|int
     * @throws Exception
     */
    public function getDiscountPrice()
    {
        $price = $this->price;
        if (!empty($price)) {
            $discountPrice = $price->discountPrice;
        }
        else $discountPrice = 0;

        return $discountPrice;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(ProductFile::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id'])->orderBy('position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(ProductVideo::className(), ['product_id' => 'id']);
    }

    /**
     * @param int|null $languageId
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation($languageId = null)
    {
        return $this->hasOne(ProductTranslation::className(), ['product_id' => 'id'])
            ->andOnCondition(['language_id' => $languageId ?? Language::getCurrent()->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ProductTranslation::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombinations()
    {
        return $this->hasMany(Combination::className(), ['product_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function hasCombinations()
    {
        $count = Combination::find()->where(['product_id' => $this->id])->count();
        return (boolean) $count;
    }

    /**
     * @param $id
     * @return Combination
     * @throws \yii\base\Exception
     */
    public function getCombination($id)
    {
        if (!empty($id)) {
            $combination = Combination::findOne($id);
            if (!empty($combination))
                return $combination;
            else
                throw new \yii\base\Exception('Combination does not exists');
        }
        else
            throw new \yii\base\Exception('Combination id can not be empty');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultCombination()
    {
        return $this->hasOne(Combination::className(), ['product_id' => 'id'])
            ->where(['default' => true]);
    }

    /**
     * Gets attributes, which used in product combinations
     * @return ShopAttribute[]
     */
    public function getProductAttributes()
    {
        $attributes = [];
        foreach ($this->combinations as $combination) {
            if (!empty($combination->combinationAttributes)) {
                foreach ($combination->combinationAttributes as $combinationAttribute) {
                    $attributes[] = $combinationAttribute->productAttribute;
                }
            }
        }
        $attributes = ShopArrayHelper::removeDuplicatedArrayElements($attributes);
        return $attributes;
    }

    /**
     * Return true if product has added to favorites already or false if not.
     * @return boolean
     */
    public function isFavorite()
    {
        $favoriteProduct = FavoriteProduct::find()
            ->where(['product_id' => $this->id, 'user_id' => \Yii::$app->user->id])->one();
        if (!empty($favoriteProduct)) return true;
        else return false;
    }

    /**
     * Generates unique name by string.
     * @param $baseName
     * @return string
     */
    public static function generateImageName($baseName)
    {
        $fileName = hash('crc32', $baseName . time());
        if (file_exists(Yii::getAlias('@frontend/web/images/shop/' . $fileName . '-original.jpg'))) {
            return static::generateImageName($baseName);
        }
        return $fileName;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = '/' . Yii::$app->controller->module->id . '/product/show';
        return Url::to([$url, 'id' => $this->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAdditionalProducts() {
        return $this->hasMany(ProductAdditionalProduct::className(), ['product_id' => 'id'])
            ->joinWith('additionalProduct')
            ->orderBy('position');

    }
}