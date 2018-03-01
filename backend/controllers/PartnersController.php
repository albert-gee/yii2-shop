<?php

namespace sointula\shop\backend\controllers;

use sointula\shop\backend\components\events\PartnersEvents;
use Yii;
use sointula\shop\common\entities\PartnerRequest;
use sointula\shop\common\entities\SearchPartnerRequest;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * PartnersController implements the CRUD actions for PartnerRequest model.
 */

class PartnersController extends Controller
{
    const EVENT_APPLY = 'apply';
    const EVENT_DECLINE = 'decline';

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
                        'actions' => ['index', 'view'],
                        'roles' => ['viewPartnerRequest'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deletePartnerRequest'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['change-partner-status'],
                        'roles' => ['moderatePartnerRequest'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all PartnerRequest models.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
            $partners = PartnerRequest::find()->all();
            $searchModel = new SearchPartnerRequest();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'partners' => $partners,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single PartnerRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing PartnerRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionChangePartnerStatus($id, $status)
    {
        if (Yii::$app->user->can('moderatePartnerRequest') && !empty($id) && !empty($status)) {
            $partner = PartnerRequest::findOne($id);
            if ($partner->moderation_status == PartnerRequest::STATUS_ON_MODERATION) {

                switch ($status) {
                    case PartnerRequest::STATUS_SUCCESS:
                        $partner->moderation_status = PartnerRequest::STATUS_SUCCESS;
                        $partner->save();
                        $role = \Yii::$app->authManager->getRole('productPartner');
                        \Yii::$app->authManager->assign($role, $partner->sender_id);

                        $this->trigger(self::EVENT_APPLY, new PartnersEvents([
                            'partnerUserId' => $partner->sender_id
                        ]));

                        break;
                    case PartnerRequest::STATUS_DECLINED:
                        $partner->moderation_status = PartnerRequest::STATUS_DECLINED;
                        $partner->save();
                        $this->trigger(self::EVENT_DECLINE, new PartnersEvents([
                            'partnerUserId' => $partner->sender_id
                        ]));
                        break;
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PartnerRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PartnerRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartnerRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
