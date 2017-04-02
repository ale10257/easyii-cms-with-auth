<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>

<?= $form->field($model, 'name')->textInput(['disabled' => !IS_ROOT]) ?>

<? if ($model->name == 'site_enabled') : ?>
    <?= $form->field($model, 'visibility')->radioList(
        [
            2 => 'Сайт включен',
            3 => 'Сайт недоступен для посетителей'
        ]
    )->label('Включить/отключить сайт')
    ?>
<? endif ?>

<?= $form->field($model, 'title')->textarea(['disabled' => !IS_ROOT]) ?>
<?= $form->field($model, 'value')->textarea() ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
