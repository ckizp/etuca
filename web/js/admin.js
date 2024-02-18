$(document).ready(function() {
    $(document).on('click', '.close', function() {
        let overlayElement = document.getElementById("overlay");
        overlayElement.style.display = "none";
    });

    $('#inputSearch').on('input', function() {
        var div = $("#resultSearch");
        if(!div.is(':visible'))
            div.show();
        var text = $(this).val();

        $.ajax({
            url: 'index.php?action=admin-search&text=' + text,
            type: 'post',
            data: {text: text},
            success: function(response){
                document.getElementById("resultSearch").innerHTML = response;
            }
        });
    });

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

    $(document).on('click', '.adminAction', function() {
        let actionType = $(this).attr('id');
        let userId = document.getElementsByClassName("infos")[0].id;

        switch(actionType) {
            case 'delete-photo':
                $.ajax({
                    url: 'index.php?action=delete-photo&user=' + userId,
                    type: 'post',
                    data: {'user': userId},
                    success: function(response) {
                        alert("La photo de " + response + " a été supprimée avec succès !");
                    }
                });
                break;
            case 'delete-phone':
                $.ajax({
                    url: 'index.php?action=delete-phone&user=' + userId,
                    type: 'post',
                    data: {'user': userId},
                    success: function(response) {
                        alert("Le numéro de téléphone de " + response + " a été supprimé avec succès !");
                    }
                });
                break;
            case 'copy':
                let date = $("#date").text();
                var tempInput = $("<textarea>");
                $("body").append(tempInput);
                tempInput.val(date).select();
                document.execCommand("copy");
                tempInput.remove();
                break;
            case 'edit-name':
            case 'edit-username':
            case 'edit-email':
            case 'write-mail':
                $.ajax({
                    url: 'index.php?action=' + actionType + '&user=' + userId,
                    type: 'post',
                    data: {'user': userId},
                    success: function(response) {
                        let actionElement = document.getElementById("action");
                        actionElement.innerHTML = response;
                        let overlayElement = document.getElementById("overlay");
                        overlayElement.style.display = "flex";
                    }
                });
                break;
        }
    });

    $(document).on('submit', 'form', async function(event) {
        event.preventDefault();
        var formData = $(this).serialize() + "&user=" + $(this).attr('id');

        await $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: formData,
            success: function(response) {
                let actionElement = document.getElementById("action");
                actionElement.innerHTML = "";
                let overlayElement = document.getElementById("overlay");
                overlayElement.style.display = "none";
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de l\'envoi du formulaire :', error);
            }
        });

        let userId = $(this).attr('id'); 
        $.ajax({
            url: 'index.php?action=userinfos&user=' + userId,
            type: 'post',
            data: { 'user': userId },
            success: function(response) {
                document.getElementById("user-activity").innerHTML = response;
            },
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });

    $(document).on('click', '#ban', function() {
        if (!confirm("Etes-vous sûr de vouloir bannir cet utilisateur ?")) return;
        let userId = document.getElementsByClassName("infos")[0].id;

        $.ajax({
            url: 'index.php?action=ban&user=' + userId,
            type: 'post',
            data: {'user': userId}
        });
        request(userId);
    });

    $(document).on('click', '#unban', function() {
        let userId = document.getElementsByClassName("infos")[0].id;

        $.ajax({
            url: 'index.php?action=unban&user=' + userId,
            type: 'post',
            data: {'user': userId}
        });
        request(userId);
    });

    $(document).on('click', '.can', function() {
        if (!confirm("Etes-vous sûr de vouloir supprimer cette publication ?")) return;
        let pubId = $(this).attr('id');

        $.ajax({
            url: 'index.php?action=delete&publication=' + pubId,
            type: 'post',
            data: {'publication': pubId}
        });

        let userId = document.getElementsByClassName("infos")[0].id;
        request(userId);
    });
});

function request(userId) {
    $.ajax({
        url: 'index.php?action=userinfos&user=' + userId,
        type: 'post',
        data: {'user': userId},
        success: function(response) {
            document.getElementById("user-activity").innerHTML = response;
        }
    });
}