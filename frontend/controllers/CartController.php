<?php
namespace sointula\shop\frontend\controllers;

use sointula\shop\frontend\components\forms\CartForm;
use sointula\shop\frontend\Module;
use sointula\shop\Mailer;
use bl\cms\seo\StaticPageBehavior;
use sointula\shop\common\entities\ProductAdditionalProduct;
use sointula\shop\frontend\widgets\models\AdditionalProductForm;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use bl\imagable\helpers\FileHelper;
use yii\helpers\Json;
use yii\log\Logger;
use yii\web\{
    Controller, NotFoundHttpException, Response
};
use sointula\shop\common\entities\Product;
use sointula\shop\common\components\user\models\User;
use sointula\shop\common\components\user\models\{
    Profile, UserAddress
};
use sointula\shop\common\entities\{
    DeliveryMethod, Order, OrderProduct, OrderStatus
};

/**
 * @author Albert Gainutdinov
 */
class CartController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'staticPage' => [
                'class' => StaticPageBehavior::className(),
                'key' => 'cart'
            ]
        ];
    }

    public $defaultAction = 'show';

    /**
     * Adds product to cart
     * @return array|bool|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new CartForm();
        $post = \Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                $additionalProductForm = [];
                $productAdditionalProducts = ProductAdditionalProduct::find()->where(['product_id' => $model->productId])
                    ->indexBy('additional_product_id')->all();
                foreach ($productAdditionalProducts as $key => $productAdditionalProduct) {
                    $additionalProductForm[$key] = new AdditionalProductForm();
                }

                if (!empty($productAdditionalProducts)) {
                    Model::loadMultiple($additionalProductForm, $post);
                    Model::validateMultiple($additionalProductForm);
                }

                \Yii::$app->cart->add($model->productId, $model->count,
                    json_encode($model->attribute_value_id), $additionalProductForm
                );

                if (\Yii::$app->request->isAjax) {
                    $data = [
                        'orderCounterValue' => \Yii::$app->cart->getOrderItemsCount(),
                        'totalCost' => \Yii::$app->formatter->asCurrency(\Yii::$app->cart->getTotalCost())
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($data);
                }

                Yii::$app->getSession()->setFlash('success',
                    Yii::t('cart', 'You have successfully added this product to cart')
                );
            } else {
                Yii::$app->log->logger->log($model->errors, Logger::LEVEL_ERROR, 'application.shop.product');
                Yii::$app->session->setFlash('error',
                    Yii::t('cart', 'An error occurred while adding a product, please try again later')
                );
            }
        }
        if (\Yii::$app->request->isAjax) {
            return false;
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Renders cart view with all order products.
     * @return mixed
     */
    public function actionShow()
    {
        $this->registerStaticSeoData();
        $cart = \Yii::$app->cart;
        $items = $cart->getOrderItems();

        /*EMPTY CART*/
        if (empty($items)) {
            return $this->render('empty-cart');
        }
        /*CART IS NOT EMPTY*/
        else {
            /*FOR GUEST*/
            if (\Yii::$app->user->isGuest) {
                $user = new User();
                $profile = new Profile();
                $address = new UserAddress();
                $order = new Order();

                $products = [];
                foreach ($items as $item) {
                    $product = Product::findOne($item['id']);
                    if (!empty($product)) {
                        $product->count = $item['count'];
                        $product->combinationId = $item['combinationId'];
                        if (!empty($item['additionalProducts'])) {
                            foreach ($item['additionalProducts'] as $additionalProduct) {
                                $product->additionalProducts[] = [
                                    'product' => Product::findOne($additionalProduct['productId']),
                                    'number' => $additionalProduct['number']
                                ];
                            }
                        }
                        $products[] = $product;
                    }
                }
                $view = 'show-for-guest';
            }
            /*FOR USER*/
            else {
                $user = User::findOne(\Yii::$app->user->id) ?? new User();
                $profile = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one() ?? new Profile();
                $address = new UserAddress();
                $order = Order::find()
                        ->where(['user_id' => \Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])
                        ->one() ?? new Order();
                $products = OrderProduct::find()->where(['order_id' => $order->id])->all();

                $view = 'show';
            }
            return $this->render($view, [
                'products' => $products,
                'order' => $order,
                'profile' => $profile,
                'user' => $user,
                'address' => $address,
            ]);
        }
    }

    /**
     * Removes product from cart
     * @param int $productId
     * @param int $combinationId
     * @return \yii\web\Response
     */
    public function actionRemove(int $productId, int $combinationId = null)
    {
        \Yii::$app->cart->removeItem($productId, $combinationId);
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Removes all products from cart
     * @return \yii\web\Response
     */
    public function actionClear()
    {
        \Yii::$app->cart->clearCart();
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMakeOrder()
    {
        if (Yii::$app->request->isPost) {

            if ($orderResult = \Yii::$app->cart->makeOrder()) {
                \Yii::$app->session->setFlash('success', \Yii::t('cart', 'Your order is accepted. Thank you.'));

                $mailer = new Mailer();
                $mailer->sendMakeOrderMessage($orderResult);

                return $this->render('order-success');
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('cart', 'Making order error'));
                return $this->render('order-error');
            }
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetDeliveryMethod($id)
    {
        if (\Yii::$app->request->isAjax) {

            $method = DeliveryMethod::findOne($id);
            $methodTranslation = $method->translation;

            $method = ArrayHelper::toArray($method);
            $method['translation'] = ArrayHelper::toArray($methodTranslation);
            $method['image_name'] = '/images/delivery/' .
                FileHelper::getFullName(
                    \Yii::$app->shop_imagable->get('delivery', 'small', $method['image_name']
                    ));
            return json_encode([
                'method' => $method,
                'field' => '<input type="text" id="useraddress-zipcode" class="form-control" name="UserAddress[zipcode]">'
            ]);
        } else throw new NotFoundHttpException();
    }

    /**
     * @param int $productId
     * @param int|NULL $combinationId
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeItemsNumber(int $productId, int $combinationId = NULL)
    {

        if (Yii::$app->request->isPost) {

            if (Yii::$app->user->isGuest) {
                if (Yii::$app->cart->changeOrderProductCountInSession($productId, $combinationId)) {
                    \Yii::$app->getSession()->setFlash('success', Yii::t('cart', 'You have successfully changed count of products.'));
                } else {
                    \Yii::$app->getSession()->setFlash('error', Yii::t('cart', 'Changing count of products error'));
                }
            } else {
                if (Yii::$app->cart->changeOrderProductCountInDB($productId, $combinationId)) {
                    \Yii::$app->getSession()->setFlash('success', Yii::t('cart', 'You have successfully changed count of products.'));
                } else {
                    \Yii::$app->getSession()->setFlash('error', Yii::t('cart', 'Changing count of products error'));
                }
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        throw new NotFoundHttpException();
    }


    public function actionAddAdditionalProduct() {

        if (\Yii::$app->request->isPost) {
            $formModel = new \sointula\shop\common\entities\AdditionalProductForm();
            if ($formModel->load(\Yii::$app->request->post())) {
                if ($formModel->validate()) {

                    if (\Yii::$app->user->isGuest) {
                        Yii::$app->get('cart')->addAdditionalProductToSession(
                            $formModel->orderProductId, $formModel->combinationId, $formModel->additionalProductId, $formModel->number);
                    } else {
                        Yii::$app->get('cart')->addAdditionalProductToDb(
                            $formModel->orderProductId, $formModel->additionalProductId, $formModel->number);
                    }

                    if (\Yii::$app->request->isAjax) {
                        $data = [
                            'totalCost' => \Yii::$app->formatter->asCurrency(\Yii::$app->cart->getTotalCost())
                        ];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return Json::encode($data);
                    }
                }
            }
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * @param $orderProductId
     * @param $combinationId
     * @param $additionalProductId
     * @return string|Response
     */
    public function actionRemoveAdditionalProduct($orderProductId, $combinationId, $additionalProductId) {

        if (\Yii::$app->user->isGuest) {
            Yii::$app->cart->removeAdditionalProductFromSession($orderProductId, $combinationId, $additionalProductId);
        }
        else {
            Yii::$app->cart->removeAdditionalProductFromDb($orderProductId, $additionalProductId);
        }

        if (\Yii::$app->request->isAjax) {
            $data = [
                'totalCost' => \Yii::$app->formatter->asCurrency(\Yii::$app->cart->getTotalCost())
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($data);
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }
}