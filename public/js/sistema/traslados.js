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

async function verRegistrar() {
    const {value: identificacion} = await Swal.fire({
        title: 'Para registrar la solicitud es necesario ingresar la identificacion del cliente',
        input: 'text',
        inputValue: '',
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'Por favor, ingrese la identificacion del cliente.';
            }
        }
    });
    if (identificacion) {
        $.get('registrar', {identificacion: identificacion}, setFormulario);
        bloqueoAjax();
    }
}
function verDetalle(idServicio) {
    $.get('detalle', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verEditar(idServicio) {
    $.get('editar', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idServicio) {
    $.get('delete', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function validarRegistrar() {
    if ($("#idServicio").val() === '') {
        alert("POR FAVOR SELECCIONE EL SERVICIO A TRASLADAR");
        $("#idServicio").focus();
        return false;
    }
    if ($("#direccion").val() === '') {
        alert("POR FAVOR INGRESE LA DIRECION DE TRASLADO");
        $("#direccion").focus();
        return false;
    }
    if (confirm(" DESEA REGISTRAR ESTA SOLICITUD DE TRASLADO ? ")) {
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function getInfoServicio(idServicio) {
    $("#idBarrio").html("<option value=''>Seleccione...</option>");
    $("#detalleservicio").val('');
    $("#idSucursal").val('');
    $("#sucursal").val('');
    $("#idZona").html("<option value=''>Seleccione...</option>");
    if (idServicio !== '') {
        $.get('getinfoservicio', {idServicio: idServicio}, setInfoServicio, 'json');
        bloqueoAjax();
    }
}
function setInfoServicio(datos) {
    $("#detalleservicio").val(datos['detalleservicio']);
    $("#idSucursal").val(datos['idSucursal']);
    $("#sucursal").val(datos['sucursal']);
    $("#idZona").html(datos['htmlzonas']);
}

//------------------------------------------------------------------------------

function getBarrios(idZona) {
    if (idZona !== '') {
        $.get('getbarrios', {idZona: idZona}, setBarrios);
        bloqueoAjax();
    } else {
        $("#idBarrio").html("<option value=''>Seleccione...</option>");
    }
}
function setBarrios(html) {
    $("#idBarrio").html(html);
}

//------------------------------------------------------------------------------

function registrarDireccion(tipo) {
    if ($("#idServicio").val() === '') {
        alert("POR FAVOR SELECCIONE UN SERVICIO.");
        $("#idServicio").focus();
        return false;
    }
    var idSucursal = $("#idSucursal").val();
    if (idSucursal === '') {
        alert("SE HA PRESENTADO UN INCONVENIENTE, NO ES POSIBLE REGISTRAR EL TRASLADO (SUCURSAL NO ENCONTRADA)");
        return false;
    }
    $.get('registrardireccion', {tipo: tipo, idSucursal: idSucursal}, setRegistrarDireccion);
    bloqueoAjax();
}
function setRegistrarDireccion(datos) {
    //console.log(datos)
    $("#divInfoDirecciones").html(datos);
    $('#modalDirecciones').modal('show');
}

//------------------------------------------------------------------------------

function setDireccion() {
    var direccion = '', i = 0;
    for (i = 1; i < 10; i++) {
        direccion += $("#dir" + i).val() + ' ';
    }
    $("#direccionOK").val($.trim(direccion));
}

//------------------------------------------------------------------------------

function setDireccionOK(tipo) {
    switch (tipo) {
        case 'instalacion':
            $("#direccion").val($("#direccionOK").val());
            $("#idMcpoInstalacion").val($("#idMunicipio").val());
            break;
        case 'facturacion':
            $("#infoDirFacturacion").val($("#direccionOK").val());
            $("#idMcpoFacturacion").val($("#idMunicipio").val());
            break;
        case 'residencia':
            $("#infoDirResidencia").val($("#direccionOK").val());
            $("#idMcpoResidencia").val($("#idMunicipio").val());
            break;
        default:
            alert("SE HA PRESENTADO UN INCONVENIENTE EN EL SISTEMA, POR FAVOR INTENTELO DE NUEVO");
            break;
    }
    $("#divInfoDirecciones").html('');
    $('#modalDirecciones').modal('hide');
    return false;
}

//------------------------------------------------------------------------------

function verificarDireccion() {
    var direccion = $.trim($("#direccionOK").val());
    if (direccion !== '') {
        $("#tblVerificacionDireccion tbody").html('');
        $("#infoVerificacionDireccion").hide('slow');
        $.get('verificardireccion', {direccion: direccion}, setVerificacionDireccion, 'json');
        bloqueoAjax();
    }
}
function setVerificacionDireccion(datos) {
    if (parseInt(datos['error']) === 0) {
        if (datos['direcciones'].length > 0) {
            alert("LA DIRECCION PRESENTA " + datos['direcciones'].length + " POSIBLE(S) CONCIDENCIA(S).");
            var fila = '';
            $.each(datos['direcciones'], function (i, direccion) {
                fila = '<tr>';
                fila += '<td>' + direccion['idDireccion'] + '</td>';
                fila += '<td>' + direccion['identificacion'] + '</td>';
                fila += '<td>' + direccion['cliente'] + '</td>';
                fila += '<td>' + direccion['sucursal'] + '</td>';
                fila += '<td>' + direccion['zona'] + '</td>';
                fila += '<td>' + direccion['barrio'] + '</td>';
                fila += '<td>' + direccion['direccion'] + '</td>';
                fila += '<td>' + direccion['estado'] + '</td>';
                fila += '</tr>';
                $("#tblVerificacionDireccion tbody").append(fila);
            });
            $("#infoVerificacionDireccion").show('slow');
        } else {
            alert("NO SE HAN ENCONTRADO CONCIDENCIAS PARA LA DIRECCION.");
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE Y NO FUE POSIBLE VALIDAR LA DIRECCION.");
    }
}

//------------------------------------------------------------------------------

function generarCobro(idTraslado, costo) {
    if (idTraslado === '' || parseInt(idTraslado) === 0 || costo === '' || parseInt(costo) === 0) {
        alert("SE HA PRESENTADO UN INCONVENIENTE, NO ES POSIBLE GENERAR EL COBRO DE ESTA SOLICITUD DE TRASLADO.");
        return false;
    }
    if (confirm(" ESTA SOLICITUD DE TRASLADO TIENE UN COSTO DE $" + costo + "\n\n DESEA GENERAR EL COBRO PARA ESTA SOLICITUD ? ")) {
        $.get('generarcobro', {idTraslado: idTraslado, costo: costo}, setGenerarCobro, 'json');
        bloqueoAjax();
    } else {

    }
}
function setGenerarCobro(datos) {
    if (parseInt(datos['error']) === 0) {
        
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE Y NO FUE POSIBLE GENERAR EL COBRO");
    }
    location.reload();
}

//------------------------------------------------------------------------------
