
// Este archivo es usado para llamar las vistas de la plantilla ya habiendo iniciado seccion
$(document).ready(function() {
    $(function viewAcount() {
        $(document).on("click", "#viewAcount", function() {
            let userId = new Object();
            userId["userId"] = $("#userId").val();
            callView("Admin", "Admin", "viewAcount", userId);
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
    /****SHOW MODAL Search HELPUSER****/
    $(document).on("click", "#SearchUser", function() {

        $.ajax({
            url: "../../app/lib/ajax.php",
            method: "post",
            data: {
                module: "HelpUser",
                controller: "HelpUser",
                nameFunction: "modalSearchHelpUser",
            }
        }).done((res) => {
            $("#modalSearchHelpUser .modal-body").html(res);
            $("#modalSearchHelpUser").modal({ backdrop: "static", keyboard: false });
        });
    });

    // -------------------------------------------------------------//
});