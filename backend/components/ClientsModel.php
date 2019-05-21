<?php
namespace albertgeeca\shop\backend\components;
use Yii;
use yii\base\Model;
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ClientsModel extends Model
{
    public static function ClientsToCsv($clients) {

        $output = fopen("php://memory", "w");
        foreach ($clients as $client) {
            fputcsv($output, [$client->email, $client->phone]);
        }
        $response = Yii::$app->getResponse();
        $response->sendStreamAsFile($output, 'testing.csv', [
            'mimeType' => 'text/csv',
            'inline' => true,
        ]);
        $response->send();
        Yii::$app->end();
    }
}