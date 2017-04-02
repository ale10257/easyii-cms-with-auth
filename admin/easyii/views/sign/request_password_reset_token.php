<?php

/* @var $this yii\web\View */
/* @var $model yii\easyii\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$asset = \yii\easyii\assets\EmptyAsset::register($this);
$this->title = yii::t('easyii', 'Request password reset');
?>
<div id="wrapper" class="col-xs-4 col-xs-offset-4 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= yii::t('easyii', 'Enter email to reset password') ?>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <?php $session = Yii::$app->session; ?>

                    <?php if ($session->hasFlash('success_request_password_reset')) : ?>
                        <?=
                        Alert::widget([
                            'options' => ['class' => 'alert-success',],
                            'body' => $session->getFlash('success_request_password_reset'),
                        ]);
                        ?>
                    <?php endif ?>

                    <?php if ($session->hasFlash('error_request_password_reset')) : ?>
                        <?=
                        Alert::widget([
                            'options' => [
                                'class' => 'alert-danger',
                            ],
                            'body' => $session->getFlash('error_request_password_reset'),
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

