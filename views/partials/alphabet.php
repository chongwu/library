<?php
use app\helpers\StringHelper;

$alphabet = StringHelper::mb_range('А', 'Я');
?>
<div class="panel panel-default">
	  <div class="panel-heading">
			<h3 class="panel-title">Алфавитный указатель</h3>
	  </div>
	  <div class="panel-body container-fluid">
			<div class="row">
                <?php foreach ($alphabet as $char): ?>
                    <?= \yii\helpers\Html::a($char, ['/book/code', 'code' => StringHelper::decimalCodeUnicodeChar($char)], ['class' => 'btn btn-default col-xs-2']) ?>
                <?php endforeach; ?>
            </div>
	  </div>
</div>
