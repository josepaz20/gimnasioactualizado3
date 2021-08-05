function verRegistrarPermisoLaboral() {
    $.get('/josandro/talentohumano/permiso-laboral/add', {}, setFormulario);
}
function verEditar(refresh, idPermisoLaboral) {
    $.get('/josandro/talentohumano/permiso-laboral/edit', {refresh: refresh, idPermisoLaboral: idPermisoLaboral}, setFormulario);
}
function verDetalle(refresh, idPermisoLaboral) {   
    $.get('/josandro/talentohumano/permiso-laboral/detail', {refresh: refresh, idPermisoLaboral: idPermisoLaboral}, setFormulario);
}
function verEliminar(refresh, idPermisoLaboral) {
    $.get('/josandro/talentohumano/permiso-laboral/delete', {refresh: refresh, idPermisoLaboral: idPermisoLaboral}, setFormulario);
}
function verConcederPermisoLaboral(refresh, idPermisoLaboral) {    
    $.get('/josandro/talentohumano/permiso-laboral/conceder', {refresh: refresh, idPermisoLaboral: idPermisoLaboral}, setFormulario);    
}
function verDenegarPermisoLaboral(refresh, idPermisoLaboral) {    
    $.get('/josandro/talentohumano/permiso-laboral/denegar', {refresh: refresh, idPermisoLaboral: idPermisoLaboral}, setFormulario);    
}
//-----------------------------------------------------------------------------------------------------------------------------

function setFormulario(datos) {
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');    
}
