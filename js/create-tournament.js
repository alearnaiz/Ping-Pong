$(function() {

    // Set date to current date and time to 18:00
    var date = new Date();
    date.setHours(18);
    date.setMinutes(00);

    $('#datetimepicker').datetimepicker({
        sideBySide: true,
        format: 'DD/MM/YYYY HH:mm',
        defaultDate: date,
        allowInputToggle: true
    });
    $('#datetime').val('');

    $("#form").on("submit", function(event) {
        var name = $("#name").val();
        var dateTime = $('#datetime').val();
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