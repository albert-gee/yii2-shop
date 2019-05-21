<?php
namespace albertgeeca\shop\widgets;

use albertgeeca\shop\common\components\user\models\Profile;
use dektrium\user\models\RegistrationForm;
use yii\base\Widget;

/**
 * This widget is for Dektrium User module.
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * (c) Dektrium project <http://github.com/dektrium>
 */
class Register extends Widget
{
    /**
     * @var bool
     */
    public $validate = true;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('register', [
            'profile' => \Yii::createObject(\Yii::$app->getModule('user')->modelMap['Profile']::className()),
            'model' => \Yii::createObject(\Yii::$app->getModule('user')->modelMap['RegistrationForm']::className()),
        ]);
    }
}