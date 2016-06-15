<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список найденных книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="find-books">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('/book/list', ['dataProvider' => $dataProvider]) ?>
</div>

