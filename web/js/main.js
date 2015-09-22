jQuery(document).ready(function () {

    // Обработчик для добавления пользователя
    jQuery('#add-user-button').click(function (e) {
        e.preventDefault();

        var newName = jQuery('#users-name').val();

        jQuery.ajax({
            method: 'post',
            data: { checkUserName: newName },
            success: function(data) {
                if(!data) {
                    alert('Данное имя уже занято');
                } else {
                    jQuery('#add-user-form').submit();
                }
            }
        });
    });
});

/**
 * Добавление денег на счёт
 *
 * @param id
 */
function addMoney(id) {
    var moneyToAdd = jQuery('#add-money-' + id).val();

    // Замена запятых на точки
    moneyToAdd = moneyToAdd.replace(/,/, '.');

    // Проверка, является ли введённая строка положительным числом с фиксированной точкой
    if(!/^\d*\.?\d*$/.test(moneyToAdd)) {
        alert('Введите положительное число');
    } else {
        jQuery.ajax({
            method: 'post',
            data: {
                addMoneyTo: id,
                moneyToAdd: moneyToAdd
            },
            success: function (data) {
                if(!isNaN(parseFloat(data))) {
                    jQuery('#user-sum-' + id).html(data);
                    jQuery('#add-money-' + id).val('');
                } else {
                    // Сообщение об ошибке
                    alert(data);
                }
            }
        });
    }
}
