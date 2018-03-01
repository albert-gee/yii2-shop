<?php
namespace sointula\shop\frontend\controllers;

use Yii;
use sointula\shop\common\entities\FavoriteProduct;
use sointula\shop\common\entities\SearchFavoriteProduct;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * FavoriteProductController implements the CRUD actions for FavoriteProduct model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class FavoriteProductController extends Controller
{

    /**
     * Adds product to list of favorites.
     *
     * @param integer $productId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAdd($productId)
    {
        if (!empty($productId)) {
            if (!Yii::$app->user->isGuest) {
                $model = new FavoriteProduct();

                $model->product_id = $productId;
                $model->user_id = Yii::$app->user->id;

                if ($model->save()) {
                    if (Yii::$app->request->isAjax) {
                        return Yii::t('shop', 'You have successfully added this product to favorites.');
                    }
                    else {
                        Yii::$app->session->setFlash('success', Yii::t('shop', 'You have successfully added this product to favorites.'));
                    }
                }
                else {
                    if (Yii::$app->request->isAjax) {
                        return Yii::t('shop', 'Error has occurred.');
                    }
                    else {
                        Yii::$app->session->setFlash('error', Yii::t('shop', 'Error has occurred.'));
                    }
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            throw new ForbiddenHttpException();
        }
        else throw new NotFoundHttpException();
    }

    /**
     * Removes product from list of favorites.
     *
     * @param integer $productId
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionRemove($productId)
    {
        if (!empty($productId)) {
            if (!Yii::$app->user->isGuest) {
                $model = FavoriteProduct::find()
                    ->where(['show'=> true, 'product_id' => $productId, 'user_id' => Yii::$app->user->id])->one();
                if (!empty($model)) {
                    $model->delete();
                    if (Yii::$app->request->isAjax) {
                        return Yii::t('shop', 'You have successfully deleted this product from favorites.');
                    }
                    else {
                        Yii::$app->session->setFlash('success', Yii::t('shop', 'You have successfully deleted this product from favorites.'));
                    }
                }
                else {
                    if (Yii::$app->request->isAjax) {
                        return Yii::t('shop', 'Error has occurred.');
                    }
                    else {
                        Yii::$app->session->setFlash('error', Yii::t('shop', 'Error has occurred.'));
                    }
                }
                return $this->redirect(Yii::$app->request->referrer);
            }
            throw new ForbiddenHttpException();
        }
        else throw new NotFoundHttpException();
    }

    /**
     * Lists all FavoriteProduct models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new SearchFavoriteProduct();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}