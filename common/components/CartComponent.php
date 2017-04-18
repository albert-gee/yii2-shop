<?php
namespace xalberteinsteinx\shop\common\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\base\{
    Component, Exception
};
use yii\db\{
    ActiveRecord, Expression
};
use xalberteinsteinx\shop\frontend\components\events\OrderInfoEvent;
use xalberteinsteinx\shop\common\entities\{
    Order, OrderProductAdditionalProduct, OrderStatus, OrderProduct
};
use xalberteinsteinx\shop\common\components\user\models\User;
use xalberteinsteinx\shop\common\components\user\models\{
    Profile, UserAddress
};
use xalberteinsteinx\shop\common\entities\{
    Product, Combination
};

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CartComponent extends Component
{
    /*Session key of order*/
    const SESSION_KEY = 'shop_order';
    const SESSION_KEY_SELECTED_COMBINATIONS = 'shop.selected_combinations';

    const EVENT_BEFORE_GET_ORDER = 'before-get-order';
    const EVENT_BEFORE_GET_ORDER_FROM_DB = 'before-get-order-from-db';

    /**
     * @var bool
     * Enabling sending e-mails
     */
    public $emailNotifications = true;

    /**
     * @var array
     * List of managers e-mails to which notification will be sent.
     */
    public $sendTo = [];

    /**
     * @var bool
     * Enabling saving order products to database. If false, order products will only be stored in the session.
     */
    public $saveToDataBase = true;

    /**
     * @var bool Enable saving the last selected products combinations in session
     * after the product is added to the cart.
     */
    public $saveSelectedCombination = true;

    /**
     * @var string
     * From this address e-mails will be sent.
     */
    public $sender;

    /**
     * @var integer
     * The minimal order unique id.
     */
    public $minOrderUid = 10000000;
    /**
     * @var integer
     * The maximal order unique id.
     */
    public $maxOrderUid = 99999999;
    /**
     * @var integer
     * The order unique id prefix.
     */
    public $uidPrefix = '';

    public $enablePayment = false;

    /**
     * Adds product to cart.
     *
     * @param $productId
     * @param $count
     * @param null $attributesAndValues
     * @param array $additionalProducts
     */
    public function add($productId, $count, $attributesAndValues = null, $additionalProducts = [])
    {
        if (!empty($attributesAndValues)) {
            $attributesAndValues = Json::decode($attributesAndValues);
        }

        if ($this->saveSelectedCombination) {
            $this->saveSelectedCombinationToSession($this->getCombination($attributesAndValues, $productId));
        }

        if ($this->saveToDataBase && !\Yii::$app->user->isGuest) {
            $this->saveProductToDataBase($productId, $count, $attributesAndValues, $additionalProducts);
        } else {
            $this->saveProductToSession($productId, $count, $attributesAndValues, $additionalProducts);
        }
    }

    /**
     * Saves product to database if the corresponding property is true.
     *
     * @param integer $productId
     * @param integer $count
     * @param array|null $attributesAndValues
     * @param array|null $additionalProducts
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    private function saveProductToDataBase($productId, $count, $attributesAndValues = null, $additionalProducts = null)
    {

        $order = $this->getIncompleteOrderFromDB();

        if (\Yii::$app->getModule('shop')->enableCombinations) {
            if (!empty($attributesAndValues)) {
                $combination = $this->getCombination($attributesAndValues, $productId);
                if (!empty($combination)) {
                    $orderProduct = $this->getOrderProduct($order->id, $productId, $combination->id);
                } else throw new Exception(\Yii::t('cart', 'Such attributes combination does not exist'));
            }
            else {
                $orderProduct = $this->getOrderProduct($order->id, $productId, null);
            }
        } else {
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $productId;
            $orderProduct->order_id = $order->id;
        }

        $orderProduct->count += $count;
        if ($orderProduct->validate()) {
            $orderProduct->save();

            if (!empty($additionalProducts)) {
                foreach ($additionalProducts as $additionalProduct) {
                    $orderProductAdditionalProduct = OrderProductAdditionalProduct::find()
                        ->where(['order_product_id' => $orderProduct->id, 'additional_product_id' => $additionalProduct['productId']])
                        ->one();
                    if (empty($orderProductAdditionalProduct)) {
                        $orderProductAdditionalProduct = new OrderProductAdditionalProduct();
                        $orderProductAdditionalProduct->order_product_id = $orderProduct->id;
                        $orderProductAdditionalProduct->additional_product_id = $additionalProduct['productId'];
                        $orderProductAdditionalProduct->number = $additionalProduct['number'];
                        if ($orderProductAdditionalProduct->validate()) $orderProductAdditionalProduct->save();
                    }
                    else {
                        $orderProductAdditionalProduct->number += $additionalProduct['number'];
                        if ($orderProductAdditionalProduct->validate()) $orderProductAdditionalProduct->save();

                    }
                }
            }
        } else die(var_dump($orderProduct->errors));
    }

    /**
     * @param int $orderId
     * @param int $productId
     * @param int $combinationId
     * @return OrderProduct
     */
    private function getOrderProduct(int $orderId, int $productId, $combinationId)
    {
        if (empty($combinationId)) {
            $orderProduct = OrderProduct::findOne([
                'product_id' => $productId,
                'order_id' => $orderId
            ]);
        }
        else {
            $orderProduct = OrderProduct::findOne([
                'product_id' => $productId,
                'combination_id' => $combinationId,
                'order_id' => $orderId
            ]);
        }
        if (empty($orderProduct)) {
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $productId;
            $orderProduct->combination_id = $combinationId;
            $orderProduct->order_id = $orderId;
        }
        return $orderProduct;
    }

    /**
     * Gets or creates incomplete order record from database.
     * @return array|Order|null|ActiveRecord
     */
    private function getIncompleteOrderFromDB()
    {
        $order = Order::find()->where([
            'user_id' => \Yii::$app->user->id,
            'status' => OrderStatus::STATUS_INCOMPLETE])
            ->one();

        if (empty($order)) {
            $order = new Order();
            $order->uid = $this->generateUniqueId($this->uidPrefix, $this->minOrderUid, $this->maxOrderUid);
            $order->user_id = \Yii::$app->user->id;
            $order->status = OrderStatus::STATUS_INCOMPLETE;
            if ($order->validate()) {
                $order->save();
            }
        }
        return $order;
    }

    /**
     * Saves product to session if user is guest or if the $saveToDataBase property is false.
     *
     * @param integer $productId
     * @param integer $count
     * @param array|null $attributesAndValues
     * @param array $additionalProducts
     * @return boolean
     */
    private function saveProductToSession(int $productId, int $count, $attributesAndValues = null, $additionalProducts = [])
    {
        if (!empty($productId) && (!empty($count))) {

            $additionalProductsArray = [];
            if (!empty($additionalProducts)) {
                foreach ($additionalProducts as $additionalProduct) {
                    $additionalProductsArray[] = [
                        'productId' => $additionalProduct->productId,
                        'number' => $additionalProduct->number
                    ];
                }
            }

            $session = Yii::$app->session;
            $productsFromSession = $session[self::SESSION_KEY];

            $combinationId = null;
            if (\Yii::$app->getModule('shop')->enableCombinations) {
                if (!empty($attributesAndValues)) {
                    $combination = $this->getCombination($attributesAndValues, $productId);
                    if (!empty($combination)) $combinationId = $combination->id;
                }
            }

            if (!empty($productsFromSession)) {
                foreach ($productsFromSession as $key => $product) {

                    if ($product['id'] == $productId) {

                        if (\Yii::$app->getModule('shop')->enableCombinations && !empty($combinationId)) {

                            if ($product['combinationId'] == $combinationId) {
                                $productsFromSession[$key]['count'] += $count;

                                $productsFromSession[$key]['additionalProducts'] = (empty($productsFromSession[$key]['additionalProducts'])) ?
                                    $additionalProductsArray : array_merge($productsFromSession[$key]['additionalProducts'], $additionalProductsArray);

                                break;
                            }
                        } else {
                            if (empty($product['combinationId'])) {
                                $productsFromSession[$key]['count'] += $count;
                                $productsFromSession[$key]['additionalProducts'] = (empty($productsFromSession[$key]['additionalProducts'])) ?
                                    $additionalProductsArray : array_merge($productsFromSession[$key]['additionalProducts'], $additionalProductsArray);

                                break;
                            }
                        }
                    }
                    if (count($productsFromSession) - 1 == $key) {
                        $productsFromSession[] =
                            [
                                'id' => $productId,
                                'count' => $count,
                                'combinationId' => $combinationId,
                                'additionalProducts' => $additionalProductsArray
                            ];
                    }
                }
                $session[self::SESSION_KEY] = $productsFromSession;
            } else {
                $_SESSION[self::SESSION_KEY][] =
                    [
                        'id' => $productId,
                        'count' => $count,
                        'combinationId' => $combinationId,
                        'additionalProducts' => $additionalProductsArray
                    ];
            }
            return true;
        }
        return false;
    }

    /**
     * Saves last selected product combinations to session.
     *
     * @param Combination $combination
     * @return bool
     */
    public function saveSelectedCombinationToSession($combination)
    {
        if (!empty($combination)) {
            $items = Yii::$app->session[self::SESSION_KEY_SELECTED_COMBINATIONS];
            $itemIsExist = false;

            if(!empty($items)) {
                foreach ($items as $key => $item) {
                    if ($item['productId'] == $combination->product_id) {
                        $items[$key]['combinationId'] = $combination->id;
                        $itemIsExist = true;
                    }
                }
            }
            if (!$itemIsExist) {
                $items[] = [
                    'productId' => $combination->product_id,
                    'combinationId' => $combination->id,
                ];
            }

            Yii::$app->session[self::SESSION_KEY_SELECTED_COMBINATIONS] = $items;

            return true;
        }

        return false;
    }

    /**
     * Gets last selected product combinations from session.
     *
     * @param $productId [[Product]] id
     * @return null|Combination
     */
    public function getSelectedCombinationFromSession($productId)
    {
        $combination = null;

        if (!empty($productId)) {
            $items = Yii::$app->session[self::SESSION_KEY_SELECTED_COMBINATIONS];

            if (!empty($items)) {
                $combinationId = null;

                foreach ($items as $item) {
                    if ($item['productId'] == $productId) {
                        $combinationId = $item['combinationId'];
                    }
                }

                $combination = Combination::findOne($combinationId);
            }
        }

        return $combination;
    }

    /**
     * @param $attributes
     * @param $productId
     * @return array|bool|null|ActiveRecord|Combination
     */
    public function getCombination($attributes, $productId)
    {
        if (!empty($attributes)) {
            $query = (new \yii\db\Query())
                ->select(['c.id'])
                ->from(['shop_combination c']);

            for ($i = 0; $i < count($attributes); $i++) {
                $query->leftJoin('shop_combination_attribute sca' . $i, 'c.id = sca' . $i . '.combination_id');
            }

            $query->where(['c.product_id' => $productId]);

            for ($i = 0; $i < count($attributes); $i++) {
                $attribute = Json::decode(current($attributes));
                $query->andWhere([
                    'sca' . $i . '.attribute_id' => $attribute['attributeId'],
                    'sca' . $i . '.attribute_value_id' => $attribute['valueId']
                ]);
                next($attributes);
            }
            $result = $query->one();
            if (!empty($result)) {
                $combinationId = $result['id'];
                $combination = Combination::findOne($combinationId);

                if ($combination != null) {
                    return $combination;
                }
            }
        }

        return false;
    }

    /**
     * Gets order items.
     *
     * @return array|bool|mixed|\yii\db\ActiveRecord[]
     */
    public function getOrderItems()
    {
        if (\Yii::$app->user->isGuest) {
            $session = \Yii::$app->session;
            $products = $session[self::SESSION_KEY];
        } else {
            $order = Order::find()->where(['user_id' => \Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])->one();
            if (!empty($order))
                $products = OrderProduct::find()->asArray()->where(['order_id' => $order->id])->all();
        }
        return $products ?? false;
    }

    /**
     * Gets order items count.
     *
     * @return integer
     */
    public function getOrderItemsCount()
    {
        if (\Yii::$app->user->isGuest) {
            $session = \Yii::$app->session;
            return count($session[self::SESSION_KEY]);
        } else {
            $order = Order::find()->where(['user_id' => \Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])->one();
            if (!empty($order)) {
                $count = OrderProduct::find()->asArray()->where(['order_id' => $order->id])->count();

            } else $count = 0;

        }
        return $count;
    }

    /**
     * Gets all user orders from database.
     *
     * @return bool|\yii\db\ActiveRecord[]
     */
    public function getAllUserOrders()
    {
        if (!\Yii::$app->user->isGuest && $this->saveToDataBase === true) {
            $orders = Order::find()
                ->where(['user_id' => \Yii::$app->user->id])
                ->andWhere(['!=', 'status', OrderStatus::STATUS_INCOMPLETE])->all();
            return $orders;
        } else return false;
    }

    /**
     * Removes item from order.
     * @param $productId integer
     * @param $combinationId integer
     */
    public function removeItem(int $productId, int $combinationId = null)
    {
        if (!\Yii::$app->user->isGuest) {
            $order = Order::find()->where([
                'user_id' => \Yii::$app->user->id,
                'status' => OrderStatus::STATUS_INCOMPLETE
            ])->one();
            if (!empty($order)) {
                $orderProduct = OrderProduct::find()->where([
                    'product_id' => $productId,
                    'combination_id' => $combinationId,
                    'order_id' => $order->id
                ])->one();
                if (!empty($orderProduct)) {
                    $orderProduct->delete();
                }
            }
        } else {
            $session = Yii::$app->session;
            if ($session->has(self::SESSION_KEY)) {
                $products = $session[self::SESSION_KEY];
                foreach ($products as $key => $product) {
                    if ($product['id'] == $productId && $product['combinationId'] == $combinationId)
                        unset($_SESSION[self::SESSION_KEY][$key]);
                }
            }
        }
    }

    /**
     * Gets registered user's incomplete order
     * @return array|bool|null|ActiveRecord
     */
    public function getIncompleteOrder()
    {
        if (!\Yii::$app->user->isGuest) {
            $user = User::findOne(\Yii::$app->user->id);
            $order = Order::find()
                ->where(['user_id' => $user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])
                ->one();
            if (!empty($order)) return $order;
        }
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function makeOrder()
    {
        $this->trigger(self::EVENT_BEFORE_GET_ORDER);

        if ($this->saveToDataBase === true && !Yii::$app->user->isGuest) {
            return $this->makeOrderFromDB();
        } else {
            return $this->makeOrderFromSession();
        }
    }

    /**
     * @return array|bool
     */
    private function makeOrderFromDB()
    {
        $this->trigger(self::EVENT_BEFORE_GET_ORDER_FROM_DB,
            new OrderInfoEvent([
                'user_id' => \Yii::$app->user->id,
                'email' => \Yii::$app->user->identity->email])
        );

        $order = $this->getIncompleteOrder();
        if (!empty($order)) {
            $profile = $order->userProfile;

            if ($profile->load(Yii::$app->request->post())) {
                if ($profile->validate()) {
                    $profile->save();
                }
            }
            if ($order->load(Yii::$app->request->post())) {
                if (empty($order->address_id)) {
                    $address = new UserAddress();
                    if ($address->load(Yii::$app->request->post())) {
                        $address->user_profile_id = $profile->id;
                        if ($address->validate()) {
                            if (!empty($address->city) && !empty($address->street) && !empty($address->house)) {
                                $address->save();
                                $order->address_id = $address->id;
                            }
                        }
                    }
                } else $address = null;
                $order->user_id = \Yii::$app->user->id;
                $order->status = OrderStatus::STATUS_CONFIRMED;
                $order->confirmation_time = new Expression('NOW()');
                $order->total_cost = $this->getTotalCost();

                if ($order->validate()) {
                    $order->save();
                    return [
                        'user' => \Yii::$app->user,
                        'profile' => $profile,
                        'order' => $order,
                        'address' => $address
                    ];
                }
            }
        }
        return false;
    }


    private function makeOrderFromSession()
    {
        $userModel = new User();
        $order = new Order();
        $address = new UserAddress();

        if ($userModel->load(Yii::$app->request->post())) {

            $user = $userModel->finder->findUserByEmail($userModel->email);
            if (empty($user)) {
                $user = $userModel;
                $user->username = $user->email;
                if (!\Yii::$app->getModule('user')->enableGeneratingPassword) {
                    $user->password = uniqid();
                }
                if ($user->validate()) {
                    $user->save();
                }

                $profile = new Profile();
                $profile->user_id = $user->id;
            }
            else {
                $profile = $user->profile;
            }

            $profile->load(Yii::$app->request->post());
            if ($profile->validate()) $profile->save();

            $address->load(Yii::$app->request->post());
            $address->user_profile_id = $profile->id;
            if ($address->validate()) $address->save();

            $order->load(Yii::$app->request->post());
            $order->user_id = $user->id;
            $order->status = OrderStatus::STATUS_CONFIRMED;
            $order->confirmation_time = new Expression('NOW()');
            $order->total_cost = $this->getTotalCost();
            $order->address_id = $address->id;
            $order->uid = $this->generateUniqueId($this->uidPrefix, $this->minOrderUid, $this->maxOrderUid);
            if ($order->validate()) $order->save();

            //saving products
            $session = \Yii::$app->session;
            $sessionProducts = $session[self::SESSION_KEY];

            if (empty($sessionProducts)) return false;

            foreach ($sessionProducts as $sessionProduct) {
                $orderProduct = new OrderProduct();
                $orderProduct->product_id = $sessionProduct['id'];
                $orderProduct->order_id = $order->id;
                $orderProduct->count = $sessionProduct['count'];
                $orderProduct->combination_id = $sessionProduct['combinationId'];
                if ($orderProduct->validate()) $orderProduct->save();

                //saving additional products
                if (!empty($sessionProduct['additionalProducts'])) {
                    foreach ($sessionProduct['additionalProducts'] as $additionalProduct) {
                        $newAdditionalProduct = new OrderProductAdditionalProduct();
                        $newAdditionalProduct->additional_product_id = $additionalProduct;
                        $newAdditionalProduct->order_product_id = $orderProduct->id;
                        if ($newAdditionalProduct->validate()) $newAdditionalProduct->save();
                    }
                }
            }

            $this->clearCart();
            return [
                'user' => $user,
                'profile' => $profile,
                'order' => $order,
                'address' => $address
            ];
        } else return false;
    }

    /**
     * Clears cart.
     */
    public function clearCart()
    {
        if (!\Yii::$app->user->isGuest && $this->saveToDataBase === true) {
            $order = Order::find()->where(['user_id' => \Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])->one();
            if (!empty($order)) $order->deleteAll();
        } else {
            $session = \Yii::$app->session;
            $session->remove(self::SESSION_KEY);
        }
    }

    /**
     * Moves order products from session to database if $saveToDataBase property is true.
     * @throws Exception
     */
    public function transportSessionDataToDB()
    {
        if ($this->saveToDataBase === true) {
            $session = Yii::$app->session;

            if ($session->has(self::SESSION_KEY)) {

                $order = $this->getIncompleteOrderFromDB();
                $products = $session[self::SESSION_KEY];

                foreach ($products as $product) {
                    $orderProduct = $this->getOrderProduct($order->id, $product['id'], $product['combinationId']);
                    $orderProduct->count += $product['count'];
                    if ($orderProduct->validate()) {
                        $orderProduct->save();

                        if (!empty($product['additionalProducts'])) {
                            foreach ($product['additionalProducts'] as $productAdditionalProduct) {
                                $additionalProduct = OrderProductAdditionalProduct::find()
                                    ->where([
                                        'order_product_id' => $orderProduct->id,
                                        'additional_product_id' => $productAdditionalProduct['productId']
                                    ])->one();
                                if (empty($additionalProduct)) {
                                    $additionalProduct = new OrderProductAdditionalProduct();
                                    $additionalProduct->order_product_id = $orderProduct->id;
                                    $additionalProduct->additional_product_id = $productAdditionalProduct['productId'];
                                    $additionalProduct->number = $productAdditionalProduct['number'];
                                    if ($additionalProduct->validate()) $additionalProduct->save();
                                }
                                else {
                                    $additionalProduct->number += $productAdditionalProduct['number'];
                                    if ($additionalProduct->validate()) $additionalProduct->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Gets total cost of user's incomplete order from session if user is guest
     * or from DB if user is authenticated
     * @return mixed
     */
    public function getTotalCost(): int
    {
        $totalCost = 0;
        if (Yii::$app->user->isGuest) {
            //Gets products from session
            $session = Yii::$app->session;
            $products = $session[self::SESSION_KEY];

            if (!empty($products)) {
                foreach ($products as $product) {

                    if (!empty($product['combinationId'])) {
                        $combination = Combination::findOne($product['combinationId']);
                        if (!empty($combination)) $totalCost += $combination->price->discountPrice * $product['count'];
                    } else {
                        $productFromDb = Product::findOne($product['id']);
                        if (!empty($productFromDb)) $totalCost += $productFromDb->discountPrice * $product['count'];
                    }

                    if (!empty($product['additionalProducts'])) {
                        foreach ($product['additionalProducts'] as $additionalProduct) {
                            if ((int)$additionalProduct['productId']) {
                                $product = Product::findOne($additionalProduct['productId']);
                                if (!empty($product)) {
                                    $totalCost += $product->discountPrice * $additionalProduct['number'];
                                }
                            }
                        }
                    }
                }
            }
        } else {
            //Gets products from incomplete order in DB
            $order = Order::find()
                ->where(['user_id' => Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])
                ->one();
            if (!empty($order)) {
                $orderProducts = OrderProduct::find()->where(['order_id' => $order->id])->all();

                if (!empty($orderProducts)) {
                    foreach ($orderProducts as $product) {
                        if (\Yii::$app->getModule('shop')->enableCombinations && !empty($product->combination)) {
                            $totalCost += $product->count * $product->combination->price->discountPrice;
                        } else {
                            $totalCost += $product->count * $product->price;
                        }
                        if (!empty($product->orderProductAdditionalProducts)) {
                            foreach ($product->orderProductAdditionalProducts as $orderProductAdditionalProduct) {
                                $totalCost += $orderProductAdditionalProduct->additionalProduct->discountPrice *
                                    $orderProductAdditionalProduct->number;
                            }
                        }
                    }
                }
            }
        }
        return $totalCost;
    }

    /**
     * @param $prefix
     * @param $min
     * @param $max
     * @return string
     */
    public function generateUniqueId($prefix, $min, $max)
    {
        $id = random_int($min, $max);
        $order = Order::find()->where(['uid' => $id])->one();
        if (empty($order)) {
            return $prefix . $id;
        } else $this->generateUniqueId($prefix, $min, $max);
    }

    /**
     * @param int $productId
     * @param int|NULL $combinationId
     * @return bool
     */
    public function changeOrderProductCountInDB(int $productId, int $combinationId = NULL)
    {
        if (!empty($productId)) {
            if (!Yii::$app->user->isGuest) {
                $order = Order::find()
                    ->where(['user_id' => Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])->one();

                if (!empty($order)) {
                    $orderProduct = (!empty($combinationId)) ?
                        OrderProduct::find()
                            ->where(['product_id' => $productId, 'order_id' => $order->id, 'combination_id' => $combinationId])
                            ->one() :
                        OrderProduct::find()
                            ->where(['product_id' => $productId, 'order_id' => $order->id])->one();

                    if (!empty($orderProduct)) {
                        $orderProduct->count = Yii::$app->request->post('count');
                        $orderProduct->save();
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param int $productId
     * @param int|NULL $combinationId
     * @return bool
     */
    public function changeOrderProductCountInSession(int $productId, int $combinationId = NULL)
    {
        if (!empty($productId)) {
            $session = Yii::$app->session;
            if ($session->has(self::SESSION_KEY)) {
                $products = $session[self::SESSION_KEY];
                foreach ($products as $key => $product) {
                    if (!empty($combinationId)) {
                        if ($product['id'] == $productId && $product['combinationId'] == $combinationId) {
                            $_SESSION[self::SESSION_KEY][$key]['count'] = (int)Yii::$app->request->post('count');
                            return true;
                        }
                    } else {
                        if ($product['id'] == $productId) {
                            $_SESSION[self::SESSION_KEY][$key]['count'] = Yii::$app->request->post('count');
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param $orderProductId Product id
     * @param $combinationId
     * @param $additionalProductId
     * @return bool
     */
    public function removeAdditionalProductFromSession($orderProductId, $combinationId, $additionalProductId)
    {
        $session = Yii::$app->session;
        $productId = $orderProductId;

        if (!empty($productId) && !empty($additionalProductId) && $session->has(self::SESSION_KEY)) {

            $products = $session[self::SESSION_KEY];
            foreach ($products as $key => $product) {
                if ($product['id'] == $productId) {

                    if (!empty($combinationId) && ($product['combinationId'] != $combinationId))
                        continue;

                    foreach ($product['additionalProducts'] as $k => $additionalProduct) {
                        if ($additionalProduct['productId'] == $additionalProductId) {
                            unset($_SESSION[self::SESSION_KEY][$key]['additionalProducts'][$k]);
                            return true;
                        }
                    }
                }

            }
        }
        return false;
    }

    /**
     * @param $orderProductId
     * @param $additionalProductId
     * @return bool|int
     */
    public function removeAdditionalProductFromDb($orderProductId, $additionalProductId)
    {
        if (!empty($orderProductId) && !empty($additionalProductId)) {
            $orderProduct = OrderProduct::findOne($orderProductId);

            if (!empty($orderProduct)) {

                return OrderProductAdditionalProduct::deleteAll([
                    'order_product_id' => $orderProduct->id, 'additional_product_id' => $additionalProductId
                ]);
            }
        }
        return false;
    }

    public function addAdditionalProductToSession($productId, $combinationId, $additionalProductId, $number)
    {
        $session = Yii::$app->session;

        if (!empty($productId) && !empty($additionalProductId) && $session->has(self::SESSION_KEY)) {
            $products = $session[self::SESSION_KEY];
            foreach ($products as $key => $product) {
                if ($product['id'] == $productId) {

                    if (!empty($combinationId) && ($product['combinationId'] != $combinationId))
                        continue;

                    $_SESSION[self::SESSION_KEY][$key]['additionalProducts'][] = [
                        'productId' => $additionalProductId,
                        'number' => $number
                    ];
                    return true;
                }

            }
        }
        return false;
    }

    public function addAdditionalProductToDb($orderProductId, $additionalProductId, $number)
    {
        if (!empty($orderProductId) && !empty($additionalProductId)) {
            $orderProduct = OrderProduct::findOne($orderProductId);
            $order = $this->getIncompleteOrder();

            if (!empty($order)) {

                if (!empty($orderProduct)) {
                    $additionalProduct = new OrderProductAdditionalProduct();
                    $additionalProduct->order_product_id = $orderProduct->id;
                    $additionalProduct->additional_product_id = $additionalProductId;
                    $additionalProduct->number = $number;

                    if ($additionalProduct->validate()) {
                        $additionalProduct->save();
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns [[Product]] if the current user is a guest, [[OrderProduct]] if the user is authenticated.
     *
     * @param $id [[Product]] id
     * @return null|ActiveRecord|Product|OrderProduct
     */
    public function getProduct($id)
    {
        $product = null;

        if (Yii::$app->user->isGuest) {
            if (!empty(Yii::$app->session[self::SESSION_KEY])) {
                $sessionProducts = ArrayHelper::index(Yii::$app->session[self::SESSION_KEY], 'id');

                if (ArrayHelper::keyExists($id, $sessionProducts)) {
                    $product = Product::findOne($id);
                }
            }
        } else {
            /** @var Order $order */
            $order = Order::find()
                ->where(['user_id' => Yii::$app->user->id, 'status' => OrderStatus::STATUS_INCOMPLETE])
                ->one();

            if (!empty($order)) {
                $product = OrderProduct::find()
                    ->where(['order_id' => $order->id, 'product_id' => $id])
                    ->one();
            }
        }

        return $product;
    }

    /**
     * Checks if the cart contains the product.
     *
     * @param $productId [[Product]] id.
     * @return bool whether the cart contains the product
     */
    public function isContainsProduct($productId) {
        $product_id = ArrayHelper::getValue($this->getProduct($productId),
            (Yii::$app->user->isGuest) ? 'id' : 'product_id'
        );

        return ($product_id === $productId);
    }
}