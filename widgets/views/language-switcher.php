<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $selectedLanguage \bl\multilang\entities\Language
 * @var $languages \bl\multilang\entities\Language[]
 * @var $model \yii\db\ActiveRecord
 */

use rmrevin\yii\fontawesome\FA;
use yii\helpers\Url;
?>

<?php if (count($languages) > 1): ?>
    <div class="dropdown pull-right">
        <button class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
            <span>
                <?= $selectedLanguage->name . ' ' . FA::i(FA::_ANGLE_DOWN) ?>
            </span>
        </button>
        <?php if (count($languages) > 1): ?>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php foreach ($languages as $language): ?>
                    <li>

                        <?php $module = (Yii::$app->controller->module->id != Yii::$app->id) ? '/' . Yii::$app->controller->module->id : ''; ?>

                        <a href="<?= Url::to([$module . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
                                    'id' => Yii::$app->request->get('id'),
                                    'languageId' => $language->id]); ?>">
                            <?= $language->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>