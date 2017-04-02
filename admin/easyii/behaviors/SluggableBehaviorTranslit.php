<?php
namespace yii\easyii\behaviors;

use yii\behaviors\SluggableBehavior;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Inflector;


class SluggableBehaviorTranslit extends SluggableBehavior
{
    protected function generateSlug($slugParts)
    {
        return Inflector::slug(TransliteratorHelper::process(implode('-', $slugParts)), '-', true);
    }
}
