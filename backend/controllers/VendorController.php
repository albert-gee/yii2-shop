<?php
namespace xalberteinsteinx\shop\backend\controllers;

use xalberteinsteinx\shop\backend\components\events\VendorEvent;
use xalberteinsteinx\shop\common\entities\VendorTranslation;
use bl\multilang\entities\Language;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use xalberteinsteinx\shop\common\entities\Vendor;
use xalberteinsteinx\shop\backend\components\form\VendorImage;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class VendorController extends Controller
{
    const EVENT_AFTER_CREATE_OR_UPDATE_VENDOR = 'afterCrateOrUpdateVendor';
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
                        'roles' => ['viewVendorList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save'],
                        'roles' => ['saveVendor'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['remove'],
                        'roles' => ['deleteVendor'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    public function actionIndex() {
        $vendors = Vendor::find()->all();
        $vendor_images = new VendorImage();

        return $this->render('index', [
            'vendors' => $vendors,
            'vendor_images' => $vendor_images
        ]);
    }

    public function actionSave(int $id = null, int $languageId = null)
    {
        $languageId = $languageId ?? Language::getCurrent()->id;
        if(!empty($id)) {
            $vendor = Vendor::findOne($id);
            $vendorTranslation = VendorTranslation::find()
                ->where(['vendor_id' => $id, 'language_id' => $languageId])->one();
            if (empty($vendorTranslation)) $vendorTranslation = new VendorTranslation();
        } else {
            $vendor = new Vendor();
            $vendorTranslation = new VendorTranslation();
        }
        $vendorTranslation->language_id = $languageId;

        $vendor_image = new VendorImage();

        if(Yii::$app->getRequest()->isPost) {

            $post = Yii::$app->request->post();
            $vendor->load($post);
            $vendorTranslation->load($post);

            $vendor_image->imageFile = UploadedFile::getInstance($vendor_image, 'imageFile');

            if($vendor->validate() && $vendor_image->validate()) {
                if($vendor_image->notEmpty()) {
                    try {
                        $vendor_image->Upload();
                        $vendor->image_name = $vendor_image->getImageName();
                    } catch (Exception $ex) {
                        $vendor->addError('image_file', Yii::t('shop', 'Failed to save image'));
                    }
                }
                $vendor->save();

                if ($vendorTranslation->isNewRecord) {
                    $vendorTranslation->vendor_id = $vendor->id;
                    $vendorTranslation->language_id = $languageId;
                }
                if ($vendorTranslation->validate()) {
                    $vendorTranslation->save();
                    $this->trigger(self::EVENT_AFTER_CREATE_OR_UPDATE_VENDOR, new VendorEvent(['vendor' => $vendor]));
                }

                Yii::$app->getSession()->setFlash('success', Yii::t('shop', 'All changes have been saved'));
                return $this->redirect(Url::toRoute('/shop/vendor'));
            }
            else {
                Yii::$app->getSession()->setFlash('danger', Yii::t('shop', 'Failed to save changes'));
            }
        }

        return $this->render('save', [
            'vendor' => $vendor,
            'vendorTranslation' => $vendorTranslation,
            'vendor_image' => $vendor_image
        ]);
    }

    public function actionRemove($id = null)
    {
        $vendor = Vendor::find()
            ->where(['id' => $id])
            ->one();
        $vendor_image = new VendorImage();
        $vendor_image->Remove($vendor->image_name);

        Vendor::deleteAll(['id' => $id]);

        Yii::$app->getSession()->setFlash('success', Yii::t('shop', 'All changes have been saved'));
        return $this->redirect(Url::toRoute('/shop/vendor'));
    }
}