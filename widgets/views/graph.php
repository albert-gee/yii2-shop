<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $graphPoints array
 */

use yii\web\View;

\albertgeeca\shop\widgets\assets\GraphAsset::register($this);

$graphPointsAsJson = json_encode($graphPoints);

//$graphPointsAsString = "[" . implode(", ", $graphPoints) . "]";


$this->registerJs(
    "var graphPoints = " . $graphPointsAsJson . ";",
    View::POS_HEAD
);
?>

<canvas id="graph-canvas" width="470" height="350">
    Unfortunately, your browser does not support the Canvas technology.
</canvas>
