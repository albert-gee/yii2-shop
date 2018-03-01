<?php
namespace sointula\shop\frontend\components\user;

use sointula\shop\common\components\user\models\Token;
use sointula\shop\common\components\user\models\User;
use bl\emailTemplates\data\Template;
use bl\multilang\entities\Language;
use Yii;
use yii\base\Component;
use yii\helpers\Url;

/**
 * Overrides dektrium/User Mailer component
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class UserMailer extends Component
{

    /** @var string */
    public $viewPath = '@dektrium/user/views/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    protected $confirmationSubject;

    /** @var string */
    protected $reconfirmationSubject;

    /** @var string */
    protected $recoverySubject;

    /** @var \dektrium\user\Module */
    protected $module;

    /**
     * @return string
     */
    public function getConfirmationSubject()
    {
        if ($this->confirmationSubject == null) {
            $this->setConfirmationSubject(Yii::t('user', 'Confirm account on {0}', Yii::$app->name));
        }

        return $this->confirmationSubject;
    }

    /**
     * @param string $confirmationSubject
     */
    public function setConfirmationSubject($confirmationSubject)
    {
        $this->confirmationSubject = $confirmationSubject;
    }

    /**
     * @return string
     */
    public function getReconfirmationSubject()
    {
        if ($this->reconfirmationSubject == null) {
            $this->setReconfirmationSubject(Yii::t('user', 'Confirm email change on {0}', Yii::$app->name));
        }

        return $this->reconfirmationSubject;
    }

    /**
     * @param string $reconfirmationSubject
     */
    public function setReconfirmationSubject($reconfirmationSubject)
    {
        $this->reconfirmationSubject = $reconfirmationSubject;
    }

    /**
     * @return string
     */
    public function getRecoverySubject()
    {
        if ($this->recoverySubject == null) {
            $this->setRecoverySubject(Yii::t('user', 'Complete password reset on {0}', Yii::$app->name));
        }

        return $this->recoverySubject;
    }

    /**
     * @param string $recoverySubject
     */
    public function setRecoverySubject($recoverySubject)
    {
        $this->recoverySubject = $recoverySubject;
    }

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendConfirmationMessage(User $user, Token $token)
    {
        return $this->sendMessage(
            $user->email,
            $this->getConfirmationSubject(),
            'confirmation',
            ['user' => $user, 'token' => $token]
        );
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendReconfirmationMessage(User $user, Token $token)
    {
        if ($token->type == Token::TYPE_CONFIRM_NEW_EMAIL) {
            $email = $user->unconfirmed_email;
        } else {
            $email = $user->email;
        }

        return $this->sendMessage(
            $email,
            $this->getReconfirmationSubject(),
            'reconfirmation',
            ['user' => $user, 'token' => $token]
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom([$mailer->transport->getUsername() => \Yii::$app->name ?? Url::to(['/'], true)])
            ->setSubject($subject)
            ->send();
    }

    /**
     * Sends an email to a user after registration.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendWelcomeMessage(User $user, Token $token = null)
    {
        $mailVars = [
            '{token}' => $token->url
        ];

        /**
         * @var $mailTemplate Template
         */
        $mailTemplate = \Yii::$app->get('emailTemplates')
            ->getTemplate('welcome', Language::getCurrent()->id);
        $mailTemplate->parseSubject($mailVars);
        $mailTemplate->parseBody($mailVars);

        return \Yii::$app->shopMailer->compose('mail-body', ['bodyContent' => $mailTemplate->getBody()])
            ->setFrom([\Yii::$app->shopMailer->transport->getUsername() => \Yii::$app->name ?? Url::to(['/'], true)])
            ->setTo($user->email)
            ->setSubject($mailTemplate->getSubject())
            ->send();
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendRecoveryMessage($user, $token)
    {
        $mailVars = [
            '{token}' => $token->url
        ];

        /**
         * @var $mailTemplate Template
         */
        $mailTemplate = \Yii::$app->get('emailTemplates')
            ->getTemplate('recovery', Language::getCurrent()->id);
        $mailTemplate->parseSubject($mailVars);
        $mailTemplate->parseBody($mailVars);

        return \Yii::$app->shopMailer->compose('mail-body', ['bodyContent' => $mailTemplate->getBody()])
            ->setFrom([\Yii::$app->shopMailer->transport->getUsername() => \Yii::$app->name ?? Url::to(['/'], true)])
            ->setTo($user->email)
            ->setSubject($mailTemplate->getSubject())
            ->send();
    }
}