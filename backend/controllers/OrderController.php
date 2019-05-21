<?php
namespace albertgeeca\shop\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use albertgeeca\shop\backend\components\events\OrderEvent;
use albertgeeca\shop\common\entities\{OrderProduct, OrderStatus, Order, SearchOrder};
use yii\web\{Controller, ForbiddenHttpException, NotFoundHttpException};

/**
 * OrdersController implements the CRUD actions for Order model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class OrderController extends Controller
{

    /**
     * Event is triggered before changing order status.
     * Triggered with albertgeeca\shop\backend\components\events\OrderEvent.
     */
    const EVENT_BEFORE_CHANGE_ORDER_STATUS = 'beforeChangeOrderStatus';
    /**
     * Event is triggered after changing order status.
     * Triggered with albertgeeca\shop\backend\components\events\OrderEvent.
     */
    const EVENT_AFTER_CHANGE_ORDER_STATUS = 'afterChangeOrderStatus';

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
                        'roles' => ['viewOrderList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['view'],
                        'roles' => ['viewOrder', 'changeOrderStatus'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteOrder'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete-product'],
                        'roles' => ['deleteOrderProduct'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchOrder();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model and changes status for this model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        if (!empty($id)) {
            $model = Order::findOne($id);
            if (empty($model)) {
                $model = new Order();
            }
            if (Yii::$app->user->can('changeOrderStatus')) {
                if ($model->load(Yii::$app->request->post())) {
                    $this->trigger(self::EVENT_BEFORE_CHANGE_ORDER_STATUS);
                    if ($model->save()) {

                        $this->trigger(self::EVENT_AFTER_CHANGE_ORDER_STATUS, new OrderEvent([
                            'model' => $model
                        ]));

                        \Yii::$app->session->setFlash('success', \Yii::t('cart', 'The record was successfully saved.'));

                    } else {
                        \Yii::$app->session->setFlash('error', \Yii::t('cart', 'An error occurred when saving the record.'));
                    }
                    return $this->redirect(['view', 'id' => $id]);
                } else {
                    $orderProducts = $model->orderProducts;

                    return $this->render('view', [
                        'model' => $this->findModel($id),
                        'statuses' => OrderStatus::find()->all(),
                        'orderProducts' => $orderProducts,
                    ]);
                }
            } else throw new ForbiddenHttpException();

        } else throw new NotFoundHttpException();
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes product from existing Order model.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDeleteProduct($id)
    {
        if (($model = OrderProduct::findOne($id)) !== null) {
            $model->delete();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}