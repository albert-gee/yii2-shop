<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $additionalProductsCategories
 */
use xalberteinsteinx\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Additional products');
$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    [
        'label' => Yii::t('shop', 'Products'),
        'url' => Url::to(['/shop/product']),
        'itemprop' => 'url'
    ],
    $this->title
];
?>


<div class="box">

    <!--TITLE-->
    <div class="box-title">
        <h1>
            <i class="glyphicon glyphicon-list"></i>
            <?= \Yii::t('shop', 'Additional products'); ?>
        </h1>
        <!--ADD BUTTON-->
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
            Yii::t('shop', 'Create category'), ['/shop/product/save', 'languageId' => Language::getCurrent()->id], ['class' => 'btn btn-primary btn-xs pull-right']);
        ?>
    </div>

    <!--CONTENT-->
    <div class="box-content">
        <table id="my-grid" class="table table-hover">
            <thead>
            <tr>
                <th class="col-md-8 text-center">
                    <?= \Yii::t('shop', 'Title'); ?>
                </th>
                <th class="col-md-2">
                    <?= \Yii::t('shop', 'Images'); ?>
                </th>
                <th class="col-md-2">
                    <?= \Yii::t('shop', 'Control'); ?>
                </th>
            </tr>
            <tbody>
            <?php foreach ($additionalProductsCategories as $category) : ?>
                <?php foreach ($category->products as $product) : ?>
                    <tr>
                        <td class="text-center project-title">
                            <?= Html::a(
                                $product->translation->title,
                                Url::toRoute(['/shop/product/save', 'id' => $product->id, 'languageId' => Language::getCurrent()->id])
                            ); ?>
                        </td>
                        <td class="project-people">
                            <a href="<?= Url::toRoute(['add-image', 'id' => $product->id, 'languageId' => Language::getCurrent()->id]); ?>">
                                <?php for ($i = 0; $i < 3; $i++) : ?>
                                    <?php if (!empty($product->images[$i])) : ?>
                                        <?= Html::img($product->images[$i]->small, ['class' => 'img-circle']); ?>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </a>
                        </td>
                        <td>
                            <?= ManageButtons::widget(['model' => $product]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>



