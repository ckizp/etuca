$(document).ready(function() {
    $(document).on('click', 'article', function() {
        var pubid = $(this).attr('pubid');

        console.log(pubid);

        $.ajax({
            url: 'index.php?action=comments&publication=' + pubid,
            type: 'post',
            data: {'publication': pubid},
            success: function(response) {
                document.getElementById("comments").innerHTML = response;
            }
        });
    });
});