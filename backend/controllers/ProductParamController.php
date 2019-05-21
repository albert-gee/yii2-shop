<?php
namespace albertgeeca\shop\backend\controllers;

use albertgeeca\shop\backend\components\events\ProductEvent;
use albertgeeca\shop\common\entities\Param;
use albertgeeca\shop\common\entities\ParamTranslation;
use albertgeeca\shop\common\entities\Product;
use bl\multilang\entities\Language;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductParamController extends Controller
{

    /**
     * Event is triggered before editing product translation.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_EDIT_PRODUCT = 'afterEditProduct';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'only' => ['save'],
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'add-param', 'update-param',
                            'delete-param',
                            'up', 'down'
                        ],
                        'roles' => ['updateProduct', 'updateOwnProduct'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Adds params for product model.
     *
     * Users which have 'updateOwnProduct' permission can add params only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can add params for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAddParam($id = null, $languageId = null)
    {
        $product = Product::findOne($id);
        $selectedLanguage = Language::findOne($languageId);

        if (empty($product)) throw new NotFoundHttpException();
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
            $param = new Param();
            $param->product_id = $id;
            $param_translation = new ParamTranslation();
            $param_translation->language_id = $languageId;

            if (Yii::$app->request->isPost) {

                $param->load(Yii::$app->request->post());
                $param_translation->load(Yii::$app->request->post());
                if ($param->validate() && $param_translation->validate()) {
                    $param->save();
                    $param_translation->param_id = $param->id;
                    $param_translation->language_id = $languageId;
                    $param_translation->save();

                    $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                        'id' => $param->product_id
                    ]));

                    Yii::$app->getSession()->setFlash('success', 'Data were successfully modified.');
                } else
                    Yii::$app->getSession()->setFlash('danger', 'Failed to change the record.');
            }

            $params = Param::find()->where(['product_id' => $id])->orderBy('position')->all();

            if (\Yii::$app->request->isPjax) {
                $this->renderPartial('../product-param/_params-table', [
                    'modifiedElementId' => $param->id,
                    'params' => $params,
                    'languageId' => $selectedLanguage->id,
                    'param_translation' => new ParamTranslation(),
                    'productId' => $id,
                ]);
            }

            return $this->render('../product/save', [
                'viewName' => '../product-param/add-param',
                'product' => Product::findOne($id),

                'params' => [
                    'selectedLanguage' => $selectedLanguage,
                    'params' => $params,
                    'param_translation' => new ParamTranslation(),
                    'product' => $product,
                    'productId' => $id,
                ]
            ]);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Deletes param from product model.
     *
     * Users which have 'updateOwnProduct' permission can delete params only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can delete params for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDeleteParam($id, $languageId)
    {
        $param = Param::findOne($id);

        if (!empty($param)) {
            $param->delete();

            $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                'id' => $param->product_id
            ]));

            if (\Yii::$app->request->isPjax) {
                return $this->renderPartial('../product-param/_params-table', [
                    'modifiedElementId' => null,
                    'params' => Param::find()->where(['product_id' => $param->product_id])->orderBy('position')->all(),
                    'languageId' => $languageId,
                    'param_translation' => new ParamTranslation(),
                    'productId' => $param->product_id,
                ]);
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Update param translation.
     * Users which have 'updateOwnProduct' permission can update params only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can update params for all Product models.
     *
     * @param int $id
     * @param int $languageId
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdateParam(int $id, int $languageId)
    {
        if (!empty($id) && !empty($languageId)) {
            $paramTranslation = ParamTranslation::find()
                ->where(['param_id' => $id, 'language_id' => $languageId])->one();

            if (empty($paramTranslation)) {
                $paramTranslation = new ParamTranslation();
                $paramTranslation->param_id = $id;
                $paramTranslation->language_id = $languageId;
            }

            if (Yii::$app->request->isPost) {
                $paramTranslation->load(Yii::$app->request->post());
                if ($paramTranslation->validate()) {
                    $paramTranslation->save();

                    $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                        'id' => $paramTranslation->param->product_id
                    ]));

                    Yii::$app->getSession()->setFlash('success', 'Data were successfully modified.');
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Failed to change the record.');
                }

                return $this->redirect(['add-param', 'id' => $paramTranslation->param->product_id, 'languageId' => $languageId,]);
            }

            return $this->renderPartial('update-param', [
                'paramTranslation' => $paramTranslation,
                'languageId' => $languageId
            ]);
        } else throw new NotFoundHttpException();
    }


    /**
     * Changes param position to up
     *
     * Users which have 'updateOwnProduct' permission can change position only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can change position for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUp($id, $languageId)
    {

        $param = Param::findOne($id);
        $product = $param->product;

        if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
            if (!empty($param)) {
                $param->movePrev();
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $product->id
                ]));
            }

            if (\Yii::$app->request->isPjax) {
                return $this->renderPartial('../product-param/_params-table', [
                    'modifiedElementId' => $id,
                    'params' => Param::find()->where(['product_id' => $param->product_id])->orderBy('position')->all(),
                    'languageId' => $languageId,
                    'param_translation' => new ParamTranslation(),
                    'productId' => $param->product_id,
                ]);
            }

            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Changes param position to down
     *
     * Users which have 'updateOwnProduct' permission can change position only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can change position for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDown($id, $languageId)
    {
        $param = Param::findOne($id);
        $product = $param->product;

        if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {

            if ($param) {
                $param->moveNext();
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $product->id
                ]));
            }

            if (\Yii::$app->request->isPjax) {
                return $this->renderPartial('../product-param/_params-table', [
                    'modifiedElementId' => $id,
                    'params' => Param::find()->where(['product_id' => $param->product_id])->orderBy('position')->all(),
                    'languageId' => $languageId,
                    'param_translation' => new ParamTranslation(),
                    'productId' => $param->product_id,
                ]);
            }

            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }
}