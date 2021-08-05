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
    $('.blockOverlay').attr('style', $('.blockOverlay').attr('style') + 'z-index: 1100 !important');
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('registrar', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idTipoTarifa) {
    $.get('detalle', {idTipoTarifa: idTipoTarifa}, setFormulario);
    bloqueoAjax();
}
function verEditar(idTipoTarifa) {
    $.get('editar', {idTipoTarifa: idTipoTarifa}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idTipoTarifa) {
    $.get('eliminar', {idTipoTarifa: idTipoTarifa}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------



