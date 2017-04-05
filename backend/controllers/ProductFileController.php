<?php
namespace xalberteinsteinx\shop\backend\controllers;

use xalberteinsteinx\shop\backend\components\events\ProductEvent;
use xalberteinsteinx\shop\backend\components\form\ProductFileForm;
use xalberteinsteinx\shop\common\entities\{
    Product, ProductFile, ProductFileTranslation
};
use bl\multilang\entities\Language;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\{
    Controller, ForbiddenHttpException, NotFoundHttpException, UploadedFile
};

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductFileController extends Controller
{


    /**
     * Event is triggered after editing product translation.
     * Triggered with xalberteinsteinx\shop\backend\events\ProductEvent.
     */
    const EVENT_BEFORE_EDIT_PRODUCT = 'beforeEditProduct';
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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'add-file', 'update-file', 'remove-file'
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
     * Users which have 'updateOwnProduct' permission can add file only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can add file for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionAddFile($id, $languageId)
    {
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($id)->owner])) {
            $file = new ProductFile();
            $fileTranslation = new ProductFileTranslation();
            $fileForm = new ProductFileForm();

            $product = Product::findOne($id);
            $selectedLanguage = Language::findOne($languageId);

            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();

                if ($fileForm->load($post) && $fileTranslation->load($post)) {

                    $fileForm->file = UploadedFile::getInstance($fileForm, 'file');

                    $fileName = $fileForm->upload();

                    $file->file = $fileName;
                    $file->product_id = $product->id;

                    if ($file->save()) {
                        $fileTranslation->product_file_id = $file->id;
                        $fileTranslation->language_id = $selectedLanguage->id;

                        if (!$fileTranslation->save())
                            throw new Exception(var_dump($fileTranslation->errors));
                        $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                            'id' => $file->product_id
                        ]));
                    }
                }
            }
            if (Yii::$app->request->isPjax) {
                return $this->renderPartial('../product-file/add-file', [
                    'fileList' => $product->files,
                    'fileModel' => $fileForm,
                    'fileTranslationModel' => $fileTranslation,
                    'product' => $product,
                    'languages' => Language::findAll(['active' => true]),
                    'language' => $selectedLanguage
                ]);
            }
            return $this->render('../product/save', [
                'viewName' => '../product-file/add-file',
                'selectedLanguage' => Language::findOne($languageId),
                'product' => $product,
                'languages' => Language::find()->all(),

                'params' => [
                    'fileList' => $product->files,
                    'fileModel' => $fileForm,
                    'fileTranslationModel' => $fileTranslation,
                    'product' => $product,
                    'languages' => Language::findAll(['active' => true]),
                    'language' => $selectedLanguage
                ]
            ]);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Users which have 'updateOwnProduct' permission can add file only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can add file for all Product models.
     *
     * @param integer $productId
     * @param integer $fileId
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionUpdateFile($productId, $fileId, $languageId)
    {
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($productId)->owner])) {

            $file = ProductFile::findOne(['id' => $fileId]);
            if (!empty($file)) {
                $product = $file->product;

                if (empty($file->getTranslation($languageId))) {
                    $fileTranslation = new ProductFileTranslation();
                    $fileTranslation->language_id = $languageId;
                    $fileTranslation->product_file_id = $fileId;
                } else {
                    $fileTranslation = $file->getTranslation($languageId);
                }
            } else throw new NotFoundHttpException();

            $fileForm = new ProductFileForm();

            $selectedLanguage = Language::findOne($languageId);

            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();

                if ($fileTranslation->load($post)) {

                    if ($file->save()) {
                        $fileTranslation->product_file_id = $file->id;
                        $fileTranslation->language_id = $selectedLanguage->id;

                        if (!$fileTranslation->save())
                            throw new Exception(var_dump($fileTranslation->errors));
                        $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                            'id' => $file->product_id
                        ]));

                        return $this->redirect(['/shop/product-file/add-file', 'id' => $productId, 'languageId' => $languageId]);
                    }
                }
            }
            if (Yii::$app->request->isPjax) {
                return $this->renderPartial('../product-file/update-file', [
                    'fileList' => $product->files,
                    'fileModel' => $fileForm,
                    'fileTranslationModel' => $fileTranslation,
                    'product' => $product,
                    'languages' => Language::findAll(['active' => true]),
                    'language' => $selectedLanguage
                ]);
            }
            return $this->render('../product/save', [
                'viewName' => '../product-file/update-file',
                'selectedLanguage' => Language::findOne($languageId),
                'product' => $product,
                'languages' => Language::find()->all(),

                'params' => [
                    'fileModel' => $fileForm,
                    'fileTranslationModel' => $fileTranslation,
                    'product' => $product,
                    'languages' => Language::findAll(['active' => true]),
                    'language' => $selectedLanguage
                ]
            ]);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Users which have 'updateOwnProduct' permission can delete file only from Product models that have been created by their.
     * Users which have 'updateProduct' permission can delete file from all Product models.
     *
     * @param integer $fileId
     * @param integer $productId
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionRemoveFile($fileId, $productId, $languageId)
    {
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($productId)->owner])) {
            ProductFile::deleteAll(['id' => $fileId]);
            $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                'id' => $productId
            ]));
            return $this->actionAddFile($productId, $languageId);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }
}