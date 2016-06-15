<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
if(Yii::$app->user->isGuest){
    $this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
}
else{
    $this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['admin']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-2">
            <?= Html::img('/'.$model->getPhotoPath(), ['class' => 'img-responsive']) ?>
        </div>
        <div class="col-sm-10">
            <div class="info">
                <p><strong>Автор: </strong><?= $model->author ?></p>
                <p><strong>Категория: </strong><?= Html::a($model->category->category, ['/category/view', 'id' => $model->category_id]) ?></p>
                <p><?= Html::a('Скачать', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></p>
            </div>
            <div class="description">
                <?= $model->description ?>
            </div>
        </div>
    </div>
</div>
