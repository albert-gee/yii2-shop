<?php
namespace albertgeeca\shop\widgets;

use yii\base\Widget;


/**
 *
 *
 * @author Vyacheslav Nozhenko <vv.nojenko@gmail.com>
 */
class ProductSort extends Widget
{
    /**
     * @var array
     */
    public $sortMethods = [];

    /**
     * @var string
     */
    public $currentSort = 'new';

    /**
     * @var array
     */
    public $options = [];


    public function init()
    {
       if (empty($this->sortMethods)) {
           $this->sortMethods = [
               'default' => \Yii::t('shop', 'By default'),
               'cheap' => \Yii::t('shop', 'From cheap to expensive'),
               'expensive' => \Yii::t('shop', 'From expensive to cheap'),
               'new' => \Yii::t('shop', 'From new to old'),
               'old' => \Yii::t('shop', 'From old to new'),
           ];
       }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (isset(\Yii::$app->request->queryParams['sort'])) {
            $this->currentSort = \Yii::$app->request->queryParams['sort'];
        } else {
            $this->currentSort = 'default';
        }

        return $this->render('product-sort', [
            'sortMethods' => $this->sortMethods,
            'currentSort' => $this->currentSort,
            'options' => $this->options
        ]);
    }
}