<?php
use yii\easyii\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('easyii', 'System');
?>

<h4><?= Yii::t('easyii', 'Current version') ?>:
    <b><?= Setting::get('easyii_version') ?></b>
</h4>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/flush-cache']) ?>" class="btn btn-default"><i
                class="glyphicon glyphicon-flash"></i> <?= Yii::t('easyii', 'Flush cache') ?></a>
</p>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/clear-assets']) ?>" class="btn btn-default"><i
                class="glyphicon glyphicon-trash"></i> <?= Yii::t('easyii', 'Clear assets') ?></a>
</p>
