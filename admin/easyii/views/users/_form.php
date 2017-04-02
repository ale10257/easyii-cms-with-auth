<?php
/**
 * @var $model \yii\easyii\models\UserModel
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<? foreach ($model->attributeLabels() as $k => $v) : ?>
    <?php if ($k != 'password') : ?>
        <?= $form->field($model, $k) ?>
    <? endif ?>
<? endforeach ?>
<?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
