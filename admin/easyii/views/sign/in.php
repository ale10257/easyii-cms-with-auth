<?php
/**
 * @var $this yii\web\View
 * @var $model yii\easyii\models\LoginForm
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$asset = \yii\easyii\assets\EmptyAsset::register($this);
$this->title = Yii::t('easyii', 'Sign in');
?>
<div class="container">
    <div id="wrapper" class="col-md-4 col-md-offset-4 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= Yii::t('easyii', 'Sign in') ?>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin()
                    ?>
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('easyii', 'Email')]) ?>
                    <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('easyii', 'Password')]) ?>
                    <?= Html::submitButton(Yii::t('easyii', 'Login'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
                    <?php ActiveForm::end(); ?>

                    <p><?= Html::a(Yii::t('easyii', 'Forgot  password?'), '/admin/sign/request-password-reset') ?></p>

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
