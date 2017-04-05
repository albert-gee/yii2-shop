<?php
namespace xalberteinsteinx\shop\frontend\controllers;

use xalberteinsteinx\shop\common\entities\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class OrderController extends Controller
{

    /**
     * Return list of user orders.
     *
     * @return array Order
     * @throws NotFoundHttpException
     */
    public function actionShowOrderList() {
        if (!\Yii::$app->user->isGuest) {
            $userOrders = \Yii::$app->cart->getAllUserOrders();

            return $this->render('order-list', [
                'userOrders' => $userOrders
            ]);
        }
        else throw new NotFoundHttpException();
    }

    /**
     * @param $id integer
     * @return Order
     * @throws NotFoundHttpException
     */
    public function actionView($id) {
        if (!empty($id)) {
            $order = Order::findOne($id);
            if ($order->user_id == \Yii::$app->user->id) {
                return $this->render('view', [
                    'order' => $order
                ]);
            }
        }
        throw new NotFoundHttpException();
    }
}