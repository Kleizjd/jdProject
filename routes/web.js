
// Este archivo es usado para llamar las vistas de la plantilla ya habiendo iniciado seccion
$(document).ready(function() {
    $(function viewOwnAcount() {
        $(document).on("click", "#viewOwnAcount", function() {
            let userId = new Object();
            userId["userId"] = $("#userId").val();
			$("#img_profile_herence").attr("src", $("#img_profile").attr("src"));
            callView("Admin", "Admin", "viewOwnAcount", userId);
        });
    });
    //======================[  Meeting/Reunion  ]=========================//	
    $(function viewMeeting() {
        $(document).on("click", "#viewCreateMeeting", function() {
            callView("Meeting", "Meeting", "viewCreateMeeting");
        });
    });
    //=============================[  HELPUSER/AYUDAUSUARIO  ]=========================//	
    $(function viewCreateHelpUser() {
        $(document).on("click", "#viewNotify", function() {
            callView("HelpUser", "HelpUser", "viewHelpUser");
        });
    });
    // -------------------------------------------------------------//
});