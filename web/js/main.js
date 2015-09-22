jQuery(document).ready(function () {
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

function addMoney(id) {
    var moneyToAdd = jQuery('#add-money-' + id).val();
    moneyToAdd = moneyToAdd.replace(/,/, '.');

    if(!/^-?\d*\.?\d*$/.test(moneyToAdd)) {
        alert('Введите число');
        return;
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
                    alert(data);
                }
            }
        });
    }
}
