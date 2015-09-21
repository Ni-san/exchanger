jQuery(document).ready(function() {
    //alert('ready');
});

function addMoney(id) {
    var moneyToAdd = jQuery('#add-money-' + id).val();

    jQuery.ajax({
        method: 'post',
        data: {
            addMoneyTo: id,
            moneyToAdd: moneyToAdd
        },
        success: function(data) {
            jQuery('#user-sum-' + id).html(data);
            jQuery('#add-money-' + id).val('');
            console.log(data);
        }
    });
}
