<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $parents \yii\db\ActiveRecord
 *
 * @var $form \yii\widgets\ActiveForm
 * @var $model \yii\base\Model
 * @var $attribute string
 * @var $languageId integer
 */
//die(var_dump($model));
?>

<div id="input-tree" data-current-category="<?=$model->$attribute ?? 0; ?>">
    <ul class="input-tree-ul">
        <li>
            <?= $form->field($model, $attribute,
                ['template' => "{input}\n{label}"]
            )->input('radio', [
                (!$model->$attribute ? 'checked' : '') =>
                    (!$model->$attribute ? 'checked' : ''),
                'value' => false,
                'class' => 'radio',
                'id' => $model::className() . '-category_id-null',
            ])->label(Yii::t('shop', 'Without category'), [
                'for' => $model::className() . '-category_id-' . '-category_id-null',
            ]); ?>
        </li>
    </ul>
    <?= $this->render(
        '@vendor/sointula/yii2-shop/widgets/views/input-tree/ul',
        [
            'parents' => $parents,
            'form' => $form,
            'model' => $model,
            'attribute' => $attribute,
            'languageId' => $languageId
        ]);
    ?>
</div>