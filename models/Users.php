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

        $command = Yii::$app->db->createCommand(
            'UPDATE users SET sum = sum + :money WHERE id=:id',
            [':money' => $money, ':id' => $userId]
        );

        if(!$command->execute()) {
            throw new OperationException('Получатель не существует');
        }
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

        $command = Yii::$app->db->createCommand(
            'UPDATE users SET sum = sum - :money WHERE id=:id AND sum>:money',
            [':money' => $money, ':id' => $userId]
        );

        if(!$command->execute()) {
            throw new OperationException('Недостаточно средств');
        }
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
