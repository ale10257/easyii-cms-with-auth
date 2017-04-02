<?php
/**
 * @var $model \yii\easyii\models\UserModel
 */
$this->title = Yii::t('easyii', 'Edit user');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>