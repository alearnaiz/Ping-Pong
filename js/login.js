$(function() {
    $("#form").on("submit", function(event) {
        var name = $("#name").val();
        if (!name) {
            $("#dname").addClass("has-error");
            event.preventDefault();
        }
    });
});