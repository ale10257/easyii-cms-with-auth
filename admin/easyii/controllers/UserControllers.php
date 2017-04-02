<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.04.17
 * Time: 11:25
 */

namespace yii\easyii\controllers;

use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\easyii\models\UserModel;

class UserControllers extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex () {

        echo 1;


    }


    /**
     * Signup action
     *
     * @return string|yii\web\Response
     */
    public function actionSignup()
    {

        echo 1;die;

        $model = new UserModel();

        if ($model->load(yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные сохранены успешно');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка сохранения данных');
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}