<?php

/* @var $this yii\web\View */

$this->title = 'Admin';
?>
<div class="site-index">
    <h1>Главная</h1>

    <h2>Добавить пользователя</h2>
    <div class="row">
        <?php $form = \yii\bootstrap\ActiveForm::begin(['id' => 'add-user-form']); ?>
        <div class="col-md-6">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-md-6">
            <?= \yii\helpers\Html::submitButton('Добавить', [
                'class' => 'btn btn-primary',
                'id' => 'add-user-button',
            ]) ?>
        </div>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>

    <h2>Пользователи</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <?php if(YII_DEBUG) { ?>
                <td>id</td>
            <?php } ?>
            <td>Имя пользователя</td>
            <td>Сумма на счёте</td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($users) && is_array($users)) {
            foreach($users as $user) { ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td>
                        <span id="user-sum-<?= $user['id'] ?>"><?= $user['sum'] ?></span>
                        <button class="pull-right btn btn-default" onclick="addMoney(<?= $user['id'] ?>)">Добавить</button>
                        <input class="pull-right" id="add-money-<?= $user['id'] ?>" type="text"/>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>

    <h2>Операции</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <?php if(YII_DEBUG) { ?>
                <td>id</td>
            <?php } ?>
            <td>Отправитель</td>
            <td>Получатель</td>
            <td>Сумма перевода</td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($operations) && is_array($operations)) {
            foreach($operations as $operation) { ?>
                <tr>
                    <td><?= $operation['id'] ?></td>
                    <td><?= $operation['sender']['name'] ?></td>
                    <td><?= $operation['recipient']['name'] ?></td>
                    <td><?= $operation['sum'] ?></td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>



</div>

<?php
if(YII_DEBUG) {
    echo '<pre>';
    var_dump($users);
    echo '</pre>';

    echo '<pre>';
    var_dump($operations);
    echo '</pre>';
}
