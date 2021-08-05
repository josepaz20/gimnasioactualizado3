//------------------------------------------------------------------------------
function actualizarAcciones() {
    if (confirm('¿ DESEA ACTUALIZAR LAS ACCIONES ?')) {
        location.href = '/josandro/usuarios/acciones/actualizarAcciones';
    }
}
function verRegistrar() {
    $.get('/josandro/usuarios/acciones/add', {}, setFormulario);
}
function verDetalle(idAccion) {
    $.get('/josandro/usuarios/acciones/detail', {idAccion: idAccion}, setFormulario);
}
function verEditar(idAccion) {
    $.get('/josandro/usuarios/acciones/edit', {idAccion: idAccion}, setFormulario);
}
function verEliminar(idAccion) {
    $.get('/josandro/usuarios/acciones/delete', {idAccion: idAccion}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function existeRol() {
//    $.get('/josandro/usuarios/acciones/existeRol', {rol: $("#rol").val()}, existeRol, 'json');
}