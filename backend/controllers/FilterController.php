<?php

namespace xalberteinsteinx\shop\backend\controllers;

use Yii;
use xalberteinsteinx\shop\common\entities\FilterType;
use xalberteinsteinx\shop\common\entities\SearchFilterType;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * FilterController implements the CRUD actions for FilterType model.
 */
class FilterController extends Controller
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
                        'roles' => ['viewFilterList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save'],
                        'roles' => ['saveFilter'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteFilter'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all FilterType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchFilterType();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FilterType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSave($id = null)
    {

        if (!empty($id)) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['save', 'id' => $model->id]);
            }
        }

        else {
            $model = new FilterType();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['save', 'id' => $model->id]);
            }
        }
        return $this->render('save', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FilterType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FilterType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FilterType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FilterType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}