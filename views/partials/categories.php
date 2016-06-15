<?php
use yii\helpers\Html;

$categories = \app\models\Category::find()->joinWith('books')->all();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Категории книг</h3></div>
    <div class="panel-body">
        <?php if(!empty($categories)): ?>
            <ul class="list-group">
            <?php foreach ($categories as $category): ?>
                <li class="list-group-item">
                    <span class="badge"><?= count($category->books) ?></span>
                    <?= Html::a($category->category, ['/category/view', 'id' => $category->id]) ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h4>Пока не добавлено ни одной категории.</h4>
            <?php if(!Yii::$app->user->isGuest): ?>
                <?= Html::a('Добавить', ['/category/create'], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>