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

function verRegistrarCobro() {
    if ($("#idCliente").val() !== '') {
        $.get('registrar', {idCliente: $("#idCliente").val(), identificacionRegistro: $("#identificacion").val()}, setFormulario);
        bloqueoAjax();
    } else {
        alert("POR FAVOR, INGRESE UNA IDENTIFICACION Y REALIZE LA BUSQUEDA");
    }
}

//------------------------------------------------------------------------------

function verRegistrarDescuento() {
    if ($("#idCliente").val() !== '') {
        $.get('registrardescuento', {idCliente: $("#idCliente").val(), identificacionRegistro: $("#identificacion").val()}, setFormulario);
        bloqueoAjax();
    } else {
        alert("POR FAVOR, INGRESE UNA IDENTIFICACION Y REALIZE LA BUSQUEDA");
        $("#identificacionBusq").focus();
    }
}

//------------------------------------------------------------------------------

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}
//------------------------------------------------------------------------------

function validarRegistroCobro() {
    if ($.trim($("#idServicio").val()).length === 0) {
        alert(" POR FAVOR SELECCIONE UN SERVICIO ");
        return false;
    }
    if (confirm(" DESEA REGISTRAR ESTE COBRO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function verGenerarNotaCredito(idCobro) {
    $.get('generarnotacredito', {idCobro: idCobro}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function setSucursalTxt() {
    $("#sucursaltxt").val($("#idSucursal option:selected").text());
}

//------------------------------------------------------------------------------

function verInformeGenerarMensualidades() {
    if ($("#idSucursal").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    if ($("#mes").val() === '') {
        alert("POR FAVOR SELECCIONE UN MES");
        $("#mes").focus();
        return;
    }
    if ($("#anio").val() === '') {
        alert("POR FAVOR SELECCIONE UN A?O");
        $("#anio").focus();
        return;
    }
    $.get('getinformemensualidades', {idSucursal: $("#idSucursal").val(), mes: $("#mes").val(), anio: $("#anio").val()}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function generarExcel() {
    if ($("#idSucursal").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    if ($("#mes").val() === '') {
        alert("POR FAVOR SELECCIONE UN MES");
        $("#mes").focus();
        return;
    }
    if ($("#anio").val() === '') {
        alert("POR FAVOR SELECCIONE UN A?O");
        $("#anio").focus();
        return;
    }
    $.post('/josandro/reportes/administracion/generar', {reporteBusq: 6, idSucursalBusq: $("#idSucursal").val(), mesBusq: $("#mes").val(), anioBusq: $("#anio").val()});
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function seleccionarServicio(idServicio) {
    $("#idServicio").val(idServicio);
}

//------------------------------------------------------------------------------

function validarRegistroNotaCredito() {
    if ($("#idCobro").val() === '' || $("#idCobro").val() === 0) {
        alert(" NO ES POSIBLE GENERAR ESTA NOTA CREDITO [Cobro no encontrado]. ");
        return false;
    }
    if (parseInt($("#valorDescuento").val()) > parseInt($("#saldo").val())) {
        alert(" EL VALOR A DESCONTAR ES MAYOR QUE EL VALOR DEL SALDO DEL COBRO. ");
        $("#valorDescuento").focus();
        return false;
    }
    if (confirm(" DESEA REGISTRAR ESTA NOTA CREDITO ? ")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function verDetalleNotaCredito(idCobro) {
    $.get('detallenotacredito', {idCobro: idCobro}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function validarRegistroDescuento() {
    var msg = "";
    if ($.trim($("#idClienteAux").val()).length === 0) {
        alert(" SE HA PRESENTADO UN INCONVENIENTE CON LA INFORMACION DEL CLIENTE, NO ES POSIBLE REGISTRAR EL DESCUENTO.");
        return false;
    }
    if (parseInt($.trim($("#valor").val())) <= 0) {
        alert(" EL VALOR DEL DESCUENTO A REGISTRAR ES MENOR O IGUAL A CERO, NO ES POSIBLE REGISTRAR EL DESCUENTO.");
        $("#valor").focus();
        return false;
    }
    if ($.trim($("#idServicio").val()).length === 0) {
        msg = "NO SE HAN SELECCIONADO SERVICIOS. \nEL DESCUENTO SE CARGARA A LA CUENTA DEL CLIENTE SIN CLASIFICAR, TENGA EN CUENTA QUE PARA HACER EFECTIVO ESTE DESCUENTO SE TENDRA QUE CLASIFICAR. \n\n";
    } else {
        if ($.trim($("#idsCobros").val()).length === 0) {
//            alert(" SE HA PRESENTADO UN INCONVENIENTE CON LA INFORMACION DE LOS COBROS, NO ES POSIBLE REGISTRAR EL DESCUENTO.");
//            return false;
            $("#valor").removeAttr('readonly');
            $("#valor").attr('required', true);
        }
        msg = "SE HA SELECCIONADO EL SERVICIO ID: " + $("#idServicio").val() + " \nEL DESCUENTO SE CARGARA A CADA COBRO DE ESTE SERVICIO, SEGUN LO REGISTRADO POR USTED. \n\n";
    }
    if (confirm(msg + "  DESEA REGISTRAR ESTE DESCUENTO ? ")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function verDetalleDescuento(idDescuento) {
    $.get('detalledescuento', {idDescuento: idDescuento}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------


function eliminarDescuento(idDescuento) {
    if (parseInt(idDescuento) !== 0) {
        if (confirm("? DESEA ELIMINAR ESTE DESCUENTO ?")) {
            $.get('eliminardescuento', {idDescuento: idDescuento}, setEliminarDescuento);
            bloqueoAjax();
        }
    } else {
        alert("NO SE HA RECIBIDO LA INFORMACION NECESARIA PARA ELIMINAR ESTE DESCUENTO.");
        return false;
    }
}
function setEliminarDescuento(datos) {
    if (parseInt(datos['ok']) === 1) {
        alert("EL DESCUENTO HA SIDO ELIMINADO DE JOSANDRO");
        location.reload();
    } else {
        alert("EL DESCUENTO NO PUDO SER ELIMINADO, POR FAVOR INTENTE MAS TARDE, DE LO CONTRARIO COMUNIQUESE CON EL ADMINISTRADOR DEL SISTEMA.");
        return false;
    }

}

//------------------------------------------------------------------------------

function seleccionarServicioDescuento(idServicio, checkbox, idDescuento) {
    $("#valor").val(0);
    if ($(checkbox).is(':checked')) {
        $('#tblServicios input[type=checkbox]').removeAttr('checked');
        $(checkbox).prop("checked", true);
        $("#idServicio").val(idServicio);
        $.get('getcobrosdescuento', {idServicio: idServicio, idDescuento: idDescuento}, setcobrosservicio);
        bloqueoAjax();
    } else {
        $(checkbox).prop("checked", false);
        $("#idServicio").val('');
        $("#idCobro").val('');
        $("#infocobrosservicio").html('');
        $("#valor").removeAttr('readonly');
        $("#valor").attr('required', true);
    }
}

function setcobrosservicio(html) {
    var contCobros = 0;
    var totalaplicar = 0;
    $("#infocobrosservicio").html(html);
    $("#tblCobrosDescuento input").each(function () {
        var vlrdescuento = parseInt($(this).val());
        if (!isNaN(vlrdescuento)) {
            totalaplicar = totalaplicar + vlrdescuento;
        }
        contCobros++;
    });
    if (contCobros === 0) {
        $("#valor").removeAttr('readonly');
        $("#valor").attr('required', true);
    } else {
        console.log('aqyui')
        $("#valor").removeAttr('required');
        $("#valor").attr('readonly', true);
    }
    if ($("#totalaplicar").length > 0) {
        $("#totalaplicar").val(formatoMoneda(totalaplicar));
        var totaldescuento = $("#totaldescuento").val().replace(/\,/g, '');
        $("#diferencia").val(parseInt(totaldescuento) - totalaplicar);
    }
}

function seleccionarCobroDescuento(idCobro, saldo, checkbox) {
    if ($(checkbox).is(':checked')) {
//        $('#tblCobrosDescuento input[type=checkbox]').removeAttr('checked');
//        $(checkbox).prop("checked", true);
        $("#idsCobros").val($("#idsCobros").val() + idCobro + ';');
//        $("#valor").val(saldo);
        console.log($("#valor").attr('max'));
        $("#valor").attr('max', parseInt($("#valor").attr('max')) + saldo);
    } else {
        $(checkbox).prop("checked", false);
        $("#idCobro").val('');
        $("#valor").val('');
        $("#valor").removeAttr('max');
    }
}

//------------------------------------------------------------------------------

function clasificarDescuento(idDescuento) {
    $.get('clasificardescuento', {idDescuento: idDescuento}, setFormulario);
}

//------------------------------------------------------------------------------

function validarClasificarDescuento() {
    if ($.trim($("#idServicio").val()).length === 0) {
        alert(" POR FAVOR SELECCIONE UN SERVICIO ");
        return false;
    }
    if ($.trim($("#idCobro").val()).length === 0) {
        alert(" POR FAVOR SELECCIONE UN COBRO ");
        return false;
    }
    if (confirm(" DESEA APLICAR ESTE DESCUENTO AL COBRO SELECCIONADO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function aprobarDescuento(idDescuento) {
    $.get('aprobardescuento', {idDescuento: idDescuento}, setFormulario);
}

//------------------------------------------------------------------------------

function validarAprobarDescuento() {
    if ($("#diferencia").length === 0) {
        alert("INFORMACION NO VALIDA, NO ES POSIBLE APROBAR ESTE DESCUENTO");
        return false;
    }
    if (parseInt($("#diferencia").val()) < 0) {
        alert("NO ES POSIBLE APROBAR ESTE DESCUENTO. EL VALOR A APLICAR ES MAYOR QUE EL VALOR REGISTRADO");
        return false;
    }
    if (confirm(" DESEA APROBAR ESTE DESCUENTO ? ")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function sumarDescuento() {
    var totalaplicar = 0;
    $("#tblCobrosDescuento input").each(function () {
        var vlrdescuento = parseInt($(this).val());
        if (!isNaN(vlrdescuento)) {
            totalaplicar = totalaplicar + vlrdescuento;
        }
    });
    $("#tdTotalDescuento").html(formatoMoneda(totalaplicar));
    $("#valor").val(totalaplicar);
    if ($("#totalaplicar").length > 0) {
        $("#totalaplicar").val(formatoMoneda(totalaplicar));
        var totaldescuento = $("#totaldescuento").val().replace(/\,/g, '');
        $("#diferencia").val(parseInt(totaldescuento) - totalaplicar);
    }
}

//------------------------------------------------------------------------------
