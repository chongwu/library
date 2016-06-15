<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'category'),
        [
            'prompt' => 'Выберите категорию'
        ]
    ) ?>

    <?= $form->field($model, 'photo')->fileInput([
        'accept' => 'image/*',
    ]) ?>
    <?php if(!$model->isNewRecord): ?>
        <div id="uploaded-photo" class="form-group">
            <?= Html::img('/'.$model->getPhotoPath(), ['class' => 'img-responsive']) ?>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'file')->fileInput([
        'accept' => 'application/msword,application/plain,application/pdf',
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
