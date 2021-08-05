//------------------------------------------------------------------------------
function actualizarRecursos() {
    if (confirm('¿ DESEA ACTUALIZAR LOS RECURSOS ?')) {
        location.href = '/josandro/usuarios/recursos/actualizarRecursos';
    }
}
function verRegistrar() {
    $.get('/josandro/usuarios/recursos/add', {}, setFormulario);
}
function verDetalle(idRecurso) {
    $.get('/josandro/usuarios/recursos/detail', {idRecurso: idRecurso}, setFormulario);
}
function verEditar(idRecurso) {
    $.get('/josandro/usuarios/recursos/edit', {idRecurso: idRecurso}, setFormulario);
}
function verEliminar(idRecurso) {
    $.get('/josandro/usuarios/recursos/delete', {idRecurso: idRecurso}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function existeRol() {
//    $.get('/josandro/usuarios/recursos/existeRol', {rol: $("#rol").val()}, existeRol, 'json');
}