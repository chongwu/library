<?php

use app\models\Book;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-admin">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=Html::beginForm(['book/change-category']);?>
    <?=Html::dropDownList(
        'category',
        null,
        \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'category'),
        [
            'class'=>'form-control',
            'style' => 'width: 200px; display: inline;',
            'prompt' => 'Выберите категорию'
        ]
    )?>
    <?=Html::submitButton('Изменить', ['class' => 'btn btn-info',]);?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                // you may configure additional properties here
            ],

            [
                'attribute' => 'photo',
                'format' => 'html',
                'value' => function(Book $book){
                    return Html::img('/'.$book->getPhotoPath(), ['class' => 'img-responsive']);
                }
            ],
            'title',
            'author',
            [
                'attribute' => 'category_id',
                'value' => function(Book $book){
                    return $book->category->category;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?= Html::endForm() ?>
</div>
