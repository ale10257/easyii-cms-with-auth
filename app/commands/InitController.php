<?php

namespace app\commands;

use yii;
use yii\console\Controller;
use yii\validators\EmailValidator;
use yii\easyii\models\Admin;
use yii\easyii\models\Setting;

class InitController extends Controller
{
    public function actionIndex()
    {
        $db = yii::$app->db;
        $password_salt = Setting::get('password_salt');
        $auth_key = Yii::$app->security->generateRandomString();

        $validator = new EmailValidator();

        echo 'Please enter email for Root user: ';
        $email = trim(fgets(STDIN));

        if (!$validator->validate($email, $error)) {
            echo $error . PHP_EOL;
            return 1;
        }

        echo 'Please enter password for Root user: ';

        $root_password = trim(fgets(STDIN));

        $root_password = sha1($root_password . $auth_key . $password_salt);

        $res = $db->createCommand()->insert(Admin::tableName(), [
            'admin_id' => 0,
            'username' => 'root',
            'auth_key' => $auth_key,
            'password' => $root_password,
            'email' => $email
        ])->execute();

        if ($res) {
            echo 'Initialization was successful.' . PHP_EOL;
        } else {
            return 2;
        }

        return 0;
    }
}