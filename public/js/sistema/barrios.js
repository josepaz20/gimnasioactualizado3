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
function verDetalle(idBarrio) {
    $.get('detail', {idBarrio: idBarrio}, setFormulario);
    bloqueoAjax();
}
function verEditar(idBarrio) {
    $.get('editar', {idBarrio: idBarrio}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idBarrio) {
    $.get('delete', {idBarrio: idBarrio}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function getZonas(idSucursal) {
    $("#idZona").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('/josandro/zonas/administracion/getZonas', {idSucursal: idSucursal}, setZonas);
        bloqueoAjax();
    }
}
function setZonas(datos) {
    $("#idZona").html(datos);
}

//------------------------------------------------------------------------------

function getZonasFiltro(idSucursal) {
    $("#idZonaFiltro").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('/josandro/zonas/administracion/getZonas', {idSucursal: idSucursal}, setZonasFiltro);
        bloqueoAjax();
    }
}
function setZonasFiltro(datos) {
    $("#idZonaFiltro").html(datos);
}

//------------------------------------------------------------------------------
