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

function verRegistrar(idPregunta) {
    $.get('/josandro/hotspot/pregunta/registrar', {}, setFormulario);
    bloqueoAjax();

}
function verDetalle(idPregunta) {
    $.get('detalle', {idPregunta: idPregunta}, setFormulario);
    bloqueoAjax();
}
function verEditar(idPregunta) {
    $.get('editar', {idPregunta: idPregunta}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idPregunta) {
    $.get('eliminar', {idPregunta: idPregunta}, setFormulario);
    bloqueoAjax();
}
function verActivar(idPregunta) {
     $.get('activar', {idPregunta: idPregunta}, setFormulario);
     bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarBusqueda() {
    if ($("#identificacionFiltro").val() === '' && $("#nombresFiltro").val() === '' && $("#apellidosFiltro").val() === '' && $("#razonsocialFiltro").val() === '') {
        alert("PARA INICIAR LA BUSQUEDA DEBE DIGITAR UNA DE LAS SIGUIENTES OPCIONES: \n\n * PARTE DE LA IDENTIFICACION A BUSCAR \n * NOMBRE(S) Y APELLIDO(S) \n * RAZON SOCIAL");
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

function limpiarBusqueda() {
    $("#formBusquedas input").each(function () {
        $(this).val('');
    });
}

//------------------------------------------------------------------------------

function existeIdentificacion() {
    if ($("#identificacion").val() !== '') {
        $.get('/josandro/clientes/administracion/existeidentificacion', {identificacion: $("#identificacion").val()}, setExisteIdentificacion, 'json');
        bloqueoAjax();
    }
}
function setExisteIdentificacion(datos) {
    if (parseInt(datos['error']) === 0) {
        if (parseInt(datos['existe']) === 1) {
            alert("LA IDENTIFICACION << " + datos['identificacion'] + " >> YA SE ENCUENTRA REGISTRADA EN JOSANDRO.");
            $("#identificacion").val('');
            $("#identificacion").focus();
            return false;
        } else {
            return true;
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE EN JOSANDRO.");
        return false;
    }
}

//------------------------------------------------------------------------------


//------------------------------------------------------------------------------


