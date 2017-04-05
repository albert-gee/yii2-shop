<?php
namespace xalberteinsteinx\shop\frontend\widgets;


use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\ProductImage;
use yii\helpers\Html;
use evgeniyrru\yii2slick\Slick;

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

        $slider .= Html::endTag($this->containerTag);
        echo $slider;
        $this->registerClientScript();
    }

    /**
     * @return array
     */
    protected function renderItems()
    {
        $items = [];
        foreach ($this->product->images as $productImage) {
            $items[] = $this->renderItem($productImage);
        }
        return $items;
    }

    /**
     * @param $item ProductImage
     * @return string
     */
    protected function renderItem($item)
    {
        $img = (!empty($item->getImage('big'))) ? $item->getImage($this->imagesSize) : '';
        $alt = (!empty($item->translation->alt)) ? $item->translation->alt : '';

        return Html::img($img, ['alt' => $alt]);
    }
}
