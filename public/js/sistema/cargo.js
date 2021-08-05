function verAgregarCargo() {
    $.get('/josandro/talentohumano/cargo/add', {}, setFormulario);
}
function verDetalle(idCargo) {
    $.get('/josandro/talentohumano/cargo/detail', {idCargo: idCargo}, setFormulario);
}
function verEditar(idCargo) {
    $.get('/josandro/talentohumano/cargo/edit', {idCargo: idCargo}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
