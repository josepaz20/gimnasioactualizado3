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
}

//------------------------------------------------------------------------------

function verRegistrar(idOT) {
    if (parseInt(idOT) === 0) {
        alert("SE HA PRESENTADO UN ERROR CON LA ORDEN DE TRABAJO ASOCIADA \nPOR FAVOR INTENTE DE NUEVO");
        location.href = '../../inicio';
        return false;
    }
    $.get('../add', {idOT: idOT}, setFormulario);
    bloqueoAjax();
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
function verReasignar(idOT) {
    $.get('reasignar', {idOT: idOT}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function seleccionarCliente() {
    var idSucursal = $("#idSucursal").val();
    var tipoCliente = $("#tipocliente").val();
    if (idSucursal === '') {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    if (tipoCliente === '') {
        alert("DEBE SELECCIONAR UN TIPO DE CLIENTE");
        $("#tipocliente").focus();
        return;
    }
    if (tipoCliente === 'Empresa') {
        $.get('/josandro/empresas/administracion/seleccionar', {idSucursalBusq: idSucursal}, setSeleccionarEmpresa);
    } else {
        $.get('/josandro/personas/administracion/seleccionar', {idSucursalBusq: idSucursal}, setSeleccionarPersona);
    }
    bloqueoAjax();
}
function setSeleccionarEmpresa(datos) {
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
    $('#modalAnexar').modal('show');
}

function selectEmpresa(idEmpresa) {
    $.get('/josandro/empresa/administracion/getEmpresa', {idEmpresa: idEmpresa}, setEmpresa);
}
function setEmpresa(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoCliente").html(datos);
    $("#fk_persona_id_factura").val(0);
    $("#fk_empresa_id_factura").val($("#pk_empresa_id").val());
    $("#cliente").val($("#razonsocial").val());
    $("#ubicacion").val($("#pk_municipio_id option:selected").html() + '-' + $("#pk_departamento_id option:selected").html());
    calcularTotalesConcepto();
    calcularTotales();
}
//------------------------------------------------------------------------------
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

function selectPersona(idPersona) {
    $.get('/josandro/personas/administracion/getPersona', {idPersona: idPersona}, setPersona);
}
function setPersona(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoCliente").html(datos);
    $("#idEmpresa").val(0);
    $("#idPersona").val($("#idPersonaSelect").val());
    $("#idSucursal").val($("#idSucursalSelect").val());
    $("#idZona").val($("#idZonaSelect").val());
    getBarrios($("#idZonaSelect").val(), $("#idBarrioSelect").val());
    $("#dirdestino").val($("#direccion").val());
    $("#cliente").val('');
}

//------------------------------------------------------------------------------

function mostrarInfoDetalleCliente() {
    if ($("#divInfoCliente").is(":visible")) {
        $("#mas_menos").attr('class', 'fa fa-plus-circle');
        $("#divInfoCliente").hide('slow');
    } else {
        $("#mas_menos").attr('class', 'fa fa-minus-circle');
        $("#divInfoCliente").show('slow');
    }
    $("#cliente").focus();
}

//------------------------------------------------------------------------------

function getBarrios(idZona, idBarrio) {
    $("#idBarrio").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('/josandro/barrios/administracion/getBarrios', {idZona: idZona, idBarrio: idBarrio}, setBarrios);
        bloqueoAjax();
    }
}
function setBarrios(datos) {
    $("#idBarrio").html(datos);
}

//------------------------------------------------------------------------------

function seleccionarTarifa() {
    var idSucursal = $("#idSucursal").val();
    if (idSucursal === '') {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    $.get('../tarifas/seleccionar', {idSucursalBusq: idSucursal}, setSeleccionarTarifa);
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

function selectTarifa(idTarifa) {
    $.get('../tarifas/getTarifa', {idTarifa: idTarifa}, setTarifa);
    bloqueoAjax();
}
function setTarifa(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoTarifa").html(datos);
    $("#tarifaSeleccionada").val($("#nombretarifa").val());
    $("#conceptofacturacion").val($("#nombretarifa").val());
    $("#idTarifa").val($("#idTarifaSelect").val());
    $("#tarifa").val($("#valortarifa").val());
    $("#velsubida").val($("#velsubidatarifa").val());
    $("#velbajada").val($("#velbajadatarifa").val());
    $("#unidadanchobanda").val($("#unidadanchobandatarifa").val());
    $("#u").val($("#velbajada").val());
}
//------------------------------------------------------------------------------

function mostrarInfoDetalleTarifa() {
    if ($("#divInfoTarifa").is(":visible")) {
        $("#mas_menos_1").attr('class', 'fa fa-plus-circle');
        $("#divInfoTarifa").hide('slow');
    } else {
        $("#mas_menos_1").attr('class', 'fa fa-minus-circle');
        $("#divInfoTarifa").show('slow');
    }
    $("#tarifa").focus();
}

//------------------------------------------------------------------------------

function setInstalarGratis(gratis) {
    if (parseInt(gratis) === 1) {
        $("#pagoinstalacion").val(0);
        $("#modalidadpago").val('Factura Instalacion');
        $("#divInstalarGratis").hide('slow');
    } else {
        $("#pagoinstalacion").val('');
        $("#modalidadpago").val('');
        $("#divInstalarGratis").show('slow');
    }
}

//------------------------------------------------------------------------------

function setDireccion() {
    var direccion = "";
    direccion = $("#parte1").val() + " " + $("#numero1").val();
    if ($("#letra1").val() !== '') {
        direccion += $("#letra1").val();
    }
    direccion += " # " + $("#numero2").val();
    if ($("#letra2").val() !== '') {
        direccion += $("#letra2").val();
    }
    direccion += " - " + $("#numero3").val();
    if ($("#parte2").val() !== '') {
        direccion += " " + $("#parte2").val();
    }
    $("#dirinstalacion").val(direccion);
}

//------------------------------------------------------------------------------

function validarAdd() {
    if ($("#idOT").val() === '' || $("#idOT").val() === '0') {
        alert("SE HA PRESENTADO UN ERROR AL RECUPERAR LA INFORMACION DE LA ORDEN DE TRABAJO. \nPOR FAVOR ACTULICE LA PAGINA E INTENTE DE NUEVO.");
        return false;
    }
    return confirm(" ¿ DESEA ANEXAR ESTA TAREA A LA PRESENTE ORDEN DE TRABAJO ? ");
}

function validarReasignacion() {
    if ($("#idEmpleado").val() === $("#idempleadoasignado").val()) {
        alert("EL EMPLEADO A RE-ASIGNAR ES EL MISMO QUE SE ENCUENTRA ASIGNADO A LA ORDEN DE TRABAJO. \nPOR FAVOR SELECCIONE OTRO EMPLEADO");
        $("#idEmpleado").focus();
        return false;
    }
    return confirm(" ¿ DESEA RE-ASIGNAR ESTA ORDEN DE TRABAJO ? ");
}

//------------------------------------------------------------------------------

function  setInfoLegalizar(idContrato, cliente) {
    console.log(idContrato + cliente)
    $("#idServicioLegal").val(idContrato);
    $("#clienteLegal").val(cliente);
    $("#soporteLegal").val('');
}

//------------------------------------------------------------------------------
