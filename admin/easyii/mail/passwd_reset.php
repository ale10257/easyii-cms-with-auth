<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user yii\easyii\models\PasswordResetRequestForm */

$resetLink = Url::to('/admin/sign/reset-password?token=' . $user->password_reset_token, true);

?>
<div class="password-reset">
    <h3><?= yii::t('easyii', 'Hello') ?> <?= Html::encode($user->username) ?>!</h3>
    <p><?= yii::t('easyii', 'Follow the link below to reset your password:') ?> <?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>