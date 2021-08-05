//------------------------------------------------------------------------------
var cerrarModalFormulario = false;
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

function verRegistrar() {
    if ($("#idSucursalBusq").val() !== '') {
        $.get('add', {idSucursalBusq: $("#idSucursalBusq").val()}, setFormulario);
        bloqueoAjax();
    } else {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursalBusq").focus();
    }
}
function verRegistrarCobro() {
    if ($("#idCliente").length === 0 || $("#tipocliente").length === 0) {
        alert("DEBE SELECCIONAR UN CLIENTE");
        $("#idCliente").focus();
    } else {
        if ($("#idCliente").val() !== '' && $("#tipocliente").val() !== '') {
            $.get('registrarcobro', {idCliente: $("#idCliente").val(), tipocliente: $("#tipocliente").val()}, setFormulario);
            bloqueoAjax();
        } else {
            alert("SE HA PRESENTADO UN INCONVENIENTE CON EL CLIENTE SELECCIONADO, POR FAVOR RECARGUE LA PAGINA HE INTENTE DE NUEVO.");
        }
    }
}
function verDetalle(idSucursal) {
    $.get('detail', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEditar(idSucursal) {
    $.get('edit', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idSucursal) {
    $.get('delete', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
//console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarBusqueda() {
    var identificacion = $.trim($("#identificacionFiltro").val());
    var nombres = $.trim($("#nombresFiltro").val());
    var apellidos = $.trim($("#apellidosFiltro").val());
    var razonsocial = $.trim($("#razonsocialFiltro").val());
    if (identificacion === '' && nombres === '' && apellidos === '' && razonsocial === '') {
        alert("PARA INICIAR LA BUSQUEDA DEBE DIGITAR UNA DE LAS SIGUIENTES OPCIONES: \n\n * PARTE DE LA IDENTIFICACION A BUSCAR \n * NOMBRE(S) Y APELLIDO(S) \n * RAZON SOCIAL");
        $("#identificacionFiltro").focus();
        return false;
    }
    if (nombres !== '' && apellidos === '') {
        alert("PARA INICIAR LA BUSQUEDA POR NOMBRE(S) ES NECESARIO DIGITAR APELLIDO(S)");
        $("#apellidosFiltro").focus();
        return false;
    }
    if (apellidos !== '' && nombres === '') {
        alert("PARA INICIAR LA BUSQUEDA POR APELLIDO(S) ES NECESARIO DIGITAR NOMBRE(S)");
        $("#nombresFiltro").focus();
        return false;
    }
    if (identificacion !== '') {
        $.post('getabonadobyidentificacion', {identificacion: $("#identificacionFiltro").val()}, setInfoCobros, 'json');
        bloqueoAjax();
    } else if (apellidos !== '' && nombres !== '') {
        $.get('getcobrosbynombresapellidos', {nombres: nombres, apellidos: apellidos}, setInfoCobros, 'json');
        bloqueoAjax();
    } else if (razonsocial !== '') {
        $.get('getcobrosbyrazonsocial', {razonsocial: razonsocial}, setInfoCobros, 'json');
        bloqueoAjax();
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE EN LA BUSQUEDA, POR FAVOR INTENTE DE NUEVO.");
        location.reload();
    }
    return false;
}
function setInfoCobros(datos) {
    if (parseInt(datos['error']) === 0) {
        $("#infoCobros").html(datos['htmlInfoCobros']);
        $("#vlrpago").focus();
    } else {
        var msg = "";
        switch (parseInt(datos['error'])) {
            case 2:
                msg = "NO SE HAN ENCONTRADO COBROS REGISTRADOS PARA LOS CRITERIOS DE BUSQUEDA.";
                break;
            default:
                msg = "SE HA PRESENTADO UN ERROR, POR FAVOR VUELVA A INTENTARLO";
                break;
        }
        alert(msg);
//        location.reload();
    }
}

function validarBusquedaCobrosInstalacion() {
    var empleado = $.trim($("#idEmpleadoFiltro").val());
    if (empleado === '') {
        alert("PARA INICIAR LA BUSQUEDA DEBE SELECCIONAR UN EMPLEADO");
        $("#idEmpleadoFiltro").focus();
        return false;
    } else {
        $.post('getcobrosinstalaciones', {idEmpleadoFiltro: $("#idEmpleadoFiltro").val()}, setInfoCobros, 'json');
        bloqueoAjax();
    }
    return false;
}

//------------------------------------------------------------------------------

function validarAdd() {
    if ($("#idEmpresa").val() === '' && $("#idPersona").val() === '') {
        alert("DEBE SELECCIONAR UNA EMPRESA O PERSONA");
        $("#cliente").focus();
        return false;
    }
    if ($("#tarifa").val() === '') {
        alert("DEBE SELECCIONAR UNA TARIFA");
        $("#tarifaSeleccionada").focus();
        return false;
    }
    return confirm(" ¿ DESEA REGISTRAR ESTE ABONADO DE INTERNET ? ");
}

//------------------------------------------------------------------------------

function imprimirVoucher(idPago) {
    $("#divVoucher").html('');
    if (idPago !== 0) {
        $.get('getvoucher', {idPago: idPago}, setVoucher, 'json');
    }
}
function setVoucher(datos) {
    if (parseInt(datos['error']) === 0) {
        $("#divVoucher").html(datos['html']);
        $("#divVoucher").print();
    }
    setTimeout($("#divVoucher").html(''), 2000);
}

//------------------------------------------------------------------------------

function getInfoServicio() {
    var idServicioBusq = $("#idServicioBusq").val();
    if (idServicioBusq !== '') {
        var vlrpago = 0;
        if ($("#servicioChekeado_" + idServicioBusq).length > 0) {
            vlrpago = $("#servicioChekeado_" + idServicioBusq).val();
        }
        $.get('getservicio', {idServicioBusq: idServicioBusq, vlrpago: vlrpago}, setInfoServicio);
        bloqueoAjax();
    } else {
        $("#divInfoServicio").hide('slow');
        $("#divInfoServicio").html('');
    }
}
function setInfoServicio(datos) {
    $("#divInfoServicio").html(datos);
    $("#divInfoServicio").show('slow');
    setConceptoCobro();
}

//------------------------------------------------------------------------------

function setConceptoCobro() {
    $("#concepto").val('');
    $("#concepto").removeAttr('required');
    $("#concepto").attr('readonly', true);
//    $("#valorcobro").val('');
//    $("#valorcobro").removeAttr('readonly');
//    $("#valorcobro").attr('required', true);
    switch ($("#tipocobro").val()) {
        case 'Equipos':
            $("#concepto").removeAttr('readonly');
            $("#concepto").attr('required', true);
            break;
        case 'Instalacion':
            $("#concepto").val('INSTALACION SERVICIO ' + $("#tiposervicio").val().toUpperCase());
            break;
        case 'Reconexion':
            $("#concepto").val('RECONEXION SERVICIO ' + $("#tiposervicio").val().toUpperCase());
            break;
        case 'Servicio':
            $("#concepto").val($("#conceptofacturacion").val());
            break;
        case 'Traslado':
            $("#concepto").val('TRASLADO SERVICIO ' + $("#tiposervicio").val().toUpperCase());
            break;
        default :
            $("#concepto").val('');
            $("#concepto").removeAttr('readonly');
            $("#concepto").attr('required', true);
            break;
    }
}

//------------------------------------------------------------------------------

function validarGenerarCobro() {
    if ($("#idServicio").length === 0) {
        alert("POR FAVOR SELECCIONE UN SERVICIO");
        $("#idServicioBusq").focus();
        return false;
    }
    if (confirm(' DESEA GENERAR ESTE COBRO ?')) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function validarPagoInstalaciones() {
    if ($("#idEmpleado").length === 0) {
        $("#contcobros").val(0);
        $("#idscobros").val('');
        $("#totalpago").val(0);
        $("#valor").val('');
        $("input:checkbox").prop('checked', false);
        alert("NO SE HAN SELECCIONADO UN EMPLEADO PARA REGISTRAR EL PAGO. \nPOR FAVOR INTENTELO DE NUEVO");
        $("#idEmpleadoFiltro").focus();
        return false;
    }
    if ($("#idscobros").val() === '') {
        alert("NO SE HAN SELECCIONADO COBROS PARA REGISTRAR EL PAGO. \nPOR FAVOR INTENTELO DE NUEVO");
        return false;
    }
    if (parseInt($("#totalpago").val()) === 0) {
        alert("EL VALOR DE LAS FACTURAS A PAGAR ES CERO. \nNO ES POSIBLE REGISTRAR EL PAGO");
        $("#totalpago").focus();
        return false;
    }
    if (parseInt($("#valor").val()) <= 0) {
        alert("EL VALOR DEL PAGO A REGISTRAR ES CERO. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
        $("#valor").focus();
        return false;
    }
    if (parseInt($("#valor").val()) > parseInt($("#totalpago").val().replace(/\,/g, ''))) {
        alert("EL VALOR A PAGAR SUPERA EL MONTO DE LOS COBROS SELECCIONADOS. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
        $("#valor").focus();
        return false;
    }
    var msg = "---------------------------------------------- \n"
            + "       INFORMACION DEL RECAUDO \n"
            + "---------------------------------------------- \n"
            + "CANT. PAGOS: " + $("#contcobros").val() + "\n"
            + "VLR. TOTAL A PAGAR: " + $("#totalpago").val() + "\n"
            + "---------------------------------------------- \n"
            + "  *** VLR. PAGO A REGISTRAR: $ " + formatoMoneda($("#valor").val()) + "\n"
            + "---------------------------------------------- \n"
            + "¿ DESEA REGISTRAR ESTE PAGO ?";
    if (confirm(msg)) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function validarBusquedaPagosExtra() {
    var empleado = $.trim($("#idEmpleadoFiltro").val());
    if (empleado === '') {
        alert("PARA INICIAR LA BUSQUEDA DEBE SELECCIONAR UN EMPLEADO");
        $("#idEmpleadoFiltro").focus();
        return false;
    } else {
        $.post('getpagosextra', {idEmpleadoFiltro: $("#idEmpleadoFiltro").val()}, setInfoCobros, 'json');
        bloqueoAjax();
    }
    return false;
}

//------------------------------------------------------------------------------

function verDetalleOT(idOT) {
    $.get('/josandro/ordenestrabajo/administracion/detalle', {idOT: idOT}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function validarPagoExtra() {
    if ($("#idEmpleado").length === 0) {
        $("#contcobros").val(0);
        $("#idscobros").val('');
        $("#totalpago").val(0);
        $("#valor").val('');
        $("input:checkbox").prop('checked', false);
        alert("NO SE HAN SELECCIONADO UN EMPLEADO PARA REGISTRAR EL PAGO. \nPOR FAVOR INTENTELO DE NUEVO");
        $("#idEmpleadoFiltro").focus();
        return false;
    }
    if ($("#idscobros").val() === '') {
        alert("NO SE HAN SELECCIONADO PAGOS PARA REGISTRAR EN EL SISTEMA. \nPOR FAVOR INTENTELO DE NUEVO");
        return false;
    }
    if (parseInt($("#totalpago").val()) === 0) {
        alert("EL VALOR DE LAS FACTURAS A PAGAR ES CERO. \nNO ES POSIBLE REGISTRAR EL PAGO");
        $("#totalpago").focus();
        return false;
    }
    if (parseInt($("#valor").val()) <= 0) {
        alert("EL VALOR DEL PAGO A REGISTRAR ES CERO. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
        $("#valor").focus();
        return false;
    }
    if (parseInt($("#valor").val()) > parseInt($("#totalpago").val().replace(/\,/g, ''))) {
        alert("EL VALOR A PAGAR SUPERA EL MONTO DE LOS COBROS SELECCIONADOS. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
        $("#valor").focus();
        return false;
    }
    var msg = "---------------------------------------------- \n"
            + "       INFORMACION DEL RECAUDO \n"
            + "---------------------------------------------- \n"
            + "CANT. PAGOS: " + $("#contcobros").val() + "\n"
            + "VLR. TOTAL A PAGAR: " + $("#totalpago").val() + "\n"
            + "---------------------------------------------- \n"
            + "  *** VLR. PAGO A REGISTRAR: $ " + formatoMoneda($("#valor").val()) + "\n"
            + "---------------------------------------------- \n"
            + "¿ DESEA REGISTRAR ESTE PAGO ?";
    if (confirm(msg)) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function seleccionarEntregaEfectivo(idPagoExtra, checkbox) {
    var operacion = '';
    if ($(checkbox).is(':checked')) {
        operacion = 'seleccionar';
    } else {
        operacion = 'quitar';
    }
    $.get('getcobrospagoextra', {idPagoExtra: idPagoExtra, operacion: operacion}, setCobrosPagoExtra, 'json');
    bloqueoAjax();
}
function setCobrosPagoExtra(datos) {
    if (parseInt(datos['error']) === 0) {
        var totalpago = 0;
        if ($("#tablaCobros tbody tr").length === 1) {
            $("#tablaCobros tbody").html(datos['tablaCobros']);
        } else {
            $("#tablaCobros tbody").append(datos['tablaCobros']);
        }
        $("#contcobros").val(parseInt($("#contcobros").val()) + parseInt(datos['contcobros']));
        if ($("#idscobros").val() === '') {
            $("#idscobros").val(datos['idscobros']);
        } else {
            $("#idscobros").val($("#idscobros").val() + ',' + datos['idscobros']);
        }
        totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
        $("#totalpago").val(formatoMoneda(totalpago + parseInt(datos['totalpago'])));
        $("#valor").val(parseInt($("#valor").val()) + parseInt(datos['valor']));
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, POR FAVOR INTENTE REGISTRAR LA ENTREGA DE EFECTIVO DE NUEVO");
    }
}

//------------------------------------------------------------------------------

function verificarPagosBanco() {
    if ($("#idBanco") === '') {
        alert("POR FAVOR SELECCIONE BANCO DESDE EL CUAL SE HIZO EL REPORTE.");
        $("#idBanco").focus();
        return false;
    }
    if ($("#campoclave") === '') {
        alert("POR FAVOR SELECCIONE EL CAMPO CLAVE DEL REPORTE.");
        $("#campoclave").focus();
        return false;
    }
    if ($.trim($("#archivo").val()) === '') {
        alert(" POR FAVOR SELECCIONE UN ARCHIVO CON EXTENSION << .CSV >>");
        $("#archivo").focus();
        return false;
    }
    if (confirm(" DESEA INICIAR LA VERIFICACION DE ESTE ARCHIVO ? ")) {
        $("#btnImportar").attr('disabled', true);
        $("#btnVerificar").attr('disabled', true);
        var inputFile = document.getElementById("archivo");
        var file = inputFile.files[0];
        var formData = new FormData();
        formData.append("archivo", file);
        formData.append("idBanco", $("#idBanco").val());
        formData.append("campoclave", $("#campoclave").val());
        $.ajax({
            url: "/josandro/pagos/administracion/verificar",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (respuestaServidor) {
            var verificacion = respuestaServidor['verificacion'];
            var contErrores = parseInt(respuestaServidor['contErrores']);
            if (contErrores > 0) {
                alert("SE HAN PRESENTADO ERRORES EN EL ARCHIVO, POR FAVOR VERIFIQUE LA LISTA DE ERRORES.");
                $("#btnImportar").attr('disabled', true);
                $("#btnVerificar").removeAttr('disabled');
            } else {
                alert("EL ARCHIVO HA SIDO VERIFICADO CON EXITO Y NO SE HAN PRESENTADO ERRORES. \n\nSI DESEA REGISTRAR ESTOS PAGOS EN JOSANDRO POR FAVOR DE CLICK EN EL BOTON << REGISTRAR PAGOS >>");
                $("#btnImportar").removeAttr('disabled');
                $("#btnVerificar").attr('disabled', true);
            }
            $("#erroresVerificacion").html(verificacion);
        });
        bloqueoAjax();
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function limpiarResultados() {
    if (confirm(" DESEA LIMPIAR LOS RESULTADOS DE VERIFICACION ? ")) {
        $("#erroresVerificacion").html('');
    }
}

//------------------------------------------------------------------------------

function reiniciar() {
    if (confirm(" DESEA RE-INICIAR EL PROCESO ? ")) {
        location.reload();
    }
}

//------------------------------------------------------------------------------

function registrarPagosBanco() {
    if (confirm(" DESEA INICIAR EL PROCESO DE IMPORTACION ? ")) {
        $("#btnImportar").attr('disabled', true);
        $("#btnVerificar").attr('disabled', true);
        $.post('/josandro/pagos/administracion/importar', {campoclave: $("#campoclave").val()}, setImportar, 'json');
        bloqueoAjax();
    }
    return false;
}

//------------------------------------------------------------------------------

function setImportar(respuestaServidor) {
    $("#btnImportar").attr('disabled', true);
    $("#btnVerificar").attr('disabled', true);
    var errores = respuestaServidor['errores'];
    if (errores.length === 0) {
        alert(respuestaServidor['msg']);
        location.reload();
    } else {
        alert("SE HAN PRESENTADO ALGUNOS ERRORES DURANTE EL PROCESO DE REGISTRO DE PAGOS.");
        $.each(errores, function (index, error) {
            $("#erroresVerificacion").html($("#erroresMigracion").html() + "<br>" + error);
        });
    }
}

//------------------------------------------------------------------------------

function calcularPago() {
    if ($("#idCliente").length === 0) {
        alert("NO SE TIENE UN CLIENTE AL CUAL REGITRAR EL PAGO");
        $("#identificacionFiltro").focus();
        return false;
    }
    if ($('input[name=selectServicio]:checked', '#tablaServicios').length !== 1) {
        alert("POR FAVOR SELECCIONE SOLO UN SERVICIO AL CUAL DESEE REGISTRAR EL PAGO");
        return false;
    }
    if ($.trim($("#vlrpago").val()) === '') {
        alert("POR FAVOR DIGITE EL VALOR A RECAUDAR");
        $("#vlrpago").focus();
        return false;
    }
    if (parseInt($.trim($("#vlrpago").val())) === 0) {
        alert("EL VALOR A RECAUDAR DEBE SER MAYOR QUE CERO");
        $("#vlrpago").focus();
        return false;
    }
    var idServicio = $('input[name=selectServicio]:checked', '#tablaServicios').val();
    $.get('calcularpago', {idCliente: $("#idCliente").val(), idServicio: idServicio, vlrRecaudo: $("#vlrpago").val()}, setCobros, 'json');
    bloqueoAjax();
}
function setCobros(datos) {
    $("#infoServiciosCobros").html('');
    if (parseInt(datos['error']) === 0) {
        $("#infoServiciosCobros").html(datos['html']);
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE AL CALCULAR LOS PAGOS, POR INTENTE DE NUEVO");
        location.reload();
    }
}

//------------------------------------------------------------------------------

function verRegistrarAdelanto() {
    if ($("#idCliente").length === 0) {
        alert("DEBE SELECCIONAR UN CLIENTE");
        $("#identificacionFiltro").focus();
        return false;
    }
    if (parseInt($("#vlrDevolucion").val()) <= 0) {
        alert("EL VALOR DE LA DEVOLUCION ES MENOR O IGUAL QUE CERO, NO ES POSIBLE REGISTRAR UN ADELANTO");
        $("#vlrDevolucion").focus();
        return false;
    }
    if ($("#idCliente").val() !== '') {
        var cobrosCheckeados = [];
        var idCobro = '';
        $('#tablaCobros input[type=checkbox]:checked').each(function () {
            idCobro = this.value;
            cobrosCheckeados.push({idCobro: idCobro, vlrpago: $("#vlrestepago_" + idCobro).val()});
        });
        $.get('validarregistraradelanto', {idServicio: $("#idServicioPago").val(), servicio: $("#servicioPago").val(), vlrAdelanto: $("#vlrDevolucion").val().replace(/\,/g, ''), cobrosCheckeados: cobrosCheckeados}, setRegistroAdelanto, 'json');
        bloqueoAjax();
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE CON EL CLIENTE SELECCIONADO, POR FAVOR RECARGUE LA PAGINA HE INTENTE DE NUEVO.");
    }
}
function setRegistroAdelanto(datos) {
    switch (parseInt(datos['error'])) {
        case 0:
            var adelanto = parseInt(datos['vlrAdelanto']);
            var trAdelanto = '<tr>' +
                    '<td>' + datos['idServicio'] + '</td>' +
                    '<td>' + datos['servicio'] + '</td>' +
                    '<td style="color: blue; font-weight: bold">' + formatoMoneda(adelanto) + '</td>' +
                    '<td><i onclick="quitarAdelanto(this)" title="ELIMINAR ESTE ADELANTO" style="color: red; cursor: pointer" class="fa fa-times"></i></td>' +
                    '</tr>';
            if ($("#tdNoAdelantos").length > 0) {
                $("#tblAdelantos tbody").html(trAdelanto);
            } else {
                $("#tblAdelantos tbody").append(trAdelanto);
            }
            $("#vlrAdelantoAux").val(adelanto);

            var vlr = 0;
            var vlrDevolucion = parseInt($("#vlrDevolucion").val().replace(/\,/g, ''));

            if (vlrDevolucion >= adelanto) {
                vlr = vlrDevolucion - adelanto;
                $("#vlrDevolucion").val(formatoMoneda(vlr));
            } else {
                $("#vlrDevolucion").val(0);
            }

            var totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
            vlr = totalpago + adelanto;
            $("#totalpago").val(formatoMoneda(vlr));
            break;
        case 1:
            alert("SE HA PRESENTADO UN INCONVENIENTE Y NO ES POSIBLE REGISTRAR EL ADELANTO. [ID Servicio]");
            break;
        case 2:
            alert("SE HA PRESENTADO UN INCONVENIENTE Y NO ES POSIBLE REGISTRAR EL ADELANTO. [Vlr Adelanto]");
            break;
        case 3:
            alert("NO ES POSIBLE REGISTRAR EL ADELANTO. \nEL SERVICIO TIENE TIENE UNA DEUDA SIN PAGO");
            break;
    }

}

//------------------------------------------------------------------------------

function seleccionarCobro(idCobro, valor, checkbox) {
    $("#vlrestepago_" + idCobro).attr('readonly', true);
    if ($(checkbox).is(':checked')) {
        $("#vlrestepago_" + idCobro).attr('max', valor);
        var devolucion = parseInt($("#vlrDevolucion").val().replace(/\,/g, ''));
        if (valor > devolucion) {
            $("#vlrestepago_" + idCobro).val(devolucion);
        } else {
            $("#vlrestepago_" + idCobro).val(valor);
        }
        $("#vlrestepago_" + idCobro).focus();
    } else {
        $("#vlrestepago_" + idCobro).val(0);
    }
    limpiarAdelantos();
    recalcularPago();
}

//------------------------------------------------------------------------------

function recalcularPago() {
    var totalpago = 0;
    var efectivo = parseInt($("#efectivo").val().replace(/\,/g, ''));
    var devolucion = efectivo;
    var vlrestecobro = 0;
    var idCobro = 0;
    var idsCobros = '';
    var contcobros = 0;
    var vlrmax = 0;

    $('#tablaCobros input[type=checkbox]').each(function () {
        idCobro = this.value;
        if ($("#vlrestepago_" + idCobro).length === 0) {
            alert("SE HA PRESENTADO UN INCONVENIENTE AL CALCULAR LOS VALORES DE PAGO");
            return false;
        }
        if ($.trim($("#vlrestepago_" + idCobro).val()).length === 0) {
            alert("POR FAVOR DIGITE EL VALOR DEL RECAUDO");
            $("#vlrestepago_" + idCobro).focus();
            return false;
        }
        vlrestecobro = parseInt($.trim($("#vlrestepago_" + idCobro).val()));
        if (vlrestecobro < 0) {
            alert("LOS VALORES A RECAUDAR NO PUEDEN SER NEGATIVOS");
            $("#vlrestepago_" + idCobro).focus();
            return false;
        }
        vlrmax = parseInt($("#vlrestepago_" + idCobro).attr('max'));
        if (vlrestecobro > vlrmax) {
            alert("EL VALOR A RECAUDAR ES MAYOR QUE EL VALOR DEL COBRO (Saldo) O MAYOR QUE EL MONTO DE DEVOLUCION");
            $("#vlrestepago_" + idCobro).val('');
            $("#vlrestepago_" + idCobro).focus();
            return false;
        }
        if (this.checked) {
            if (vlrestecobro === 0) {
                alert("EL VALOR DEL RECAUDO DEBE SER MAYOR QUE CERO");
                $("#vlrestepago_" + idCobro).focus();
                return false;
            }
            if (devolucion > 0) {
                if (vlrestecobro > devolucion) {
                    vlrestecobro = devolucion;
                }
                totalpago = totalpago + vlrestecobro;
                devolucion = devolucion - vlrestecobro;
                contcobros++;
                if (idsCobros === '') {
                    idsCobros = idCobro;
                } else {
                    idsCobros = idsCobros + ',' + idCobro;
                }
            } else {
                alert("NO SE POSIBLE SELECCIONAR ESTE COBRO. MONTO DE EFECTIVO INSUFIMIENTE");
            }
        }
    });
    var vlrAdelanto = parseInt($("#vlrAdelantoAux").val());
    totalpago = totalpago + vlrAdelanto;
    $("#contcobros").val(contcobros);
    $("#idscobros").val(idsCobros);
    $("#totalpago").val(formatoMoneda(totalpago));
//    $("#efectivo").val(totalpago);
//    $("#vlrDevolucion").val(formatoMoneda(devolucion));
    calcularDevolucion();
    return true;
}

//------------------------------------------------------------------------------

function validarRegistrarPago() {
    if ($("#idCliente").length === 0) {
        $("#contcobros").val(0);
        $("#idscobros").val('');
        $("#totalpago").val(0);
        $("input:checkbox").prop('checked', false);
        alert("NO SE HAN SELECCIONADO UN CLIENTE PARA REGISTRAR EL PAGO. \nPOR FAVOR INTENTELO DE NUEVO");
        $("#identificacion").focus();
        return false;
    }
    if (recalcularPago()) {
        var efectivo = parseInt($("#efectivo").val().replace(/\,/g, ''));
        var totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
        if ($("#idscobros").val() === '') {
            alert("NO SE HAN SELECCIONADO COBROS PARA REGISTRAR EL PAGO. \nPOR FAVOR INTENTELO DE NUEVO");
            return false;
        }
        if (efectivo <= 0) {
            alert("EL EFECTIVO ENTREGADO POR EL USUARIO ES CERO. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
            $("#efectivo").focus();
            return false;
        }
        if (totalpago === 0) {
            alert("EL VALOR A RECAUDAR ES CERO. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
            $("#totalpago").focus();
            return false;
        }
        if (totalpago > efectivo) {
            alert("EL VALOR A PAGAR SUPERA EL MONTO DE EFECTIVO ENTREGADO POR EL CLIENTE. \nNO ES POSIBLE REGISTRAR ESTE PAGO");
            $("#totalpago").focus();
            return false;
        }

        var vlrAdelanto = parseInt($("#vlrAdelantoAux").val());
        totalpago = totalpago - vlrAdelanto;

        var msg = "EFECTIVO:    " + $("#efectivo").val() + "\n"
                + "---------------------------------------------- \n"
                + "PAGOS:        " + formatoMoneda(totalpago) + "\n"
                + "ADELANTO:  " + formatoMoneda(vlrAdelanto) + "\n"
                + "---------------------------------------------- \n"
                + "DEVOLUCION:  " + $("#vlrDevolucion").val() + "\n"
                + "---------------------------------------------- \n"
                + "|| *** VLR. RECAUDO:  $ " + $("#totalpago").val() + "   *** ||\n"
                + "********************************************** \n"
                + "   ¿ DESEA REGISTRAR ESTE PAGO ?";
        if (confirm(msg)) {
            bloqueoAjax();
            return true;
        } else {
//            limpiarAdelantos();
            return false;
        }
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function calcularDevolucion() {
    var efectivo = parseInt($("#efectivo").val().replace(/\,/g, ''));
    var totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
//    var vlradelanto = parseInt($("#vlrAdelantoAux").val());
    if (efectivo > 0 && totalpago >= 0) {
        $("#vlrDevolucion").val(formatoMoneda(efectivo - totalpago));
    }
}

//------------------------------------------------------------------------------

function ajustarPago(idCobro, saldo) {
    var vlrDevolucion = parseInt($("#vlrDevolucion").val().replace(/\,/g, ''));
    if (vlrDevolucion >= 0) {
        alert("NO ES POSIBLE REALIZAR AJUSTES A LOS VALORES DE LOS PAGOS. \nEL VALOR A DEVOLVER ES MAYOR O IGUAL QUE CERO POR LO QUE SOLO SE PUEDEN REALIZAR ADELANTOS");
        $("#vlrDevolucion").focus();
        return false;
    }
    if ($("#check_" + idCobro).is(':checked')) {
        var ajuste = saldo + vlrDevolucion;
        if (ajuste > 0) {
            $("#vlrestepago_" + idCobro).val(ajuste);
            return recalcularPago();
        } else {
            alert("NO ES POSIBLE AJUSTAR ESTE PAGO POR FAVOR VERIFIQUE LOS VALORES DE LOS DEMAS PAGOS Y EL EFECTIVO ENTREGADO POR EL CLIENTE");
            return false;
        }
    } else {
        alert("PARA AJUSTAR EL PAGO PRIMERO DEBE SELECCIONARLO");
        $("#check_" + idCobro).focus();
        return false;
    }
}

//------------------------------------------------------------------------------

function validarGenerarAdelanto() {
    if ($("#idServicio").length === 0) {
        alert("POR FAVOR SELECCIONE EL SERVICIO AL QUE SE LE VA A REGISTRAR EL ADELANTO");
        $("#idServicioBusq").focus();
        return false;
    }
    if ($("#deuda").length > 0) {
        if (parseInt($("#deuda").val().replace(/\,/g, '')) > 0) {
            alert("NO ES POSIBLE REGISTRAR UN ADELANTO PARA ESTE SERVICIO \nEL SERVICIO TIENE UNA DEUDA DE $" + $("#deuda").val());
            return false;
        }
    }
    var adelanto = parseInt($("#valorcobro").val().replace(/\,/g, ''));
    if (adelanto <= 0) {
        alert("NO ES POSIBLE REGISTRAR EL ADELANTO, VALOR DEL ADELANTO DEBE SER MAYOR QUE CERO");
        return false;
    }
    if (confirm(" DESEA CARGAR ESTE ADELANTO AL PRESENTE PAGO ? ")) {
        var trAdelanto = '<tr>' +
                '<td>' + $("#idServicio").val() + '</td>' +
                '<td>' + $("#conceptofacturacion").val() + '</td>' +
                '<td style="color: blue; font-weight: bold">' + $("#valorcobro").val() + '</td>' +
                '<td><i onclick="quitarAdelanto(this)" title="ELIMINAR ESTE ADELANTO" style="color: red; cursor: pointer" class="fa fa-times"></i></td>' +
                '</tr>';
        if ($("#tdNoAdelantos").length > 0) {
            $("#tblAdelantos tbody").html(trAdelanto);
        } else {
            $("#tblAdelantos tbody").append(trAdelanto);
        }

        var vlr = 0;
        var vlrDevolucion = parseInt($("#vlrDevolucion").val().replace(/\,/g, ''));

        if (vlrDevolucion >= adelanto) {
            vlr = vlrDevolucion - adelanto;
            $("#vlrDevolucion").val(formatoMoneda(vlr));
        } else {
            $("#vlrDevolucion").val(0);
        }

        var totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
        vlr = totalpago + adelanto;
        $("#totalpago").val(formatoMoneda(vlr));

        cerrarModalFormulario = true;
        $("#divContenido").html('');
        $('#modalFormulario').modal('hide');
    }
    return false;
}

//------------------------------------------------------------------------------

function quitarAdelanto(fila) {
    var objFila = $(fila).parents().get(1);
//    var tds = $(objFila).children('td').get(2);
//    var objvlradelanto = $(tds).children('b').get(0);
//    var vlradelanto = $(objvlradelanto).html().replace(/\,/g, '');
    var vlradelanto = $("#vlrAdelantoAux").val();
    var vlrDevolucion = parseInt($("#vlrDevolucion").val().replace(/\,/g, ''));
    var vlr = vlrDevolucion + vlradelanto;
    $(objFila).remove();
    $("#vlrAdelantoAux").val(0);
    $("#vlrDevolucion").val(formatoMoneda(vlr));
    var totalpago = parseInt($("#totalpago").val().replace(/\,/g, ''));
    vlr = totalpago - vlradelanto;
    $("#totalpago").val(formatoMoneda(vlr));
    if ($("#tblAdelantos tbody tr").length === 0) {
        $("#tblAdelantos tbody").html('<td id="tdNoAdelantos" colspan="4" style="text-align: center; padding: 5px">NO HAY ADELANTOS REGISTRADOS PARA ESTE PAGO</td>');
    }
}

//------------------------------------------------------------------------------

function setBillete(denominacion) {
    var efectivo = 0;
    if ($.trim($("#vlrpago").val()).length !== 0) {
        if (!isNaN($("#vlrpago").val())) {
            efectivo = parseInt($("#vlrpago").val());
            if ($("#sumarBilletes").is(':checked')) {
                efectivo = efectivo + denominacion;
            } else {
                efectivo = denominacion;
            }
        }
    } else {
        efectivo = denominacion;
    }
    $("#vlrpago").val(efectivo);
}

//------------------------------------------------------------------------------

function regresarAbusqueda() {
    if ($("#identificacion").length > 0) {
        if ($.trim($("#identificacion").val()) !== '') {
            $.post('getabonadobyidentificacion', {identificacion: $.trim($("#identificacion").val())}, setInfoCobros, 'json');
            bloqueoAjax();
        } else {
            location.reload();
        }
    } else {
        location.reload();
    }
}

//------------------------------------------------------------------------------

function limpiarAdelantos() {
    $("#tblAdelantos tbody").html('<td id="tdNoAdelantos" colspan="4" style="text-align: center; padding: 5px">NO HAY ADELANTOS REGISTRADOS PARA ESTE PAGO</td>');
    $("#vlrAdelantoAux").val(0);
}


//------------------------------------------------------------------------------

function seleccionarCobroInstlacion(idCobro, valor, checkbox) {
    var totalpago = 0;
    if ($(checkbox).is(':checked')) {
        if ($("#idscobros").val() === '') {
            $("#idscobros").val(idCobro);
        } else {
            $("#idscobros").val($("#idscobros").val() + ',' + idCobro);
        }
        totalpago = parseInt($("#totalpago").val().replace(/\,/g, '')) + parseInt(valor);
        $("#totalpago").val(formatoMoneda(totalpago));
        $("#valor").val(totalpago);
        $("#contcobros").val(parseInt($("#contcobros").val()) + 1);
    } else {
        var idscobros = $("#idscobros").val().split(',');
        idscobros.splice($.inArray(idCobro.toString(), idscobros), 1);
        $("#idscobros").val(idscobros.toString());
        totalpago = parseInt($("#totalpago").val().replace(/\,/g, '')) - parseInt(valor);
        $("#totalpago").val(formatoMoneda(totalpago));
        $("#valor").val(totalpago);
        $("#contcobros").val(parseInt($("#contcobros").val()) - 1);
    }
    if (totalpago < 0) {
        alert("SE HA PRESENTADO UN ERROR EN EL SISTEMA, POR FAVOR INTENTE REGISTRAR EL PAGO NUEVAMENTE");
        location.reload();
    }
}

//------------------------------------------------------------------------------


