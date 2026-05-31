document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.flash-close').forEach(function (button) {
        button.addEventListener('click', function () {
            const alertBox = button.closest('div');
            if (alertBox) {
                alertBox.remove();
            }
        });
    });

    document.querySelectorAll('[data-action="go-back"]').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            history.back();
        });
    });
});
