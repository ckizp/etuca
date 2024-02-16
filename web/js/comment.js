$(document).ready(function() {
    console.log("Script chargé");
    $(document).on('click', '.show-comment', function(e) {
        var pubid = $(this).data('pubid');
        console.log("Clic sur .show-comment détecté pour l'ID de publication :", pubid);

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
