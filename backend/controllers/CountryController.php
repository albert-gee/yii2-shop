<?php
namespace sointula\shop\backend\controllers;

use sointula\shop\backend\components\events\CountryEvent;
use sointula\shop\backend\components\form\CountryImageForm;
use sointula\shop\common\entities\ProductCountry;
use sointula\shop\common\entities\ProductCountryTranslation;
use bl\multilang\entities\Language;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CountryController extends Controller
{
    const EVENT_AFTER_CREATE_OR_UPDATE_COUNTRY = 'afterCrateOrUpdateCountry';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'roles' => ['viewCountryList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['save', 'remove-image'],
                        'roles' => ['saveCountry'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'roles' => ['deleteCountry'],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * List of countries
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', [
            'countries' => ProductCountry::find()->all()
        ]);
    }

    /**
     * @param null $id
     * @param null $languageId
     * @return string|\yii\web\Response
     */
    public function actionSave($id = null, $languageId = null) {

        $selectedLanguage = Language::findOne($languageId);
        $countryImageModel = new CountryImageForm();

        if (!empty($id)) {
            $country = ProductCountry::find()->where([
                'id' => $id
            ])->one();
            $countryTranslation = ProductCountryTranslation::find()->where([
                'country_id' => $id,
                'language_id' => $languageId
            ])->one();
            if (empty($countryTranslation)) {
                $countryTranslation = new ProductCountryTranslation;
            }
        }
        else {
            $country = new ProductCountry();
            $countryTranslation = new ProductCountryTranslation();
        }

        if(\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $country->load($post);
            $countryTranslation->load($post);

            if ($countryImageModel->load(\Yii::$app->request->post())) {
                $countryImageModel->image = UploadedFile::getInstance($countryImageModel, 'image');

                $fileName = $countryImageModel->upload();
                $country->image = (string) $fileName ?? $country->image;
            }

            if ($countryTranslation->validate()) {
                if ($country->validate())
                    $country->save();

                $countryTranslation->country_id = $country->id;
                $countryTranslation->language_id = $selectedLanguage->id;
                $countryTranslation->save();

                $this->trigger(self::EVENT_AFTER_CREATE_OR_UPDATE_COUNTRY, new CountryEvent(['country' => $country]));
                return $this->redirect(Url::toRoute('/shop/country'));
            }
        }

        return $this->render('save', [
            'country' => $country,
            'countryTranslation' => $countryTranslation,
            'countryImageModel' => $countryImageModel,
            'languages' => Language::findAll(['active' => true]),
            'selectedLanguage' => $selectedLanguage
        ]);

    }

    public function actionDelete($id) {
        ProductCountry::deleteAll(['id' => $id]);
        return $this->redirect(Url::to(['/shop/country']));
    }

    /**
     * @param int $countryId
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRemoveImage(int $countryId)
    {
        $country = ProductCountry::findOne($countryId);
        if (!empty($country)) {
            unlink(\Yii::getAlias('@frontend/web/images/shop-product-country/') . $country->image);
            $country->image = '';
            $country->save();
            return $this->redirect(\Yii::$app->request->referrer);
        }
        throw new NotFoundHttpException();
    }
}