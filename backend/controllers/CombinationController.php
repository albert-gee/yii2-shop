<?php
namespace sointula\shop\backend\controllers;

use sointula\shop\backend\components\events\ProductEvent;
use sointula\shop\backend\components\form\{
    CombinationAttributeForm, CombinationImageForm
};
use sointula\shop\common\components\user\models\UserGroup;
use sointula\shop\common\entities\{
    Combination, CombinationAttribute, CombinationImage, CombinationPrice, CombinationTranslation, Price,
    Product, ProductImage
};
use bl\multilang\entities\Language;
use Yii;
use yii\base\{
    Exception, Model
};
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\{
    Controller, NotFoundHttpException
};

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CombinationController extends Controller
{

    /**
     * Event is triggered after editing product translation.
     * Triggered with sointula\shop\backend\events\ProductEvent.
     */
    const EVENT_BEFORE_EDIT_PRODUCT = 'beforeEditProduct';
    /**
     * Event is triggered before editing product translation.
     * Triggered with sointula\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_EDIT_PRODUCT = 'afterEditProduct';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'add-combination', 'edit-combination',
                            'change-default-combination', 'remove-combination',
                            'remove-combination-attribute'
                        ],
                        'roles' => ['createProduct', 'createProductWithoutModeration',
                            'updateProduct', 'updateOwnProduct'],
                        'allow' => true,
                    ]
                ],
            ],
        ];
    }

    /**
     * Adds new combination
     *
     * @param int $productId
     * @param int $languageId
     * @return mixed
     */
    public function actionAddCombination(int $productId, int $languageId)
    {
        $combination = new Combination();
        $combinationTranslation = new CombinationTranslation();
        $combinationsList = Combination::find()
            ->with([
                'images',
                'prices',
                'combinationAttributes.productAttribute',
                'combinationAttributes.productAttribute.translation',
                'combinationAttributes.productAttributeValue',
                'combinationAttributes.productAttributeValue.translation',
                'combinationAvailability',
                'combinationAvailability.translation',
            ])
            ->where(['product_id' => $productId])
            ->all();

        $imageForm = new CombinationImageForm();
        $productImages = ProductImage::find()->where(['product_id' => $productId])->all();

        $combinationAttributeForm = new CombinationAttributeForm();
        $combinationAttribute = new CombinationAttribute();

        $userGroups = UserGroup::find()->all();

        $prices = [];
        foreach ($userGroups as $userGroup) {
            $price = new Price();
            $prices[$userGroup->id] = $price;
        }

        if (\Yii::$app->request->isPost) {

            $post = \Yii::$app->request->post();
            if ($combination->load($post)) {
                $combination->product_id = $productId;

                $combination->setDefaultOrNotDefault();
                if ($combination->validate()) $combination->save();

                if ($combinationTranslation->load($post)) {
                    $combinationTranslation->combination_id = $combination->id;
                    $combinationTranslation->language_id = $languageId;
                    if ($combinationTranslation->validate()) $combinationTranslation->save();
                }

                if (Model::loadMultiple($prices, Yii::$app->request->post()) && Model::validateMultiple($prices)) {
                    foreach ($prices as $key => $price) {
                        $price->save(false);
                        $combinationPrice = new CombinationPrice();
                        $combinationPrice->combination_id = $combination->id;
                        $combinationPrice->price_id = $price->id;
                        $combinationPrice->user_group_id = $key;
                        if ($combinationPrice->validate()) $combinationPrice->save();
                    }
                }

                $this->loadCombinationAttributeForm($combination, $post, $combinationAttributeForm, $combinationAttribute);
                $this->saveCombinationImages($imageForm, $post, null, $combination);

                $eventName = self::EVENT_AFTER_EDIT_PRODUCT;
                $this->trigger($eventName, new ProductEvent([
                    'id' => $combination->product_id
                ]));

                $this->redirect(['add-combination',
                    'productId' => $productId,
                    'languageId' => $languageId
                ]);
            }
        }

        return $this->render('../product/save', array(
            'viewName' => '../combination/add-combination',
            'product' => Product::findOne($productId),

            'params' => array(
                'combination' => $combination,
                'combinationTranslation' => $combinationTranslation,
                'combinations' => $combinationsList,
                'product' => Product::findOne($productId),
                'productImages' => $productImages,
                'image_form' => $imageForm,
                'language' => Language::findOne($languageId),
                'combinationAttribute' => $combinationAttribute,
                'combinationAttributeForm' => $combinationAttributeForm,
                'prices' => $prices,
            )
        ));
    }

    /**
     * @param $imageForm CombinationImageForm
     * @param $post array
     * @param $combinationImages array
     * @param $combination Combination
     * @param $newCombination boolean
     * @param $productImages ProductImage|null
     */
    private function saveCombinationImages($imageForm, $post, $combinationImages,
                                           $combination, $newCombination = true, $productImages = null)
    {
        if ($imageForm->load($post)) {

            if (!empty($imageForm->product_image_id)) {
                if ($imageForm->validate()) {

                    if (!$newCombination) {
                        /*Deleting*/
                        foreach ($combinationImages as $combinationImage) {
                            if (!in_array($combinationImage->id, $imageForm->product_image_id)) {
                                CombinationImage::deleteAll(['id' => $combinationImage->id]);
                            }
                        }
                    }

                    foreach ($imageForm->product_image_id as $image) {
                        if (!$newCombination) {
                            /*Adding*/
                            if (!in_array($image, ArrayHelper::toArray($combinationImages))) {
                                $newImage = new CombinationImage();
                                $newImage->product_image_id = $image;
                                $newImage->combination_id = $combination->id;
                                if ($newImage->validate()) $newImage->save();

                                CombinationImage::deleteAll(['id' => $image]);
                            }
                        } else {
                            $newCombinationImage = new CombinationImage();

                            $newCombinationImage->combination_id = (int)$combination->id;
                            $newCombinationImage->product_image_id = (int)$image;
                            if ($newCombinationImage->validate()) {
                                $newCombinationImage->save();
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * @param $combination
     * @param $post
     * @param $combinationAttributeForm CombinationAttributeForm
     * @param $combinationAttribute CombinationAttribute
     */
    private function loadCombinationAttributeForm($combination, $post, $combinationAttributeForm, $combinationAttribute)
    {
        if ($combinationAttributeForm->load($post)) {
            if ($combinationAttributeForm->validate()) {
                foreach ($combinationAttributeForm->attribute_id as $key => $attributeId) {

                    if (!empty($attributeId)) {
                        $combinationAttribute->combination_id = $combination->id;
                        $combinationAttribute->attribute_id = (int)$attributeId;
                        $combinationAttribute->attribute_value_id =
                            (int)$combinationAttributeForm->attribute_value_id[$key];

                        if ($combinationAttribute->validate()) {
                            $combinationAttribute->save();
                            $combinationAttribute = new CombinationAttribute();
                        }
                    }
                }
            }
        }
    }

    /**
     * Updates combination
     *
     * @param int $combinationId
     * @param int $languageId
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionEditCombination(int $combinationId, int $languageId)
    {
        $combination = Combination::findOne($combinationId);
        if (empty($combination)) throw new NotFoundHttpException();

        $combinationTranslation = CombinationTranslation::find()->where([
            'combination_id' => $combination->id, 'language_id' => $languageId
        ])->one();
        if (empty($combinationTranslation)) {
            $combinationTranslation = new CombinationTranslation();
            $combinationTranslation->combination_id = $combination->id;
            $combinationTranslation->language_id = $languageId;
        }

        $imageForm = new CombinationImageForm();
        $productImages = ProductImage::find()->where(['product_id' => $combination->product_id])->all();
        $combinationImages = CombinationImage::find()->where(['combination_id' => $combination->id])->all();

        $combinationAttributeForm = new CombinationAttributeForm();
        $combinationAttributes = CombinationAttribute::find()
            ->where(['combination_id' => $combination->id])->all();

        $prices = [];
        $userGroups = UserGroup::find()->all();
        foreach ($userGroups as $userGroup) {
            $price = Price::find()->joinWith('combinationPrice')
                ->where(['combination_id' => $combination->id, 'user_group_id' => $userGroup->id])->one();
            if (empty($price)) {
                $price = new Price();
                if ($price->validate()) $price->save();
                $combinationPrice = new CombinationPrice();
                $combinationPrice->combination_id = $combination->id;
                $combinationPrice->user_group_id = $userGroup->id;
                $combinationPrice->price_id = $price->id;
                if ($combinationPrice->validate()) $combinationPrice->save();

            }
            $prices[$price->id] = $price;
        }

        if (\Yii::$app->request->isPost) {

            $this->trigger(self::EVENT_BEFORE_EDIT_PRODUCT, new ProductEvent([
                'id' => $combination->product_id
            ]));

            $post = \Yii::$app->request->post();
            if ($combination->load($post)) {
                if ($combination->validate()) $combination->save();

                $combination->setDefaultOrNotDefault();

                if ($combinationTranslation->load($post) && $combinationTranslation->validate())
                    $combinationTranslation->save();

                /*Saving prices*/
                if (Model::loadMultiple($prices, Yii::$app->request->post()) && Model::validateMultiple($prices)) {
                    foreach ($prices as $price) {
                        $price->save(false);
                    }
                }

                $this->loadCombinationAttributeForm($combination, $post, $combinationAttributeForm, new CombinationAttribute());
                $this->saveCombinationImages($imageForm, $post, $combinationImages, $combination, false, $productImages);

                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $combination->product_id
                ]));

                $this->redirect(['add-combination',
                    'productId' => $combination->product_id,
                    'languageId' => $languageId
                ]);
            }
        }

        return $this->render('../product/save', [
            'viewName' => '../combination/edit-combination',
            'selectedLanguage' => Language::findOne($languageId),
            'product' => Product::findOne($combination->product_id),
            'languages' => Language::find()->all(),

            'params' => [
                'combination' => $combination,
                'combinationTranslation' => $combinationTranslation,
                'product' => Product::findOne($combination->product_id),
                'productImages' => $productImages,

                'image_form' => $imageForm,
                'combinationImagesIds' => ArrayHelper::getColumn($combinationImages, 'product_image_id'),

                'languageId' => $languageId,
                'combinationAttributes' => $combinationAttributes,
                'combinationAttributeForm' => $combinationAttributeForm,
                'prices' => $prices
            ]
        ]);
    }

    /**
     * @param $combinationId
     * @return \yii\web\Response
     * @throws Exception
     */
    public function actionChangeDefaultCombination($combinationId)
    {

        $combination = Combination::findOne($combinationId);

        if (!$combination->default) {
            $combination->findDefaultCombinationAndUndefault();

            $combination->default = !$combination->default;
            if ($combination->validate()) $combination->save();

            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new Exception('Product must have one default combination');
    }

    /**
     * Removes combination
     *
     * @param int $combinationId
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRemoveCombination(int $combinationId)
    {
        $combination = Combination::findOne($combinationId);
        if (empty($combination)) throw new NotFoundHttpException('Such combination does not exists');
        $productId = $combination->product_id;

        if (!empty($combination)) {
            $combination->delete();
            if (Combination::find()->where(['product_id' => $productId])->count() == 1) {
                $combination = Combination::find()->where(['product_id' => $productId])->one();
                $combination->default = 1;
                if ($combination->validate()) $combination->save();
            }

            $eventName = self::EVENT_AFTER_EDIT_PRODUCT;
            $this->trigger($eventName, new ProductEvent([
                'id' => $combination->product_id
            ]));

        } else throw new NotFoundHttpException();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRemoveCombinationAttribute(int $id)
    {
        $combinationAttribute = CombinationAttribute::findOne($id);
        if (empty($combinationAttribute)) throw new NotFoundHttpException();

        $combinationAttribute->delete();

        $eventName = self::EVENT_AFTER_EDIT_PRODUCT;
        $this->trigger($eventName, new ProductEvent([
            'id' => $combinationAttribute->combination->product_id
        ]));

        return $this->redirect(\Yii::$app->request->referrer);
    }
}