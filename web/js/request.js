$(document).ready(function() {
    $(document).on('click', '.submitButton', function() {
        let buttonName = $(this).attr('name');

        $.ajax({
            url: 'index.php?action=' + buttonName,
            type: 'post',
            data: {'user': $(this).val()},
            success: function(response) {
                var friendshipElement = document.getElementById("friendship");
                if (friendshipElement) {
                    friendshipElement.innerHTML = response;
                }
            }
        });
    });
});