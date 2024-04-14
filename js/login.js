$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var formData = {
            username: $('#username').val(),
            password: $('#password').val()
        };

        $.ajax({
            type: 'POST',
            url: 'http://localhost/guvi/php/login.php',
            data: formData,
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    window.location.href = 'profile.html';
                } else if (jsonResponse.status === 'error') {
                    alert(jsonResponse.message);
                }
            }
        });
    });
});
