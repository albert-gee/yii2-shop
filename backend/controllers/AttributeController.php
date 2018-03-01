<?php

namespace sointula\shop\backend\controllers;

use sointula\shop\backend\components\events\AttributeEvent;
use sointula\shop\backend\components\form\AttributeTextureForm;
use sointula\shop\common\entities\SearchAttributeValue;
use sointula\shop\common\entities\ShopAttributeTranslation;
use sointula\shop\common\entities\ShopAttributeType;
use sointula\shop\common\entities\ShopAttributeValue;
use sointula\shop\common\entities\ShopAttributeValueColorTexture;
use sointula\shop\common\entities\ShopAttributeValueTranslation;
use bl\multilang\entities\Language;
use Yii;
use sointula\shop\common\entities\ShopAttribute;
use sointula\shop\common\entities\SearchAttribute;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AttributeController implements the CRUD actions for ShopAttribute model.
 */
class AttributeController extends Controller
{
    const EVENT_AFTER_CREATE_OR_UPDATE_ATTRIBUTE = 'afterCrateOrUpdateAttribute';

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
                        'actions' => ['index', 'get-attribute-values'],
                        'roles' => ['viewAttributeList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save', 'remove-attribute-value'],
                        'roles' => ['saveAttribute'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['remove'],
                        'roles' => ['deleteAttribute'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add-value', 'save-value'],
                        'roles' => ['addAttributeValue'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all ShopAttribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchAttribute();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Save data action for ShopAttribute.
     * If user has not permissions to do this action, a 403 HTTP exception will be thrown.
     *
     * @param integer $languageId
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if user has not permissions
     */
    public function actionSave($languageId = null, $id = null)
    {
        if (empty($languageId)) {
            $languageId = Language::getCurrent()->id;
        }
        $selectedLanguage = Language::findOne($languageId);
        $attributeType = ArrayHelper::toArray(ShopAttributeType::find()->all(), [
            'sointula\shop\common\entities\ShopAttributeType' =>
                [
                    'id',
                    'title' => function ($attributeType) {
                        return \Yii::t('shop', $attributeType->title);
                    }
                ]
        ]);

        if (empty($id)) {
            $model = new ShopAttribute();
            $modelTranslation = new ShopAttributeTranslation();

            $searchAttributeValueModel = null;
            $dataProviderAttributeValue = null;

        } else {

            $searchAttributeValueModel = new SearchAttributeValue();
            $dataProviderAttributeValue = $searchAttributeValueModel->search(Yii::$app->request->queryParams);

            $model = ShopAttribute::findOne($id);
            $modelTranslation = ShopAttributeTranslation::find()
                ->where([
                    'attr_id' => $id,
                    'language_id' => $languageId
                ])->one();
            if (empty($modelTranslation)) {
                $modelTranslation = new ShopAttributeTranslation();
            }
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $modelTranslation->load(Yii::$app->request->post());

            if ($model->validate()) {
                $model->save();
                $modelTranslation->attr_id = $model->id;
                $modelTranslation->language_id = $languageId;

                if ($modelTranslation->validate()) {
                    $modelTranslation->save();

                    $this->trigger(self::EVENT_AFTER_CREATE_OR_UPDATE_ATTRIBUTE, new AttributeEvent(['attribute' => $model]));
                    Yii::$app->getSession()->setFlash('success', 'Data were successfully modified.');
                    return $this->redirect(['save', 'id' => $model->id, 'languageId' => $languageId]);
                }
            }

        }

        return $this->render('save', [
            'attribute' => $model,
            'attributeTranslation' => $modelTranslation,
            'attributeType' => $attributeType,
            'selectedLanguage' => $selectedLanguage,
            'dataProvider' => $dataProviderAttributeValue,
            'valueModel' => new ShopAttributeValue(),
            'valueModelTranslation' => new ShopAttributeValueTranslation(),
            'attributeTextureModel' => new AttributeTextureForm()
        ]);
    }

    /**
     * Deletes an existing ShopAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Url::to(['/shop/attribute']));
    }

    /**
     * Finds the ShopAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws Exception
     */
    public function actionAddValue($id, $languageId)
    {
        if (!empty($id)) {
            $languageId = empty($languageId) ? Language::getCurrent()->id : $languageId;

            $attributeValue = new ShopAttributeValue();
            $attributeValueTranslation = new ShopAttributeValueTranslation();
            $attributeTextureModel = new AttributeTextureForm();

            $searchAttributeValueModel = new SearchAttributeValue();
            $dataProviderAttributeValue = $searchAttributeValueModel->search(Yii::$app->request->queryParams);

            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                if ($attributeValueTranslation->load($post) || $attributeTextureModel->load($post)) {

                    $attributeValue->attribute_id = $id;
                    $shopAttribute = ShopAttribute::findOne($id);

                    if ($shopAttribute->type_id == ShopAttribute::TYPE_COLOR || $shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                        $colorTexture = new ShopAttributeValueColorTexture();
                        if ($shopAttribute->type_id == ShopAttribute::TYPE_COLOR) {
                            $colorTexture->color = $attributeTextureModel->color;
                        } elseif ($shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                            $attributeTextureModel->imageFile = UploadedFile::getInstance($attributeTextureModel, 'imageFile');
                            $colorTexture->texture = $attributeTextureModel->upload();
                        }
                        $colorTexture->title = $attributeTextureModel->title;
                        if ($colorTexture->validate()) {
                            $colorTexture->save();
                            $attributeValueTranslation->value = (string)$colorTexture->id;
                        } else throw new Exception('Color or texture saving error');
                    }

                    if ($attributeValue->validate()) {
                        $attributeValue->save();

                        $attributeValueTranslation->value_id = $attributeValue->id;
                        $attributeValueTranslation->language_id = $languageId;

                        if ($attributeValueTranslation->validate()) {
                            $attributeValueTranslation->save();
                            $this->trigger(self::EVENT_AFTER_CREATE_OR_UPDATE_ATTRIBUTE, new AttributeEvent(['attribute' => $shopAttribute]));

                            if (\Yii::$app->request->isPjax) {
                                return $this->renderPartial('add-value', [
                                    'dataProvider' => $dataProviderAttributeValue,
                                    'attribute' => ShopAttribute::findOne($id),
                                    'selectedLanguage' => Language::findOne($languageId),
                                    'valueModel' => new ShopAttributeValue(),
                                    'valueModelTranslation' => new ShopAttributeValueTranslation(),
                                    'attributeTextureModel' => $attributeTextureModel
                                ]);
                            } else {
                                return $this->redirect(Url::toRoute(['save', 'id' => $id, 'languageId' => $languageId]));
                            }
                        }
                    }
                }
            }
        }
        throw new NotFoundHttpException();
    }

    public function actionSaveValue($id = null, int $languageId)
    {
        $attributeValue = ShopAttributeValue::findOne($id);
        if (empty($attributeValue)) throw new NotFoundHttpException();

        $attributeValueTranslation = ShopAttributeValueTranslation::find()
            ->where(['value_id' => $id, 'language_id' => $languageId])->one();

        if (empty($attributeValueTranslation)) {
            $attributeValueTranslation = new ShopAttributeValueTranslation();
            $attributeValueTranslation->value_id = $id;
            $attributeValueTranslation->language_id = $languageId;
        }
        $attributeTextureModel = new AttributeTextureForm();


        if (\Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($attributeValueTranslation->load($post)) {
                if ($attributeValueTranslation->validate()) $attributeValueTranslation->save();
            }
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($attributeValueTranslation->load($post) || $attributeTextureModel->load($post)) {

                if ($attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_COLOR || $attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                    $colorTexture = new ShopAttributeValueColorTexture();

                    if ($attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_COLOR) {
                        $colorTexture->color = $attributeTextureModel->color;
                    } elseif ($attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                        $attributeTextureModel->imageFile = UploadedFile::getInstance($attributeTextureModel, 'imageFile');
                        $texture = $attributeTextureModel->upload();
                        if ($texture) {
                            $colorTexture->texture = $texture;
                        } else {
                            $colorTexture->texture = (!empty($attributeValueTranslation->colorTexture)) ?
                                $attributeValueTranslation->colorTexture->texture :
                                $attributeValueTranslation->shopAttributeValue->translation->colorTexture->texture;
                        }
                    }
                    $colorTexture->title = $attributeTextureModel->title;

                    if ($colorTexture->validate()) {
                        $colorTexture->save();
                        $attributeValueTranslation->value = (string)$colorTexture->id;
                    } else throw new Exception('Color or texture saving error');
                }

                $attributeValueTranslation->value_id = $attributeValue->id;
                $attributeValueTranslation->language_id = $languageId;

                if ($attributeValueTranslation->validate()) {
                    $attributeValueTranslation->save();
                    return $this->redirect(Url::toRoute(['save', 'id' => $attributeValue->shopAttribute->id,
                        'languageId' => $languageId]));
                }
            }
        }

        return $this->render('save-value', [
            'attributeValueTranslation' => $attributeValueTranslation,
            'attributeTextureModel' => $attributeTextureModel,
            'languageId' => $languageId
        ]);
    }

    public function actionRemoveAttributeValue($attributeValueId)
    {
        $attributeValue = ShopAttributeValue::findOne($attributeValueId);

        if ($attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE ||
            $attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_COLOR
        ) {

            if ($attributeValue->shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                $path = Yii::getAlias('@frontend/web/images/shop/attribute-texture/') .
                    $attributeValue->translation->colorTexture->texture;
                unlink($path);
            }

            $colorTexture = ShopAttributeValueColorTexture::findOne($attributeValue->translation->value);
            $colorTexture->delete();
        }
        $attributeValue->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Return attribute values by ajax request.
     *
     * @param $attributeId
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetAttributeValues($attributeId)
    {
        if (\Yii::$app->request->isAjax) {
            $attributeValues = ShopAttributeValue::find()
                ->where(['attribute_id' => $attributeId])->all();

            $attributeValuesArray = ArrayHelper::toArray($attributeValues, [
                'sointula\shop\common\entities\ShopAttributeValue' => [
                    'id',
                    'attribute_id',
                    'translation' => 'translation'
                ]
            ]);

            $shopAttribute = ShopAttribute::findOne($attributeId);
            if ($shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE || $shopAttribute->type_id == ShopAttribute::TYPE_COLOR) {
                $newArray = [];
                foreach ($attributeValuesArray as $attributeValuesItem) {

                    $texture = ShopAttributeValueColorTexture::findOne($attributeValuesItem['translation']['value']);
                    if ($shopAttribute->type_id == ShopAttribute::TYPE_TEXTURE) {
                        $texture = $texture->attributeTexture . Html::tag('p', $texture->title);
                    } else if ($shopAttribute->type_id == ShopAttribute::TYPE_COLOR) {
                        $texture = $texture->attributeColor . Html::tag('p', $texture->title);
                    }

                    $attributeValuesItem['translation'] =
                        array_replace($attributeValuesItem['translation'], ['value' => $texture]);
                    $newArray[] = $attributeValuesItem;
                }
                $attributeValuesArray = $newArray;
            }
            return json_encode($attributeValuesArray);
        } else throw new NotFoundHttpException();
    }
}