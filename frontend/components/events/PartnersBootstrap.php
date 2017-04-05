<?php
namespace xalberteinsteinx\shop\frontend\components\events;

use Yii;
use xalberteinsteinx\shop\Mailer;
use yii\helpers\{Html, Url};
use yii\base\{BootstrapInterface, Event};
use xalberteinsteinx\shop\common\entities\PartnerRequest;
use xalberteinsteinx\shop\common\components\user\models\Profile;
use xalberteinsteinx\shop\frontend\controllers\PartnerRequestController;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PartnersBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(PartnerRequestController::className(), PartnerRequestController::EVENT_SEND, [$this, 'send']);
    }

    /**
     * Sends emails about partner request to manager and partner
     * @param $event
     */
    public function send($event)
    {

        if (Yii::$app->user->isGuest) {
            $profile = Yii::$app->request->post('Profile');
            $partnerRequest = Yii::$app->request->post('PartnerRequest');
            $partnerEmail = [Yii::$app->request->post('register-form')['email']];

            $userMailVars = [
                '{name}' => $profile['name'],
                '{surname}' => $profile['surname'],
                '{patronymic}' => $profile['patronymic'],
                '{info}' => $profile['info'],
            ];
        } else {
            $profile = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
            $partnerRequest = PartnerRequest::find()->where(['sender_id' => Yii::$app->user->id])->one();
            $partnerEmail = [$profile->user->email];

            $userMailVars = [
                '{name}' => $profile->name,
                '{surname}' => $profile->surname,
                '{patronymic}' => $profile->patronymic,
                '{info}' => $profile->info,
            ];
        }

        $mailVars = [
            '{contact_person}' => $partnerRequest['contact_person'],
            '{company_name}' => $partnerRequest['company_name'],
            '{website}' => $partnerRequest['website'],
            '{message}' => $partnerRequest['message'],
            '{link}' => Html::a(\Yii::t('shop', 'Details'), Url::to('admin/shop/partners/view?id=' . $partnerRequest['id'], true))
        ];

        $mailVars = array_merge($userMailVars, $mailVars);

        $mailer = \Yii::createObject(Mailer::className());

        $mailer->sendPartnerRequestToPartner(
            $mailVars,
            [$event->sender->module->senderEmail ??
            \Yii::$app->get('shopMailer')->transport->getUsername() => \Yii::$app->name ?? parse_url(Url::base(true), PHP_URL_HOST)],
            $partnerEmail
        );
        $mailer->sendPartnerRequestToManager(
            $mailVars,
            [$event->sender->module->senderEmail ??
            \Yii::$app->get('shopMailer')->transport->getUsername() => \Yii::$app->name ?? parse_url(Url::base(true), PHP_URL_HOST)],
            $event->sender->module->partnerManagerEmail
        );
    }

}