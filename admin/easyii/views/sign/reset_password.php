<?php
/**
 * @var $this yii\web\View
 * @var $model \yii\easyii\models\ResetPasswordForm
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use  yii\bootstrap\Alert;

$this->title = 'Reset password';
$asset = \yii\easyii\assets\EmptyAsset::register($this);
?>
<div class="site-reset-password">
    <div id="wrapper" class="col-xs-4 col-xs-offset-4 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= yii::t('easyii', 'Please choose your new password') ?>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'value' => '']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <?php $session = Yii::$app->session; ?>
                    <?php if ($session->hasFlash('error_reset_password')) : ?>
                        <?=
                        Alert::widget([
                            'options' => [
                                'class' => 'alert-danger',
                            ],
                            'body' => $session->getFlash('error_reset_password'),
                        ]);
                        ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="text-center">
                <a class="logo" href="http://easyiicms.com" target="_blank" title="EasyiiCMS homepage">
                    <img src="<?= $asset->baseUrl ?>/img/logo_20.png">EasyiiCMS
                </a>
            </div>
        </div>
    </div>
</div>
