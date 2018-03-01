<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $count integer
 */
use yii\helpers\Html;
\sointula\shop\widgets\assets\OrderCounterAsset::register($this);
?>

<?= Html::tag('span',
    $count,
    ['id' => 'order-counter', 'style' => ($count) ? 'background-color: #079276' : 'background-color: #40586f']
);?>
