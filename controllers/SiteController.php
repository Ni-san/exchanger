<?php

namespace app\controllers;

use app\models\Operations;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'index', 'admin'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'admin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {

        if(Yii::$app->user->id == 0) {
            return $this->redirect('admin');
        }

        $users = Users::find()
            ->where('id<>:id', [':id' => Yii::$app->user->id])
            ->asArray()
            ->all()
        ;

        $currentUser = Users::find()
            ->where(['id' => Yii::$app->user->id])
            ->asArray()
            ->one()
        ;

        $postData = Yii::$app->getRequest()->post();

        if(isset($postData['send-money'])) {
            $result = Operations::sendMoney($postData['send-money'], $postData['recipient']);

            if(is_string($result)) {

                return $this->render('index', [
                    'error' => $result,
                    'users' => $users,
                    'currentUser' => $currentUser,
                ]);
            } else {

                return $this->redirect('');
            }
        }

        return $this->render('index', [
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    public function actionAdmin() {
        $model = new Users();

        $postData = Yii::$app->getRequest()->post();

        if(isset($postData['Users'])) {
            $model->name = $postData['Users']['name'];
            $model->save();
            $this->goHome();
        }

        if(isset($postData['addMoneyTo'])) {
            echo Operations::giveMoney($postData['addMoneyTo'], $postData['moneyToAdd']);
            exit;
        }

        $operations = Operations::getList();
        $users = Users::find()->orderBy('id')->asArray()->all();

        return $this->render('admin', [
            'operations' => $operations,
            'users'      => $users,
            'model'      => $model,
        ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
