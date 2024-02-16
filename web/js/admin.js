$(document).ready(function() {
    $(document).on('click', '.user', function() {
        let userId = $(this).attr('id');

        $.ajax({
            url: 'index.php?action=userinfos&user=' + userId,
            type: 'post',
            data: {'user': userId},
            success: function(response) {
                document.getElementById("user-activity").innerHTML = response;
            }
        });
    });
});