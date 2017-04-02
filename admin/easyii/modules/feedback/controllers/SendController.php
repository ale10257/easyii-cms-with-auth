<?php
namespace yii\easyii\modules\feedback\controllers;

use Yii;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {



        $model = new FeedbackModel;

        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }


/*        $model = new FeedbackModel;
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            $admin_email = yii::$app->params['admin_email'];
            $data = [
                'title' => $model->title,
                'name' => $model->name,
                'email' => $model->email,
                'text' => $model->text,
                'phone' => !empty($model->phone) ? $model->phone : '',
            ];
            $m = yii::$app->mailer->compose('@easyii/modules/feedback/mail/ru/new_feedback', $data)
                ->setTo($admin_email)
                ->setSubject('Сообщение с формы обратной связи')
                ->setFrom($admin_email)
                ->setReplyTo($model->email)
                ->setTextBody($model->text);
            $returnUrl = $m->send() ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }*/
    }
}
