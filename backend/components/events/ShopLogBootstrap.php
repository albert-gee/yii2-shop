<?php
namespace albertgeeca\shop\backend\components\events;

use albertgeeca\shop\backend\controllers\CategoryController;
use albertgeeca\shop\backend\controllers\ProductController;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ShopLogBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(ProductController::className(),
            ProductController::EVENT_AFTER_CREATE_PRODUCT, [$this, 'addLogRecord']);
        Event::on(ProductController::className(),
            ProductController::EVENT_AFTER_EDIT_PRODUCT, [$this, 'addLogRecord']);
        Event::on(ProductController::className(),
            ProductController::EVENT_AFTER_DELETE_PRODUCT, [$this, 'addLogRecord']);

        Event::on(CategoryController::className(),
            CategoryController::EVENT_AFTER_CREATE_CATEGORY, [$this, 'addLogRecord']);
        Event::on(CategoryController::className(),
            CategoryController::EVENT_AFTER_EDIT_CATEGORY, [$this, 'addLogRecord']);
        Event::on(CategoryController::className(),
            CategoryController::EVENT_AFTER_DELETE_CATEGORY, [$this, 'addLogRecord']);
    }

    public function addLogRecord($event) {

        $userId = \Yii::$app->user->id;
        $message = "ID: $event->id, userId: $userId";

        Yii::info($message, $event->name);

    }

}