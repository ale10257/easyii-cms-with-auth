<?php
use yii\helpers\Html;

$this->title = $subject;
?>
<p>Пользователь <b><?= $feedback->name ?></b> оставил сообщение в форме обратной связи.</p>
<p>Посмотреть сообщение можно <?= Html::a('здесь', $link) ?>.</p>
