<?php
namespace xalberteinsteinx\shop\backend\components\events;

use xalberteinsteinx\shop\backend\controllers\ProductController;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\Mailer;
use yii\base\{
    BootstrapInterface, Event
};
use xalberteinsteinx\shop\backend\controllers\PartnersController;
use xalberteinsteinx\shop\common\components\user\models\User;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PartnersBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(PartnersController::className(), PartnersController::EVENT_APPLY, [$this, 'applyPartnerRequest']);
        Event::on(PartnersController::className(), PartnersController::EVENT_DECLINE, [$this, 'declinePartnerRequest']);
        Event::on(ProductController::className(), ProductController::EVENT_AFTER_CREATE_PRODUCT, [$this, 'createNewProduct']);
        Event::on(ProductController::className(), ProductController::EVENT_AFTER_ACCEPT_PRODUCT, [$this, 'acceptProduct']);
    }

    public function applyPartnerRequest($event)
    {

        $userId = $event->partnerUserId;
        $user = User::findOne($userId);

        if (!empty($user->email)) {
            $mailer = \Yii::createObject(Mailer::className());
            $mailer->sendPartnerAcceptance($user->email);
        }

    }

    public function declinePartnerRequest($event)
    {

    }

    public function createNewProduct($event)
    {
        $productId = $event->id;

        if (!\Yii::$app->user->can('createProductWithoutModeration')) {
            $product = Product::findOne($productId);
            $mailer = \Yii::createObject(Mailer::className());
            $mailer->sendNewProductToManagerAndOwner($product);
        }

    }

    public function acceptProduct($event)
    {
        $productId = $event->id;

        $product = Product::findOne($productId);
        $mailer = \Yii::createObject(Mailer::className());
        $mailer->sendAcceptProductToOwner($product);

    }

}