<?php
namespace xalberteinsteinx\shop\frontend\widgets;

use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\ProductImage;
use yii\helpers\Html;
use evgeniyrru\yii2slick\Slick;
use newerton\fancybox\FancyBox;

/**
 * Widget renders product images.
 * @see https://github.com/EvgeniyRRU/yii2-slick
 * @see http://kenwheeler.github.io/slick/
 *
 * Example:
 * ```php
 * <?= \xalberteinsteinx\shop\frontend\widgets\ProductImageSlider::widget([
 *      'product' => $product,
 *
 *      // @see http://kenwheeler.github.io/slick/#settings
 *      'clientOptions' => [
 *          'autoplay' => true,
 *      ]
 * ]); ?>
 * ```
 *
 * @author Vyacheslav Nozhenko <vv.nojenko@gmail.com>
 */
class ProductImageSlider extends Slick
{
    /**
     * @var bool
     */
    public $fancyBox = false;

    /**
     * @var array
     */
    public $fancyBoxWidgetConfig = [];

    /**
     * @var string
     */
    public $defaultImage = '';

    /**
     * @var Product
     */
    public $product;

    /**
     * @var string
     */
    public $imagesSize = ProductImage::SIZE_BIG;

    /**
     * @inheritdoc
     */
    public $containerOptions = ['class' => 'product-image-slider'];

    /**
     * @inheritdoc
     */
    public $itemOptions = ['class' => 'product-image-slide'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->normalizeOptions();

        if(empty($this->items) && empty($this->product)) {
            throw new \Exception('Not allowed without items');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $slider = Html::beginTag($this->containerTag, $this->containerOptions);
        if (!empty($this->product)) {
            $this->items = $this->renderItems();
        }
        foreach($this->items as $item) {
            $slider .= Html::tag($this->itemContainer, $item, $this->itemOptions);
        }
        if($this->fancyBox) {
            $this->fancyBoxWidgetConfig['target'] = "a[rel=product-image-fancybox]";
            echo FancyBox::widget($this->fancyBoxWidgetConfig);
        }
        $slider .= Html::endTag($this->containerTag);
        echo $slider;
        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    protected function renderItems()
    {
        $items = [];
        if (empty($this->product->images)) {
            $items[] = ($this->fancyBox) ? Html::a(Html::img($this->defaultImage)) : Html::img($this->defaultImage);
        }
        foreach ($this->product->images as $productImage) {
            $items[] = $this->renderItem($productImage);
        }
        return $items;
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        $img = (!empty($item->getImage($this->imagesSize))) ? $item->getImage($this->imagesSize) : '';
        $alt = (!empty($item->translation->alt)) ? $item->translation->alt : '';
        if($this->fancyBox) {
            return Html::a(Html::img($img), $img, ['rel' => "product-image-fancybox"]);
        }
        return Html::img($img, ['alt' => $alt]);
    }
}