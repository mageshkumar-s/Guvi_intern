$(document).ready(function() {
    // Profile update AJAX request
    $('#profileForm').submit(function(event) {
        event.preventDefault();

        var formData = {
            age: $('#age').val(),
            dob: $('#dob').val(),
            contact: $('#contact').val()
        };

        $.ajax({
            type: 'POST',
            url: 'http://localhost/guvi/php/profile.php',
            data: formData,
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    // Clear the form fields
                    $('#age').val('');
                    $('#dob').val('');
                    $('#contact').val('');
                    
                    // Display success message
                    $('#successMessage').text(jsonResponse.message);
                } else {
                    // Display error message
                    $('#errorMessage').text(jsonResponse.message);
                }
            }
        });
    });
});
