//------------------------------------------------------------------------------

var cerrarModal = false;

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('/gimnasio/usuarios/privilegios/add', {}, setFormulario);
}
function verDetalle(idPrivilegio) {
    $.get('/josandro/usuarios/privilegios/detail', {idPrivilegio: idPrivilegio}, setFormulario);
}
function verEditar(idPrivilegio) {
    $.get('/josandro/usuarios/privilegios/edit', {idPrivilegio: idPrivilegio}, setFormulario);
}
function verEliminar(idPrivilegio) {
    $.get('/josandro/usuarios/privilegios/delete', {idPrivilegio: idPrivilegio}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function cargarAcciones() {
    $.get('/gimnasio/usuarios/acciones/getAccionesSelect', {recurso: $("#fk_recursoacl_id option:selected").html()}, setAcciones);
}
function setAcciones(html) {
    $("#fk_accion_id").html(html);
}

function existePrivilegio() {
    if ($("#fk_rol_id").val() !== '' && $("#fk_accion_id").val() !== '' && $("#fk_recursoacl_id").val() !== '') {
        if ($("#fk_rol_id").val() !== $("#fk_rol_id_old").val() || $("#fk_accion_id").val() !== $("#fk_accion_id_old").val() || $("#fk_recursoacl_id").val() !== $("#fk_recursoacl_id_old").val()) {
            $.get('/josandro/usuarios/privilegios/existePrivilegio', {idRecurso: $("#fk_recursoacl_id").val(), accion: $("#fk_accion_id option:selected").html(), idRol: $("#fk_rol_id").val()}, setExistePrivilegio, 'json');
        }
    }
}
function setExistePrivilegio(datos) {
    if (parseInt(datos['existe']) === 1) {
        cerrarModal = true;
        alert("ESTE PRIVILEGIO YA SE ENCUENTRA REGISTRADO EN EL SISTEMA, POR FAVOR VERIFIQUE LA LISTA DE PRIVILEGIOS");
        $('#modalFormulario').modal('hide');
        cerrarModal = false;
    }
}
