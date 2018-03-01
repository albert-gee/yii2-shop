<?php
namespace sointula\shop\backend\controllers;

use sointula\shop\common\entities\OrderStatus;
use sointula\shop\common\entities\OrderStatusTranslation;
use bl\multilang\entities\Language;
use Exception;
use Yii;
use sointula\shop\common\entities\SearchOrderStatus;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrderStatusController implements the CRUD actions for OrderStatusTranslation model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class OrderStatusController extends Controller
{
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
                        'roles' => ['viewOrderStatusList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save'],
                        'roles' => ['saveOrderStatus'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteOrderStatus'],
                        'allow' => true,
                    ]
                ],
            ]
        ];
    }

    /**
     * Lists all OrderStatus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchOrderStatus();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new or updates existing OrderStatus and OrderStatusTranslation models.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws Exception
     */
    public function actionSave($id = null, $languageId)
    {
        if (!empty($languageId)) {
            if (!empty($id)) {
                $model = $this->findModel($id);
            }
            else {
                $model = new OrderStatus();
            }

            $modelTranslation = $this->findOrCreateModelTranslation($model->id, $languageId);

            if ($modelTranslation->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {
                $model->save();
                $modelTranslation->language_id = $languageId;
                $modelTranslation->order_status_id = $model->id;
                if ($modelTranslation->validate()) {
                    $modelTranslation->save();
                    \Yii::$app->session->setFlash('success', \Yii::t('cart', 'The record was successfully saved.'));
                } else {
                    \Yii::$app->session->setFlash('error', \Yii::t('cart', 'An error occurred when saving the record.'));
                }
                return $this->redirect(['save',
                    'id' => $model->id,
                    'languageId' => $languageId
                ]);
            }

            return $this->render('save',
                [
                    'model' => $model,
                    'modelTranslation' => $modelTranslation,
                    'languages' => Language::find()->all(),
                    'selectedLanguage' => Language::findOne($languageId)
                ]);
        } else throw new Exception(\Yii::t('cart', 'Language id can not be empty'));
    }

    /**
     * Deletes an existing OrderStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Exception if user try to delete default statuses.
     * @throws NotFoundHttpException if there is not $id.
     */
    public function actionDelete($id)
    {
        if (!empty($id)) {
            if ($id != OrderStatus::STATUS_INCOMPLETE && $id != OrderStatus::STATUS_CONFIRMED) {
                $status = $this->findModel($id);
                if (!empty($status)) {
                    \Yii::$app->session->setFlash('success', Yii::t('cart', 'The status was successfully deleted.'));
                    $status->delete();
                }
            } else {
                \Yii::$app->session->setFlash('success', Yii::t('cart', 'Removing this status will break cart functionality.'));
            }
            return $this->redirect(['index']);
        }

        throw new NotFoundHttpException();
    }

    /**
     * Finds the OrderStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the OrderStatusTranslation model based on 'order_status_id' property.
     * If the model is not found, a new model will be created.
     * @param integer $id
     * @param integer $languageId
     * @return OrderStatusTranslation|ActiveRecord the loaded model
     */
    protected function findOrCreateModelTranslation($id, $languageId)
    {
        $modelTranslations = OrderStatusTranslation::find()
            ->where(['order_status_id' => $id, 'language_id' => $languageId])->one();
        return (!empty($modelTranslations)) ? $modelTranslations : new OrderStatusTranslation();
    }
}