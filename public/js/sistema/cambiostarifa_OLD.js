var datosCliente = null;
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
    //Funcion ubicacda en sweetalertCambiotarifa.js
    sweetAIdentificacion();
}
function verDetalle(idServicio) {
    $.get('detalle', {idServicio: idServicio}, setFormulario);
    bloqueoAjaxSwal();
}
function verEditar(idServicio) {
    $.get('editar', {idServicio: idServicio}, setFormulario);
    bloqueoAjaxSwal();
}
function verEliminar(idServicio) {
    $.get('delete', {idServicio: idServicio}, setFormulario);
    bloqueoAjaxSwal();
}
function verConfirmar(idServicio, idCambioPlan) {
    $.get('confirmar', {idServicio: idServicio, idCambioPlan: idCambioPlan}, setFormulario);
    bloqueoAjaxSwal();
}

function setFormulario(datos) {
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function searchCliente(identificacion) {
    if (identificacion !== null) {
        $.get("registrar", {identificacion: identificacion}, setFormulario);
        //Funcion ubicacda en SwtSistema.js
        bloqueoAjaxSwal();
    } else {
        //Funcion ubicacda en sweetalertCambiotarifa.js
        sweetAClipboardEmpty();
    }
}
function setDatosCliente(datos) {
    $("#idCliente").val(datos['infoCliente']['idcliente']);
    $("#cliente").val(datos['infoCliente']['cliente']);
    $("#tipocliente").val(datos['infoCliente']['tipocliente']);
    $("#identificacion").val(datos['infoCliente']['identificacioncliente']);
    $("#idServicioBusq").html(datos['selectServiciosCliente']);
    $("#idServicioBusq").removeAttr('disabled');
    $("#idServicioBusq").attr('required', true);
}
function EmptyCliente() {
    $("#idCliente").val("");
    $("#cliente").val("NO SE ENCONTRO");
    $("#tipocliente").val("NO SE ENCONTRO");
    $("#identificacion").val("");
    $("#idServicioBusq").val("");
    $("#idServicioBusq").attr('disabled', 'disabled');
    EmptyServicios();
}

//------------------------------------------------------------------------------
function getInfoServicio(idServicio) {
    if (idServicio !== '') {
        $.get('getinfoservicio', {idServicio: idServicio}, setInfoServicio, 'json');
        //Funcion ubicacda en SwtSistema.js
        bloqueoAjaxSwal();
    } else {
        EmptyServicios();
    }
}
function setInfoServicio(datos) {

    if (parseInt(datos['error']) === 0) {
        $("#detalleservicio").val(datos['detalleservicio']);

        $('#idServicio').attr('value', datos['idServicio']);
        $('#idSucursal').attr('value', datos['idSucursal']);
        $('#idTarifaOLD').attr('value', datos['idTarifaOld']);

        $("#sucursal").val(datos['sucursal']);
        $("#idEmpleado").focus();
        $("#btnSeleccionarTarifa").removeAttr('disabled');
        $("#btnRegistrar").removeAttr('disabled');
    } else {
        EmptyServicios();
    }
}
function EmptyServicios() {
    $("#detalleservicio").val('');
    $("#sucursal").val('');

    $('#idServicio').removeAttr('value');
    $('#idSucursal').removeAttr('value');
    $('#idTarifaOLD').removeAttr('value');
    $('#idTarifaNEW').removeAttr('value');
    $('#idServicioInterno').removeAttr('value');

    $("#btnSeleccionarTarifa").attr('disabled', 'disabled');
    $("#btnRegistrar").attr('disabled', 'disabled');
    if ($("#idTipoServicioTarifa").length > 0) {
        EmptyTarifa();
    }
}
//------------------------------------------------------------------------------

function getInfoTarifa() {
    if ($("#idServicio").val() === '') {
        sweetAEmpty("POR FAVOR SELECCIONE UN SERVICIO");
        $("#idServicioBusq").focus();
        return;
    }
    if ($("#idSucursal").val() === '') {
        sweetAEmpty("POR FAVOR SELECCIONE UN SERVICIO");
        $("#idServicio").focus();
        return;
    }
    var idServicioBusq = $('#idServicio option:selected').text().split(' ');
    var id;
    switch ($.trim(idServicioBusq[0])) {
        case "[INTERNET]":
            id = 1;
            break;
        case "[TELEVISION]":
            id = 2;
            break;
        case "[HD]":
            id = 3;
            break;
    }
    $.get('seleccionar', {idSucursal: $("#idSucursal").val(), idTipoServicio: id}, setSeleccionar);
    //Funcion ubicacda en SwtSistema.js
    bloqueoAjaxSwal();
}

function setSeleccionar(datos) {

    $("#divSeleccionar").html(datos);
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
                "sFirst": "<i class=\'fa fa-fast-backward\' aria-hidden=\'true\' title=\'Inicio\'></i>",
                "sPrevious": "<i class=\'fa fa-step-backward\' aria-hidden=\'true\' title=\'Anterior\'></i>",
                "sNext": "<i class=\'fa fa-step-forward\' aria-hidden=\'true\' title=\'Siguiente\'></i>",
                "sLast": "<i class=\'fa fa-fast-forward\' aria-hidden=\'true\' title=\'Fin\'></i>",
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalSeleccionar').modal('show');
}
//------------------------------------------------------------------------------
function selectTarifa(idTarifa) {
    $.get('getTarifa', {idTarifa: idTarifa}, setTarifa, 'json');
    //Funcion ubicacda en SwtSistema.js
    bloqueoAjaxSwal();
}
function setTarifa(datos) {

    EmptyTarifa();
    if (parseInt(datos['error']) === 0) {
        $("#divInfoTarifa").html(datos['html']);
        $('#idTarifaNEW').attr('value', datos['idTarifaNew']);
        $("#btnRegistrar").removeAttr('disabled');

    } else {
        SSEmptyDataError();
    }
    $("#modalSeleccionar").modal('hide');
}
function EmptyTarifa() {
    $("#idTarifa").val('');
    $("#divInfoTarifa").html('');
    $("#btnRegistrar").attr('disabled', 'disabled');
}
//------------------------------------------------------------------------------

function validarRegistrar() {
    if ($("#idServicio").length <= 0 || $("#idServicio").val() === "" || isNaN($("#idServicio").val())) {
        sweetAEmpty("servicio");
        return false;
    }
    if ($("#idTarifaNEW").length <= 0 || $("#idTarifaNEW").val() === "" || isNaN($("#idTarifaNEW").val())) {
        sweetAEmpty("LA TARIFA SELECCIONADA NO CUMPLE CON LAS CONDICIONES DE REGISTRO");
        return false;
    }
    if ($("#idTarifaOLD").length <= 0 || $("#idTarifaOLD").val() === "" || isNaN($("#idTarifaOLD").val())) {
        sweetAEmpty("EL SERVICIO SELECCIONADO NO CUMPLE CON LAS CONDICIONES DE REGISTRO");
        return false;
    }
    if ($("#idTipoServicioTarifa").length <= 0 || $("#idTipoServicioTarifa").val() === "" || isNaN($("#idTipoServicioTarifa").val())) {
        sweetAEmpty("POR FAVOR SELECCIONE LA NUEVA TARIFA");
        return false;
    }
    if (!sweetASave())
        return false;
}

//------------------------------------------------------------------------------

