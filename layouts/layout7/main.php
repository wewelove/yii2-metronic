<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use iamok\metronic\Metronic;
use iamok\metronic\helpers\Layout;

$asset = Metronic::registerThemeAsset($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl($asset->sourcePath);
?>
<?php $this->beginPage() ?>
<!--[if IE 8]> <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body <?= Layout::getHtmlOptions('body', [], true) ?> >
<?php $this->beginBody() ?>

    <?= $this->render('parts/header.php', ['directoryAsset' => $directoryAsset]) ?>

    <div class="page-container page-content-inner page-container-bg-solid">

        <?= $this->render('parts/content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]) ?>

        <?= $this->render('parts/sidebar.php', ['directoryAsset' => $directoryAsset]) ?>

    </div>

    <?= $this->render('parts/footer.php', ['directoryAsset' => $directoryAsset]) ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>