"use strict";

$(document).ready(function() {
    $("#category").change(function() {
        var category = $("#category").val();
        $.ajax({
            url: '../inc/order_service_mobile.php',
            data: 'category=' + category,
            type: 'POST',
            dataType: 'html',
            success: function(msg) {
                $("#service").html(msg);
            }
        });
        $.ajax({
            url: '../inc/order_input_mobile.php',
            data: 'category=' + category,
            type: 'POST',
            dataType: 'html',
            success: function(msg) {
                $("#input_data").html(msg);
            }
        });
    });
    $("#service").change(function() {
        var service = $("#service").val();
        $.ajax({
            url: '../inc/order_note_mobile.php',
            data: 'service=' + service,
            type: 'POST',
            dataType: 'html',
            success: function(msg) {
                $("#note").html(msg);
            }
        });
        $.ajax({
            url: '../inc/custom_mobile.php',
            async: false,
            data: 'custom=' + service,
            type: 'POST',
            dataType: 'html',
            success: function(msg) {
                $("#input_data").html(msg);
            }
        });
        $.ajax({
            url: '../inc/order_rate_mobile.php',
            data: 'service=' + service,
            type: 'POST',
            dataType: 'html',
            success: function(msg) {
                $("#rate").val(msg);
            }
        });
    });
});

function get_total(quantity) {
    var rate = $("#rate").val();
    var result = eval(quantity) * rate;
    $('#total').val(result);
}
function get_count() {
    var count = $("#comments").val().split("\n");
    $('#jumlah').val(count.length);
    var rate = $("#rate").val();
    var result = $("#jumlah").val() * rate;
    $('#total').val(result);
}