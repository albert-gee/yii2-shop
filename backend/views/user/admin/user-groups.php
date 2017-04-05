<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $userGroups \xalberteinsteinx\shop\common\components\user\models\UserGroup[]
 */

use yii\helpers\Url;

$this->title = \Yii::t('shop', 'User groups');
?>

<div class="ibox">
    <div class="ibox-title">
        <a class="btn btn-primary btn-xs pull-right" href="<?= Url::to(['save-user-group']); ?>">
            <i class="fa fa-user-plus"></i><?= Yii::t('shop', 'Create user group'); ?>
        </a>
        <h5>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= $this->title; ?>
        </h5>
    </div>
    <div class="ibox-content">
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
                        <?= \xalberteinsteinx\shop\widgets\ManageButtons::widget([
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