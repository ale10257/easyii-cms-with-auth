<?php
namespace yii\easyii\models;

use Yii;

class Admin extends \yii\easyii\components\ActiveRecord implements \yii\web\IdentityInterface
{
    static $rootUser = [
        'admin_id' => -1,
    ];

    static $adminUser = [
        'admin_id' => 1,
    ];

    public static function tableName()
    {
        return 'easyii_users';
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username', 'email', 'password'], 'trim'],
            ['password', 'required', 'on' => 'create'],
            ['password', 'safe'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => $this, 'message' => yii::t('easyii', 'Email already exists')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('easyii', 'Username'),
            'password' => Yii::t('easyii', 'Password'),
            'email' => Yii::t('easyii', 'Email'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->auth_key) {
                    $this->auth_key = $this->generateAuthKey();
                }
                $this->password = $this->hashPassword($this->password);
            } else {
                $this->password = $this->password != '' ? $this->hashPassword($this->password) : $this->oldAttributes['password'];
            }
            return true;
        } else {
            return false;
        }
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        if (!$this->auth_key) {
            $this->auth_key = $this->generateAuthKey();
        }
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $this->hashPassword($password);
    }

    protected function hashPassword($password)
    {
        return sha1($password . $this->getAuthKey() . Setting::get('password_salt'));
    }

    function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }

    public function isRoot()
    {
        return $this->admin_id === self::$rootUser['admin_id'];
    }

    public function isAdmin()
    {
        return $this->admin_id === self::$adminUser['admin_id'];
    }

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 3600;
        return $timestamp + $expire >= time();
    }
}
