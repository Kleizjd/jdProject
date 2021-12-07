// INCLUIR SCRIPTS DE LAS VISTAS
$.getScript("../../routes/web.js");



// LLAMAR LA VISTA QUE SE CARGARA CON LOS DATOS 
function callView(module, controller, nameFunction, parameters, blank) {
    

    $("#loadView").append(`
        <form id="Data${nameFunction}" action="./" method="post" >
            <input type="hidden" name="module" value=${module}>
            <input type="hidden" name="controller" value=${controller}>
            <input type="hidden" name="nameFunction" value=${nameFunction}>
            ${parameters ? Object.keys(parameters).map(key => `<input type="hidden" name="${key}" value="${parameters[key]}">\n`).join("") : null}
        </form>
    `);
    $(`#Data${nameFunction}`).submit();
    $(`#Data${nameFunction}`).remove();
}