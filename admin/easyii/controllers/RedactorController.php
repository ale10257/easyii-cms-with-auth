<?php
namespace yii\easyii\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\easyii\helpers\Image;

class RedactorController extends \yii\easyii\components\Controller
{
    public $controllerNamespace = 'yii\redactor\controllers';
    public $defaultRoute = 'upload';
    public $uploadDir = '@webroot/uploads';
    public $uploadUrl = '/uploads';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ]
        ];
    }

    public function actionUpload()
    {
        $dir = yii::$app->request->get('dir');
        $fileInstance = UploadedFile::getInstanceByName('file');
        if ($fileInstance) {
            $file = Image::upload($fileInstance, $dir);
            if ($file) {
                return $this->setResponse($file);
            }
        }

        return ['error' => 'Unable to save image file'];
    }

    public function actionDelImg()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(403, 'This action allow only ajaxRequest');
        }
        $img = Yii::getAlias('@webroot') . Yii::$app->request->post('img');
        if (is_file($img)) {
            unlink($img);
        }
        $img = str_replace('thumbs', 'pages', $img);
        if (is_file($img)) {
            unlink($img);
        }
    }

    private function setResponse($fileName)
    {
        return [
            'filelink' => $fileName,
            'filename' => basename($fileName)
        ];
    }
}
