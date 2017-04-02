<?php
namespace yii\easyii\helpers;

use Yii;
use yii\easyii\models\Setting;

class Mail
{
    public static function send($toEmail, $subject, $template = [], $data = [], $options = [])
    {
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL) || !$subject) {
            return false;
        }
        $data['subject'] = trim($subject);

        if ($template) {
            $message = Yii::$app->mailer->compose($template, $data);
        } else {
            if (!empty($options['htmlBody'])) {
                $message = Yii::$app->mailer->compose()->setHtmlBody($options['htmlBody']);
            } else return false;
        }

        $message->setTo($toEmail)->setSubject($data['subject']);

        if (filter_var(Setting::get('robot_email'), FILTER_VALIDATE_EMAIL)) {
            $message->setFrom(Setting::get('robot_email'));
        }

        if (!empty($options['replyTo']) && filter_var($options['replyTo'], FILTER_VALIDATE_EMAIL)) {
            $message->setReplyTo($options['replyTo']);
        }

        return $message->send();
    }
}
