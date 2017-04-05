<?php
namespace xalberteinsteinx\shop\frontend\components\user\controllers;

use xalberteinsteinx\shop\common\components\user\models\Profile;
use xalberteinsteinx\shop\common\components\user\models\UserAddress;
use dektrium\user\controllers\SettingsController as BaseController;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * This controller adds some actions for dektrium\user\controllers\SettingsController
 * Its manage updating user settings (e.g. profile, email and password).
 *
 * @property \dektrium\user\Module $module
 *
 */
class SettingsController extends BaseController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'networks', 'disconnect', 'delete', 'addresses', 'save-address', 'delete-address'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAddresses() {

        $addresses = \Yii::$app->user->identity->profile->userAddresses;
        return $this->render('addresses', [
            'addresses' => $addresses
        ]);
    }

    public function actionSaveAddress($id = null) {
        if (!empty($id)) {
            $address = UserAddress::findOne($id);
            if ($address->user_profile_id == \Yii::$app->user->identity->profile->id) {
                if ($address->load(\Yii::$app->request->post())) {
                    if ($address->validate()) {
                        $address->save();
                        return $this->redirect(Url::toRoute('/user/settings/addresses'));
                    }
                    else throw new Exception($address->errors);
                }
                return $this->render('save-address', [
                    'address' => $address
                ]);
            }
            else throw new ForbiddenHttpException();
        }
        else {
            $address = new UserAddress();
            if ($address->load(\Yii::$app->request->post())) {
                $address->user_profile_id = \Yii::$app->user->identity->profile->id;
                if ($address->validate()) {
                    $address->save();
                    return $this->redirect('addresses');
                }
            }
            return $this->render('save-address', [
                'address' => $address
            ]);
        }
    }

    public function actionDeleteAddress($id) {
        if (!empty($id)) {
            $address = UserAddress::findOne($id);
            if ($address->user_profile_id == \Yii::$app->user->identity->profile->id) {
                $address->delete();
                return $this->redirect('addresses');
            }
            else throw new ForbiddenHttpException('Such address does not exists or it is not your address.');
        }
        else throw new ForbiddenHttpException('Id can not be empty.');
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}
