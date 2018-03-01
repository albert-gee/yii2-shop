<?php
namespace sointula\shop\backend\components\user\controllers;

use sointula\shop\common\components\user\models\UserGroup;
use sointula\shop\common\components\user\models\UserGroupTranslation;
use bl\multilang\entities\Language;
use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class AdminController extends BaseAdminController
{
    /**
     * @return string
     */
    public function actionShowUserGroups() {
        $userGroups = UserGroup::find()->all();

        return $this->render('user-groups', [
            'userGroups' => $userGroups
        ]);
    }

    /**
     * @param null $id
     * @param null $languageId
     * @return string|\yii\web\Response
     */
    public function actionSaveUserGroup($id = null, $languageId = null)
    {
        if (!empty($id) && !empty($languageId)) {
            $userGroupTranslation = UserGroupTranslation::find()->where([
                'user_group_id' => $id,
                'language_id' => $languageId
            ])->one();
        }
        $languageId = $languageId ?? Language::getCurrent()->id;
        if (empty($userGroupTranslation)) {
            $userGroupTranslation = new UserGroupTranslation();
        }

        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();

            $userGroupTranslation->load($post);
            $userGroup = UserGroup::findOne($id) ?? new UserGroup();
            $userGroup->save();

            $userGroupTranslation->user_group_id = $userGroup->id;
            $userGroupTranslation->language_id = $languageId;
            if ($userGroupTranslation->validate()) {
                $userGroupTranslation->save();
            }

            return $this->redirect(
                Url::to([
                    'save-user-group',
                    'id' => $userGroup->id,
                    'languageId' => $languageId
                ])
            );
        }

        return $this->render('save-user-group', [
            'languageId' => $languageId,
            'userGroupTranslation' => $userGroupTranslation
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteUserGroup(int $id) {
        $userGroup = UserGroup::findOne($id);
        if (!empty($userGroup)) {
            $userGroup->delete();
            return $this->redirect(\Yii::$app->request->referrer);
        }
        else throw new NotFoundHttpException();
    }
}