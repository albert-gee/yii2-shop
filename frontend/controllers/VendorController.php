<?php
namespace xalberteinsteinx\shop\frontend\controllers;

use xalberteinsteinx\shop\common\entities\Product;
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

                $query = Product::find()->where(['vendor_id' => $id]);

                if (\Yii::$app->request->isPost) {
                    $filter = \Yii::$app->request->post('FilterForm');


                    $category_id = $filter['category_id'];
                    $availability_id = $filter['availability_id'];

                    /*Find by vendor*/
                    if (!empty($category_id)) {
                        $query->joinWith('vendor')->where(['category_id' => $category_id]);
                    }
                    /*Find by availability*/
                    if (!empty($availability_id)) {
                        $query->joinWith('productAvailability')->where(['availability' => $availability_id]);
                    }

                }

                $products = $query->all();

                $vendor->registerMetaData();

                return $this->render('show', [
                    'vendor' => $vendor,
                    'products' => $products
                ]);
            }
        }
        throw new NotFoundHttpException();
    }
}