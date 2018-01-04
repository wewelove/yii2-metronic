<?php
use yii\helpers\Html;
use iamok\metronic\widgets\Breadcrumbs;
use iamok\metronic\widgets\Button;
?>

<div class="breadcrumbs hide">
    <div class="container-fluid">
        <h2 class="breadcrumbs-title"><?= $this->title ?></h2>
        <?= Breadcrumbs::widget([
            'tag' => 'ol',
            'options' => ['class' => 'breadcrumbs-list'],
            'itemTemplate' => "<li>{link}</li>\n",
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
</div>

<div class="container-fluid container-lf-space margin-top-30">
    <?= $content ?>
</div>
