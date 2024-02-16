$(document).ready(function() {
    $(document).on('click', '#show-comment', function() {
        var pubid = $(this).attr('pubid'); 

        console.log(pubid);

        $.ajax({
            url: 'index.php?action=comments&publication=' + pubid,
            type: 'post',
            data: { 'publication': pubid },
            beforeSend: function() {
                console.log("Envoi de la requête AJAX pour l'ID de publication :", pubid);
            },
            success: function(response) {
                console.log("Réponse reçue :", response);
                $("#comments-" + pubid).html(response);
                $("#comment-section-" + pubid).show();
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });
});
