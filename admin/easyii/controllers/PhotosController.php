<?php
namespace yii\easyii\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\easyii\models\Setting;

use yii\easyii\helpers\Image;
use yii\easyii\components\Controller;
use yii\easyii\models\Photo;
use yii\easyii\behaviors\SortableController;

class PhotosController extends Controller
{
    private $width;
    private $height;


    public function init()
    {
        parent::init();
        $size = Setting::get('preview_img_size_gallery');
        $this->width = !empty($size['width']) ? $size['width'] : Photo::PHOTO_THUMB_WIDTH;
        $this->height = !empty($size['height']) ? $size['height'] : Photo::PHOTO_THUMB_HEIGHT;

    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
            [
                'class' => SortableController::className(),
                'model' => Photo::className(),
            ]
        ];
    }

    public function actionUpload($class, $item_id)
    {
        $success = null;

        $photo = new Photo;
        $photo->class = $class;
        $photo->item_id = $item_id;
        $photo->image = UploadedFile::getInstance($photo, 'image');

        if ($photo->image && $photo->validate(['image'])) {
            $photo->image = Image::upload($photo->image, 'photos');

            if ($photo->image) {
                if ($photo->save()) {
                    $success = [
                        'message' => Yii::t('easyii', 'Photo uploaded'),
                        'photo' => [
                            'id' => $photo->primaryKey,
                            'image' => $photo->image,
                            'thumb' => Image::thumb($photo->image, $this->width, $this->height),
                            'description' => ''
                        ]
                    ];
                } else {
                    @unlink(Yii::getAlias('@webroot') . str_replace(Url::base(true), '', $photo->image));
                    $this->error = Yii::t('easyii', 'Create error. {0}', $photo->formatErrors());
                }
            } else {
                $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
            }
        } else {
            $this->error = Yii::t('easyii', 'File is incorrect');
        }

        return $this->formatResponse($success);
    }

    public function actionDescription($id)
    {
        if (($model = Photo::findOne($id))) {
            if (Yii::$app->request->post('description')) {
                $model->description = Yii::$app->request->post('description');
                if (!$model->update()) {
                    $this->error = Yii::t('easyii', 'Update error. {0}', $model->formatErrors());
                }
            } else {
                $this->error = Yii::t('easyii', 'Bad response');
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii', 'Photo description saved'));
    }

    public function actionImage($id)
    {
        $success = null;

        if (($photo = Photo::findOne($id))) {
            $oldImage = $photo->image;

            $photo->image = UploadedFile::getInstance($photo, 'image');

            if ($photo->image && $photo->validate(['image'])) {
                $photo->image = Image::upload($photo->image, 'photos');
                if ($photo->image) {
                    if ($photo->save()) {
                        @unlink(Yii::getAlias('@webroot') . $oldImage);

                        $success = [
                            'message' => Yii::t('easyii', 'Photo uploaded'),
                            'photo' => [
                                'image' => $photo->image,
                                'thumb' => Image::thumb($photo->image, $this->width, $this->height)
                            ]
                        ];
                    } else {
                        @unlink(Yii::getAlias('@webroot') . $photo->image);

                        $this->error = Yii::t('easyii', 'Update error. {0}', $photo->formatErrors());
                    }
                } else {
                    $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
                }
            } else {
                $this->error = Yii::t('easyii', 'File is incorrect');
            }

        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse($success);
    }

    public function actionDelete($id)
    {
        if (($model = Photo::findOne($id))) {
            $file = Yii::getAlias('@webroot') . $model->image;
            if ($model->delete()) {
                if (is_file($file)) {
                    unlink($file);
                }
                $file = str_replace('photos', 'thumbs', $file);
                if (is_file($file)) {
                    unlink($file);
                }
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Photo deleted'));
    }

    public function actionUp($id, $class, $item_id)
    {
        return $this->move($id, 'up', ['class' => $class, 'item_id' => $item_id]);
    }

    public function actionDown($id, $class, $item_id)
    {
        return $this->move($id, 'down', ['class' => $class, 'item_id' => $item_id]);
    }
}
