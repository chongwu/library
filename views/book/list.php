<?php
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php Pjax::begin(); ?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'book-item'],
    'layout' => "{items}\n{pager}",
    'itemView' => '/book/_view',
]) ?>
<?php Pjax::end(); ?>