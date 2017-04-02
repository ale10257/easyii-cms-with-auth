<?php

namespace yii\easyii\controllers;

use yii;
use yii\easyii\models;
use yii\web\BadRequestHttpException;

class SignController extends yii\web\Controller
{
    public $layout = 'empty';
    public $enableCsrfValidation = false;

    public function actionIn()
    {
        $model = new models\LoginForm;

        if (!Yii::$app->user->isGuest || ($model->load(Yii::$app->request->post()) && $model->login())) {
            return $this->redirect(Yii::$app->user->getReturnUrl(['/admin']));
        } else {
            return $this->render('in', [
                'model' => $model,
            ]);
        }
    }

    public function actionUp()
    {
        if (!empty(yii::$app->user->identity)) {
            $model = models\UserModel::findOne(yii::$app->user->identity->id);
        } else {
            $model = new models\UserModel();
            $model->scenario = 'create';
        }

        if ($model->load(yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success_signup', Yii::t('easyii', 'Data saved successfully'));
            } else {
                Yii::$app->session->setFlash('error_signup', 'error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
            }
        }

        return $this->render('up', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new models\PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success_request_password_reset', yii::t('easyii', 'Check your email and follow the instructions'));
            } else {
                Yii::$app->session->setFlash('error_request_password_reset', yii::t('easyii', 'An error has occurred'));
            }
        }

        return $this->render('request_password_reset_token', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword()
    {
        $token = yii::$app->request->get('token');
        $model = new models\ResetPasswordForm();
        if (!$model = $model::findByPasswordResetToken($token)) {
            throw new BadRequestHttpException('Your token not found');
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->password_reset_token = null;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', yii::t('easyii', 'New password saved successfully'));
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error_reset_password', yii::t('easyii', 'An error has occurred'));
            }
        }

        return $this->render('reset_password', [
            'model' => $model,
        ]);
    }

    public function actionOut()
    {
        Yii::$app->user->logout();

        return $this->redirect(Yii::$app->homeUrl);
    }
}
