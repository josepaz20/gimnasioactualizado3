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
function verDetalle(idServicio) {
    $.get('detalle', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verEditar(idServicio) {
    $.get('editar', {idServicio: idServicio}, setFormulario);
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

function validarBusqueda() {
    if ($("#identificacionFiltro").val() === '' && $("#nombresFiltro").val() === '' && $("#apellidosFiltro").val() === '') {
        alert("PARA INICIAR LA BUSQUEDA DEBE DIGITAR PARTE DE LA IDENTIFICACION A BUSCAR O NOMBRE(S) Y APELLIDO(S)");
        $("#identificacionFiltro").focus();
        return false;
    }
    if ($("#nombresFiltro").val() !== '' && $("#apellidosFiltro").val() === '') {
        alert("PARA INICIAR LA BUSQUEDA POR NOMBRE(S) ES NECESARIO DIGITAR APELLIDO(S)");
        $("#apellidosFiltro").focus();
        return false;
    }
    if ($("#apellidosFiltro").val() !== '' && $("#nombresFiltro").val() === '') {
        alert("PARA INICIAR LA BUSQUEDA POR APELLIDO(S) ES NECESARIO DIGITAR NOMBRE(S)");
        $("#nombresFiltro").focus();
        return false;
    }
    return true;
}

//------------------------------------------------------------------------------

function setTipoBusqueda() {
    $("#identificacionBusq").val('');
    $("#nombresBusq").val('');
    $("#apellidosBusq").val('');
    switch (parseInt($("#buscarpor").val())) {
        case 1:
            $("#divBusqIdentificacion").show('slow');
            $("#divBusqNombresapellidos").hide('slow');
            break;
        case 2:
            $("#divBusqNombresapellidos").show('slow');
            $("#divBusqIdentificacion").hide('slow');
            break;
        default:
            break;
    }
}

//------------------------------------------------------------------------------

function getInfoCambioPlan(buscarpor) {
    switch (buscarpor) {
        case 'identificacion':
            if ($("#identificacionBusq").val() === '') {
                alert("POR FAVOR DIGITE LA IDENTIFICACION PARA INICIAR LA BUSQUEDA");
                $("#identificacionBusq").focus();
            } else {
                if ($("#identificacionBusq").val().length < 3) {
                    alert("LA IDENTIFICACION DEBE TENER AL MENOS 3 DIGITOS");
                    $("#identificacionBusq").focus();
                } else {
                    $.get('getinfocambioplan', {buscarpor: buscarpor, identificacion: $("#identificacionBusq").val()}, setInfoCambioPlan, 'json');
                    bloqueoAjax();
                }
            }
            break;
        case 'nombresapellidos':
            if ($("#nombresBusq").val() === '') {
                alert("POR FAVOR DIGITE NOMBRE(S) PARA INICIAR LA BUSQUEDA");
                $("#nombresBusq").focus();
                return;
            } else {
                if ($("#nombresBusq").val().length < 3) {
                    alert("EL NOMBRE DEBE TENER AL MENOS 3 LETRAS");
                    $("#nombresBusq").focus();
                    return;
                }
            }
            if ($("#apellidosBusq").val() === '') {
                alert("POR FAVOR DIGITE APELLIDO(S) PARA INICIAR LA BUSQUEDA");
                $("#apellidosBusq").focus();
                return;
            } else {
                if ($("#apellidosBusq").val().length < 3) {
                    alert("EL APELLIDO DEBE TENER AL MENOS 3 LETRAS");
                    $("#apellidosBusq").focus();
                    return;
                }
            }
            break;
    }
}
//------------------------------------------------------------------------------
function setInfoCambioPlan(datos) {
    $("#modalAnexar").modal('hide');
    $("#divBusquedaOK").hide('slow');
    if (parseInt(datos['error']) === 0) {
        if (parseInt(datos['seleccionar']) === 0) {
            $("#divInfoCambioplan").html(datos['html']);
            $("#divBusquedaOK").show('slow');
            $("#idServicioInternoBusq").focus();
        } else {
            setSeleccionarPersona(datos['html']);
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, JOSANDRO NO HA PODIDO REALIZAR LA OPERACION SOLICITADA.");
    }
}

function setSeleccionarPersona(datos) {
    $("#divAnexar").html(datos);//pone los datos
    $("#tblSeleccionar").DataTable({
        responsive: true,
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sLengthMenu": "Mostrar: _MENU_ registros por pagina",
            "sZeroRecords": "NO SE HA ENCONTRADO INFORMACION",
            "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> registros <br>TOTAL REGISTROS: <b>_TOTAL_</b> Registros</b>",
            "sInfoEmpty": "Mostrando 0 A 0 registros",
            "sInfoFiltered": "(Filtrados de un total de <b>_MAX_</b> registros)",
            "sLoadingRecords": "CARGANDO...",
            "sProcessing": "EN PROCESO...",
            "sSearch": "Buscar:",
            "sEmptyTable": "NO HAY INFORMACION DISPONIBLE PARA LA TABLA",
            "oPaginate": {
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}
function selectPersona(identificacion, buscarpor) {
    $.get('getinfocambioplan', {buscarpor: buscarpor, identificacion: identificacion}, setInfoCambioPlan, 'json');
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function getInfoServicio() {
    if ($("#idServicioInternoBusq").val() !== '') {
        $.get('getinfoservicio', {infoIdServicio: $("#idServicioInternoBusq").val()}, setInfoServicio, 'json');
        bloqueoAjax();
    } else {
        $("#idServicioInterno").val('');
        $("#idServicio").val('');
        $("#detalleservicio").val('');
        $("#idSucursal").val('');
        $("#sucursal").val('');
        $("#idZona").val('');
        $("#zona").val('');
        $("#idBarrio").val('');
        $("#barrio").val('');
        $("#dirdestino").val('');
        $("#idTarifaOLD").val('');
        $("#tiposervicio").val('');
        $("#idTarifaNEW").val('');
        $("#divInfoTarifa").html('');
    }
}
function setInfoServicio(datos) {
    if (parseInt(datos['error']) === 0) {
        $("#idServicioInterno").val(datos['idServicioInterno']);
        $("#idServicio").val(datos['idServicio']);
        $("#detalleservicio").val(datos['detalleservicio']);
        $("#idSucursal").val(datos['idSucursal']);
        $("#sucursal").val(datos['sucursal']);
        $("#idZona").val(datos['idZona']);
        $("#zona").val(datos['zona']);
        $("#idBarrio").val(datos['idBarrio']);
        $("#barrio").val(datos['barrio']);
        $("#dirdestino").val(datos['dirdestino']);
        $("#idTarifaOLD").val(datos['idTarifa']);
        $("#tiposervicio").val(datos['tiposervicio']);
    } else {
        $("#idServicioInterno").val('');
        $("#idServicio").val('');
        $("#detalleservicio").val('');
        $("#idSucursal").val('');
        $("#sucursal").val('');
        $("#idZona").val('');
        $("#zona").val('');
        $("#idBarrio").val('');
        $("#barrio").val('');
        $("#dirdestino").val('');
        $("#idTarifaOLD").val('');
        $("#tiposervicio").val('');
        $("#idTarifaNEW").val('');
        $("#divInfoTarifa").html('');
    }
}

//------------------------------------------------------------------------------

function seleccionartarifa() {
    var idTipoServicio;
    switch ($("#tiposervicio").val()) {
        case 'Internet':
            idTipoServicio = 1;
            break;
        case 'Television':
            idTipoServicio = 2;
            break;
        case 'HD':
            idTipoServicio = 3;
            break;
        default:
            idTipoServicio = 0;
            break;
    }
    $.get('../../tarifas/administracion/seleccionar', {idSucursalBusq: $("#idSucursal").val(), idTipoServicio: idTipoServicio, numtarifa: 1}, setSeleccionarTarifa);
    bloqueoAjax();
}
function setSeleccionarTarifa(datos) {
    $("#divAnexar").html(datos);//pone los datos
    $("#tblSeleccionar").DataTable({
        responsive: true,
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sLengthMenu": "Mostrar: _MENU_ registros por pagina",
            "sZeroRecords": "NO SE HA ENCONTRADO INFORMACION",
            "sInfo": "Mostrando <b>_START_</b> a <b>_END_</b> registros <br>TOTAL REGISTROS: <b>_TOTAL_</b> Registros</b>",
            "sInfoEmpty": "Mostrando 0 A 0 registros",
            "sInfoFiltered": "(Filtrados de un total de <b>_MAX_</b> registros)",
            "sLoadingRecords": "CARGANDO...",
            "sProcessing": "EN PROCESO...",
            "sSearch": "Buscar:",
            "sEmptyTable": "NO HAY INFORMACION DISPONIBLE PARA LA TABLA",
            "oPaginate": {
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}

function selectTarifa(idTarifa, numtarifa) {
    $.get('../../tarifas/administracion/getTarifa', {idTarifa: idTarifa, numtarifa: numtarifa}, setTarifa, 'json');
    bloqueoAjax();
}
function setTarifa(datos) {
    $("#idTarifaNEW").val(datos['idTarifa']);
    $("#divInfoTarifa").html(datos['html']);
    $("#modalAnexar").modal('hide');
}

//------------------------------------------------------------------------------

function validarRegistrar() {
    if ($("#idServicioInternoBusq").length === 0) {
        alert("POR FAVOR SELECCIONE UN CLIENTE");
        $("#buscarpor").focus();
        return false;
    }
    if ($("#idServicio").val() === '' || $("#idServicioInterno").val() === '') {
        alert("POR FAVOR SELECCIONE EL SERVICIO AL QUE SE LE REGISTRARA EL CAMBIO DE PLAN");
        return false;
    }
    if ($("#idTarifaNEW").val() === '') {
        alert("POR FAVOR SELECCIONE LA NUEVA TARIFA");
        $("#idTarifaNEW").focus();
        return false;
    }
    if (confirm(" DESEA REGISTRAR ESTE CAMBIO DE PLAN ? ")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------


//------------------------------------------------------------------------------

