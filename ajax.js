"use strict";

//отправляем асинхронный запрос нажатием на кнопку "найти"
$(document).ready(function () {
    $(".button").click(function (e) {
        e.preventDefault();
        var parameters = {
            text: $("#palindrome").val(),
        };
        $.post("getPalindrome.php", parameters, function (data) {
            $("#answer").html(data);
        });
    });
});

//отправляем асинхронный запрос нажатием на кнопку "enter"
$(document).ready(function () {
    $("#palindrome").keypress(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            var parameters = {
                text: $("#palindrome").val(),
            };
            $.post("getPalindrome.php", parameters, function (data) {
                $("#answer").html(data);
            });
        }
    });
});