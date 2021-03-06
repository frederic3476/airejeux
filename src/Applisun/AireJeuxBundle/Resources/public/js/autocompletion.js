jQuery(document).ready(function () {
    $("#applisun_aire_form_ville").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: Routing.generate('ville_completion', { query: request.term }),
                dataType: "json",
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        response: function(event, ui) {
            if (ui.content.length === 0) {
                $("#empty-message").text("Aucun résultat");
            } else {
                $("#empty-message").empty();
            }
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
});


