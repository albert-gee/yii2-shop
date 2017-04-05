<?php
namespace xalberteinsteinx\shop\frontend\controllers;

use xalberteinsteinx\shop\common\entities\Vendor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class VendorController
 * @package xalberteinsteinx\shop\frontend\controllers
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class VendorController extends Controller
{
    public function actionIndex() {
        $vendors = Vendor::find()->all();

        return $this->render('index', [
            'vendors' => $vendors
        ]);
    }

    public function actionShow($id) {

        if (!empty($id)) {
            $vendor = Vendor::findOne($id);
            if (!empty($vendor)) {

                $vendor->registerMetaData();

                return $this->render('show', [
                    'vendor' => $vendor
                ]);
            }
        }
        throw new NotFoundHttpException();
    }
}