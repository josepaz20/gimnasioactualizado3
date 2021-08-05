//------------------------------------------------------------------------------
function bloqueoAjax() {
    $.blockUI(
            {
                message: $('#msgBloqueo'),
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .85,
                    color: '#fff',
                    'z-index': 10000
                }
            }
    );
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idSucursal) {
    $.get('detail', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEditar(idSucursal) {
    $.get('edit', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idSucursal) {
    $.get('delete', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function gestionsucursales(idTarifaCosto, idTipoCosto) {
    $.get('gestionsucursales', {idTarifaCosto: idTarifaCosto, idTipoCosto: idTipoCosto}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
function getFormularioAdd() {
    $.get('getformulariotarifa', {idSucursal: $("#idSucursalAdd").val(), idTipo: $("#idTipoAdd").val()}, setFormularioAdd);
    bloqueoAjax();
}
function setFormularioAdd(datos) {
    //console.log(datos)
    $("#divFormTarifa").html(datos);
}

//------------------------------------------------------------------------------



