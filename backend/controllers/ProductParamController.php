<?php
namespace xalberteinsteinx\shop\backend\controllers;

use xalberteinsteinx\shop\backend\components\events\ProductEvent;
use xalberteinsteinx\shop\common\entities\Param;
use xalberteinsteinx\shop\common\entities\ParamTranslation;
use xalberteinsteinx\shop\common\entities\Product;
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
     * Triggered with xalberteinsteinx\shop\backend\events\ProductEvent.
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

            $languages = Language::find()->all();
            $languageIndex = 0;
            foreach ($languages as $key => $language) {
                if ($language->id == $languageId) {
                    $languageIndex = $key;
                    break;
                }
            }

            return $this->render('../product/save', [
                'viewName' => '../product-param/add-param',
                'product' => Product::findOne($id),

                'params' => [
                    'selectedLanguage' => Language::findOne($languageId),
                    'params' => Param::find()->where(['product_id' => $id])->orderBy('position')->all(),
                    'param_translation' => new ParamTranslation(),
                    'product' => $product,
                    'languageIndex' => $languageIndex
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
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDeleteParam($id)
    {
        $param = Param::findOne($id);
        $param->delete();
        $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
            'id' => $param->product_id
        ]));
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
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUp($id)
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
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDown($id)
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
            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }
}