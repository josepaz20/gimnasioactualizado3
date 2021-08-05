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

function verInformeGenerarMensualidades() {
    if ($("#servicio").val() !== '' && $("#idSucursal").val() !== '') {
        $.get('/josandro/facturas/administracion/getinformemensualidades', {servicio: $("#servicio").val(), idSucursal: $("#idSucursal").val(), sucursal: $("#idSucursal option:selected").text(), mes: $("#mes").val(), anio: $("#anio").val()}, setFormulario);
        bloqueoAjax();
    } else {
        if ($("#servicio").val() === '') {
            alert("SELECCIONE UN SERVICIO");
            $("#servicio").focus();
            return;
        }
        if ($("#idSucursal").val() === '') {
            alert("SELECCIONE UNA SUCURSAL");
            $("#idSucursal").focus();
            return;
        }
    }
}
function verCargarpagosmesgratis() {
    if ($("#idSucursal").val() !== '' && $("#mes").val() !== '' && $("#anio").val() !== '') {
        $.get('/josandro/pagos/administracion/cargarpagosmesgratis', {idSucursal: $("#idSucursal").val(), mes: $("#mes").val(), anio: $("#anio").val()}, setFormulario);
        bloqueoAjax();
    } else {
        if ($("#idSucursal").val() === '') {
            alert("SELECCIONE UNA SUCURSAL");
            $("#idSucursal").focus();
            return;
        }
        if ($("#mes").val() === '') {
            alert("SELECCIONE UN MES");
            $("#mes").focus();
            return;
        }
        if ($("#anio").val() === '') {
            alert("SELECCIONE UN AÑO");
            $("#anio").focus();
            return;
        }
    }
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarBusqueda() {
    var identificacionBusq = $("#identificacionBusq").val();
    var nombresBusq = $("#nombresBusq").val();
    var apellidossBusq = $("#apellidossBusq").val();
    if (identificacionBusq.trim() === '') {
        if (nombresBusq.trim() === '' || apellidossBusq.trim() === '') {
            alert("PARA INICIAR LA BUSQUEDA SE REQUIERE LA IDENTIFICACION O NOMBRES Y APELLIDOS");
            return false;
        }
    }
    return true;
}

//------------------------------------------------------------------------------

function enviarFacturas() {
    if ($("input[name='checkes[]']:checked").length === 0) {
        alert("DEBE SELECCIONAR AL MENOS UNA FACTURA PARA ENVIO POR EMAIL");
        $("#checkTodos").focus();
        return;
    }
    if (confirm("¿ DESEA ENVIAR LAS FACTURAS SELECCIONADAS POR EMAIL ?")) {
        var idsFacturas = "";
        $("input[name='checkes[]']:checked").each(function () {
            idsFacturas += $(this).val() + ",";
        });
        idsFacturas = idsFacturas.substring(0, idsFacturas.length - 1);
        $.get('enviarporemail', {idsFacturas: idsFacturas}, setFacturasEnviadas, 'json');
        bloqueoAjax();
    }
}
function setFacturasEnviadas(datos) {
    if (parseInt(datos['ok']) === 1) {
        alert("LAS FACTURAS FUERON ENVIADAS POR EMAIL");
    } else {
        alert("SE HA PRESENTADO UN ERROR!! \nLAS FACTURAS NO FUERON ENVIADAS POR EMAIL");
    }
    location.reload();
}

//------------------------------------------------------------------------------

function validarGenerarExportacionPDF() {
    if ($("#idSucursalBusq").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursalBusq").focus();
        return false;
    }
    if ($("#idZonaBusq").val() === '') {
        alert("POR FAVOR SELECCIONE UNA COMUNA");
        $("#idZonaBusq").focus();
        return false;
    }
    if ($("#mesBusq").val() === '') {
        alert("POR FAVOR SELECCIONE UN MES");
        $("#mesBusq").focus();
        return false;
    }
    if ($("#anioBusq").val() === '') {
        alert("POR FAVOR SELECCIONE UN AÑO");
        $("#anioBusq").focus();
        return false;
    }
    if (confirm(" DESEA GENERAR ARCHIVO(S) PDF DE EXPORTACION ? ")) {
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function verExportacionesPDF() {
    $.get('verexportacionespdf', {}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function setFechaEmision() {
    $("#fechalimitepago").attr('min', $("#fechaemision").val());
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
        alert("POR FAVOR SELECCIONE UN AÑO");
        $("#anio").focus();
        return;
    }

//    bloqueoAjax();
}

//------------------------------------------------------------------------------

function getZonas(idSucursal) {
    $("#idBarrioBusq").html("<option value=''>Seleccione...</option>");
    if (idSucursal !== '') {
        $.get('getselectzonas', {idSucursal: idSucursal}, setZonas);
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
        $.get('getselectbarrios', {idZona: idZona}, setBarrios);
        bloqueoAjax();
    } else {
        $("#idBarrioBusq").html("<option value=''>Seleccione...</option>");
    }
}

function checkBarrios() {
    var idsBarrios = '';
    tablaBarrios.rows().nodes().to$().find('input[type="checkbox"]').each(function () {
        if ($(this).is(':checked')) {
            idsBarrios += $(this).val() + ',';
        }
    });
    $("#idsBarrio").val(idsBarrios);
}

function setBarrios(html) {
    $('#div-barrios-barrios').html(html);
    tablaBarrios = $("#tblBarrios").DataTable({
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

    //------------------------------------------------------------------------------
    // MANIPULACION DEL EVENTO DE CIERRE DEL MODAL
    //------------------------------------------------------------------------------
    $("#modalFormulario").on("hide.bs.modal", function (e) {
        return confirm("� DESEAS CERRAR EL FORMULARIO ?");
    });
}

//------------------------------------------------------------------------------

function validarBusquedaReimpresion() {
    if ($("#idSucursalBusq").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL PARA INICIAR LA BUSQUEDA");
        return false;
    }
    if ($("#identificacionBusq").val() === '' && $("#referenciapagoBusq").val() === '') {
        alert("POR FAVOR DIGITE LA IDENTIFICACION O LA REFERENCIA DE PAGO PARA INICIAR LA BUSQUEDA");
        $("#identificacionBusq").focus();
        return false;
    }
    return true;
}

//------------------------------------------------------------------------------

//-----------------------------------------------------------------------------
