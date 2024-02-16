$(document).ready(function() {
    $(document).on('click', '#show-comment', function() {
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
