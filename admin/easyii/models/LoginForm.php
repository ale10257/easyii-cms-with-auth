<?php

namespace yii\easyii\models;

use Yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\validators\EscapeValidator;

class LoginForm extends ActiveRecord
{
    const CACHE_KEY = 'SIGNIN_TRIES';

    private $_user = false;

    public static function tableName()
    {
        return 'easyii_loginform';
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], EscapeValidator::className()],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('easyii', 'Email'),
            'password' => Yii::t('easyii', 'Password'),
            'remember' => Yii::t('easyii', 'Remember me')
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('easyii', 'Incorrect username or password.'));
            }
        }
    }

    public function login()
    {
        $cache = Yii::$app->cache;

        if (($tries = (int)$cache->get(self::CACHE_KEY)) > 5) {
            $this->addError('username', Yii::t('easyii', 'You tried to login too often. Please wait 5 minutes.'));
            return false;
        }

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), Setting::get('auth_time') ? : null);
        } else {
            $cache->set(self::CACHE_KEY, ++$tries, 300);
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->time = time();

        $time = time() - 3600 * 24 * 7;
        $this->deleteAll("time < $time");
        $this->insert(false);

        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByEmail($this->email);
        }

        return $this->_user;
    }
}
