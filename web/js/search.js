$(document).ready(function() {
    $('#inputSearch').on('input', function() {
        var div = $("#resultSearch");
        if(!div.is(':visible'))
            div.show();
        var text = $(this).val();

        $.ajax({
            url: 'index.php?action=search&text=' + text,
            type: 'post',
            data: {text: text},
            success: function(response){
                document.getElementById("resultSearch").innerHTML = response;
            }
        });
    });
});