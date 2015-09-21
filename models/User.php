<?php

namespace app\models;

use yii\base\Object;
use yii\web\IdentityInterface;

class User extends Object implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '0' => [
            'id' => '0',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if(isset(self::$users[$id])) {

            return new static(self::$users[$id]);
        }

        return self::findUser((int)$id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return self::findUser($username);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {

        return $this->password === $password;
    }

    /**
     * Поиск пользователя в таблице users
     * по name или id (в зависимости от типа параметра)
     * пароль совпадает с именем
     *
     * @param int|string $search
     *
     * @return static|null
     */
    protected static function findUser($search) {

        if(is_integer($search)) {
            $search = ['id' => $search];
        } else {
            $search = ['name' => $search];
        }

        $user = Users::find()
            ->where($search)
            ->asArray()
            ->one()
        ;


        if(isset($user)) {

            return new static([
                'id' => $user['id'],
                'username' => $user['name'],
                'password' => $user['name'],
                'authKey' => $user['name'] . 'key',
                'accessToken' => $user['name'] . 'token',
            ]);

        } else {

            return null;
        }
    }
}
