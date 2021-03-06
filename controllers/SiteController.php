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
        // Неавторизованным запрещено всё
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['login', 'logout', 'index', 'admin'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['login', 'logout', 'index', 'admin'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {

        // Админ видит страницу со списками пользователей и операций
        if(Yii::$app->user->id == 0) {

            return $this->redirect('admin');
        }

        // Все пользователи, кроме текущего (получатели переводов)
        $users = Users::find()
            ->where('id<>:id', [':id' => Yii::$app->user->id])
            ->asArray()
            ->all()
        ;

        // текущий пользователь
        $currentUser = Users::find()
            ->where(['id' => Yii::$app->user->id])
            ->asArray()
            ->one()
        ;

        $postData = Yii::$app->getRequest()->post();

        // Отправка денег
        if(isset($postData['send-money'])) {
            $result = Operations::sendMoney($postData['send-money'], $postData['recipient']);

            if(is_string($result)) {

                return $this->render('index', [
                    'error'       => $result,
                    'users'       => $users,
                    'currentUser' => $currentUser,
                ]);
            } else {

                return $this->redirect('');
            }
        }

        return $this->render('index', [
            'users'       => $users,
            'currentUser' => $currentUser,
        ]);
    }

    public function actionAdmin() {

        $postData = Yii::$app->getRequest()->post();

        // Проверка свободно ли имя (ajax)
        if(isset($postData['checkUserName'])) {
            $user = Users::find()
                ->where(['name' => $postData['checkUserName']])
                ->one()
            ;

            return $user === null;
        }

        // Добавление пользователя
        if(isset($postData['Users'])) {
            Users::addUser($postData['Users']['name']);

            $this->goHome();
        }

        // Добавление денег на счёт (ajax)
        if(isset($postData['addMoneyTo'])) {
            echo Operations::giveMoney($postData['addMoneyTo'], $postData['moneyToAdd']);
            exit;
        }

        // Все операции
        $operations = Operations::getList();

        // Все пользователи
        $users      = Users::find()->orderBy('id')->asArray()->all();

        return $this->render('admin', [
            'operations' => $operations,
            'users'      => $users,
            'model'      => new Users(), // Для формы
        ]);
    }

    /**
     * Стандартный обработчик формы входа
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin() {
        if(!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Стандартный обработчик формы выхода
     *
     * @return \yii\web\Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
