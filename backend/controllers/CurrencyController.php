<?php
namespace albertgeeca\shop\backend\controllers;

use Yii;
use albertgeeca\shop\common\entities\Currency;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CurrencyController implements the CRUD actions for Currency model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CurrencyController extends Controller
{
    /**
     * Event is triggered before creating new product.
     * Triggered with albertgeeca\shop\backend\events\ProductEvent.
     */
    const EVENT_AFTER_CHANGE = 'afterChangeCurrency';

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
                        'roles' => ['viewCurrencyList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update'],
                        'roles' => ['updateCurrency'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['remove'],
                        'roles' => ['deleteCurrency'],
                        'allow' => true,
                    ]
                ],
            ]
        ];
    }

    /**
     * Lists all Currency models.
     * @return mixed
     * @throws Exception
     */
    public function actionIndex()
    {
        $rates = Currency::find()->orderBy('id DESC')->all();
        $model = new Currency();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    $model->save();
                    $this->trigger(self::EVENT_AFTER_CHANGE);

                    if (\Yii::$app->request->isPjax) {
                        return $this->renderPartial('index', [
                            'modifiedElement' => $model->id,
                            'rates' => Currency::find()->orderBy('id DESC')->all(),
                            'model' => $model
                        ]);
                    }
                }

                return $this->redirect(\Yii::$app->request->referrer);
            }
            else {
                throw new Exception();
            }
        }
        return $this->render('index', [
            'modifiedElement' => null,
            'rates' => $rates,
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Currency model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        $this->findModel($id)->delete();

        if (\Yii::$app->request->isPjax) {
            return $this->renderPartial('index', [
                'modifiedElement' => null,
                'rates' => Currency::find()->orderBy('id DESC')->all(),
                'model' => new Currency()
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Currency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        if (!empty($id)) {
            $model = Currency::findOne($id);
        }
        else {
            $model = new Currency();
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    $model->save();

                    if (\Yii::$app->request->isPjax) {
                        return $this->renderPartial('index', [
                            'modifiedElement' => $id,
                            'rates' => Currency::find()->orderBy('id DESC')->all(),
                            'model' => new Currency()
                        ]);
                    }
                }
            }
            else {
                throw new Exception();
            }
        }
        return $this->redirect(\Yii::$app->request->referrer);

    }

    /**
     * Finds the Currency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Currency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Currency::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}