//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('/josandro/usuarios/roles/add', {}, setFormulario);
}
function verDetalle(idRol) {
    $.get('/josandro/usuarios/roles/detail', {idRol: idRol}, setFormulario);
}
function verEditar(idRol) {
    $.get('/josandro/usuarios/roles/edit', {idRol: idRol}, setFormulario);
}
function verEliminar(idRol) {
    $.get('/josandro/usuarios/roles/delete', {idRol: idRol}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function existeRol() {
//    $.get('/josandro/usuarios/roles/existeRol', {rol: $("#rol").val()}, existeRol, 'json');
}