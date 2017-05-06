<?php
namespace xalberteinsteinx\shop\widgets;

use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * This widget adds edit button for multilingual controller
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ManageButtons extends Widget
{

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $action = 'save';

    /**
     * @var string
     */
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