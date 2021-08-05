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
        $.get('/josandro/comercial/empresa/add', {idSucursalBusq: $("#idSucursalBusq").val()}, setFormulario);
        bloqueoAjax();
    } else {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursalBusq").focus();
    }

}
function verDetalle(idEmpresa) {
    $.get('/josandro/comercial/empresa/detail', {idEmpresa: idEmpresa}, setFormulario);
}
function verEditar(idEmpresa) {
    $.get('/josandro/comercial/empresa/edit', {idEmpresa: idEmpresa}, setFormulario);
}
function verEliminar(idEmpresa) {
    $.get('/josandro/comercial/empresa/delete', {idEmpresa: idEmpresa}, setFormulario);
}
//--------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function getMunicipios(idDepartamento) {
    $("#pk_municipio_id").html('<option value="">Seleccione...</option>');
    $("#pk_centro_poblado_id").html('<option value="">Seleccione...</option>');
    $("#fk_centro_poblado_id").val("");
    $.get('/josandro/comercial/ubicacion/getMunicipios', {idDepartamento: idDepartamento}, setMunicipios);
}
function setMunicipios(datos) {
    $("#pk_municipio_id").html(datos);
}

function getPoblados(idMunicipio) {
    $("#pk_centro_poblado_id").html('<option value="">Seleccione...</option>');
    $("#fk_centro_poblado_id").val("");
    $.get('/josandro/comercial/ubicacion/getCentrosPoblados', {idMunicipio: idMunicipio}, setPoblados);
}
function setPoblados(datos) {
    $("#pk_centro_poblado_id").html(datos);
}

function setFkCentroPoblado(idCentroPoblado) {
    $("#fk_centro_poblado_id").val(idCentroPoblado);
}

function getZonas(idSucursal) {
    $("#fk_zona_id").html('<option value="">Seleccione...</option>');
    $("#fk_barrio_id").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('/josandro/configuracion/zonas/getZonas', {idSucursal: idSucursal}, setZonas);
    }
}
function setZonas(datos) {
    $("#fk_zona_id").html(datos);
}

function getBarrios(idZona) {
    $("#fk_barrio_id").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('/josandro/configuracion/barrios/getBarrios', {idZona: idZona}, setBarrios);
    }
}
function setBarrios(datos) {
    $("#fk_barrio_id").html(datos);
}

//------------------------------------------------------------------------------

function existeIdentificacion(identificacion) {
    var identificacionOLD = 'null';
    if ($("#identificacionOLD").length) {
        identificacionOLD = $("#identificacionOLD").val();
    }
    if (identificacionOLD !== identificacion) {
        $.get('/josandro/comercial/empresa/existeIdentificacion', {identificacion: identificacion}, setExisteIdentificacion, 'json');
    }
}
function setExisteIdentificacion(datos) {
    if (parseInt(datos['existe']) === 1) {
        var identificacion = datos['identificacion'];
        alert(' LA IDENTIFICACION << ' + identificacion + ' >> YA ESTA REGISTRADA EN EL SISTEMA ');
        $("#identificacion").val('');
        $("#identificacion").focus();
    } else if (parseInt(datos['existe2C']) === 1) {
        var identificacion = datos['identificacion'];
        alert(' LA IDENTIFICACION << ' + identificacion + ' >> YA ESTA REGISTRADA EN LA ANTERIOR VERSION DEL SISTEMA. ');
        $("#identificacion").val('');
        $("#identificacion").focus();
    }
    calcularDigitoVerificacion();
}

function eliminar(idEmpresa) {
    if (confirm("¿ DESEA ELIMINAR ESTA EMPRESA ?")) {
        $.post('/josandro/comercial/empresa/delete', {idEmpresa: idEmpresa}, setEliminar, 'json');
    }
}
function setEliminar(datos) {
    if (datos['eliminado']) {
        alert(" EMPRESA ELIMINADA DEL SISTEMA ");
        location.reload();
    } else {
        alert(" LA EMPRESA NO FUE ELIMINADA \n\n SE HA PRESENTADO UN COMPORTAMIENTO INESPERADO EN EL SISTEMA \n EN CASO DE PERSISTIR ESTE COMPORTAMIENTO COMUNIQUESE CON EL ADMINISTRADOR");
    }
}

//------------------------------------------------------------------------------

function calcularDigitoVerificacion() {
    var nit = $("#identificacion").val();
    if (nit.trim().length > 0 && nit.trim().length < 15 && !isNaN(nit)) {
        var numPrimos = ['3', '7', '13', '17', '19', '23', '29', '37', '41', '43', '47', '53', '59', '67', '71'];
        var nitArray = nit.split('');
        var sumatoria = 0;
        var j = 0;
        for (i = nitArray.length - 1; i >= 0; i--) {
            sumatoria = sumatoria + (nitArray[i] * numPrimos[j]);
            j++;
        }
        var modulo = sumatoria % 11;
        var dv;
        if (modulo > 1) {
            dv = 11 - modulo;
        } else {
            dv = modulo;
        }
        $("#dv").val(dv);
    } else {
        $("#dv").val('');
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
    $("#direccion").val(direccion);
}

