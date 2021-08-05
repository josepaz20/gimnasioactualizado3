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
    $('.blockOverlay').attr('style', $('.blockOverlay').attr('style') + 'z-index: 1100 !important');
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('add', {}, setFormulario);
    bloqueoAjax();
}
function setFormulario(datos) {
    $("#divContenido").html(datos);
    $("#modalFormulario").modal('show');
}

//------------------------------------------------------------------------------

function cortarServicios() {
    if ($("#idSucursalBusq").val() === '') {
        alert("POR FAVOR SELECCIONE LA SUCURSAL SOBRE LA CUAL SE GENERAN LOS CORTES");
        $("#idSucursalBusq").focus();
        return;
    }
    if ($("#montolimite").val() === '') {
        alert("POR FAVOR INGRESE EL LIMITE DE MONTO ADEUDADO PARA GENERAR LOS CORTES");
        $("#montolimite").focus();
        return;
    }
    if ($("#meses").val() === '') {
        alert("POR FAVOR INGRESE EL NUMERO DE MESES EN DEUDA PARA GENERAR LOS CORTES");
        $("#meses").focus();
        return;
    }
}

//------------------------------------------------------------------------------

function verResumenCortes() {
    if ($("#idSucursalBusq").val() === '') {
        alert("POR FAVOR SELECCIONE LA SUCURSAL SOBRE LA CUAL SE GENERAN LOS CORTES");
        $("#idSucursalBusq").focus();
        return;
    }
    if ($("#montolimite").val() === '') {
        alert("POR FAVOR INGRESE EL LIMITE DE MONTO ADEUDADO PARA GENERAR LOS CORTES");
        $("#montolimite").focus();
        return;
    }
    if ($("#numMeses").val() === '') {
        alert("POR FAVOR INGRESE EL NUMERO DE MESES EN DEUDA PARA GENERAR LOS CORTES");
        $("#numMeses").focus();
        return;
    }
    $.get('resumencortes', {idSucursal: $("#idSucursalBusq").val(), sucursal: $("#idSucursalBusq option:selected").text(), montolimite: $("#montolimite").val(), numMeses: $("#numMeses").val()}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function generarExcelCortes() {
    if ($("#idSucursalBusq").val() === '') {
        alert("POR FAVOR SELECCIONE LA SUCURSAL SOBRE LA CUAL SE GENERAN LOS CORTES");
        $("#idSucursalBusq").focus();
        return false;
    }
    if ($("#montolimite").val() === '') {
        alert("POR FAVOR INGRESE EL LIMITE DE MONTO ADEUDADO PARA GENERAR LOS CORTES");
        $("#montolimite").focus();
        return false;
    }
    if ($("#numMeses").val() === '') {
        alert("POR FAVOR INGRESE EL NUMERO DE MESES EN DEUDA PARA GENERAR LOS CORTES");
        $("#numMeses").focus();
        return false;
    }
    return confirm(" DESEA GENERAR REPORTE DE EXCEL CON LA INFORMACION DE LOS SERVICIOS A CORTAR ? ");
}

//------------------------------------------------------------------------------

function generarOTsCorteMasivo() {
    if ($("#idSucursalAux").val() === '' || $("#montolimiteAux").val() === '' || $("#numMesesAux").val() === '') {
        alert("SE HA PRESENTADO UN INCONVENIENTE AL OBTENER LA INFORMACION DE CORTE DESDE EL SERVIDOR");
        return false;
    }
    if (confirm(" DESEA GENERAR LA OTs DE CORTE ? ")) {
        $("#formGenerarOTsCorte").submit();
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
