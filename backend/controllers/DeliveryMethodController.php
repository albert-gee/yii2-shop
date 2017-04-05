<?php
namespace xalberteinsteinx\shop\backend\controllers;

use xalberteinsteinx\shop\common\entities\DeliveryMethodTranslation;
use bl\multilang\entities\Language;
use Yii;
use xalberteinsteinx\shop\common\entities\DeliveryMethod;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DeliveryMethodController implements the CRUD actions for DeliveryMethod model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class DeliveryMethodController extends Controller
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
                        'roles' => ['viewDeliveryMethodList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save'],
                        'roles' => ['saveDeliveryMethod'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteDeliveryMethod'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all DeliveryMethod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $deliveryMethods = DeliveryMethod::find()->all();

        return $this->render('index', [
            'deliveryMethods' => $deliveryMethods,
        ]);
    }

    /**
     * Creates a new DeliveryMethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionSave($id = null, $languageId)
    {
        if (!empty($languageId)) {
            if (!empty($id)) {

                $model = $this->findModel($id);
                if (empty($model)) {
                    throw new NotFoundHttpException;
                }
                $modelTranslation = $this->findModelTranslation($id, $languageId);
                if (empty($modelTranslation)) {
                    $modelTranslation = new DeliveryMethodTranslation();
                }
            }
            else {
                $model = new DeliveryMethod();
                $modelTranslation = new DeliveryMethodTranslation();
            }
        }
        else throw new BadRequestHttpException();


        if ($model->load(Yii::$app->request->post()) && $modelTranslation->load(Yii::$app->request->post())) {

            $model->logo = UploadedFile::getInstance($model, 'logo');
            if (!empty($model->logo)) {

                $uploadedImageName = $model->upload();

                $model->image_name = $uploadedImageName;
            }
            $model->save(false);

            $modelTranslation->delivery_method_id = $model->id;
            $modelTranslation->language_id = $languageId;
            if ($modelTranslation->validate()) {

                $modelTranslation->save();
                return $this->redirect(['save', 'id' => $model->id, 'languageId' => $languageId]);
            }
        }
        return $this->render('save', [
            'model' => $model,
            'modelTranslation' => $modelTranslation,
            'languages' => Language::find()->all(),
            'selectedLanguage' => Language::findOne($languageId)
        ]);

    }

    /**
     * Deletes an existing DeliveryMethod model.
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
     * Finds the DeliveryMethod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeliveryMethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeliveryMethod::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the DeliveryMethodTranslation model based on delivery method id and language id.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelTranslation($id, $languageId)
    {
        $model = DeliveryMethodTranslation::find()->where([
            'delivery_method_id' => $id,
            'language_id' => $languageId
        ])->one();

        if ($model !== null) {
            return $model;
        } else {
            return false;
        }
    }
}