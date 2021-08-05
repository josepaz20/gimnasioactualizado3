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
                    'z-index': 2000
                }
            }
    );
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idTipoIncidente) {
    $.get('detail', {idTipoIncidente: idTipoIncidente}, setFormulario);
    bloqueoAjax();
}
function verEditar(idTipoIncidente) {
    $.get('edit', {idTipoIncidente: idTipoIncidente}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idTipoIncidente) {
    $.get('delete', {idTipoIncidente: idTipoIncidente}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------
