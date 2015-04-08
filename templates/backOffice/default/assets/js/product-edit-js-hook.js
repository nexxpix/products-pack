$(document).ready(function() {
    // Change the pack status button
    $("#isPack").on("switch-change", function(e, data){
        $("#changePackStatusForm").submit();
    });
});