$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            type: 'GET',
            async: false,
            url: '../assets/includes/checkinactive.ajax.php',
            success: function(response) {
                if (response == 'logout_redirect') {
                    location.href = "../AQuA2-Cloud/";
                }
            }
        });
    }, 5000);
});