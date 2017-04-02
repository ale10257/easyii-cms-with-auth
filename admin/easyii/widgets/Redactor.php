<?php
namespace yii\easyii\widgets;

use Yii;
use yii\easyii\helpers\Data;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\AssetBundle;

use yii\easyii\assets\RedactorAsset;

class Redactor extends InputWidget
{
    public $options = [];

    private $_optionsExt = [
        'minHeight' => 400,
        'toolbarFixed' => true,
        'toolbarFixedBox' => true,
        'plugins' => [
            'table',
            'fontcolor',
            'fontsize',
            'video',
            'fullscreen',
        ],
        'imageUpload' => '/admin/redactor/upload',
        'fileUpload' => '/admin/redactor/upload',
    ];

    public $action_img;
    public $action_file;

    private $_assetBundle;

    public function init()
    {
        $this->options = array_merge($this->_optionsExt, $this->options);

        if (!empty($this->action_img)) {
            $this->options['imageUpload'] = $this->options['imageUpload'] . '?dir=' . $this->action_img;
        }

        if (!empty($this->action_file)) {
            $this->options['fileUpload'] = $this->options['fileUpload'] . '?dir=' . $this->action_file;
        }

        if (isset($this->options['imageUpload'])) {
            $this->options['imageUploadErrorCallback'] = new JsExpression("function(json){alert(json.error);}");
            $this->options['imageDeleteCallback'] = new JsExpression("function(url, image){
                $.post('/admin/redactor/del-img', {img:image.attr('src') }); 
            }");

        }
        if (isset($this->options['fileUpload'])) {
            $this->options['fileUploadErrorCallback'] = new JsExpression("function(json){alert(json.error);}");
        }
        $this->registerAssetBundle();
        $this->registerRegional();
        $this->registerPlugins();
        $this->registerScript();
    }

    public function run()
    {
        echo Html::activeTextarea($this->model, $this->attribute);
    }

    public function registerRegional()
    {
        $lang = Data::getLocale();
        if ($lang != 'en') {
            $js = yii::getAlias('@easyii') . '/assets/redactor/' . $lang . '.js';
            if (is_file($js)) {
                $langAsset = $lang . '.js';
                $this->assetBundle->js[] = $langAsset;
                $this->options['lang'] = $lang;
            }
        }
    }

    public function registerPlugins()
    {
        $this->assetBundle->js[] = 'redactor_plugin.js';
        $this->assetBundle->css[] = 'redactor.css';
    }

    public function registerScript()
    {
        $clientOptions = (count($this->options)) ? Json::encode($this->options) : '';
        $this->getView()->registerJs("jQuery('#" . Html::getInputId($this->model,
                $this->attribute) . "').redactor({$clientOptions});");
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = RedactorAsset::register($this->getView());
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }

        return $this->_assetBundle;
    }

}
