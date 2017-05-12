<?php
use rmrevin\yii\fontawesome\FA;
use xalberteinsteinx\shop\backend\assets\ProductAsset;
use xalberteinsteinx\shop\common\entities\Category;
use xalberteinsteinx\shop\common\entities\CategoryTranslation;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\SearchProduct;
use xalberteinsteinx\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;
use dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this View
 * @var $categories CategoryTranslation
 * @var $languages Language[]
 * @var $searchModel SearchProduct
 * @var $dataProvider ActiveDataProvider
 * @var $notModeratedProductsCount Product
 */

$this->title = \Yii::t('shop', 'Product list');
ProductAsset::register($this);

$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    Yii::t('shop', 'Products')
];
?>
<?php Pjax::begin([
    'id' => 'p-products',
    'linkSelector' => '.pjax',
    'enablePushState' => false
]); ?>
<div class="box">

    <!--TITLE-->
    <div class="box-title">
        <h1>
            <?= FA::i(FA::_NAVICON) . ' ' . \Yii::t('shop', 'Product list'); ?>
        </h1>
        <!--ADD BUTTON-->
        <a href="<?= Url::to(['/shop/product/save', 'languageId' => Language::getCurrent()->id]) ?>" class="btn btn-primary btn-xs">
            <span>
                <?= FA::i(FA::_USER_PLUS) . ' ' . \Yii::t('shop', 'Add'); ?>
            </span>
        </a>
    </div>

    <!--CONTENT-->
    <div class="box-content">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterRowOptions' => ['class' => 'm-b-sm m-t-sm'],
            'options' => [
                'class' => 'project-list'
            ],
            'tableOptions' => [
                'id' => 'my-grid',
                'class' => 'table table-hover'
            ],

            'pager' => ['linkOptions' => ['class' => 'pjax']],

            'summary' => "",

            'columns' => [

                /*POSITION*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-1'],
                    'format' => 'html',
                    'label' => Yii::t('shop', 'Position'),
                    'value' => function ($model) {
                        $buttonUp = Html::a(
                            '',
                            Url::toRoute(['up', 'id' => $model->id]),
                            [
                                'class' => 'fa fa-chevron-up pjax'
                            ]
                        );
                        $buttonDown = Html::a(
                            '',
                            Url::toRoute(['down', 'id' => $model->id]),
                            [
                                'class' => 'fa fa-chevron-down pjax'
                            ]
                        );
                        return $buttonUp . '<div>' . $model->position . '</div>' . $buttonDown;
                    },
                    'contentOptions' => ['class' => 'vote-actions'],
                ],

                /*TITLE*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-3'],
                    'attribute' => 'title',
                    'value' => function ($model) {
                        $content = null;
                        if (!empty($model->translation->title)) {
                            /** @var User $owner */
                            $owner = (!empty(User::find()->where(['id' => $model->owner])->one()))
                                ? User::find()->where(['id' => $model->owner])->one()
                                : new User();

                            $content = Html::a(
                                $model->translation->title,
                                Url::toRoute(['save', 'id' => $model->id, 'languageId' => Language::getCurrent()->id])
                            );
                            $content .= '<br><small>' . Yii::t('shop', 'Created') . ' ' . $model->creation_time . '</small><br>';
                            $content .= '<small>' . \Yii::t('shop', 'Created by') . ' ' . $owner->email . '</small><br>';
                            $content .= (!empty($model->number)) ?
                                '<small>' . \Yii::t('shop', 'Number') . ': ' . $model->number . '</small>' : '';
                        }
                        return $content;
                    },
                    'label' => Yii::t('shop', 'Title'),
                    'format' => 'html',
                    'contentOptions' => ['class' => 'project-title'],
                ],

                /*CATEGORY*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => 'category',
                    'value' => 'category.translation.title',
                    'label' => Yii::t('shop', 'Category'),
                    'format' => 'text',
                    'filter' => ArrayHelper::map(Category::find()->joinWith('translations')->orderBy('title')->all(), 'id', 'translation.title'),
                    'contentOptions' => ['class' => 'project-title'],
                ],

                /*IMAGES*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => 'images',
                    'value' => function ($model) {
                        $content = '';
                        $number = 3;
                        $i = 0;
                        foreach ($model->images as $image) {
                            if (!empty($image)) {
                                if ($i < $number) {
                                    $content .= Html::img($image->small, ['class' => 'img-circle']);
                                    $i++;
                                }
                            }
                        }
                        return Html::a($content, Url::toRoute(['add-image', 'id' => $model->id, 'languageId' => Language::getCurrent()->id]));
                    },
                    'label' => Yii::t('shop', 'Images'),
                    'format' => 'html',
                    'contentOptions' => ['class' => 'project-people'],
                ],

                /*STATUS*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-1'],
                    'attribute' => \Yii::t('shop', 'Status'),

                    'value' => function ($model) {
                        switch ($model->status) {
                            case Product::STATUS_ON_MODERATION:
                                return
                                    Html::button(
                                        Yii::$app->user->can('moderateProductCreation') ?
                                            Html::a(\Yii::t('shop', 'On moderation'),
                                                Url::toRoute(['save', 'id' => $model->id, 'languageId' => Language::getCurrent()->id]),
                                                ['class' => '']) :
                                            Html::tag('span', \Yii::t('shop', 'On moderation')),
                                        ['class' => 'col-md-12 btn btn-warning btn-xs']
                                    );
                                break;
                            case Product::STATUS_DECLINED:
                                return Html::tag('p', \Yii::t('shop', 'Declined'), ['class' => 'col-md-12 btn btn-danger btn-xs']);
                                break;
                            case Product::STATUS_SUCCESS:
                                return Html::tag('p', \Yii::t('shop', 'Success'), ['class' => 'col-md-12 btn btn-primary btn-xs']);
                                break;
                            default:
                                return $model->status;
                        }
                    },
                    'label' => Yii::t('shop', 'Status'),
                    'format' => 'raw',
                    'filter' => Html::activeDropDownList($searchModel, 'status',
                        [
                            Product::STATUS_ON_MODERATION => \Yii::t('shop', 'On moderation'),
                            Product::STATUS_DECLINED => \Yii::t('shop', 'Declined'),
                            Product::STATUS_SUCCESS => \Yii::t('shop', 'Success')
                        ], ['class' => 'form-control', 'prompt' => \Yii::t('shop', 'All')]),
                    'contentOptions' => ['class' => 'project-title text-center'],
                ],


                /*SHOWS*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-1'],
                    'attribute' => 'shows',
                    'value' => 'shows',
                    'label' => Yii::t('shop', 'Shows'),
                    'contentOptions' => ['class' => 'text-center'],
                ],

                /*ACTIONS*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => \Yii::t('shop', 'Control'),

                    'value' => function ($model) {
                        return ManageButtons::widget(['model' => $model]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center'],
                ],
            ],
        ]);
        ?>

        <?= \Yii::t('shop', 'Count of waiting moderation products is') . ' <b>' . $notModeratedProductsCount . '</b>'; ?>

        <!--ADD BUTTON-->
        <a href="<?= Url::to(['/shop/product/save', 'languageId' => Language::getCurrent()->id]) ?>"
           class="btn btn-primary btn-xs pull-right">
            <?= FA::i(FA::_USER_PLUS) . ' ' . \Yii::t('shop', 'Add'); ?>
        </a>
    </div>
</div>
<?php Pjax::end(); ?>
