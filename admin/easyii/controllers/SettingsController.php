<?php
namespace yii\easyii\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\easyii\models\Setting;

class SettingsController extends \yii\easyii\components\Controller
{
    public $rootActions = ['create', 'delete'];

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Setting::find()->where(['>=', 'visibility', IS_ROOT ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL])->orderBy('setting_id'),
        ]);
        Yii::$app->user->setReturnUrl('/admin/settings');

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Setting;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Setting created'));
                    return $this->redirect('/admin/settings');
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Setting::findOne($id);

        if ($model === null || ($model->visibility < (IS_ROOT ? Setting::VISIBLE_ROOT : Setting::VISIBLE_ALL))) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/settings']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->name == 'admin_email') {
                    if (!filter_var($model->value, FILTER_VALIDATE_EMAIL)) {
                        $model->value = $model->oldAttributes['value'];
                    }
                }
                if ($model->name == 'admin_email' || $model->name == 'passwd_mail') {
                    if ($model->name == 'admin_email') {
                        $admin_email = $model->value;
                        $passwd_mail = Setting::get('passwd_mail');
                    }
                    if ($model->name == 'passwd_mail') {
                        $admin_email = Setting::get('admin_email');
                        $passwd_mail = $model->value;
                    }
                    if (!empty($admin_email) && !empty($passwd_mail)) {
                        $str = '<?php' . "\n";
                        $str .= '$admin_email = "' . $admin_email . "\";\n";
                        $str .= '$passwd_mail = "' . $passwd_mail . "\";\n";
                        if (file_put_contents(yii::getAlias('@app') . '/config/data_mail.php', $str) === false) {
                            die('<h2>Файл app/config/data_mail.php недоступен для записи!</h2>');
                        }
                    }
                }
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Setting updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (($model = Setting::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Setting deleted'));
    }
}
