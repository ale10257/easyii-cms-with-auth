<?php
namespace yii\easyii\models;

use Yii;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Admin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'yii\easyii\models\UserModel',
                'message' => yii::t('easyii', 'There is no user with such email'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $user = self::findByEmail($this->email);

        if (!$user) {
            return false;
        }

        if (!self::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => '@easyii/mail/passwd_reset'],
                ['user' => $user]
            )
            ->setFrom('asd@fgd.ru')
            ->setTo($this->email)
            ->setSubject(yii::t('easyii', 'Password reset') . ' ' . yii::t('easyii', 'on site') . ' ' . $_SERVER['SERVER_NAME'])
            ->send();
    }
}
