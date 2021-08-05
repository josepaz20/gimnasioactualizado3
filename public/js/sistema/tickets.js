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

function verRegistrar(tipoServicio) {
        $.get('add', {tipoServicio: tipoServicio}, setFormulario);
        bloqueoAjax();
}
function verDetalle(idIncidente) {
    $.get('detail', {idIncidente: idIncidente}, setFormulario);
    bloqueoAjax();
}
function verEditar(idUsuario) {
    $.get('/josandro/usuarios/usuarios/edit', {idUsuario: idUsuario}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idIncidente) {
    $.get('delete', {idIncidente: idIncidente}, setFormulario);
    bloqueoAjax();
}
function verConversacion(idIncidente) {
    $.get('../mensajes/conversacion', {idIncidente: idIncidente}, setFormulario);
    bloqueoAjax();
}
function verReasignar(idIncidente) {
    $.get('../incidentes/asignar', {idIncidente: idIncidente}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarRegistro() {
    if ($("#idcliente").val() === '') {
        alert("POR FAVOR SELECCIONE UN CLIENTE, DEBE DIGITAR EL NUMERO DE IDENTIFICACION DE UN CLIENTE VALIDO Y PRESIONE EL BOTON BUSCAR PARA CONTINUAR.");
        $("#identificacion").focus();
        return false;
    } else {
        return confirm("¿ DESEA REGISTRAR ESTE INCIDENTE ?");
    }
}

//------------------------------------------------------------------------------

function abrirCerrarFieldset(abrirCerrar) {
    var $BOX_PANEL = $(abrirCerrar).closest("fieldset"),
            $ICON = $(abrirCerrar).find("i"),
            $BOX_CONTENT = $BOX_PANEL.find(".colapseFieldset");
    $BOX_CONTENT.slideToggle(200);
    $BOX_PANEL.css("height", "auto");
    $ICON.toggleClass("fa-chevron-up fa-chevron-down");
}

//------------------------------------------------------------------------------

function registrarMensaje() {
    var msg = $.trim($("#texto").val());
    var tipomensaje = $("#tipomensaje").val();
    if (msg !== '' && tipomensaje !== '') {
        var msgConfirm = "¿ DESEA  << REGISTRAR >>  ESTE MENSAJE ?";
        if ($("#estado").val() === 'Solucionado') {
            msgConfirm = " EL PRESENTE TICKET YA ESTA SOLUCIONADO \n SI USTED REGISTRA ESTE MENSAJE EL TICKET VOLVERA A ESTADO EN PROCESO. \n\n ¿ DESEA  << REGISTRAR >>  ESTE MENSAJE ?";
        }
        if (confirm(msgConfirm)) {
            var formulario = new FormData();
            formulario.append('tiposervicio', $("#tiposervicio").val());
            formulario.append('idIncidente', $("#idIncidente").val());
            formulario.append('texto', msg);
            formulario.append('tipomensaje', $("#tipomensaje").val());
            if ($.trim($("#adjunto")) !== '') {
                formulario.append('adjunto', $("#adjunto")[0].files[0]);
            }
            $.ajax({
                url: '../mensajes/add',
                type: 'POST',
                contentType: false,
                data: formulario,
                dataType: "json",
                processData: false,
                cache: false
            }).done(function (respuestaArray) {
                if (parseInt(respuestaArray['ok']) === 1) {
                    var newDiv = '';
                    switch (respuestaArray['mensaje']['tipomensaje']) {
                        case 'Solucion':
                            alert(" EL TICKET HA SIDO SOLUCIONADO SATISFACTORIAMENTE. ");
                            location.reload();
                            break;
                        case 'Cliente':
                            newDiv = '<div class="cliente"><p><b>';
                            break;
                        case 'Soporte':
                            newDiv = '<div class="asesor"><p><b>';
                            break;
                    }
                    newDiv += respuestaArray['mensaje']['fechahorareg'] + ' -- '
                            + respuestaArray['mensaje']['registradopor']
                            + '</b></p>'
                            + respuestaArray['mensaje']['texto'];
                    if (respuestaArray['adjuntoOK']) {
                        newDiv += '<div class="ln_solid"></div><h5>ARCHIVOS ADJUNTOS</h5>'
                                + '<a href="/josandro/tickets/adjunto/descargar/' + respuestaArray['idAdjunto'] + '/' + respuestaArray['tipoServicio'] + '" title="VISUALIZAR O DESCARGAR ESTE ADJUNTO" target="_blank">'
                                + '<i style="font-size: 15px" class="' + respuestaArray['tipoAdjunto'] + '"></i>'
                                + '</a>';
                    }
                    newDiv += '</div>';
                    $("#panelChat").append(newDiv);
                    var desplaza = $("#panelChat")[0].scrollHeight;
                    $("#panelChat").animate({
                        scrollTop: desplaza
                    }, 1500);
                    $("#texto").val('');
                    $("#tipomensaje").val('');
                    $("#adjunto").val('');
                    $("#texto").focus();
                } else {
                    alert(" SE HA PRESENTADO UN INCONVENIENTE. \n\n EL MENSAJE NO HA SIDO REGISTRADO.");
                }
            });
            bloqueoAjax();
        }
    } else {
        alert(" LOS CAMPOS MARCADOS CON (*) SON OBLIGATORIOS ");
    }
    return false;
}

function setRespuesta(datos) {
    if (datos['error'] === 0) {
        $("#panelChat").append(datos['respuesta']);
        var desplaza = $("#panelChat")[0].scrollHeight;
        $("#panelChat").animate({
            scrollTop: desplaza
        }, 1500);
        $("#nuevoMensaje").val('');
        $("#nuevoMensaje").focus();
    } else {
        alert(" SE HA PRESENTADO UN ERROR EN EL SISTEMA. \n\n POR FAVOR COMUNIQUESE CON EL ADMINISTRADOR.");
    }
}

//------------------------------------------------------------------------------

function validarReasignacion() {
    if (parseInt($("#idUsuario").val()) === parseInt($("#idUsuarioOLD").val())) {
        alert("EL TICKET YA ESTA ASIGNADO AL USUARIO: " + $("#usuarioAsignado").val());
        $("#idUsuario").focus();
        return false;
    }
    return confirm(" ¿ DESEA RE-ASIGNAR ESTE TICKET ? ");
}

//------------------------------------------------------------------------------

function setInfoPrioridad(prioridad) {
    switch (parseInt(prioridad)) {
        case 1:
            $("#infoPrioridad").html("<b>PRIORIDAD 1:</b> Se presenta completa perdida de alguno de los servicios.");
            $("#divInfoPrioridad").show('slow');
            break;
        case 2:
            $("#infoPrioridad").html("<b>PRIORIDAD 2:</b> Se presenta intermitencia en alguno de los servicios.");
            $("#divInfoPrioridad").show('slow');
            break;
        case 3:
            $("#infoPrioridad").html("<b>PRIORIDAD 3:</b> Se requiere aclarar dudas sobre alguno de los servicios o equipos suministrados.");
            $("#divInfoPrioridad").show('slow');
            break;
        default:
            $("#infoPrioridad").html("");
            $("#divInfoPrioridad").hide('slow');
            break;
    }
}

//------------------------------------------------------------------------------
function buscarCliente() {
    if($("#identificacion").val() !== ''){
        $("#divInfoCliente").hide('slow');
        $("#divInfoServiciosCliente").hide('slow');
        $.get('buscarCliente', {identificacion: $("#identificacion").val()}, setBuscarCliente, 'json');
        bloqueoAjax();
    } else {
        alert("POR FAVOR DIGITE EL NUMERO DE IDENTIFICACION DE UN CLIENTE.");
        $("#divInfoCliente").hide('slow');
        $("#divInfoServiciosCliente").hide('slow');
        $("#idcliente").val('');
        $("#cliente").val('');
        $("#identificacioncliente").val();
        $("#idServicio").removeAttr('required');
        $("#identificacion").focus();
    }
}
function setBuscarCliente(datos){
    $("#idcliente").val('');
    $("#cliente").val('');
    $("#identificacioncliente").val();
    if(parseInt(datos['error']) === 0){
        $("#idcliente").val(datos['infoCliente']['idcliente']);
        $("#cliente").val(datos['infoCliente']['cliente']);
        $("#identificacioncliente").val(datos['infoCliente']['identificacioncliente']);
        $("#divInfoServiciosCliente").html(datos['selectServiciosCliente']);
        $("#divInfoCliente").show('slow');
        $("#divInfoServiciosCliente").show('slow');
        $("#idServicio").attr('required', true);
    } else if(parseInt(datos['error']) === 1){
        alert("POR FAVOR DIGITE EL NUMERO DE IDENTIFICACION DEL CLIENTE.");
        $("#divInfoServiciosCliente").html('');
        $("#idcliente").val('');
        $("#cliente").val('');
        $("#identificacioncliente").val('');
        $("#divInfoCliente").hide('slow');
        $("#divInfoServiciosCliente").hide('slow');
        $("#idServicio").removeAttr('required');
        $("#identificacion").focus();
        return false;
    } else if(parseInt(datos['error']) === 2){
        alert("NO SE HA ENCONTRADO SERVICIOS PARA EL CLIENTE CON EL NUMERO DE IDENTIFICACION DIGITADO.");
        $("#divInfoServiciosCliente").html('');
        $("#idcliente").val('');
        $("#cliente").val('');
        $("#identificacioncliente").val('');
        $("#divInfoCliente").hide('slow');
        $("#divInfoServiciosCliente").hide('slow');
        $("#idServicio").removeAttr('required');
        $("#identificacion").focus();
        return false;
    } else {
        $("#idcliente").val('');
        $("#cliente").val('');
        $("#identificacioncliente").val('');
        $("#divInfoCliente").hide('slow');
        $("#divInfoServiciosCliente").hide('slow');
        $("#identificacion").val('');
        $("#identificacion").focus();
        $("#idServicio").removeAttr('required');
        alert("SE HA PRESENTADO UN INCONVENIENTE INESPERADO EN EL SISTEMA.");
        return false;
    }
}
//------------------------------------------------------------------------------

function limpiarFechas(){
    $("#fechaIniBusq").val('');
    $("#fechaFinBusq").val('');
    $("#fechaFinBusq").attr('readonly', 'readonly');
}

function validarFechas(){
    $("#fechaFinBusq").val('');
    $("#fechaFinBusq").removeAttr('readonly');
    $("#fechaFinBusq").attr('min', $("#fechaIniBusq").val());
}

