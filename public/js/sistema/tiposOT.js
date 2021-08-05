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

function verRegistrar() {
    $.get('registrar', {}, setFormulario);
    bloqueoAjax();
}
function verEditar(idTipoOT) {
    $.get('editar', {idTipoOT: idTipoOT}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idCliente) {
    $.get('detalle', {idCliente: idCliente}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idServicio) {
    $.get('delete', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verCostos(idTipoOT) {
    $.get('costos', {idTipoOT: idTipoOT}, setFormulario);
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

function activarCambioCosto(idTipoOT, idSucursal, activar) {
    if (parseInt(activar) === 1) {
        $("#costo_" + idTipoOT + "_" + idSucursal).removeAttr('readonly');
        $("#costo_" + idTipoOT + "_" + idSucursal).attr('required', true);

        var costo = $("#costo_" + idTipoOT + "_" + idSucursal).val();
        $("#costo_" + idTipoOT + "_" + idSucursal).val(costo.replace(/\,/g, ''));

        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('class', 'btn btn-success');
        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('title', 'GUARDAR CAMBIOS');
        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('onclick', "cambiarCosto(" + idTipoOT + ", " + idSucursal + ")");
        $("#iCosto_" + idTipoOT + "_" + idSucursal).attr('class', 'fa fa-save');

        $("#costo_" + idTipoOT + "_" + idSucursal).focus();
    } else {
        var costoOLD = $("#costoOLD_" + idTipoOT + "_" + idSucursal).val();
        $("#costo_" + idTipoOT + "_" + idSucursal).val(formatoMoneda(costoOLD));

        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('class', 'btn btn-primary');
        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('title', 'CAMBIAR COSTO');
        $("#btnCosto_" + idTipoOT + "_" + idSucursal).attr('onclick', "activarCambioCosto(" + idTipoOT + ", " + idSucursal + ", 1)");
        $("#iCosto_" + idTipoOT + "_" + idSucursal).attr('class', 'fa fa-edit');

        $("#costo_" + idTipoOT + "_" + idSucursal).removeAttr('required');
        $("#costo_" + idTipoOT + "_" + idSucursal).attr('readonly', true);

        $("#costo_" + idTipoOT + "_" + idSucursal).focus();
    }
}

//------------------------------------------------------------------------------

function cambiarCosto(idTipoOT, idSucursal) {
    if (idTipoOT !== '' && idSucursal !== '') {
        var costo = $.trim($("#costo_" + idTipoOT + '_' + idSucursal).val());
        if (costo.length === 0 || isNaN(costo)) {
            alert("POR FAVOR DIGITE EL COSTO, SOLO SE PERMITEN NUMEROS");
            $("#costo_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (costo % 1 !== 0) {
            alert("EL COSTO NO PUEDE SER UN NUMERO DECIMAL, POR FAVOR UTILIZE NUMEROS ENTEROS");
            $("#costo_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (parseInt(costo) < 0) {
            alert("EL COSTO NO PUEDE SER NEGATIVO");
            $("#costo_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (confirm("  DESEA GUARDAR LOS CAMBIOS ? ")) {
            $.post('cambiarcosto', {idTipoOT: idTipoOT, idSucursal: idSucursal, costo: costo}, setCambioCosto, 'json');
            bloqueoAjax();
        } else {
            activarCambioCosto(idTipoOT, idSucursal, 0);
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, NO ES POSIBLE GUARDAR LOS CAMBIOS.");
    }
    return false;
}
function setCambioCosto(datos) {
    if (parseInt(datos['ok']) > 0) {
        alert(" EL COSTO FUE ACTUALIZADO EN JOSANDRO. ");
    } else {
        alert(" [ERROR] SE HA PRESENTADO UN INCONVENIENTE, EL COSTO NO FUE ACTUALIZADO EN JOSANDRO. ");
    }
    if (parseInt(datos['idTipoOT']) !== 0) {
        verCostos(datos['idTipoOT']);
    } else {
        location.reload();
    }
}

//------------------------------------------------------------------------------

function activarNewCosto(idTipoOT, idSucursal, activar) {
    if (parseInt(activar) === 1) {
        var input = '<div class="col-md-6 col-sm-6 col-xs-12">' +
                '<div class="input-group">' +
                '<input type="text" id="costoNEW_' + idTipoOT + '_' + idSucursal + '" name="costoNEW_' + idTipoOT + '_' + idSucursal + '" value="" placeholder="Digite solo numeros" class="form-control" required>' +
                '<span class="input-group-btn">' +
                '<button onclick="guardarCosto(' + idTipoOT + ', ' + idSucursal + ')" class="btn btn-primary" type="button" title="GUARDAR COSTO"><i class="fa fa-save"></i></button>' +
                '</span>' +
                '</div>' +
                '</div>';
        $("#tdNewCosto_" + idTipoOT + "_" + idSucursal).html(input);
    } else {
        var td = '<i style="color: red">COSTO NO DEFINIDO</i> &nbsp; <button id="btnNewCosto_' + idTipoOT + '_' + idSucursal + '" onclick="activarNewCosto(' + idTipoOT + ', ' + idSucursal + ', 1)" class="btn btn-primary" type="button" title="REGISTRAR COSTO"><i class="fa fa-plus"></i></button>';
        $("#tdNewCosto_" + idTipoOT + "_" + idSucursal).html(td);
    }

}

//------------------------------------------------------------------------------

function guardarCosto(idTipoOT, idSucursal) {
    if (idTipoOT !== '' && idSucursal !== '') {
        var costo = $.trim($("#costoNEW_" + idTipoOT + '_' + idSucursal).val());
        if (costo.length === 0 || isNaN(costo)) {
            alert("POR FAVOR DIGITE EL COSTO, SOLO SE PERMITEN NUMEROS");
            $("#costoNEW_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (costo % 1 !== 0) {
            alert("EL COSTO NO PUEDE SER UN NUMERO DECIMAL, POR FAVOR UTILIZE NUMEROS ENTEROS");
            $("#costoNEW_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (parseInt(costo) < 0) {
            alert("EL COSTO NO PUEDE SER NEGATIVO");
            $("#costoNEW_" + idTipoOT + '_' + idSucursal).focus();
            return false;
        }
        if (confirm("  DESEA GUARDAR ESTE COSTO ? ")) {
            $.post('guardarcosto', {idTipoOT: idTipoOT, idSucursal: idSucursal, costo: costo}, setGuardarCosto, 'json');
            bloqueoAjax();
        } else {
            activarNewCosto(idTipoOT, idSucursal, 0);
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, NO ES POSIBLE GUARDAR ESTE COSTO.");
    }
    return false;
}
function setGuardarCosto(datos) {
    if (parseInt(datos['ok']) > 0) {
        alert(" EL COSTO FUE REGISTRADO EN JOSANDRO. ");
    } else {
        alert(" [ERROR] SE HA PRESENTADO UN INCONVENIENTE, EL COSTO NO FUE REGISTRADO EN JOSANDRO. ");
    }
    if (parseInt(datos['idTipoOT']) !== 0) {
        verCostos(datos['idTipoOT']);
    } else {
        location.reload();
    }
}

//------------------------------------------------------------------------------


//------------------------------------------------------------------------------


//------------------------------------------------------------------------------


