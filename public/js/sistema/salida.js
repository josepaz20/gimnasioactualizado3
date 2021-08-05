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
    $.get('/josandro/inventario/salida/registrar', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idSalida) {
    $.get('/josandro/inventario/salida/detalle', {idSalida: idSalida}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idSalida) {
    $.get('/josandro/inventario/salida/eliminar', {idSalida: idSalida}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function getSalidasBusqueda() {
    var tipoBusq = $("#tipoBusq").val();
    location.href = '/josandro/inventario/salida/index/' + tipoBusq;
}

//------------------------------------------------------------------------------

function seleccionarOT() {
    var idOTBuscar = $("#idOTBuscar").val();
    var identificacionBuscar = $("#identificacionBuscar").val();
    if (idOTBuscar !== '' || identificacionBuscar !== '') {
        $.get('/josandro/ordenestrabajo/administracion/seleccionarOT', {idOTBuscar: idOTBuscar, identificacionBuscar: identificacionBuscar}, setSeleccionarOT);
        bloqueoAjax();
    } else {
        alert("POR FAVOR DIGITE UN ID OT O UNA IDENTIFICACION DE UN EMPLEADO PARA CONTINUAR CON LA BUSQUEDA !");
        $("#idOTBuscar").focus();
        return false;
    }
}
function setSeleccionarOT(datos) {
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
                "sFirst": "<i class=\'fa fa-fast-backward\' aria-hidden=\'true\' title=\'Inicio\'></i>",
                "sPrevious": "<i class=\'fa fa-step-backward\' aria-hidden=\'true\' title=\'Anterior\'></i>",
                "sNext": "<i class=\'fa fa-step-forward\' aria-hidden=\'true\' title=\'Siguiente\'></i>",
                "sLast": "<i class=\'fa fa-fast-forward\' aria-hidden=\'true\' title=\'Fin\'></i>",
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}
//------------------------------------------------------------------------------

function selectOT(idOT) {
    $.get('/josandro/ordenestrabajo/administracion/getOrdenTrabajo', {idOT: idOT}, setOT);
    bloqueoAjax();
}
function setOT(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoOT").html(datos);
    $("#idOT").val($("#idOTDetalle").val());
    $("#divInfoOT").show('slow');
}

//------------------------------------------------------------------------------

function seleccionarRecurso() {
    var idTipoRecursoBuscar = $("#idTipoRecursoBuscar").val();
    var serialBuscar = $("#serialBuscar").val();
    if (idTipoRecursoBuscar !== '' || serialBuscar !== '') {
        $.get('/josandro/inventario/recursos/seleccionarRecurso', {idTipoRecursoBuscar: idTipoRecursoBuscar, serialBuscar: serialBuscar, tipoServicio: $('#idTipoRecursoBuscar option:selected').text()}, setSeleccionarRecurso);
        bloqueoAjax();
    } else {
        alert("POR FAVOR SELECCIONE UN TIPO DE RECURSO O DIGITE UN SERIAL PARA CONTINUAR CON LA BUSQUEDA !");
        $("#idTipoRecursoBuscar").focus();
        return false;
    }
}
function setSeleccionarRecurso(datos) {
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
                "sFirst": "<i class=\'fa fa-fast-backward\' aria-hidden=\'true\' title=\'Inicio\'></i>",
                "sPrevious": "<i class=\'fa fa-step-backward\' aria-hidden=\'true\' title=\'Anterior\'></i>",
                "sNext": "<i class=\'fa fa-step-forward\' aria-hidden=\'true\' title=\'Siguiente\'></i>",
                "sLast": "<i class=\'fa fa-fast-forward\' aria-hidden=\'true\' title=\'Fin\'></i>",
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}

//------------------------------------------------------------------------------

function selectRecurso(idRecurso) {
    $.get('/josandro/inventario/recursos/getRecurso', {idRecurso: idRecurso}, setRecurso, 'json');
}
function setRecurso(datos) {
    if (datos['error'] !== '0') {
        var idRecursos = $("#idRecurso").val();
        var tr = "<tr>\n\
                    <td>" + datos['recurso']['idRecurso'] + "</td>\n\
                    <td>" + datos['tipoRecurso'] + "</td>\n\
                    <td>" + datos['recurso']['marca'] + "</td>\n\
                    <td>" + datos['recurso']['serial'] + "</td>\n\
                    <td>" + datos['recurso']['estado'] + "</td>\n\
                    <td align='center'><a href='#' title='Quitar' class='quitar'><i class='fa fa-times'></i></a></td>\n\
                </tr>";
        $("#modalAnexar").modal('hide');
        $("#divRecursosSeleccionados").show('slow');
        if (idRecursos !== '') {
            if (idRecursos.indexOf(';') >= 0) {
                var idRecursosArray = idRecursos.split(';');
                var recursoSeleccionado = 0;
                idRecursosArray.forEach(function (valor, indice, array) {
                    if (parseInt(valor) === parseInt(datos['recurso']['idRecurso'])) {
                        recursoSeleccionado = 1;
                    }
                });
                if (recursoSeleccionado === 1) {
                    alert('ESTE RECURSO YA HA SIDO SELECCIONADO !');
                } else {
                    $("#idRecurso").val(idRecursos + ';' + datos['recurso']['idRecurso']);
                    $('#tblRecursosSeleccionados > tbody').html($('#tblRecursosSeleccionados > tbody').html() + tr);
                }
            } else {
                if (parseInt(datos['recurso']['idRecurso']) !== parseInt(idRecursos)) {
                    $("#idRecurso").val(idRecursos + ';' + datos['recurso']['idRecurso']);
                    $('#tblRecursosSeleccionados > tbody').html($('#tblRecursosSeleccionados > tbody').html() + tr);
                } else {
                    alert('ESTE RECURSO YA HA SIDO SELECCIONADO !');
                }
            }
        } else {
            $("#idRecurso").val(datos['recurso']['idRecurso']);
            $('#tblRecursosSeleccionados > tbody').html($('#tblRecursosSeleccionados > tbody').html() + tr);
        }
    } else {
        alert("SE HA PRESENTAQDO UN INCONVENIENTE EN EL SISTEMA, POR FAVOR COMUNIQUESE CON EL ADMINISTRADOR.");
        return false;
    }
}

//------------------------------------------------------------------------------
function validarRegistro() {
    if ($("#idOT").val() !== '') {
        if ($("#idRecurso").val() !== '') {
            if (!confirm(" ¿ DESEA REGISTRAR ESTA SALIDA ? ")) {
                return false;
            }
        } else {
            alert("NO SE HA SELECCIONADO NINGUN RECURSO, POR FAVOR REALICE LA BUSQUEDA DE UNO O MAS RECURSOS PARA CONTINUAR CON EL REGISTRO DE ESTA SALIDA.");
            $("#idTipoRecursoBuscar").focus();
            return false;
        }
    } else {
        alert("NO SE HA SELECCIONADO UNA ORDEN DE TRABAJO, POR FAVOR REALICE LA BUSQUEDA DE UNA ORDEN DE TRABAJO PARA CONTINUAR CON EL REGISTRO DE ESTA SALIDA.");
        $("#idOTBuscar").focus();
        return false;
    }
}
//------------------------------------------------------------------------------
function limpiarBusqueda() {
    $("#formBusquedas input").each(function () {
        $(this).val('');
    });
    $("#formBusquedas select").each(function () {
        $(this).val('');
    });
}

//------------------------------------------------------------------------------

function validarFechaRegistroIncioFiltro() {
    if ($("#fechaRegistroInicioFiltro").val() !== '') {
        $("#fechaRegistroFinFiltro").val('');
        $("#fechaRegistroFinFiltro").focus();
        $("#fechaRegistroFinFiltro").attr('required', 'required');
        $("#fechaRegistroFinFiltro").attr('min', $("#fechaRegistroInicioFiltro").val());
    } else {
        $("#fechaRegistroFinFiltro").val('');
        $("#fechaRegistroFinFiltro").removeAttr('min');
        $("#fechaRegistroFinFiltro").removeAttr('required');
    }
}

//------------------------------------------------------------------------------

function validarFechaRegistroFinFiltro() {
    if ($("#fechaRegistroInicioFiltro").val() === '') {
        alert('NO HA SELECCIONADO UNA FECHA INICIO, POR FAVOR SELECCIONE UNA FECHA INICIO PARA CONTINUAR.');
        $("#fechaRegistroFinFiltro").val('');
        $("#fechaRegistroInicioFiltro").focus();
        return false;
    }
}

//------------------------------------------------------------------------------