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

function formatoMoneda(cnt, cents) {
    cnt = cnt.toString().replace(/\$|\u20AC|\,/g, '');
    if (isNaN(cnt)) {
        return 0;
    }
    var sgn = (cnt == (cnt = Math.abs(cnt)));
    cnt = Math.floor(cnt * 100 + 0.5);
    cvs = cnt % 100;
    cnt = Math.floor(cnt / 100).toString();
    if (cvs < 10) {
        cvs = '0' + cvs;
    }
    for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++) {
        cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + ',' + cnt.substring(cnt.length - (4 * i + 3));
    }
    return (((sgn) ? '' : '-') + cnt) + (cents ? '.' + cvs : '');
}

//------------------------------------------------------------------------------

function verDetalleApertura(idApertura) {
    $.get('detalleapertura', {idApertura: idApertura}, setFormulario);
    bloqueoAjax();
}
function verConfirmarApertura(idApertura, idSucursalBusq) {
    $.get('confirmarapertura', {idApertura: idApertura, idSucursalBusq: idSucursalBusq}, setFormulario);
    bloqueoAjax();
}
function verCerrarCaja(idCaja) {
    $.get('cerrar', {idCaja: idCaja}, setFormulario);
    bloqueoAjax();
}
function verRegistrarApertura() {
    $.get('registrarapertura', {}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarCerrarCaja() {
    var msg = "SE DISPONE A CERRAR LA CAJA DEL SEÑOR <<" + $("#cajero").val() + ">>" + " EL VALOR DEL CIERRE ES DE $ " + $("#saldoactual").val() + "\n\n  ¿ DESEA REALIZAR ESTE CIERRE ?";
    return confirm(msg);
}


//------------------------------------------------------------------------------

function getCajeros() {
    if ($("#idSucursalBusq").val() !== '') {
        $.get('getCajeros', {idSucursal: $("#idSucursalBusq").val()}, setCajeros);
        bloqueoAjax();
    }
}
function setCajeros(respuesta) {
    $("#idCajaBusq").html(respuesta);
}

//------------------------------------------------------------------------------

function verificarAperturas() {
    if ($("#idCaja").val() !== '') {
        $.get('verificaraperturas', {idCaja: $("#idCaja").val()}, setValidacionAperturas, 'json');
        bloqueoAjax();
    } else {
        $("#btnRegistrarApertura").attr('disabled', true);
    }
}
function setValidacionAperturas(respuesta) {
    if (parseInt(respuesta['ok']) === 1) {
        $("#btnRegistrarApertura").removeAttr('disabled');
    } else {
        $("#btnRegistrarApertura").attr('disabled', true);
        alert("LA CAJA SELECCIONADA TIENE UNA APERTURA SIN CIERRE. \n\n NO ES POSIBLE REGISTRAR ESTA NUEVA APERTURA");
    }
}

//------------------------------------------------------------------------------

function validarRegistroApertura() {
    return confirm(" ¿ DESEA REGISTRAR ESTA APERATURA ? ");
}

//------------------------------------------------------------------------------

function validarConfirmarApertura() {
    return confirm(" ¿ DESEA CONFIRMAR ESTA APERATURA ? ");
}

//------------------------------------------------------------------------------


//------------------------------------------------------------------------------

