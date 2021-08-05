//------------------------------------------------------------------------------

var interval = null;
var numVeces = 1;

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

function habilitar(id, lista) {
    if (confirm('¿ DESEA HABILITAR ESTE CLIENTE ?')) {
        location.href = '../administracion/habilitar?id=' + id + '&lista=' + lista;
    }
}

//------------------------------------------------------------------------------

function deshabilitar(id, lista) {
    if (confirm('¿ DESEA DESHABILITAR ESTE CLIENTE ?')) {
        location.href = '../administracion/deshabilitar?id=' + id + '&lista=' + lista;
    }
}

//------------------------------------------------------------------------------

function removerListaCorte(id, lista) {
    if (confirm('¿ DESEA REMOVER ESTE CLIENTE DE LA LISTA DE CORTE ?')) {
        location.href = '../administracion/removerListaCorte?id=' + id + '&lista=' + lista;
    }
}

//------------------------------------------------------------------------------

function addListaCorte(id, lista, ip, comment) {
    if (confirm('¿ DESEA CORTAR ESTE CLIENTE ?')) {
        location.href = '../administracion/addListaCorte?id=' + id + '&lista=' + lista + '&ip=' + ip + '&comment=' + comment;
    }
}
function detailQueue(idQueue, queueIP) {
    $.get('/josandro/mikrotik/administracion/detailQueue', {idQueue: idQueue, queueIP: queueIP}, setFormulario);
}
function setFormulario(datos) {
    if (parseInt(datos) === 1) {
        alert("ERROR! NO SE HA RECIBIDO DATOS PARA REALIZAR LA PETICION DE ESTE SERVICIO.");
        return false;
    } else if (parseInt(datos) === 2) {
        alert("ERROR! NO SE HA PODIDO ESTABLECER CONEXION CON LA MIKROTIK.");
        return false;
    } else if (parseInt(datos) === 3) {
        alert("ERROR! NO SE HA RECIBIDO LA DIRECCION IP PARA REALIZAR LA PETICION DE ESTE SERVICIO.");
        return false;
    } else {
        interval = setInterval('getConsumoTiempoReal()', 5000);
        $("#divContenido").html(datos);
        $('#modalFormulario').modal('show');
    }
}

function getConsumoTiempoReal() {
    $.get('/josandro/mikrotik/administracion/getConsumoTiempoReal', {idQueue: $("#idqueue").val(), queueIP: $("#target").val()}, setConsumoTiempoReal, 'json');
}
function setConsumoTiempoReal(datos) {
    if (parseInt(datos['error']) === 0) {
        $("#consumosubida").val(datos['consumosubida']);
        $("#consumobajada").val(datos['consumobajada']);
        $("#divInfoConsumo").html('<label style="color: green" class="control-label col-md-7 col-sm-5 col-xs-12">Recibiendo datos de consumo...</label>');
        if (numVeces === 12) {
            $("#divInfoConsumo").html('<label style="color: #0040FF" class="control-label col-md-7 col-sm-5 col-xs-12">Tiempo de recepcion de datos de consumo terminado.</label>');
            clearInterval(interval); // stop the interval
        }
    } else if (parseInt(datos['error']) === 1) {
        $("#divInfoConsumo").html('<label style="color: red" class="control-label col-md-7 col-sm-5 col-xs-12">ERROR! No se ha recibido datos para realizar la peticion de consumo.</label>');
        if (numVeces === 12) {
            $("#divInfoConsumo").html('<label style="color: #0040FF" class="control-label col-md-7 col-sm-5 col-xs-12">Tiempo de recepcion de datos de consumo terminado.</label>');
            clearInterval(interval); // stop the interval
        }
    } else if (parseInt(datos['error']) === 2) {
        $("#divInfoConsumo").html('<label style="color: red" class="control-label col-md-7 col-sm-5 col-xs-12">ERROR! No se ha podido estrablecer conexion con la Mikrotik.</label>');
        if (numVeces === 12) {
            $("#divInfoConsumo").html('<label style="color: #0040FF" class="control-label col-md-7 col-sm-5 col-xs-12">Tiempo de recepcion de datos de consumo terminado.</label>');
            clearInterval(interval); // stop the interval
        }
    } else {
        $("#divInfoConsumo").html('<label style="color: red" class="control-label col-md-7 col-sm-5 col-xs-12">ERROR! Se ha presentado un Inconveniente.</label>');
        if (numVeces === 12) {
            $("#divInfoConsumo").html('<label style="color: #0040FF" class="control-label col-md-7 col-sm-5 col-xs-12">Tiempo de recepcion de datos de consumo terminado.</label>');
            clearInterval(interval); // stop the interval
        }
    }
    numVeces += 1;
}

//------------------------------------------------------------------------------

function cargarRegistrosMK() {
    if (confirm('¿ DESEA CARGAR LOS ABONADOS DE JOSANDRO EN LA MIKROTIK DE GESTION ?')) {
        $("#btnCargarMK").attr('disabled', true);
        location.href = '../administracion/cargarmikrotik';
    }
}

//------------------------------------------------------------------------------

function eliminarAddressList(id) {
    $.get('eliminaraddresslist', {id: id}, setFormulario);
    bloqueoAjax();
}
function validarEliminarAddressList() {
    if (confirm(" DESEA ELIMINAR ESTE REGISTRO ?")) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

//------------------------------------------------------------------------------

