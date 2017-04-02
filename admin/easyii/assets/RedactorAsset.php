<?php
namespace yii\easyii\assets;

class RedactorAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@easyii/assets/redactor';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->js[] = 'redactor.js';
        $this->css[] = 'redactor.css';
    }

}
