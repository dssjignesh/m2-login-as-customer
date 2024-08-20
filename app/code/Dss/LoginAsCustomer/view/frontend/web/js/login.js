
define([
    "jquery",
    "domReady!"
], function ($) {
    return function () {
        setTimeout(function () {
            $('#loginascustomer-button').click();
        }, 5000);
    }
});
