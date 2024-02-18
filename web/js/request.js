$(document).ready(function() {
    $(document).on('click', '.close', function() {
        let overlayElement = document.getElementById("overlay");
        overlayElement.style.display = "none";
    });

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

    $(document).on('click', '#edit-picture', function() {
        $.ajax({
            url: 'index.php?action=edit-picture',
            type: 'post',
            data: {'user': $(this).val()},
            success: function(response) {
                let actionElement = document.getElementById("action");
                actionElement.innerHTML = response;
                let overlayElement = document.getElementById("overlay");
                overlayElement.style.display = "flex";
            }
        });
    });

    $(document).on('submit', '#picture-form', async function(event) {
        event.preventDefault();
        var formData = new FormData(this);
    
        await $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
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

        $.ajax({
            url: 'index.php?action=profile',
            type: 'post',
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", error);
            }
        });
    });
});