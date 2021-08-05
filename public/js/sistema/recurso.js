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
                    'z-index': 10000000
                }
            }
    );
    $('.blockOverlay').attr('style', $('.blockOverlay').attr('style') + 'z-index: 1100 !important');
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('/josandro/inventario/recursos/registrar', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idRecurso) {
    $.get('/josandro/inventario/recursos/detalle', {idRecurso: idRecurso}, setFormulario);
    bloqueoAjax();
}
function verEditar(idRecurso) {
    $.get('/josandro/inventario/recursos/editar', {idRecurso: idRecurso}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idRecurso) {
    $.get('/josandro/inventario/recursos/eliminar', {idRecurso: idRecurso}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function existeSerial(serial) {
    var serialOLD = $("#serialOLD").val();
    if (serialOLD !== serial) {
        $.get('/josandro/inventario/recursos/existeserial', {serial: serial}, setExisteSerial, 'json');
        bloqueoAjax();
    }
}
function setExisteSerial(datos) {
    if (parseInt(datos['error']) === 0) {
        if (parseInt(datos['existe']) !== 0) {
            var serial = datos['serial'];
            alert('EL SERIAL << ' + serial + ' >> YA SE ENCUENTRA REGISTRADO EN EL SISTEMA.');
            $("#serial").val('');
            $("#serial").focus();
        }
    } else {
        alert('SE HA PRESENTADO UN ERROR EN EL SISTEMA, POR FAVOR COMUNIQUESE CON EL ADMINISTRAOR.');
        return false;
    }
}

//------------------------------------------------------------------------------
function limpiarBusqueda() {
    $("#formBusquedas select").each(function () {
        $(this).val('');
    });
}
//------------------------------------------------------------------------------