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

function verRegistrar(idRegistro) {
    $.get('registrar', {idRegistro: idRegistro}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idRegistro) {
    $.get('detalle', {idRegistro: idRegistro}, setFormulario);
    bloqueoAjax();
}
function verEditar(idRegistro) {
    $.get('editar', {idRegistro: idRegistro}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idRegistro) {
    $.get('eliminar', {idRegistro: idRegistro}, setFormulario);
    bloqueoAjax();
}

function verActivar(idRegistro) {
    $.get('activar', {idRegistro: idRegistro}, setFormulario);
    bloqueoAjax();
}

function verImprimir(idRegistro) {
    $.get('imprimir', {idRegistro: idRegistro}, setFormulario);
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

function setTipoEntrenamiento(entrenamiento) {
    switch (entrenamiento) {

        case 'Mensual':
           
            $("#divInfoEntrenamiento").show('slow');
            break;
        case 'Quincenal':
           
            $("#divInfoEntrenamiento").show('slow');
            break;
        case 'Diario':
           
            $("#divInfoEntrenamiento").show('slow');
            break;
        default:
            $("#comprobante").attr('required', true);
            $("#cajaTransferirA").attr('required', true);
            $("#transferirA").attr('required', true);
            break;
    }
}
function setTipoProducto(producto) {
    switch (producto) {

        case 'Proteina':
            $("#comprobante").removeAttr('required');
            $("#cajaTransferirA").slideDown('slow');
            $("#cajaTransferirB").hide('slow');
            $("#cajaTransferirC").hide('slow');
            $("#transferirA").removeAttr('required');
            $("#divInfoProducto").show('slow');
            $("#divInfoProducto2").hide('slow');
            break;
        case 'Energizante':
            $("#comprobante").attr('required', true);
            $("#cajaTransferirB").slideDown('slow');
            $("#cajaTransferirC").hide('slow');
            $("#transferirA").removeAttr('required');
            $("#divInfoProducto").show('slow');
            $("#divInfoProducto2").hide('slow');
            break;
        case 'Saborizante':
            $("#comprobante").attr('required', true);
            $("#cajaTransferirC").slideDown('slow');
            $("#cajaTransferirB").hide('slow');
            $("#cajaTransferirA").hide('slow');
            $("#transferirA").removeAttr('required');
            $("#divInfoProducto").hide('slow');
            $("#divInfoProducto1").hide('slow');
            $("#divInfoProducto2").show('slow');
            break;
        default:
            $("#comprobante").attr('required', true);
            $("#cajaTransferirA").attr('required', true);
            $("#transferirA").attr('required', true);
            break;
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


