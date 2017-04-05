<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @author Vyacheslav Nozhenko <vv.nojenko@gmail.com>
 *
 * @var \yii\web\View $this
 * @var array $sortMethods
 * @var string $currentSort
 * @var array $options
 */


?>
<div class="pull-right text-center">
    <small><?= Yii::t('shop', 'Sorting') ?></small>
    <div class="dropdown <?= ArrayHelper::getValue($options, 'class') ?>">
        <button class="btn btn-default dropdown-toggle" type="button" id="productSortDropdown" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="true">
            <?= ArrayHelper::getValue($sortMethods, $currentSort) ?>
            <span class="caret"></span>
        </button>
        <?php if (!empty($sortMethods)): ?>
            <ul class="dropdown-menu" aria-labelledby="productSortDropdown">
                <?php foreach ($sortMethods as $sort => $sortName): ?>
                    <li class="<?= ($sort == $currentSort) ? 'active' : '' ?>">
                        <?= Html::a($sortName, array_merge(
                            ['/' . Yii::$app->controller->getRoute()],
                            Yii::$app->request->getQueryParams(),
                            ['sort' => $sort])
                        ) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif ?>
    </div>
</div>