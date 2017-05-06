<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var \yii\db\ActiveRecord    $model
 * @var string                  $action
 * @var string                  $deleteUrl
 */

use bl\multilang\entities\Language;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<div class="btn-group">

    <a href="<?= Url::toRoute([$action, 'id' => $model->id, "languageId" => Language::getCurrent()->id]); ?>" class="btn btn-primary">
        <span><?= Fa::i(FA::_EDIT) . ' ' . \Yii::t('shop', 'Edit'); ?></span>
    </a>

    <section class="dropdown">
        <a href="<?= Url::to(['/']); ?>">
            <?= FA::i(FA::_ANGLE_DOWN); ?>
        </a>

        <?php if (!empty($languages = Language::find()->all())): ?>
            <ul>
                <?php foreach ($languages as $language): ?>
                    <li>
                        <?= Html::a($language->name, Url::toRoute([$action, 'id' => $model->id, "languageId" => $language->id])); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </section>

</div>

<!--Remove button-->
<a href="<?= $deleteUrl ?? Url::toRoute(['delete', 'id' => $model->id]); ?>" class="btn btn-danger pjax">
    <?= FA::i(FA::_TIMES); ?>
</a>
