<?php
namespace bl\cms\shop\widgets;

use yii\base\Widget;

/**
 * This widget adds edit button for multilingual controller
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ManageButtons extends Widget
{
    public $model;

    public $action = 'save';

    public $deleteUrl;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('manage-buttons', [
            'model' => $this->model,
            'action' => $this->action,
            'deleteUrl' => $this->deleteUrl
        ]);
    }
}