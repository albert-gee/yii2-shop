<?php
namespace albertgeeca\shop\backend\controllers;

use albertgeeca\shop\common\models\PaymentImageModel;
use albertgeeca\shop\common\entities\PaymentMethod;
use albertgeeca\shop\common\entities\PaymentMethodTranslation;
use bl\multilang\entities\Language;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DefaultController implements the CRUD actions for PaymentMethod model.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PaymentController extends Controller
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
                        'roles' => ['viewPaymentMethodList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save', 'delete-image'],
                        'roles' => ['savePaymentMethod'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deletePaymentMethod'],
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }
    /**
     * Lists all PaymentMethod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = PaymentMethod::find()->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    /**
     * Creates a new PaymentMethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @param integer $languageId
     * @return mixed
     * @throws Exception
     */
    public function actionSave($id = null, $languageId = null)
    {
        if (empty($languageId)) {
            $languageId = Language::getCurrent()->id;
        }
        if (!empty($id)) {
            $model = PaymentMethod::findOne($id);
            if (empty($model)) throw new NotFoundHttpException();
            $modelTranslation = PaymentMethodTranslation::find()
                ->where(['payment_method_id' => $model->id, 'language_id' => $languageId])->one();
            if (empty($modelTranslation)) {
                $modelTranslation = new PaymentMethodTranslation();
            }
        }
        else {
            $model = new PaymentMethod();
            $modelTranslation = new PaymentMethodTranslation();
        }
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post())) {
                $imageModel = new PaymentImageModel();
                $imageModel->imageFile = UploadedFile::getInstance($model, 'image');
                if (!empty($imageModel->imageFile)) {
                    $uploadedImageName = $imageModel->upload();
                    $model->image = $uploadedImageName;
                }
            }
            if ($modelTranslation->load(Yii::$app->request->post())) {
                if ($modelTranslation->validate()) {
                    $model->save();
                    $modelTranslation->payment_method_id = $model->id;
                    $modelTranslation->language_id = $languageId;
                    $modelTranslation->save();
                    return $this->redirect(['save', 'id' => $model->id, 'languageId' => $languageId]);
                }
            }
        }
        return $this->render('save', [
            'model' => $model,
            'modelTranslation' => $modelTranslation,
            'selectedLanguage' => Language::findOne($languageId)
        ]);
    }
    /**
     * Deletes an existing PaymentMethod model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if (($model = PaymentMethod::findOne($id)) !== null) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Deletes image from Payment method model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionDeleteImage($id) {
        if (!empty($id)) {
            $paymentMethod = PaymentMethod::findOne($id);
            $isDeleted = \Yii::$app->shop_imagable->delete('payment', $paymentMethod->image);
            if ($isDeleted) {
                $paymentMethod->image = NULL;
                $paymentMethod->save();
                return $this->redirect(Yii::$app->request->referrer);
            }
            else throw new Exception('Deleting failed.');
        }
        else throw new NotFoundHttpException();
    }
}