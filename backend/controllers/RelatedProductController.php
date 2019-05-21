<?php
namespace albertgeeca\shop\backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use bl\multilang\entities\Language;
use albertgeeca\shop\common\entities\{Product, RelatedProduct};

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class RelatedProductController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'only' => ['save'],
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'list', 'remove'
                        ],
                        'roles' => ['updateProduct', 'updateOwnProduct'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Shows all related products for current product
     * @param int $productId
     * @param int $languageId
     * @return string|\yii\web\Response
     */
    public function actionList(int $productId, int $languageId)
    {

        $product = Product::findOne($productId);
        $products = Product::find()->joinWith('translations')->orderBy('title')->all();
        $relatedProducts = $product->relatedProducts;
        $newRelatedProduct = new RelatedProduct();

        $selectedLanguage = Language::findOne($languageId);

        if (\Yii::$app->request->isPost && $newRelatedProduct->load(\Yii::$app->request->post())) {
            if (empty(RelatedProduct::find()
                ->where(['product_id' => $productId, 'related_product_id' => $newRelatedProduct->related_product_id])->one())
            ) {

                $newRelatedProduct->product_id = $productId;
                if ($newRelatedProduct->validate()) $newRelatedProduct->save();
                \Yii::$app->session->setFlash('info', \Yii::t('shop', 'Relation product has added successfully'));
            } else {
                \Yii::$app->session->setFlash('info', \Yii::t('shop', 'Such relation product already exists for this product'));
            }

            return $this->redirect(\Yii::$app->request->referrer);
        }

        if (\Yii::$app->request->isPjax) {
            return $this->renderPartial('../related-product/list', [
                'product' => $product,
                'products' => $products,
                'relatedProducts' => $relatedProducts,
                'newRelatedProduct' => $newRelatedProduct,
                'selectedLanguage' => $selectedLanguage,
            ]);
        }
        return $this->render('../product/save', [
            'viewName' => '../related-product/list',
            'product' => $product,

            'params' => [
                'product' => $product,
                'products' => $products,
                'relatedProducts' => $relatedProducts,
                'newRelatedProduct' => $newRelatedProduct,
                'selectedLanguage' => $selectedLanguage,
            ]
        ]);
    }

    /**
     * Removes relation product
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionRemove(int $id)
    {

        RelatedProduct::deleteAll(['id' => $id]);

        return $this->redirect(\Yii::$app->request->referrer);
    }
}