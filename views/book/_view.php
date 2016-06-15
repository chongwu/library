<?php
/**
 * @var $model \app\models\Book
 */
?>
<div class="row">
    <div class="col-sm-2">
        <?= \yii\helpers\Html::img('/'.$model->getPhotoPath(), ['class' => 'img-responsive']) ?>
    </div>
    <div class="col-sm-10">
        <h3><?= \yii\helpers\Html::a($model->title, ['/book/view', 'id' => $model->id]) ?></h3>
        <div class="short-description"><?= \app\helpers\StringHelper::truncateWords($model->description, 30) ?></div>
    </div>
</div>
