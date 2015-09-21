<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Exchanger';
?>

    <div class="alert alert-info">У вас на счёте <strong><?= $currentUser['sum'] ?></strong> рублей</div>

<?php if(isset($error)) { ?>
    <div class="alert alert-danger"><strong>Произошла ошибка:</strong> <?= $error ?></div>
<?php } ?>

<?php if($currentUser['sum'] > 0) { ?>
    <h2>Отправить</h2>

    <div class="row">
        <?php $form = \yii\bootstrap\ActiveForm::begin(['layout' => 'horizontal']); ?>

        <div class="col-md-5">
            <label for="recipient">Кому:</label>
            <select class="form-control" name="recipient" id="recipient">
                <?php foreach($users as $user) { ?>
                    <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-5">
            <label for="send-money">Сколько:</label>
            <input class="form-control" type="text" name="send-money" id="send-money" />
        </div>
        <div class="col-md-2">
            <input class="btn btn-primary" type="submit" />
        </div>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>
<?php } ?>

<?php
if(YII_DEBUG) {
    echo '<pre>';
    var_dump($currentUser);
    echo '</pre>';

    echo '<pre>';
    var_dump($users);
    echo '</pre>';
}
