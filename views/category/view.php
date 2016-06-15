<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->category;
if(!Yii::$app->user->isGuest){
    $this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить категорию?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>
    
    <h3>Список книг:</h3>
    <?= $this->render('/book/list', ['dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $model->books])]) ?>
</div>
