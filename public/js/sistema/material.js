//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('/josandro/inventario/material/registrar', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idMaterial) {
    $.get('/josandro/inventario/material/detail', {idMaterial: idMaterial}, setFormulario);
}
function verEditar(idMaterial) {
    $.get('/josandro/inventario/material/edit', {idMaterial: idMaterial}, setFormulario);
}
function verEliminar(idMaterial) {
    $.get('/josandro/inventario/material/delete', {idMaterial: idMaterial}, setFormulario);
}
//------------------------------------------------------------------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

