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

function verRegistrar() {
    if ($("#idSucursalBusq").val() !== '') {
        $.get('add', {idSucursalBusq: $("#idSucursalBusq").val()}, setFormulario);
        bloqueoAjax();
    } else {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursalBusq").focus();
    }
}
function verDetalle(idPersona, idSucursal) {
    $.get('detail', {idPersona: idPersona, idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEditar(idPersona, idSucursal) {
    $.get('edit', {idPersona: idPersona, idSucursal: idSucursal}, setFormulario);
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

function getBarrios(idZona) {
    $("#fk_barrio_id").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('../../barrios/administracion/getBarrios', {idZona: idZona}, setBarrios);
        bloqueoAjax();
    }
}
function setBarrios(datos) {
    $("#idBarrio").html(datos);
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
    $("#direccion").val(direccion);
}

//------------------------------------------------------------------------------

function existeIdentificacion(identificacion) {
    var identificacionOLD = 'null';
    if ($("#identificacionOLD").length) {
        identificacionOLD = $("#identificacionOLD").val();
    }
    if (identificacionOLD !== identificacion) {
        $.get('existeIdentificacion', {identificacion: identificacion}, setExisteIdentificacion, 'json');
        bloqueoAjax();
    }
}
function setExisteIdentificacion(datos) {
    if (parseInt(datos['existe']) === 1) {
        alert(' LA IDENTIFICACION << ' + datos['identificacion'] + ' >> YA ESTA REGISTRADA EN EL SISTEMA ');
        $("#identificacion").val('');
        $("#identificacion").focus();
    }
}

//------------------------------------------------------------------------------
