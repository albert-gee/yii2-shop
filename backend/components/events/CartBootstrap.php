<?php
namespace sointula\shop\backend\components\events;

use sointula\shop\backend\controllers\OrderController;
use bl\emailTemplates\data\Template;
use bl\multilang\entities\Language;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\Exception;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CartBootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        Event::on(OrderController::className(),
            OrderController::EVENT_AFTER_CHANGE_ORDER_STATUS, [$this, 'addLogRecord']);
        Event::on(OrderController::className(),
            OrderController::EVENT_AFTER_CHANGE_ORDER_STATUS, [$this, 'send']);
    }

    /**
     * @param $event
     *
     * Records log
     */
    public function addLogRecord($event) {
        /**
         * If logging is enabled
         */
        if ($event->sender->module->enableLog) {

            $userId = \Yii::$app->user->id;
            $order = $event->model;

            $message = "ID: $order->id userId: $userId";

            Yii::info($message, $event->name);
        }
    }

    /**
     * @param $event
     * @throws \yii\base\Exception
     *
     * Sends email to customer
     */
    public function send($event)
    {
        $mail = $event->model->orderStatus->mail;

        if (!empty($mail)) {
            try {

                /**
                 * @var $mailTemplate Template
                 */
                $mailTemplate = Yii::$app->get('emailTemplates')->getTemplate($mail->key, Language::getCurrent()->id);
                $mailTemplate->parseSubject([
                    '{order_id}' => $event->model->uid,
                    '{created_at}' => $event->model->creation_time,
                    '{status}' => $event->model->orderStatus->translation->title,
                ]);
                $mailTemplate->parseBody([
                    '{order_id}' => $event->model->uid,
                    '{created_at}' => $event->model->creation_time,
                    '{status}' => $event->model->orderStatus->translation->title,
                ]);

                Yii::$app->shopMailer->compose('@vendor/sointula/yii2-shop/frontend/views/mail/mail-body',
                    ['bodyContent' => $mailTemplate->getBody()])
                    ->setFrom([\Yii::$app->cart->sender ?? \Yii::$app->shopMailer->transport->getUsername() => \Yii::$app->name ?? Url::to(['/'], true)])
                    ->setTo($event->model->user->email)
                    ->setSubject($mailTemplate->getSubject())
                    ->send();

            } catch (Exception $ex) {
                throw new Exception($ex);
            }
        }
    }

}