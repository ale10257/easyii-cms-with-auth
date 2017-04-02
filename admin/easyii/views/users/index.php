<?php
use yii\helpers\Url;

$this->title = Yii::t('easyii', 'Admins');
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th><?= Yii::t('easyii', 'Username') ?></th>
            <th width="30"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td>
                    <a href="<?= Url::to(['/admin/users/edit', 'id' => $user->id]) ?>"><?= $user->username ?></a>
                </td>
                <td><a href="<?= Url::to(['/admin/users/delete', 'id' => $user->id]) ?>"
                       class="glyphicon glyphicon-remove confirm-delete"
                       title="<?= Yii::t('easyii', 'Delete item') ?>"></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?= yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ]) ?>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>
