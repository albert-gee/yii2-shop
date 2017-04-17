<?php
namespace xalberteinsteinx\shop\frontend\controllers;

use xalberteinsteinx\shop\common\entities\PaymentMethod;
use bl\imagable\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PaymentController extends Controller
{
    /**
     * Returns payment method model if request is Ajax.
     *
     * @param integer $id
     * @return PaymentMethod
     * @throws NotFoundHttpException
     */
    public function actionGetPaymentMethodInfo($id) {
        if (\Yii::$app->request->isAjax) {
            if (!empty($id)) {
                $method = PaymentMethod::findOne($id);
                $methodTranslation = $method->translation;
                $method = ArrayHelper::toArray($method);
                $method['translation'] = ArrayHelper::toArray($methodTranslation);
                $method['image'] = '/images/payment/' .
                    FileHelper::getFullName(
                        \Yii::$app->shop_imagable->get('payment', 'small', $method['image']
                        ));
                return json_encode([
                    'paymentMethod' => $method,
                ]);
            }
        }
        throw new NotFoundHttpException();
    }
}