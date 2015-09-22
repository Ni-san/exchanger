<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $sum
 */
class Users extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['sum'],  'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'   => 'ID',
            'name' => 'Имя пользователя',
            'sum'  => 'Сумма на счёте',
        ];
    }

    /**
     * Если пользователь $userId существует, добавляет $money на счёт
     * Иначе выбрасывает исключение
     *
     * @param $money
     * @param $userId
     *
     * @throws OperationException
     */
    public static function addMoney($money, $userId) {
        $user = Users::find()
            ->where(['id' => $userId])
            ->one()
        ;

        if(!isset($user)) {
            throw new OperationException('Получатель не существует');
        }

        $user->sum += $money;

        $user->save();
    }

    /**
     * Если пользователь $userId существует и денег на счёте хватает, вычитает $money со счёта
     * Иначе выбрасывает исключение
     *
     * @param $money
     * @param $userId
     *
     * @throws OperationException
     */
    public static function sendMoney($money, $userId) {
        $user = self::find()
            ->where(['id' => $userId])
            ->andWhere('sum>:money', [':money' => $money])
            ->one()
        ;

        if(!isset($user)) {
            throw new OperationException('Недостаточно средств');
        }

        $user->sum -= $money;

        $user->save();
    }

    /**
     * Если имя уникально, создаётся новый юзер
     *
     * @param $name
     */
    public static function addUser($name) {
        $user = self::find()
            ->where(['name' => $name])
        ;

        if($user === null) {
            $user = new self();

            $user->name = $name;
            $user->save();
        }
    }
}
