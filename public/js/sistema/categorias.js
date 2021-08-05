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
function verDetalle(idCategoria) {
    $.get('detail', {idCategoria: idCategoria}, setFormulario);
    bloqueoAjax();
}
function verEditar(idCategoria) {
    $.get('edit', {idCategoria: idCategoria}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idCategoria) {
    $.get('delete', {idCategoria: idCategoria}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------



