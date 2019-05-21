<?php
namespace albertgeeca\shop\frontend\controllers;

use albertgeeca\shop\common\components\user\models\Profile;
use albertgeeca\shop\common\components\user\models\RegistrationForm;
use albertgeeca\shop\common\components\user\models\User;
use bl\cms\seo\StaticPageBehavior;
use albertgeeca\shop\frontend\components\events\PartnersEvents;
use albertgeeca\shop\common\entities\PartnerRequest;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PartnerRequestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'staticPage' => [
                'class' => StaticPageBehavior::className(),
                'key' => 'partner'
            ]
        ];
    }

    const EVENT_SEND = 'send';

    public function actionSend()
    {
        $partner = new PartnerRequest();

        if (Yii::$app->user->isGuest) {
            $user = \Yii::createObject(RegistrationForm::className());
            $profile = \Yii::createObject(Profile::className());
        } else {
            $user = User::findOne(Yii::$app->user->id);
            $profile = Profile::find()->where(['user_id' => $user->id])->one();
        }

        if (Yii::$app->request->isPost) {

            if (Yii::$app->user->isGuest) {
                if ($user->load(\Yii::$app->request->post())) {

                    if ($profile->user_id = $user->register()) {
                        $profile->name = $user->name;
                        $profile->surname = $user->surname;
                        $profile->phone = $user->phone;

                        if ($profile->validate()) {
                            $profile->save();
                        }
                    }
                } else throw new Exception('Registration is failed.');
            }

            $partner->load(Yii::$app->request->post());
            if ($partner->validate()) {

                $partner->sender_id = $profile->user_id;
                $partner->moderation_status = PartnerRequest::STATUS_ON_MODERATION;
                $partner->save();

                $this->trigger(self::EVENT_SEND, new PartnersEvents());

                Yii::$app->getSession()->setFlash('success', \Yii::t('shop', 'Your partner request was successfully sent.'));
                return $this->render('success');
            } else throw new Exception();
        }
        $this->registerStaticSeoData();

        return $this->render('send',
            [
                'partner' => $partner,
                'user' => \Yii::createObject(\dektrium\user\models\RegistrationForm::className()),
                'profile' => $profile
            ]
        );
    }

}
