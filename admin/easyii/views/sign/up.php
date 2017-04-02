<?php
/**
 * @var $this yii\web\View
 * @var $model yii\easyii\models\UserModel
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$asset = \yii\easyii\assets\EmptyAsset::register($this);
$this->title = Yii::t('easyii', 'New user registration');
?>

<div class="container">
    <div id="wrapper" class="col-xs-6 col-xs-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= $this->title ?>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <? foreach ($model->attributeLabels() as $k => $v) : ?>
                        <?php if ($k != 'password') : ?>
                            <?= $form->field($model, $k) ?>
                        <? endif ?>
                    <? endforeach ?>
                    <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    <?php $session = Yii::$app->session; ?>
                    <?php if ($session->hasFlash('success_signup')) : ?>
                        <?=
                        Alert::widget([
                            'options' => ['class' => 'alert-success',],
                            'body' => $session->getFlash('success_signup') .
                                '<p>' .
                                Html::a(
                                    yii::t('easyii', 'Log in'),
                                    '/admin/sign/in'
                                ) .
                                '</p>',
                        ]);
                        ?>
                    <?php endif ?>

                    <?php if ($session->hasFlash('error_signup')) : ?>
                        <?=
                        Alert::widget([
                            'options' => [
                                'class' => 'alert-danger',
                            ],
                            'body' => $session->getFlash('error_signup'),
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
