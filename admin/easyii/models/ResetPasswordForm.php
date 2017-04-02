<?php
namespace yii\easyii\models;

use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Admin
{
    public static function tableName()
    {
        return 'easyii_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
}
