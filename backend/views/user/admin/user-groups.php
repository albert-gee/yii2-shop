<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $userGroups \albertgeeca\shop\common\components\user\models\UserGroup[]
 */

use yii\helpers\Url;

$this->title = \Yii::t('shop', 'User groups');
?>

<div class="box">
    <div class="box-title">
        <a class="btn btn-primary btn-xs pull-right" href="<?= Url::to(['save-user-group']); ?>">
            <i class="fa fa-user-plus"></i><?= Yii::t('shop', 'Create user group'); ?>
        </a>
        <h1>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= $this->title; ?>
        </h1>
    </div>
    <div class="box-content">
        <table id="my-grid" class="table-hover table">
            <tr>
                <th>
                    <?= \Yii::t('shop', 'Title'); ?>
                </th>
                <th>
                    <?= \Yii::t('shop', 'Description'); ?>
                </th>
            </tr>

            <?php foreach ($userGroups as $userGroup) : ?>
                <tr>

                    <td>
                        <?= $userGroup->translation->title ?>
                    </td>
                    <td>
                        <?= $userGroup->translation->description; ?>
                    </td>
                    <td>
                        <?= \albertgeeca\shop\widgets\ManageButtons::widget([
                            'model' => $userGroup,
                            'action' => 'save-user-group',
                            'deleteUrl' => Url::to(['delete-user-group', 'id' => $userGroup->id])
                        ]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>