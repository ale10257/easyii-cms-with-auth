<?php

use yii\db\Migration;

use yii\easyii\models;

use yii\easyii\modules\catalog;
use yii\easyii\modules\shopcart;
use yii\easyii\modules\article;
use yii\easyii\modules\carousel;
use yii\easyii\modules\faq;
use yii\easyii\modules\feedback;
use yii\easyii\modules\file;
use yii\easyii\modules\gallery;
use yii\easyii\modules\guestbook;
use yii\easyii\modules\news;
use yii\easyii\modules\page;
use yii\easyii\modules\subscribe;
use yii\easyii\modules\text;

class m170330_124953_install extends Migration
{
    const VERSION = 0.9;

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function safeUp()
    {
        //USERS
        $this->createTable(models\Admin::tableName(), [
            'id' => $this->primaryKey(),
            'admin_id' => $this->smallInteger()->null(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password' => $this->string(64)->notNull(),
            'password_reset_token' => $this->string(256)->null(),
            'email' => $this->string()->null()->unique(),
            'phone' => $this->string()->null(),
            'sity' => $this->string()->null(),
            'address' => $this->string()->null(),
            'region' => $this->string()->null(),
            'post_index' => $this->integer()->null(),
        ], $this->engine);

        //LOGINFORM
        $this->createTable(models\LoginForm::tableName(), [
            'log_id' => 'pk',
            'email' => $this->string(128)->notNull(),
            'password' => $this->string(128)->notNull(),
            'ip' => $this->string(16)->notNull(),
            'user_agent' => $this->string(1024)->notNull(),
            'time' => $this->integer()->defaultValue(0),
            'success' => $this->boolean()->defaultValue(0),
        ], $this->engine);

        //MODULES
        $this->createTable(models\Module::tableName(), [
            'module_id' => 'pk',
            'name' => $this->string(64)->notNull(),
            'class' => $this->string(128)->notNull(),
            'title' => $this->string(128)->notNull(),
            'settings' => $this->text()->notNull(),
            'icon' => $this->string(32)->notNull(),
            'notice' => $this->integer()->defaultValue(0),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(0),
            'table_name' => $this->string(256)->null(),
        ], $this->engine);
        $this->createIndex('name', models\Module::tableName(), 'name', true);

        //PHOTOS
        $this->createTable(models\Photo::tableName(), [
            'photo_id' => 'pk',
            'class' => $this->string(128)->notNull(),
            'item_id' => $this->integer()->notNull(),
            'image' => $this->string(128)->notNull(),
            'description' => $this->string(1024)->notNull(),
            'order_num' => $this->integer()->notNull(),
        ], $this->engine);
        $this->createIndex('model_item', models\Photo::tableName(), ['class', 'item_id']);

        //SEOTEXT
        $this->createTable(models\SeoText::tableName(), [
            'seotext_id' => 'pk',
            'class' => $this->string(128)->notNull()->unique(),
            'item_id' => $this->integer()->notNull()->unique(),
            'h1' => $this->string(128)->null(),
            'title' => $this->string(128)->null(),
            'keywords' => $this->string(128)->null(),
            'description' => $this->string(128)->null(),
        ], $this->engine);

        //SETTINGS
        $this->createTable(models\Setting::tableName(), [
            'setting_id' => 'pk',
            'name' => $this->string(64)->notNull()->unique(),
            'title' => $this->string(128)->null(),
            'value' => $this->string(1024)->null(),
            'visibility' => $this->boolean()->defaultValue(0),
        ], $this->engine);

        //CAROUSEL MODULE
        $this->createTable(carousel\models\Carousel::tableName(), [
            'carousel_id' => 'pk',
            'image' => $this->string(128)->notNull(),
            'link' => $this->string(256)->notNull(),
            'title' => $this->string(128)->null(),
            'text' => $this->text()->null(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1),
        ], $this->engine);

        //CATALOG MODULE
        $this->createTable(catalog\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128)->null(),
            'fields' => $this->text()->null(),
            'slug' => $this->string(128)->null()->unique(),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        $this->createTable(catalog\models\Item::tableName(), [
            'item_id' => 'pk',
            'category_id' => $this->integer(),
            'title' => $this->string(128)->notNull(),
            'description' => $this->text()->null(),
            'available' => $this->integer()->defaultValue(1),
            'price' => $this->float()->defaultValue(0),
            'discount' => $this->integer()->null(),
            'data' => $this->text()->notNull(),
            'image' => $this->string(128)->null(),
            'slug' => $this->string(128)->unique(),
            'time' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        $this->createTable(catalog\models\ItemData::tableName(), [
            'data_id' => 'pk',
            'item_id' => $this->integer(),
            'name' => $this->string(128)->notNull(),
            'value' => $this->string(1024)->null(),
        ], $this->engine);
        $this->createIndex('item_id_name', catalog\models\ItemData::tableName(), ['item_id', 'name']);
        $this->createIndex('value', catalog\models\ItemData::tableName(), 'value(300)');

        //SHOPCART MODULE
        $this->createTable(shopcart\models\Order::tableName(), [
            'order_id' => 'pk',
            'name' => $this->string(64)->notNull(),
            'address' => $this->string(256)->notNull(),
            'phone' => $this->string(64)->notNull(),
            'email' => $this->string(128)->notNull(),
            'comment' => $this->string(1024)->notNull(),
            'remark' => $this->string(1024)->notNull(),
            'access_token' => $this->string(32)->notNull(),
            'ip' => $this->string(16)->notNull(),
            'time' => $this->integer()->defaultValue(0),
            'new' => $this->boolean()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        $this->createTable(shopcart\models\Good::tableName(), [
            'good_id' => 'pk',
            'order_id' => $this->integer(),
            'item_id' => $this->integer(),
            'count' => $this->integer(),
            'options' => $this->string(256)->notNull(),
            'price' => $this->float()->defaultValue(0),
            'discount' => $this->integer()->defaultValue(0),
        ], $this->engine);

        //FEEDBACK MODULE
        $this->createTable(feedback\models\Feedback::tableName(), [
            'feedback_id' => 'pk',
            'name' => $this->string(64)->notNull(),
            'email' => $this->string(128)->notNull(),
            'phone' => $this->string(64)->null(),
            'title' => $this->string(128)->null(),
            'text' => $this->text()->notNull(),
            'answer_subject' => $this->string(128)->null(),
            'answer_text' => $this->text()->null(),
            'time' => $this->integer()->defaultValue(0),
            'ip' => $this->string(16)->notNull(),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        //FILE MODULE
        $this->createTable(file\models\File::tableName(), [
            'file_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'file' =>$this->string(256)->notNull(),
            'size' => $this->integer()->notNull(),
            'slug' => $this->string(128)->null()->unique(),
            'downloads' => $this->integer()->defaultValue(0),
            'time' => $this->integer()->defaultValue(0),
            'order_num' =>$this->integer(),
        ], $this->engine);

        //GALLERY MODULE
        $this->createTable(gallery\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128)->null(),
            'slug' => $this->string(128)->null()->unique(),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        //GUESTBOOK MODULE
        $this->createTable(guestbook\models\Guestbook::tableName(), [
            'guestbook_id' => 'pk',
            'name' => $this->string(128)->notNull(),
            'title' => $this->string(128)->null(),
            'text' => $this->text()->notNull(),
            'answer' => $this->text()->null(),
            'email' => $this->string(128)->null(),
            'time' => $this->integer()->defaultValue(0),
            'ip' => $this->string(16)->notNull(),
            'new' => $this->boolean()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        //NEWS MODULE
        $this->createTable(news\models\News::tableName(), [
            'news_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128)->null(),
            'short' => $this->string(1024)->null(),
            'text' => $this->text()->notNull(),
            'slug' => $this->string(128)->null()->unique(),
            'time' => $this->integer()->defaultValue(0),
            'views' =>$this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        //ARTICLE MODULE
        $this->createTable(article\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128)->null(),
            'order_num' => $this->integer(),
            'slug' => $this->string(128)->null()->unique(),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        $this->createTable(article\models\Item::tableName(), [
            'item_id' => 'pk',
            'category_id' => $this->integer(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128)->null(),
            'short' => $this->string(1024)->null(),
            'text' => $this->text()->notNull(),
            'slug' => $this->string(128)->null()->unique(),
            'time' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        //PAGE MODULE
        $this->createTable(page\models\Page::tableName(), [
            'page_id' => 'pk',
            'title' => $this->string(128)->notNull(),
            'text' => $this->text()->notNull(),
            'slug' => $this->string(128)->null()->unique()
        ], $this->engine);

        //FAQ MODULE
        $this->createTable(faq\models\Faq::tableName(), [
            'faq_id' => 'pk',
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        //SUBSCRIBE MODULE
        $this->createTable(subscribe\models\Subscriber::tableName(), [
            'subscriber_id' => 'pk',
            'email' => $this->string(128)->notNull()->unique(),
            'ip' => $this->string(16)->notNull(),
            'time' => $this->integer()->defaultValue(0)
        ], $this->engine);

        $this->createTable(subscribe\models\History::tableName(), [
            'history_id' => 'pk',
            'subject' => $this->string(128)->notNull(),
            'body' => $this->text()->notNull(),
            'sent' => $this->integer()->defaultValue(0),
            'time' => $this->integer()->defaultValue(0)
        ], $this->engine);

        //TEXT MODULE
        $this->createTable(text\models\Text::tableName(), [
            'text_id' => 'pk',
            'text' => $this->text()->notNull(),
            'slug' => $this->string(128)->null()->unique()
        ], $this->engine);

        //Tags
        $this->createTable(models\Tag::tableName(), [
            'tag_id' => 'pk',
            'name' => $this->string(128)->notNull()->unique(),
            'frequency' => $this->integer()->defaultValue(0)
        ], $this->engine);

        $this->createTable(models\TagAssign::tableName(), [
            'class' => $this->string(128)->notNull(),
            'item_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $this->engine);
        $this->createIndex('class', models\TagAssign::tableName(), 'class');
        $this->createIndex('item_tag', models\TagAssign::tableName(), ['item_id', 'tag_id']);

        //INSERT VERSION
        $this->delete(models\Setting::tableName(), ['name' => 'easyii_version']);
        $this->insert(models\Setting::tableName(), [
            'name' => 'easyii_version',
            'value' => self::VERSION,
            'title' => 'EasyiiCMS version',
            'visibility' => models\Setting::VISIBLE_NONE
        ]);

        //INSERT DATA SETTINGS

        $password_salt = Yii::$app->security->generateRandomString();

        $this->insert(models\Setting::tableName(), [
            'name' => 'recaptcha_key',
            'title' => 'ReCaptcha key',
            'visibility' => models\Setting::VISIBLE_ROOT
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'password_salt',
            'value' => $password_salt,
            'title' => 'Password salt',
            'visibility' => models\Setting::VISIBLE_NONE
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'auth_time',
            'value' => 86400,
            'title' => 'Authentication time',
            'visibility' => models\Setting::VISIBLE_ROOT
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'robot_email',
            'title' => 'Root E-mail',
            'visibility' => models\Setting::VISIBLE_ROOT
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'recaptcha_secret',
            'title' => 'ReCaptcha secret',
            'visibility' => models\Setting::VISIBLE_ROOT
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'admin_email',
            'title' => Yii::t('easyii', 'Email for messages from the site'),
            'visibility' => models\Setting::VISIBLE_ALL
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'toolbar_position',
            'value' => 'top',
            'title' => 'Frontend toolbar position ("top" or "bottom")',
            'visibility' => models\Setting::VISIBLE_NONE
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'preview_img_size_gallery',
            'value' => '210x150',
            'title' => yii::t('easyii', 'Picture size for preview in the gallery'),
            'visibility' => models\Setting::VISIBLE_ALL
        ]);

        $this->insert(models\Setting::tableName(), [
            'name' => 'data_slider_on_main_page',
            'value' => '1170x500',
            'title' => yii::t('easyii', 'Dimensions of slides in the carousel'),
            'visibility' => models\Setting::VISIBLE_ALL
        ]);

        //INSERT MODULES SETTINGS

        $language = substr(Yii::$app->language, 0, 2);

        $arr_conf = [
            'catalog' => catalog\CatalogModule::$installConfig,
            'shopcart' => shopcart\ShopcartModule::$installConfig,
            'article' => article\ArticleModule::$installConfig,
            'carousel' => carousel\CarouselModule::$installConfig,
            'faq' => faq\FaqModule::$installConfig,
            'feedback' => feedback\FeedbackModule::$installConfig,
            'file' => file\FileModule::$installConfig,
            'gallery' => gallery\GalleryModule::$installConfig,
            'guestbook' => guestbook\GuestbookModule::$installConfig,
            'news' => news\NewsModule::$installConfig,
            'page' => page\PageModule::$installConfig,
            'subscribe' => subscribe\SubscribeModule::$installConfig,
            'text' => text\TextModule::$installConfig,
        ];
        $data = [];
        foreach ($arr_conf as $name => $conf) {
            $class = 'yii\easyii\modules\\' . $name . '\\' . ucfirst($name) . 'Module';
            $title = !empty($conf['title'][$language]) ? $conf['title'][$language] : $conf['title']['en'];
            $icon = $conf['icon'];
            $settings = json_encode(Yii::createObject($class, [$name])->settings);
            $order_num = $conf['order_num'];
            $status = 1;
            $data[] = [$name, $class, $title, $icon, $settings, $order_num, $status,];
        }

        $this->batchInsert(models\Module::tableName(), ['name', 'class', 'title', 'icon', 'settings', 'order_num', 'status'], $data);
    }

    public function down()
    {
        $this->dropTable(models\Admin::tableName());
        $this->dropTable(models\LoginForm::tableName());
        $this->dropTable(models\Module::tableName());
        $this->dropTable(models\Photo::tableName());
        $this->dropTable(models\Setting::tableName());
        $this->dropTable(shopcart\models\Order::tableName());
        $this->dropTable(shopcart\models\Good::tableName());
        $this->dropTable(carousel\models\Carousel::tableName());
        $this->dropTable(catalog\models\Category::tableName());
        $this->dropTable(catalog\models\Item::tableName());
        $this->dropTable(catalog\models\ItemData::tableName());
        $this->dropTable(faq\models\Faq::tableName());
        $this->dropTable(models\SeoText::tableName());
        $this->dropTable(models\Tag::tableName());
        $this->dropTable(models\TagAssign::tableName());
        $this->dropTable(article\models\Category::tableName());
        $this->dropTable(article\models\Item::tableName());
        $this->dropTable(feedback\models\Feedback::tableName());
        $this->dropTable(file\models\File::tableName());
        $this->dropTable(gallery\models\Category::tableName());
        $this->dropTable(guestbook\models\Guestbook::tableName());
        $this->dropTable(news\models\News::tableName());
        $this->dropTable(page\models\Page::tableName());
        $this->dropTable(subscribe\models\Subscriber::tableName());
        $this->dropTable(subscribe\models\History::tableName());
        $this->dropTable(text\models\Text::tableName());
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
