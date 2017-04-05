<?php
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>

<?php $this->beginPage() ?>
    <!doctype html>
    <html lang="<?= Yii::$app->sourceLanguage; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset; ?>">
        <?php $this->head() ?>
    </head>
    <body>
    <div style="width: 100%; background: #48b2ff; padding: 10px 0;">
        <?php $this->beginBody() ?>
        <div style="width: 700px; margin: 0 auto; background: #fff; padding: 15px;">
            <?= $content ?>
        </div>
        <?php $this->endBody() ?>
    </div>
    </body>
    </html>
<?php $this->endPage() ?>