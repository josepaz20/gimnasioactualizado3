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
    $.get('registrarcambioplan', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idCliente) {
    $.get('detalle', {idCliente: idCliente}, setFormulario);
    bloqueoAjax();
}
function verEditar(idCliente) {
    $.get('editar', {idCliente: idCliente}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idServicio) {
    $.get('delete', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function setTipoReporte() {
    $("#anioBusq").attr('required', true);
    $("#mesBusq").attr('required', true);
    $("#fechaejecucionIniBusq").attr('required', true);
    $("#fechaejecucionFinBusq").attr('required', true);
//    console.log($("#reporteBusq").val())
    if ($("#reporteBusq").val() !== '') {
        switch (parseInt($("#reporteBusq").val())) {
            case 6:
                $("#fechaejecucionIniBusq").removeAttr('required');
                $("#fechaejecucionFinBusq").removeAttr('required');
                break;
            case 7:
                $("#fechaejecucionIniBusq").removeAttr('required');
                $("#fechaejecucionFinBusq").removeAttr('required');
                break;
            default:
                $("#anioBusq").removeAttr('required');
                $("#mesBusq").removeAttr('required');
                break;
        }
    }
}

//------------------------------------------------------------------------------

function limpiarBusqueda() {
    $("#formBusquedas input").each(function () {
        $(this).val('');
    });
}

//------------------------------------------------------------------------------

function getZonas(idSucursal) {
    $("#sucursalBusq").val($("#idSucursalBusq option:selected").text());
    $("#idBarrioBusq").html("<option value=''>Seleccione...</option>");
    if (idSucursal !== '') {
        $.get('/josandro/servicios/administracion/getselectzonas', {idSucursal: idSucursal}, setZonas);
        bloqueoAjax();
    } else {
        $("#idZonaBusq").html("<option value=''>Seleccione...</option>");
    }
}
function setZonas(html) {
    $("#idZonaBusq").html(html);
}

//------------------------------------------------------------------------------

function getBarrios(idZona) {
    if (idZona !== '') {
        $.get('/josandro/servicios/administracion/getselectbarrios', {idZona: idZona}, setBarrios);
        bloqueoAjax();
    } else {
        $("#idBarrioBusq").html("<option value=''>Seleccione...</option>");
    }
}
function setBarrios(html) {
    $("#idBarrioBusq").html(html);
}

//------------------------------------------------------------------------------

function confirmarGenerarReporte() {
    if (confirm(" DESEA GENERAR ESTE REPORTE ? ")) {
//        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function getCajeros(idSucursal) {
    $("#idCajaBusq").html("<option value=''>Seleccione...</option>");
    if (idSucursal !== '') {
        $.get('getcajeros', {idSucursal: idSucursal}, setCajeros);
        bloqueoAjax();
    } else {
        $("#idCajaBusq").html("<option value=''>Seleccione...</option>");
    }
}
function setCajeros(html) {
    $("#idCajaBusq").html(html);
}

//------------------------------------------------------------------------------

//------------------------------------------------------------------------------




