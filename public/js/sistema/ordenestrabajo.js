//------------------------------------------------------------------------------

var preguntarCierreModal = 1;

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

function setFechasFiltro(campo) {
    if ($("#fechainiFiltro").val() !== '' && $("#fechafinFiltro").val() !== '') {
        if ($("#fechainiFiltro").val() > $("#fechafinFiltro").val()) {
            alert("LA FECHA DE INICIO NO PUEDE SER SUPERIOR A LA FECHA FIN.");
            $(campo).val('');
            $(campo).focus();
            return false;
        }
    }
}

//------------------------------------------------------------------------------

function verAsignarOT(idOT, desde) {
    $.get('asignar', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function verAsignarEquipoOT(idOT, desde) {
    $.get('asignarequipo', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function verSolucionar(idOT, desde) {
    $.get('solucionar', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function verSupervisar(idOT, desde) {
    $.get('supervisar', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function verRegistrar() {
    $.get('registrar', {}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function verRegistrarPago(idOT, desde) {
    $.get('registrarpago', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}
//------------------------------------------------------------------------------

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function agregarTipoEvidencia() {
    if ($("#tiposEvidencia").val() !== '') {
        var id = $("#tiposEvidencia").val();
        var tipo = $("#tiposEvidencia option:selected").text();
        if ($("#idsEvidencias").val().indexOf(id) !== -1) {
            alert("ESTA EVIDENCIA YA ESTA ASIGNADA A LA PRESENTE OT \n\n NO ES POSIBLE AGREGARLA DE NUEVO");
            return;
        }
        var nuevo = "<tr>" +
                "<td>" + id + "</td>" +
                "<td>" + tipo + "</td>" +
                "<td><a href='#' class='quitarEvidencia' title='Quitar esta evidencia de la OT'><i class='fa fa-times' aria-hidden='true'></i></a></td>" +
                "</tr>";
        $("#tblEvidencias tbody").append(nuevo);
        $("#idsEvidencias").val($("#idsEvidencias").val() + id + ',');
        $("#numEvidencias").val(parseInt($("#numEvidencias").val()) + 1);
    } else {
        alert('DEBE SELECCIONAR UNA EVIDENCIA');
        $("#tiposEvidencia").focus();
    }
}

//------------------------------------------------------------------------------

function setNuevaTarea() {
    var ban = true;
    $("#divTareas").find('textarea').each(function () {
        if ($(this).val() === '') {
            alert("POR FAVOR, INDIQUE LA DESCRIPCION DE LAS NUEVAS TAREAS PARA PODER AGREGAR UNA NUEVA");
            $(this).focus();
            ban = false;
            return false;
        }
    });
    if (ban) {
        var contTarea = parseInt($("#contTareas").val()) + 1;
        var numTarea = parseInt($("#numTareas").val()) + 1;
        var nueva = '<div id="divTarea_' + contTarea + '" class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12" for="tarea_' + contTarea + '">Tarea ' + contTarea + ' <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea id="tarea_' + contTarea + '" name="tarea_' + contTarea + '" placeholder="Describa la tarea a realizar" class="form-control" required></textarea><button onclick="eliminarTarea(' + contTarea + ')" class="btn btn-link" style="float: right"><i class="fa fa-times"></i> Eliminar esta Tarea</button> <br><div class="ln_solid"></div></div></div>';
        $("#divTareas").append(nueva);
        $("#contTareas").val(contTarea);
        $("#numTareas").val(numTarea);
    }
    return false;
}

function eliminarTarea(contTarea) {
    $("#divTarea_" + contTarea).remove();
    $("#numTareas").val(parseInt($("#numTareas").val()) - 1);
}

//------------------------------------------------------------------------------

function agregarTipoRecurso() {
    if ($("#tiposRecurso").val() !== '') {
        var id = $("#tiposRecurso").val();
        var tipo = $("#tiposRecurso option:selected").text();
        if ($("#idsRecursosAsignados").val().indexOf(id) !== -1) {
            alert("ESTE RECURSO YA ESTA ASIGNADO A LA PRESENTE OT \n\n NO ES POSIBLE AGREGARLO DE NUEVO");
            return;
        }
        var nuevo = "<tr>" +
                "<td>" + id + "</td>" +
                "<td><input type='text' id='tiporecurso_" + id + "' name='tiporecurso_" + id + "' value='" + tipo + "' class='form-control' readonly></td>" +
                "<td><input type='number' id='cantidad_" + id + "' name='cantidad_" + id + "' min='1' class='form-control' onblur='verificarExistencias(this.id, " + id + ")' required></td>" +
                "<td><a href='#' class='quitarRecurso' title='Quitar este recurso de la OT'><i class='fa fa-times' aria-hidden='true'></i></a></td>" +
                "</tr>";
        $("#tblRecursos tbody").append(nuevo);
        $("#idsRecursosAsignados").val($("#idsRecursosAsignados").val() + id + ',');
        $("#numRecursos").val(parseInt($("#numRecursos").val()) + 1);
    } else {
        alert('DEBE SELECCIONAR UN RECURSO');
        $("#tiposRecurso").focus();
    }
}

//------------------------------------------------------------------------------

function validarAsignarOT() {
    if ($("#idEmpleado").val() === '') {
        alert("POR FAVOR, SELECCIONE EL EMPLEADO AL CUAL SE VA ASIGNAR ESTA ORDEN DE TRABAJO");
        $("#idEmpleado").focus();
        return false;
    }

    var msgconfirm = "EMPLEADO ASIGNADO: \n" + $("#idEmpleado option:selected").text()
            + "\n\nEVIDENCIAS SOLICITADAS: " + $("#numEvidencias").val()
            + "\n\nRECURSOS ASIGNADOS: " + $("#numRecursos").val()
            + "\n\n  DESEA ASIGNAR ESTA ORDEN DE TRABAJO ? ";
    if (confirm(msgconfirm)) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function activarOT(idOT) {
    if (confirm(" DESEA ACTIVAR ESTA ORDEN DE TRABAJO ? ")) {
        $.get('activar', {idOT: idOT}, setActivacion, 'json');
        bloqueoAjax();
    }
}
function setActivacion(respuesta) {
    if (parseInt(respuesta['ok']) === 1) {
        alert("[ OK ] - ORDEN DE TRABAJO ACTIVADA EN JOSANDRO");
    } else {
        alert("[ ERROR ] - ORDEN DE TRABAJO NO ACTIVADA EN JOSANDRO");
    }
    location.reload();
}

//------------------------------------------------------------------------------

function subirEvidencia(idEvidencia) {
    if (document.getElementById("fileEvidencia_" + idEvidencia).files.length === 0) {
        alert("DEBE SELECCIONAR UN ARCHIVO");
        $("#fileEvidencia_" + idEvidencia).focus();
        return false;
    }
    if (confirm("� DESEA SUBIR ESTA EVIDENCIA AL SERVIDOR ?")) {
        var inputFile = document.getElementById("fileEvidencia_" + idEvidencia);
        var file = inputFile.files[0];
        var formData = new FormData();
        formData.append("archivoEvidencia", file);
        formData.append("idEvidencia", idEvidencia);
        $.ajax({
            url: "/josandro/ordenestrabajo/administracion/subirevidencia",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (respuestaServidor) {
            if (parseInt(respuestaServidor['ok']) === 1) {
                var opciones = "Evidencia subida a Servidor: &nbsp; <a href='#' onclick='' title='Eliminar esta Evidencia del Servidor'><i class='fa fa-close'></i></a>&nbsp;&nbsp;"
                        + "<a href='/josandro/ordenestrabajo/administracion/descargarEvidencia/" + idEvidencia + "' title='Ver esta Evidencia desde el Servidor' target='_blank'><i class='fa fa-eye'></i></a>";
                $("#tdSubirEvidencia_" + idEvidencia).html(opciones);
            }
            alert(respuestaServidor['msg']);
        });
        bloqueoAjax();
    }
}

//------------------------------------------------------------------------------

function validarSolucionar() {
    if ($("input:file").size() > 0) {
        alert("DEBE ANEXAR TODAS LAS EVIDENCIAS REQUERIDAS PARA PODER SOLUCINAR LA ORDEN DE TRABAJO");
        return false;
    }
    if (confirm(" DESEA SOLUCIONAR ESTA ORDEN DE TRABAJO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function  setMotivoNOexito(exitosa, idTarea) {
    if (parseInt(exitosa) === 0) {
        $("#obsrevisor_" + idTarea).removeAttr('readonly');
        $("#obsrevisor_" + idTarea).attr('required', true);
        $("#obsrevisor_" + idTarea).focus();
    } else {
        $("#obsrevisor_" + idTarea).removeAttr('required');
        $("#obsrevisor_" + idTarea).attr('readonly', true);
        $("#obsrevisor_" + idTarea).val('');
    }
}

//------------------------------------------------------------------------------

function  setMotivoRechazo(rechazado, idTarea) {
    if (parseInt(rechazado) === 0) {
        $("#obsrechazo_" + idTarea).removeAttr('required');
        $("#obsrechazo_" + idTarea).attr('readonly', true);
        $("#obsrechazo_" + idTarea).val('');
    } else {
        $("#obsrechazo_" + idTarea).removeAttr('readonly');
        $("#obsrechazo_" + idTarea).attr('required', true);
        $("#obsrechazo_" + idTarea).focus();
    }
}

//------------------------------------------------------------------------------

function validarSupervisar() {
    if (confirm(" DESEA SUPERVISAR ESTA ORDEN DE TRABAJO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function validarAsignarEquipo() {
    if (confirm(" DESEA PROCEDER CON LA ASIGNACION DE EQUIPO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function setEvidenciasEstandar() {
    if ($("#evidenciastandar").is(':checked')) {
        var ids = $("#idsevidenciastandar").val().split(",");
        for (var i = 0; i < ids.length; i++) {
            if (ids[i] !== '') {
                $("#tiposEvidencia").val(ids[i]);
                agregarTipoEvidencia();
            }
        }
    } else {
        $("#numEvidencias").val(0);
        $("#idsEvidencias").val('');
        $("#tblEvidencias tbody").html('');
    }
}

//------------------------------------------------------------------------------

function verAsignarMutiple(desde) {
    if ($("input[name='checkes[]']:checked").length === 0) {
        alert("DEBE SELECCIONAR AL MENOS UNA ORDEN DE TRABAJO");
        return;
    }
    var idsOT = [];
    $("input[name='checkes[]']:checked").each(function () {
        idsOT.push(this.value);
    });
    $.get('asignarmultiple', {idsOT: idsOT, desde: desde}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------


function validarAsignarOTs() {
    if ($("#idEmpleado").val() === '') {
        alert("POR FAVOR, SELECCIONE EL EMPLEADO AL CUAL SE VA ASIGNAR ESTA ORDEN DE TRABAJO");
        $("#idEmpleado").focus();
        return false;
    }

    var msgconfirm = "LAS SIGUIENTES OTs: " + $("#idsOT").val() + "\n\nSE ASIGNARAN AL EMPLEADO: " + $("#idEmpleado option:selected").text()
            + "\n\n  DESEA PROCEDER CON ESTA ASIGNACION ? ";
    if (confirm(msgconfirm)) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function buscarCliente() {
    if ($("#identificacionBusq").val() !== '') {
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
        $.get('buscarcliente', {identificacionBusq: $("#identificacionBusq").val()}, setBusquedaCliente);
        bloqueoAjax();
    } else {
        alert("POR FAVOR DIGITE LA IDENTIFICACION");
        $("#identificacionBusq").focus();
    }
}
function setBusquedaCliente(html) {
    $("#divInfoCliente").html(html);
    $("#divInfoCliente").show('slow');
}

//------------------------------------------------------------------------------

function getInfoServicio() {
    $("#divInfoTipoOT").html('');
    $("#divInfoTipoOT").hide('slow');
    $("#idTipoOT").val('');
    if ($("#idServicioBusq").val() !== '') {
        $.get('getinfoservicio', {idServicio: $("#idServicioBusq").val()}, setInfoServicio, 'json');
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
        $("#idEmpleado").focus();
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
    }
}

//------------------------------------------------------------------------------

function getDetalleTipoOT() {
    if ($("#idTipoOT").val() !== '' && $("#idServicioBusq").val() !== '' && $("#idSucursal").val() !== '') {
        $.get('getdetalletipoot', {idTipoOT: $("#idTipoOT").val(), idServicio: $("#idServicio").val(), idSucursal: $("#idSucursal").val()}, setInfoDetalleTipoOT);
        bloqueoAjax();
    } else {
        $("#divInfoTipoOT").html('');
        $("#divInfoTipoOT").hide('slow');

    }
}
function setInfoDetalleTipoOT(html) {
    $("#divInfoTipoOT").html(html);
    $("#divInfoTipoOT").show('slow');
}

//------------------------------------------------------------------------------

function getZonasTraslado(idSucursal) {
    $("#idZonaTraslado").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('getselectzonas', {idSucursal: idSucursal}, setZonasTraslado);
        bloqueoAjax();
    }
}
function setZonasTraslado(datos) {
    $("#idZonaTraslado").html(datos);
}

function getBarriosTraslado(idZona, idBarrio) {
    $("#idBarrioTraslado").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('getselectbarrios', {idZona: idZona, idBarrio: idBarrio}, setBarriosTraslado);
        bloqueoAjax();
    }
}
function setBarriosTraslado(datos) {
    $("#idBarrioTraslado").html(datos);
}

//------------------------------------------------------------------------------

function setCausaRetiroTXT() {
    if ($("#causaretiro").val() !== '') {
        $("#causaretiroTXT").val($("#causaretiro option:selected").text());
    } else {
        $("#causaretiroTXT").val('');
    }
}

//------------------------------------------------------------------------------

function setAveriaTXT() {
    if ($("#averia").val() !== '') {
        $("#averiaTXT").val($("#averia option:selected").text());
    } else {
        $("#averiaTXT").val('');
    }
}

//------------------------------------------------------------------------------

function gestionarEvidenciasOT() {
    $("#modalGestionarEvidencias").modal('show');
}

//------------------------------------------------------------------------------

function gestionarTareasOT() {
    $("#modalGestionarTareas").modal('show');
}

//------------------------------------------------------------------------------

function setBasico(base) {
    var basico = parseInt(base);
    if (isNaN(basico)) {
    } else {
        var htmlPrincipales = '', htmlAdicionales = '';
        for (var i = 0; i <= basico; i++) {
            htmlPrincipales += '<option value="' + i + '">' + i + '</option>';
        }
        $("#numtvsprincipales").html(htmlPrincipales);
        htmlAdicionales += '<option value="">Seleccione...</option>';
        for (var i = 0; i <= (basico * 3); i++) {
            htmlAdicionales += '<option value="' + i + '">' + i + '</option>';
        }
        $("#numtvadicionales").html(htmlAdicionales);
    }
}

//------------------------------------------------------------------------------

function setAdicionales(adicionales) {
    var htmlLegalizados = '';
    var basico = parseInt($("#basico").val());
    if (isNaN(basico)) {
        htmlLegalizados += '<option value="">Seleccione...</option>';
    } else {
        for (var i = 0; i <= ((basico * 3) - adicionales); i++) {
            htmlLegalizados += '<option value="' + i + '">' + i + '</option>';
        }
    }
    $("#numlegalizados").html(htmlLegalizados);
    calcularVlrOTpuntoadicional();
}

//------------------------------------------------------------------------------

function validarRegistrarPago() {
    if (confirm(" DESEA REGISTRAR ESTE PAGO ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function seleccionarServicioOT(idServicio) {
    $("#divInfoTipoOT").html('');
    $("#divInfoTipoOT").hide('slow');
    $("#idTipoOT").val('');
    if (idServicio !== '') {
        $.get('getinfoservicio', {idServicio: idServicio}, setInfoServicio, 'json');
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
    }
}

//------------------------------------------------------------------------------

function calcularCosto() {
    var vlrCableInstalado = 0;
    if ($("#concable").is(':checked')) {
        vlrCableInstalado = 0;
    }
    if ($("#sincable").is(':checked')) {
        vlrCableInstalado = parseInt($("#vlrsincable").val());
    }
    var costoAux = parseInt($("#costoAux").val().replace(/\,/g, ''));
    $("#costo").val(formatoMoneda(costoAux + vlrCableInstalado));
}

//------------------------------------------------------------------------------

function validarRegistrarOT() {
    if ($("#idCliente").length === 0) {
        alert("POR FAVOR SELECCIONE UN CLIENTE");
        $("#btnIdentificacionBusq").focus();
        return false;
    }
    if ($("#idServicio").val() !== '' && $("#idServicio").val() !== 0) {
        if ($("#numtvadicionales").length > 0 && $("#numlegalizados").length > 0) {
            if (parseInt($("#numtvadicionales").val()) === 0 && parseInt($("#numlegalizados").val()) === 0) {
                alert("LOS PUNTOS ADICIONALES Y LEGALIZADOS NO PUEDEN SER AMBOS CERO.");
                $("#numtvadicionales").focus();
                return false;
            }
        }
        if (confirm(" DESEA GENERAR ESTA ORDEN DE TRABAJO ? ")) {
            bloqueoAjax();
            return true;
        } else {
            return false;
        }
    } else {
        alert("NO SE HA SELECCIONADO UN SERVICIO PARA ESTA OT. POR FAVOR SELECCIONE UNO");
        $("#identificacion").focus();
        return false;
    }
}

//------------------------------------------------------------------------------

function getInfoEquipo(buscarpor) {
    var serial = $.trim($("#serialBusq").val());
    var idSucursal = $("#idSucursal").val();
    switch (buscarpor) {
        case 'serial':
            if (serial === '') {
                alert("POR FAVOR INGRESE EL SERIAL DEL EQUIPO PARA INICIAR LA BUSQUEDA");
                $("#serialBusq").focus();
            } else {
                if (serial.length < 3) {
                    alert("EL SERIAL DEBE TENER AL MENOS 3 DIGITOS");
                    $("#serialBusq").focus();
                } else {
                    limpiarInfoEquipo();
                    $.get('/josandro/inventario/recursos/getequipobyserial', {serial: serial, idSucursal: idSucursal}, setInfoEquipo, 'json');
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
function setInfoEquipo(datos) {
    limpiarInfoEquipo();
    switch (parseInt(datos['error'])) {
        case 0:
            var info = datos['info'];
            if (info['estado'] === 'Registrado') {
                $("#idRecurso").val(info['idRecurso']);
                $("#recurso").val(info['tipo']);
                $("#serial").val(info['serial']);
                $("#marca").val(info['marca']);
                $("#estado").val(info['estado']);
            } else {
                alert("EL RECURSO CARGADO A LA OT DE INSTALACION NO PUEDE SER ASIGNADO. \n\n EL ESTADO DEL RECURSO ES << " + info['estado'] + " >>\n\n");
                return false;
            }
            break;
        case 1:
            alert("SE HA PRESENTADO UN INCONVENIENTE AL BUSCAR EL EQUIPO EN EL INVENTARIO.");
            break;
        case 2:
            alert("RECURSO NO ENCONTRADO EN EL SISTEMA. \nPOR FAVOR VERIFIQUE EL SERIAL E INTENTE DE NUEVO");
            break;
    }

}
function limpiarInfoEquipo() {
    $("#idRecurso").val('');
    $("#recurso").val('');
    $("#serial").val('');
    $("#marca").val('');
    $("#estado").val('');
}

//------------------------------------------------------------------------------

function validarLiberarEquipo(idOrdenRecurso, idRecurso) {
    if ($.trim(idOrdenRecurso).length !== 0 && $.trim(idRecurso).length !== 0) {
        if (confirm(" DESEA LIBERAR EL EQUIPO ASIGNADO A LA OT DE INSTALACION ? ")) {
            $.get('liberarequipo', {idOrdenRecurso: idOrdenRecurso, idRecurso: idRecurso}, setEquipoLiberado, 'json');
            bloqueoAjax();
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, NO FUE POSIBLE LIBERAR EL EQUIPO.");
    }
}
function setEquipoLiberado(datos) {
    if (parseInt(datos['ok']) === 1) {
        alert(" EQUIPO LIBERADO EN EL SISTEMA, DEBE ASIGNARSE UN NUEVO EQUIPO A LA OT DE INSTALACION");
    } else {
        alert(" SE HA PRESENTADO UN INCONVENIENTE Y EL EQUIPO NO FUE LIBERADO EN EL SISTEMA");
    }
    preguntarCierreModal = 0;
    $('#modalFormulario').modal('hide');
}

//------------------------------------------------------------------------------

function verEliminarOT(idOT, desde) {
    $.get('eliminar', {idOT: idOT, desde: desde}, setFormulario);
    bloqueoAjax();
}

function validarEliminarOT() {
    if (confirm(" DESEA ELIMINAR ESTA ORDEN DE TRABAJO ?")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function setDireccionOnOT(inputDireccion) {
    var direccion = '', i = 0;
    for (i = 1; i < 10; i++) {
        direccion += $("#dir" + i).val() + ' ';
    }
    $("#" + inputDireccion).val($.trim(direccion));
}

//------------------------------------------------------------------------------

function calcularVlrOTpuntoadicional() {
    var vlrptoadicional = parseInt($("#vlrptoadicional").val().replace(/\,/g, ''));
    var vlrptolegalizado = parseInt($("#vlrptolegalizado").val().replace(/\,/g, ''));
    var costo = (parseInt($("#numtvadicionales").val()) * vlrptoadicional) + ((parseInt($("#numlegalizados").val()) * vlrptolegalizado));
    $("#costo").val(formatoMoneda(costo));
}

//------------------------------------------------------------------------------

function validarBusquedaOTs() {
    if ($("#fechainiFiltro").val() !== '') {
        if ($("#fechafinFiltro").val() === '') {
            alert(" POR FAVOR INDIQUE LA FECHA FIN PARA INICIAR LA BUSQUEDA.");
            $("#fechafinFiltro").focus();
            return false;
        }
    }
    if ($("#estadoFiltro").val() !== '' && $("#identificacionFiltro").val() === '' && $("#nombresFiltro").val() === '' && $("#apellidosFiltro").val() === '') {
        if ($("#fechainiFiltro").val() === '') {
            alert("PARA REALIZAR LA BUSQUEDA POR << ESTADO >>, ES NECESARIO QUE INDIQUE LAS FECHAS DE INICIO Y FIN.");
            $("#fechainiFiltro").focus();
            return false;
        }
    }
    return true;
}

//------------------------------------------------------------------------------




