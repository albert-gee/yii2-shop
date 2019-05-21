<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $viewName string
 * @var $params array
 */

use yii\widgets\Pjax;
use yii\helpers\{
    Html, Url
};
use albertgeeca\shop\backend\assets\EditProductAsset;

EditProductAsset::register($this);

$this->title = ($params['category']->isNewRecord) ? \Yii::t('shop', 'Add new category') : \Yii::t('shop', 'Edit category');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('shop', 'Shop'),
        'url' => ['/seo/static/save-page', 'page_key' => 'shop', 'languageId' => $params['selectedLanguage']->id],
        'itemprop' => 'url'
    ],
    [
        'label' => Yii::t('shop', 'Categories'),
        'url' => ['/shop/category'],
        'itemprop' => 'url'
    ],
    $params['category']->translation->title ?? ''
];
?>


<?php Pjax::begin([
    'linkSelector' => '.pjax',
    'enablePushState' => true,
    'timeout' => 10000
]);
?>

<!--TAB VIEW-->
<?= $this->render($viewName, $params); ?>

<?php Pjax::end(); ?>

