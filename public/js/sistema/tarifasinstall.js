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
function verDetalle(idTarifa) {
    $.get('detalle', {idTarifa: idTarifa}, setFormulario);
    bloqueoAjax();
}
function verEditar(idTarifa) {
    $.get('editar', {idTarifa: idTarifa}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idTarifa) {
    $.get('eliminar', {idTarifa: idTarifa}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function getTiposTarifa(idTipoServicio) {
    $.get('gettipostarifa', {idTipoServicio: idTipoServicio}, setTiposTarifa, 'json');
    bloqueoAjax();
}
function setTiposTarifa(datos) {
    $("#idTipoTarifa").html(datos['html']);
}

//------------------------------------------------------------------------------

function validarRegistrar() {
    if ($("#fechaini").val() > $("#fechafin").val()) {
        alert("LA FECHA DE ENTRADA EN VIGENCIA (Fecha Inicio) NO PUEDE SER MAYOR QUE LA FECHA DE FINALIZACION DE VIGENCIA (Fecha Fin)");
        $("#fechaini").focus();
        return false;
    }
    return confirm(" ¿ DESEA REGISTRAR ESTA TARIFA ? ");
}

//------------------------------------------------------------------------------

function validarEditar() {
    if ($("#fechaini").val() > $("#fechafin").val()) {
        alert("LA FECHA DE ENTRADA EN VIGENCIA (Fecha Inicio) NO PUEDE SER MAYOR QUE LA FECHA DE FINALIZACION DE VIGENCIA (Fecha Fin)");
        $("#fechaini").focus();
        return false;
    }
    return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?");
}

//------------------------------------------------------------------------------

function setFechaFin() {
    if ($("#fechaini").val() !== '') {
        $("#fechafin").attr('min', $("#fechaini").val());
    } else {
        $("#fechafin").removeAttr('min');
    }
}

//------------------------------------------------------------------------------



//------------------------------------------------------------------------------



//------------------------------------------------------------------------------



