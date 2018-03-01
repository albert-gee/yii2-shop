<?php
namespace sointula\shop\frontend\controllers;

use sointula\shop\common\entities\SearchViewedProduct;
use sointula\shop\common\entities\ViewedProduct;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ViewedProductController extends Controller
{

    /**
     * Shows list of all user viewed products.
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionList()
    {
        if (!Yii::$app->user->isGuest) {
            $searchModel = new SearchViewedProduct();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else throw new NotFoundHttpException();
    }

    /**
     * Deletes an existing ViewedProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->isGuest) {
            if (!empty($id)) {
                $viewedProduct = ViewedProduct::find()
                    ->where(['product_id' => $id, 'user_id' => Yii::$app->user->id])->one();
                if ($viewedProduct->user_id == Yii::$app->user->id) {
                    $viewedProduct->delete();
                    return $this->redirect(['list']);
                }
            }
        } else throw new NotFoundHttpException();
    }

    /**
     * Deletes all user viewed products.
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionClearList()
    {
        if (!Yii::$app->user->isGuest) {
            ViewedProduct::deleteAll(['user_id' => \Yii::$app->user->id]);
            return $this->redirect(['list']);
        } else throw new NotFoundHttpException();
    }

}