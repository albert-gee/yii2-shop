<?php
use bl\multilang\MultiLangUrlManager;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\backend\assets\EditProductAsset;
use yii\widgets\Pjax;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var \yii\web\View $this
 * @var Product $product
 * @var MultiLangUrlManager $urlManagerFrontend
 * @var string $viewName
 * @var array $params
 */

EditProductAsset::register($this);

$this->title = \Yii::t('shop', ($product->isNewRecord) ? 'Creating a new product' : 'Changing the product');

$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    [
        'label' => Yii::t('shop', 'Products'),
        'url' => ['/shop/product'],
        'itemprop' => 'url'
    ]
];
$this->params['breadcrumbs'][] = (!empty($product->translation)) ? $product->translation->title :
    \Yii::t('shop', 'New product');
?>

<!--BODY PANEL-->
<?php Pjax::begin([
    'id' => 'p-product-save',
    'linkSelector' => '.pjax'
]); ?>

<!--CONTENT-->
<?= $this->render($viewName, $params); ?>

<?php Pjax::end(); ?>