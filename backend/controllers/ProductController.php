<?php
namespace albertgeeca\shop\backend\controllers;

use albertgeeca\shop\common\components\user\models\UserGroup;
use bl\seo\entities\SeoData;
use Yii;
use yii\base\{
    Exception, Model
};
use yii\helpers\{
    ArrayHelper, Inflector, Url
};
use yii\filters\AccessControl;
use bl\multilang\entities\Language;
use albertgeeca\shop\backend\components\events\ProductEvent;
use yii\web\{
    Controller, ForbiddenHttpException, NotFoundHttpException, UploadedFile
};
use albertgeeca\shop\backend\components\form\{
    ProductImageForm, ProductVideoForm
};
use albertgeeca\shop\common\entities\{
    Product, ProductImage, ProductImageTranslation, Price,
    ProductPrice, SearchProduct, ProductTranslation, ProductVideo
};
use yii2tech\ar\position\PositionBehavior;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductController extends Controller
{

    /**
     * Event is triggered before creating new product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_BEFORE_CREATE_PRODUCT = 'beforeCreateProduct';
    /**
     * Event is triggered after creating new product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_CREATE_PRODUCT = 'afterCreateProduct';
    /**
     * Event is triggered after editing product translation.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_BEFORE_EDIT_PRODUCT = 'beforeEditProduct';
    /**
     * Event is triggered before editing product translation.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_EDIT_PRODUCT = 'afterEditProduct';
    /**
     * Event is triggered before deleting product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_BEFORE_DELETE_PRODUCT = 'beforeDeleteProduct';
    /**
     * Event is triggered after deleting product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_DELETE_PRODUCT = 'afterDeleteProduct';
    /**
     * Event is triggered after moderator accepting partner's product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_ACCEPT_PRODUCT = 'afterAcceptProduct';

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
                        'actions' => ['index'],
                        'roles' => ['viewProductList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'save',
                            'add-image', 'delete-image', 'edit-image',
                            'add-video', 'delete-video',
                            'image-up', 'image-down',
                            'up', 'down', 'generate-seo-url',
                        ],
                        'roles' => ['createProduct', 'createProductWithoutModeration',
                            'updateProduct', 'updateOwnProduct'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteProduct', 'deleteOwnProduct'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['change-product-status'],
                        'roles' => ['moderateProductCreation'],
                        'allow' => true,
                    ]
                ],
            ],
        ];
    }

    /**
     * Displays products list.
     *
     * DataProvider sends created by user product models for users which have 'viewProductList' permission
     * and for users which have 'viewCompleteProductList' permission it sends all product models.
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchProduct();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $notModeratedProductsCount = Product::find()->where(['status' => Product::STATUS_ON_MODERATION])->count();

        return $this->render('index', [
            'notModeratedProductsCount' => $notModeratedProductsCount,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => Language::findAll(['active' => true])
        ]);

    }

    /**
     * Creates or edits product model, adds basic info.
     *
     * Users which have 'updateOwnProduct' permission can edit only Product models that have been created by their.
     * Users which have 'updateProduct' permission can create and editing all Product models.
     * Users which have 'createProduct' permission can create Product models with status column equal to constant STATUS_ON_MODERATION.
     * Users which have 'createProductWithoutModeration' permission can create Product models with status column equal to constant STATUS_SUCCESS.
     *
     * @param integer|NULL $id
     * @param integer|NULL $languageId
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSave(int $id = null, int $languageId = null)
    {
        $selectedLanguage = (!empty($languageId)) ? Language::findOne($languageId) :  Language::getCurrent();

        //Getting or creating Product and ProductTranslation models
        if (!empty($id)) {
            $product = Product::findOne($id);

            if (!empty($product)) {
                if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
                    $productTranslation = ProductTranslation::find()->where([
                            'product_id' => $id,
                            'language_id' => $languageId
                        ])->one() ?? new ProductTranslation();
                } else throw new ForbiddenHttpException();
            }
            else throw new NotFoundHttpException();

        } else {
            if (\Yii::$app->user->can('createProduct')) {

                $this->trigger(self::EVENT_BEFORE_CREATE_PRODUCT);

                $product = new Product();
                $productTranslation = new ProductTranslation();
            } else throw new ForbiddenHttpException();
        }

        //Creates array of base prices
        $prices = [];
        $userGroups = UserGroup::find()->all();
        foreach ($userGroups as $userGroup) {

            $price = Price::find()->joinWith('productPrice')
                    ->where(['product_id' => $product->id, 'user_group_id' => $userGroup->id])->one() ?? new Price();
            $prices[$userGroup->id] = $price;
        }

        if (Yii::$app->request->isPost) {

            $this->trigger(($product->isNewRecord) ? self::EVENT_BEFORE_CREATE_PRODUCT : self::EVENT_BEFORE_EDIT_PRODUCT);
            $post = \Yii::$app->request->post();

            if ($product->load($post)) {
                $product->category_id = (!empty($product->category_id)) ? $product->category_id : NULL;

                if ($product->isNewRecord) {
                    $product->owner = Yii::$app->user->id;
                    $product->status = (\Yii::$app->user->can('createProductWithoutModeration')) ?
                        Product::STATUS_SUCCESS : Product::STATUS_ON_MODERATION;
                    if ($product->validate()) {
                        $product->save();
                    }
                    else throw new Exception(\Yii::t('shop', 'An error occurred during the creation of the product'));
                }

                else {
                    if (!$product->validate()) throw new Exception(
                        \Yii::t('shop', 'An error occurred during the creation of the product'));
                }

                if ($productTranslation->load($post)) {

                    //Sets SEO-Url
                    if (empty($productTranslation->seoUrl)) {
                        $productTranslation->seoUrl = Inflector::slug($productTranslation->title);
                    }

                    $product->save();
                    $productTranslation->product_id = $product->id;
                    $productTranslation->language_id = $selectedLanguage->id;
                    if ($productTranslation->validate()) {

                        $productTranslation->save();

                        //Loads prices
                        if (Model::loadMultiple($prices, Yii::$app->request->post()) && Model::validateMultiple($prices)) {
                            foreach ($prices as $key => $price) {
                                $price->save(false);
                                $productPrice = ProductPrice::find()
                                    ->where(['product_id' => $product->id, 'user_group_id' => $key])
                                    ->one();
                                if (empty($productPrice)) {
                                    $productPrice = new ProductPrice();
                                    $productPrice->product_id = $product->id;
                                    $productPrice->price_id = $price->id;
                                    $productPrice->user_group_id = $key;
                                    if ($productPrice->validate()) $productPrice->save();
                                }
                            }
                        }

                        $eventName = $productTranslation->isNewRecord ? self::EVENT_AFTER_CREATE_PRODUCT :
                            self::EVENT_AFTER_EDIT_PRODUCT;
                        $this->trigger($eventName, new ProductEvent([
                            'id' => $product->id
                        ]));

                        return $this->redirect(Url::to(['save', 'id' => $product->id, 'languageId' => $selectedLanguage->id]));

                    }

                }
            }
        }

        if (Yii::$app->request->isPjax) {
            return $this->renderPartial('add-basic', [
                'selectedLanguage' => $selectedLanguage,
                'prices' => $prices,
                'product' => $product,
                'productTranslation' => $productTranslation,
            ]);
        } else {
            return $this->render('save', [
                'product' => $product,
                'viewName' => 'add-basic',

                'params' => [
                    'selectedLanguage' => $selectedLanguage,
                    'prices' => $prices,
                    'product' => $product,
                    'productTranslation' => $productTranslation,
                ]
            ]);
        }
    }

    /**
     * Users which have 'updateOwnProduct' permission can add image only for Product models that have been created by their.
     * Users which have 'updateProduct' permission can add image for all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionAddImage($id, $languageId)
    {
        $product = Product::findOne($id);
        $modifiedElement = null;

        if (empty($product)) throw new NotFoundHttpException();
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
            $image_form = new ProductImageForm();
            $image = new ProductImage();
            $imageTranslation = new ProductImageTranslation();

            if (Yii::$app->request->isPost) {

                $image_form->load(Yii::$app->request->post());
                $image_form->image = UploadedFile::getInstance($image_form, 'image');

                if (!empty($image_form->image)) {
                    if ($uploadedImageName = $image_form->upload()) {

                        $image->file_name = $uploadedImageName;
                        $imageTranslation->alt = $image_form->alt2;
                        $image->product_id = $id;
                        if ($image->validate()) {
                            $image->save();
                            $imageTranslation->image_id = $image->id;
                            $imageTranslation->language_id = $languageId;
                            if ($imageTranslation->validate()) {
                                $imageTranslation->save();

                                $modifiedElement = $image->id;
                            }
                        }
                    }
                }
                if (!empty($image_form->link)) {
                    $image_name = $image_form->copy($image_form->link);
                    $image->file_name = $image_name;
                    $imageTranslation->alt = $image_form->alt1;
                    $image->product_id = $id;
                    if ($image->validate()) {
                        $image->save();
                        $imageTranslation->image_id = $image->id;
                        $imageTranslation->language_id = $languageId;
                        if ($imageTranslation->validate()) {
                            $imageTranslation->save();
                        }
                    }
                }
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $id
                ]));
            }

            $params = [
                'modifiedElement' => $modifiedElement,
                'selectedLanguage' => Language::findOne($languageId),
                'product' => $product,
                'image_form' => new ProductImageForm(),
                'images' => ProductImage::find()->where(['product_id' => $id])->orderBy('position')->all(),
            ];

            if (Yii::$app->request->isPjax) {
                return $this->renderPartial('add-image', $params);
            }
            return $this->render('save', [
                'product' => Product::findOne($id),
                'viewName' => 'add-image',
                'params' => $params
            ]);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * @param $id
     * @param $languageId
     * @return string|\yii\web\Response
     */
    public function actionEditImage($id, $languageId)
    {
        if (Yii::$app->request->isPost) {
            $image = ProductImage::findOne($id);
            $imageTranslation = ProductImageTranslation::find()->where([
                'image_id' => $id,
                'language_id' => $languageId
            ])->one();
            if (empty($imageTranslation)) {
                $imageTranslation = new ProductImageTranslation();
            }

            $imageTranslation->load(Yii::$app->request->post());
            $imageTranslation->image_id = $id;
            $imageTranslation->language_id = $languageId;

            if ($imageTranslation->validate()) {
                $imageTranslation->save();

                if (Yii::$app->request->isPjax) {
                    $product = $image->product;
                    return $this->renderPartial('add-image', [
                        'modifiedElement' => $id,
                        'selectedLanguage' => Language::findOne($languageId),
                        'product' => $product,
                        'image_form' => new ProductImageForm(),
                        'images' => ProductImage::find()->where(['product_id' => $product->id])->orderBy('position')->all(),
                    ]);
                }

            } else \Yii::$app->session->setFlash('error', \Yii::t('shop', 'Edit image error'));

        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Users which have 'updateOwnProduct' permission can delete image only from Product models that have been created by their.
     * Users which have 'updateProduct' permission can delete image from all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionDeleteImage($id, $languageId)
    {
        if (!empty($id)) {
            $image = ProductImage::find()->where(['id' => $id])->one();
            if (!empty($image)) {
                $product = Product::findOne($image->product_id);
                if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
                    $image->removeImage($id);

                    $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                        'id' => $image->product_id
                    ]));

                    if (Yii::$app->request->isPjax) {
                        return $this->renderPartial('add-image', [
                            'modifiedElement' => null,
                            'selectedLanguage' => Language::findOne($languageId),
                            'product' => $product,
                            'image_form' => new ProductImageForm(),
                            'images' => ProductImage::find()->where(['product_id' => $image->product_id])->orderBy('position')->all(),
                        ]);
                    }

                    return $this->redirect(['add-image', 'id' => $image->product_id, 'languageId' => $languageId]);

                } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
            }
        } else throw new Exception();
    }

    /**
     * Changes ProductImage position to down
     *
     * Users which have 'updateOwnProduct' permission can change position only for ProductImage models that have been created by their.
     * Users which have 'updateProduct' permission can change position for all ProductImage models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionImageDown($id, $languageId)
    {
        $productImage = ProductImage::findOne($id);
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($productImage->product_id)->owner])) {

            if ($productImage) {
                $productImage->moveNext();
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $productImage->product_id
                ]));
            }
            return $this->actionAddImage($productImage->product_id, $languageId);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Changes ProductImage position to up
     *
     * Users which have 'updateOwnProduct' permission can change position only for ProductImage models that have been created by their.
     * Users which have 'updateProduct' permission can change position for all ProductImage models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionImageUp($id, $languageId)
    {
        $productImage = ProductImage::findOne($id);
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($productImage->product_id)->owner])) {

            if ($productImage) {
                $productImage->movePrev();

                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $productImage->product_id
                ]));
            }
            return $this->actionAddImage($productImage->product_id, $languageId);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Users which have 'updateOwnProduct' permission can add video only from Product models that have been created by their.
     * Users which have 'updateProduct' permission can add video from all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionAddVideo($id, $languageId)
    {
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($id)->owner])) {
            $product = Product::findOne($id);
            $video = new ProductVideo();
            $videoForm = new ProductVideoForm();

            if (Yii::$app->request->isPost) {

                $video->load(Yii::$app->request->post());

                $videoForm->load(Yii::$app->request->post());
                $videoForm->file_name = UploadedFile::getInstance($videoForm, 'file_name');
                if ($fileName = $videoForm->upload()) {
                    $video->file_name = $fileName;
                    $video->resource = 'videofile';
                    $video->product_id = $id;
                    $video->save();
                }

                if ($video->resource == 'youtube') {
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->file_name, $match)) {
                        $id = $match[1];
                        $video->product_id = $product->id;
                        $video->file_name = $id;
                        if ($video->validate()) {
                            $video->save();
                        }
                    } else {
                        \Yii::$app->session->setFlash('error', \Yii::t('shop', 'Sorry, this format is not supported'));
                    }
                } elseif ($video->resource == 'vimeo') {
                    $regexstr = '~
                        # Match Vimeo link and embed code
                        (?:&lt;iframe [^&gt;]*src=")?		# If iframe match up to first quote of src
                        (?:							        # Group vimeo url
                            https?:\/\/				        # Either http or https
                            (?:[\w]+\.)*			        # Optional subdomains
                            vimeo\.com				        # Match vimeo.com
                            (?:[\/\w]*\/videos?)?	        # Optional video sub directory this handles groups links also
                            \/						        # Slash before Id
                            ([0-9]+)				        # $1: VIDEO_ID is numeric
                            [^\s]*					        # Not a space
                        )							        # End group
                        "?							        # Match end quote if part of src
                        (?:[^&gt;]*&gt;&lt;/iframe&gt;)?	# Match the end of the iframe
                        (?:&lt;p&gt;.*&lt;/p&gt;)?		    # Match any title information stuff
                        ~ix';
                    if (preg_match($regexstr, $video->file_name, $match)) {
                        $id = $match[1];
                        $video->product_id = $product->id;
                        $video->file_name = $id;
                        if ($video->validate()) {
                            $video->save();
                        }
                    } else {
                        \Yii::$app->session->setFlash('error', \Yii::t('shop', 'Sorry, this format is not supported'));
                    }
                }
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $id
                ]));
            }

            $params = [
                'product' => $product,
                'selectedLanguage' => Language::findOne($languageId),
                'video_form_upload' => new ProductVideoForm(),
            ];

            if (Yii::$app->request->isPjax) {
                return $this->renderPartial('add-video', $params);
            }

            return $this->render('save', [
                'product' => $product,
                'viewName' => 'add-video',

                'params' => $params]);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Users which have 'updateOwnProduct' permission can delete video only from Product models that have been created by their.
     * Users which have 'updateProduct' permission can delete video from all Product models.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDeleteVideo($id, $languageId)
    {
        if (!empty($id)) {
            $video = ProductVideo::findOne($id);
            $product = Product::findOne($video->product_id);

            if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {

                if ($video->resource == 'videofile') {
                    $dir = Yii::getAlias('@frontend/web/video');
                    unlink($dir . '/' . $video->file_name);
                }
                ProductVideo::deleteAll(['id' => $id]);

                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $video->product_id
                ]));

                $params = [
                    'product' => $product,
                    'selectedLanguage' => Language::findOne($languageId),
                    'video_form_upload' => new ProductVideoForm(),
                ];

                return $this->renderPartial('add-video', $params);

            } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));

        }
        return false;
    }

    /**
     * Changes product status property by ModerationManager
     *
     * Users which have 'moderateProductCreation' permission can change product status.
     *
     * @param integer $id
     * @param integer $status
     * @return mixed
     */
    public function actionChangeProductStatus($id, $status)
    {
        if (!empty($id) && !empty($status)) {
            $product = Product::findOne($id);
            if ($product->status == Product::STATUS_ON_MODERATION) {

                switch ($status) {
                    case Product::STATUS_SUCCESS:
                        $product->status = Product::STATUS_SUCCESS;
                        $product->save();

                        $this->trigger(self::EVENT_AFTER_ACCEPT_PRODUCT, new ProductEvent([
                            'id' => $id
                        ]));

                        break;
                    case Product::STATUS_DECLINED:
                        $product->status = Product::STATUS_DECLINED;
                        $product->save();
                        break;
                }

                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent([
                    'id' => $id
                ]));
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Changes product position to up
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
        $product = Product::findOne($id);
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => $product->owner])) {
            if (!empty($product)) {
                /**
                 * @var $product PositionBehavior
                 */
                $product->movePrev();
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent(['id' => $id]));
            }
            if (\Yii::$app->request->isPjax) return $this->actionIndex();
            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Changes product position to down
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
        $product = Product::findOne($id);
        if (\Yii::$app->user->can('updateProduct', ['productOwner' => Product::findOne($id)->owner])) {

            if ($product) {
                /**
                 * @var $product PositionBehavior
                 */
                $product->moveNext();
                $this->trigger(self::EVENT_AFTER_EDIT_PRODUCT, new ProductEvent(['id' => $id]));
            }
            if (\Yii::$app->request->isPjax) return $this->actionIndex();
            return $this->redirect(\Yii::$app->request->referrer);
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to do this action.'));
    }

    /**
     * Deletes product.
     *
     * Users which have 'deleteProduct' permission can delete all Product models.
     * Users which have 'deleteOwnProduct' permission can delete only Product models that have been created by their.
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $product = Product::findOne($id);
        if (empty($product)) throw new NotFoundHttpException();

        if (\Yii::$app->user->can('deleteProduct', ['productOwner' => $product->owner])) {
            $this->trigger(self::EVENT_BEFORE_DELETE_PRODUCT);

            SeoData::deleteAll(
                ['AND',
                    ['entity_name' => ProductTranslation::className()],
                    ['in', 'entity_id', ArrayHelper::getColumn($product->translations, 'id')]]);

            $product->delete();

            $this->trigger(self::EVENT_AFTER_DELETE_PRODUCT, new ProductEvent(['id' => $id]));

            if (\Yii::$app->request->isPjax) return $this->actionIndex();
            return $this->redirect('index');
        } else throw new ForbiddenHttpException(\Yii::t('shop', 'You have not permission to delete this product.'));
    }

    /**
     * Generates seo Url from title on add-basic page
     *
     * @param string $title
     * @return string
     */
    public function actionGenerateSeoUrl($title)
    {
        $newSeoUrl = Inflector::slug($title);
        $seoUrl = SeoData::find()
            ->where(['entity_name' => ProductTranslation::className(), 'seo_url' => $newSeoUrl])
            ->one();
        if (!empty($seoUrl)) $newSeoUrl = $newSeoUrl . '-' . date("d-m-y-H-i-s");

        return $newSeoUrl;
    }
}