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
function verDetalle(idParametro) {
    $.get('detail', {idParametro: idParametro}, setFormulario);
    bloqueoAjax();
}
function verEditar(idParametro) {
    $.get('edit', {idParametro: idParametro}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idParametro) {
    $.get('delete', {idParametro: idParametro}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------



