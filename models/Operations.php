<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "operations".
 *
 * @property integer $id
 * @property string $recipient
 * @property string $sender
 * @property string $sum
 */
class Operations extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'operations';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recipient', 'sender', 'sum'], 'required'],
            [['sum'], 'number'],
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
     * Связь с пользователем-получателем
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient() {
        return $this->hasOne(Users::className(), ['id' => 'recipient']);
    }

    /**
     * Связь с пользователем-отправителем
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender() {
        return $this->hasOne(Users::className(), ['id' => 'sender']);
    }

    /**
     * Возвращает true при успешной операции или строку с ошибкой
     *
     * @param $money
     * @param $recipient
     *
     * @return bool|string
     *
     * @throws Exception
     */
    public static function sendMoney($money, $recipient) {
        // Проверка входных данных (только положительные числа)
        if(!preg_match('/^\d*\.?\d*$/', $money)) {

            return 'Введите положительное число';
        }


        $transaction = Yii::$app->db->beginTransaction();
        $transaction->setIsolationLevel('SERIALIZABLE');

        try {
            // Вычесть деньги у отправителя
            Users::sendMoney($money, Yii::$app->user->id);
            // Прибавить полуателю
            Users::addMoney($money, $recipient);

            // Записать операцию
            $operation            = new self();
            $operation->sum       = $money;
            $operation->recipient = $recipient;
            $operation->link('sender', Users::findOne(Yii::$app->user->id));

            $transaction->commit();

            // Ошибок нет
            return true;
        } catch(OperationException $e) {
            $transaction->rollback();

            return $e->getMessage();
        } catch(Exception $e) {
            $transaction->rollback();

            if(YII_DEBUG) {

                throw $e;
            } else {

                return 'Неизвестная ошибка';
            }
        }
    }

    /**
     * Возвращает список операций
     *
     * @return array
     */
    public static function getList() {
        $operations = self::find()
            ->with([
                'sender',
                'recipient'
            ])
            ->asArray()
            ->all()
        ;

        // Операции без отправителя - начисление админом
        foreach($operations as $key => $operation) {
            if(!isset($operation['sender']) || $operation['sender'] === null) {
                $operations[ $key ]['sender']['name'] = 'admin';
            }
        }

        return $operations;
    }

    /**
     * Добавление админом денег пользователю
     * Возвращает сумму на счёте после проведения операции,
     * независимо от исхода
     *
     * @param int $recipient
     * @param $money
     *
     * @return mixed
     *
     * @throws Exception
     */
    public static function giveMoney($recipient, $money) {

        $transaction = Yii::$app->db->beginTransaction();
        $transaction->setIsolationLevel('SERIALIZABLE');

        try {
            Users::addMoney($money, $recipient);

            $operation         = new self();
            $operation->sum    = $money;
            $operation->sender = 0;

            $operation->link('recipient', Users::findOne($recipient));

            $transaction->commit();

        } catch(OperationException $e) {
            $transaction->rollback();

            return $e->getMessage();
        } catch(Exception $e) {
            $transaction->rollback();

            if(YII_DEBUG) {

                throw $e;
            } else {

                return 'Произошла ошибка';
            }
        }

        return Users::find()
            ->where(['id' => $recipient])
            ->select('sum')
            ->scalar()
        ;
    }
}
