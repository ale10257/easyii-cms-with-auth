<?php
namespace yii\easyii\models;

use yii;

use yii\easyii\helpers\Data;
use yii\easyii\behaviors\CacheFlush;

class Setting extends \yii\easyii\components\ActiveRecord
{
    const VISIBLE_NONE = 0;
    const VISIBLE_ROOT = 1;
    const VISIBLE_ALL = 2;

    const CACHE_KEY = 'easyii_settings';

    static $_data;

    public static function tableName()
    {
        return 'easyii_settings';
    }

    public function rules()
    {
        return [
            [['name', 'title', 'value',], 'required'],
            [['name', 'title', 'value'], 'trim'],
            ['name', 'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['name', 'unique'],
            ['visibility', 'number', 'integerOnly' => true]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'value' => Yii::t('easyii', 'Value'),
            'visibility' => Yii::t('easyii', 'Only for developer')
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }

    public static function get($name)
    {
        if (!self::$_data) {
            self::$_data = Data::cache(self::CACHE_KEY, 3600, function () {
                $result = [];
                try {
                    foreach (self::find()->all() as $setting) {
                        switch ($setting->name) {
                            /*case 'site_enabled' :
                                $result[$setting->name] = $setting->visibility == 2 ? true : $setting->value;
                                break;*/
                            case ($setting->name == 'data_slider_on_main_page' || $setting->name == 'preview_img_size_gallery') :
                                preg_match_all('|\d{2,4}|', $setting->value, $t);
                                //TODO checking $t!
                                $result[$setting->name]['width'] = $t[0][0];
                                $result[$setting->name]['height'] = $t[0][1];
                                break;
                            /*case 'phones' :
                                $result['phones'] = explode('#', $setting->value);
                                foreach ($result['phones'] as $key => $val) {
                                    $result['phones'][$key] = trim($val);
                                }
                                break;*/
                            default :
                                $result[$setting->name] = $setting->value;
                        }
                    }
                } catch (\yii\db\Exception $e) {
                }
                return $result;
            });
        }

        return isset(self::$_data[$name]) ? self::$_data[$name] : null;
    }

    public static function set($name, $value)
    {
        if (self::get($name)) {
            $setting = Setting::find()->where(['name' => $name])->one();
            $setting->value = $value;
        } else {
            $setting = new Setting([
                'name' => $name,
                'value' => $value,
                'title' => $name,
                'visibility' => self::VISIBLE_ALL
            ]);
        }
        $setting->save();
    }
}
