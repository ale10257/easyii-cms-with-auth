<?php
namespace yii\easyii\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\web\HttpException;

class Image
{
    public static function upload(UploadedFile $fileInstance, $dir = '', $resizeWidth = null, $resizeHeight = null, $resizeCrop = false)
    {
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance);
        $uploaded = $resizeWidth
            ? self::copyResizedImage($fileInstance->tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop)
            : $fileInstance->saveAs($fileName);

        if (!$uploaded) {
            throw new HttpException(500, 'Cannot upload file "' . $fileName . '". Please check write permissions.');
        }

        return Upload::getLink($fileName);
    }

    static function thumb($filename, $width = null, $height = null, $crop = true)
    {
        if (is_file($filename) || is_file(($filename = Yii::getAlias('@webroot') . $filename))) {
            $info = pathinfo($filename);
            $thumbName = $info['basename'];
            $thumbWebFile = '/' . Upload::$UPLOADS_DIR . '/thumbs/' . $thumbName;
            $thumbFile = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $thumbName;
            //$thumbFile = str_replace(['pages', 'photos'], 'thumbs', $filename);
            if (is_file($thumbFile)) {
                list($w, $h) = getimagesize($thumbFile);
                if ($w == $width && $h == $height) {
                    return $thumbWebFile;
                }
            }
            self::copyResizedImage($filename, $thumbFile, $width, $height, $crop);
            return $thumbWebFile;
        }
        return '';
    }

    static function copyResizedImage($inputFile, $outputFile, $width, $height = null, $crop = true)
    {
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            if (extension_loaded('imagick')) {
                $image = new \Imagick($inputFile);
                if ($height && !$crop) {
                    $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
                } else {
                    $image->resizeImage($width, null, \Imagick::FILTER_LANCZOS, 1);
                }
                if ($height && $crop) {
                    $image->cropThumbnailImage($width, $height);
                }
                return $image->writeImage($outputFile);
            } else {
                $image = new GD($inputFile);
                if ($height) {
                    if ($width && $crop) {
                        $image->cropThumbnail($width, $height);
                    } else {
                        $image->resize($width, $height);
                    }
                } else {
                    $image->resize($width);
                }
                return $image->save($outputFile);
            }
        } else {
            throw new HttpException(500, 'Please install GD or Imagick extension');
        }
    }

    static function checkSizeFile($file, $width, $height)
    {
        if (is_file($file_path = Yii::getAlias('@webroot') . $file)) {
            list($w, $h) = getimagesize($file_path);
            if ($w == $width && $h == $height) {
                return $file;
            } else {
                if (self::copyResizedImage($file_path, $file_path, $width, $height)) {
                    return $file;
                }
            }
        }
        return null;
    }
}
