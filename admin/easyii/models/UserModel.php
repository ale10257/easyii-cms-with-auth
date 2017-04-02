<?php

namespace yii\easyii\models;

use yii;
use yii\easyii\validators\EscapeValidator;

class UserModel extends Admin
{
    public static function tableName()
    {
        return 'easyii_users';
    }

    public function rules()
    {
        /**
         * username, email, password, post_index, region, sity, address, phone
         */
        return [
            [['username', 'email', 'password', 'post_index', 'region', 'sity', 'address', 'phone'], 'trim'],
            [['username',  'email', 'sity', 'address', 'phone'], 'required'],
            ['password', 'required', 'on' => 'create'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => $this, 'message' => yii::t('easyii', 'Email already exists')],
            ['password', 'string', 'min' => 6],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            [['username', 'sity', 'post_index', 'region', 'sity', 'address'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'username' => yii::t('easyii', 'Name and Last name'),
            'password' => yii::t('easyii', 'Password'),
            'post_index' => yii::t('easyii', 'Postcode'),
            'region' => yii::t('easyii', 'Region'),
            'sity' => yii::t('easyii', 'Sity'),
            'address' => yii::t('easyii', 'Address'),
            'phone' => yii::t('easyii', 'Phone'),
        ];
    }
}