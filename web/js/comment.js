$(document).ready(function() {
    $(document).on('click', '.close', function() {
        let overlayElement = document.getElementById("overlay");
        overlayElement.style.display = "none";
    });

    $(document).on('click', '.like', function() {
        let pubid = $(this).attr('id');
        let action = 'like';

        request(action, pubid);
    });

    $(document).on('click', '.dislike', function() {
        let pubid = $(this).attr('id');
        let action = 'dislike';

        request(action, pubid);
    });

    $(document).on('click', '.show-comment', function() {
        let pubid = $(this).attr('id'); 

        $.ajax({
            url: 'index.php?action=comments&publication=' + pubid,
            type: 'post',
            data: { 'publication': pubid },
            success: function(response) {
                let actionElement = document.getElementById("action");
                actionElement.innerHTML = response;
                let overlayElement = document.getElementById("overlay");
                overlayElement.style.display = "flex";
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });

    $(document).on('submit', '#send-comment', async function(event){
        event.preventDefault();
        var formData = $(this).serialize();

        await $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: formData,
            error: function(xhr, status, error) {
                console.error('Erreur lors de l\'envoi du formulaire :', error);
            }
        });

        let pubid = $(this).attr('pubid'); 
        $.ajax({
            url: 'index.php?action=comments&publication=' + pubid,
            type: 'post',
            data: { 'publication': pubid },
            success: function(response) {
                let actionElement = document.getElementById("action");
                actionElement.innerHTML = response;
                let overlayElement = document.getElementById("overlay");
                overlayElement.style.display = "flex";
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });

    $(document).on('click', '.delete-comment', function() {
        if (!confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
            return;
        }

        let commentId = $(this).attr('id');
        $.ajax({
            url: 'index.php?action=delete-comment&comment=' + commentId,
            type: 'post',
            data: { 'comment': commentId },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });

        let pubid = $("#send-comment").attr('pubid'); 
        $.ajax({
            url: 'index.php?action=comments&publication=' + pubid,
            type: 'post',
            data: { 'publication': pubid },
            success: function(response) {
                let actionElement = document.getElementById("action");
                actionElement.innerHTML = response;
                let overlayElement = document.getElementById("overlay");
                overlayElement.style.display = "flex";
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });
});

function request(action, pubid) {
    console.log('Publication :', pubid);
    $.ajax({
        url: 'index.php?action=' + action + '&publication=' + pubid,
        type: 'post',
        data: { 'publication': pubid },
        error: function(xhr, status, error) {
            console.log("Erreur AJAX :", error);
        }
    });
}