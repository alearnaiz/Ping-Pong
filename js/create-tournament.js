$(function() {
    $('#datetimepicker').datetimepicker({
        sideBySide: true,
        format: 'DD/MM/YYYY HH:mm',
        allowInputToggle: true
    });
    $("#form").on("submit", function(event) {
        var name = $("#name").val();
        var dateTime = $('#datetimepicker').data("DateTimePicker").date;
        var error = false;
        if (!name) {
            $("#dname").addClass("has-error");
            error = true;
        } else {
            $("#dname").removeClass("has-error");
        }
        if (!dateTime) {
            $("#ddatetime").addClass("has-error");
            error = true;
        } else {
            $("#ddatetime").removeClass("has-error");
        }
        if (error) {
            event.preventDefault();
        }
    });
});