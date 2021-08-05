//------------------------------------------------------------------------------

var direccionCopiada = '', idMcpoCopiado = 0;

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

function setSeleccionar(datos) {
    $("#divSeleccionar").html(datos);
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
    $('#modalSeleccionar').modal('show');
}

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('registrar', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idServicio) {
    $.get('detalle', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verEditar(idSucursal) {
    $.get('edit', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idInternet) {
    $.get('delete', {idInternet: idInternet}, setFormulario);
    bloqueoAjax();
}
function verLegalizar(idServicio) {
    $.get('../legalizacion/verLegalizar', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verCredenciales(idServicio) {
    $.get('credenciales', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function actualizarMK(idServicio, idSucursalBusq) {
    $.get('actualizarmk', {idServicio: idServicio, idSucursalBusq: idSucursalBusq}, setFormulario);
    bloqueoAjax();
}
function verRegistrarHD(idServicio) {
    $.get('registrarhd', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function gestionarDirecciones(idServicio) {
    $.get('gestionardirecciones', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verClasificar(idServicio) {
    $.get('verClasificar', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function setTipoBusqueda() {
    $("#identificacionBusq").val('');
    $("#nombresBusq").val('');
    $("#apellidosBusq").val('');
    switch (parseInt($("#buscarpor").val())) {
        case 1:
            $("#divBusqIdentificacion").show('slow');
            $("#divBusqNombresapellidos").hide('slow');
            break;
        case 2:
            $("#divBusqNombresapellidos").show('slow');
            $("#divBusqIdentificacion").hide('slow');
            break;
        default:
            break;
    }
}

//------------------------------------------------------------------------------

function getInfoCliente(buscarpor) {
    $("#tblDireccionesOLD tbody").html('');
    $("#idEmpaquetado").val(0);
    switch (buscarpor) {
        case 'identificacion':
            if ($("#identificacionBusq").val() === '') {
                alert("POR FAVOR DIGITE LA IDENTIFICACION PARA INICIAR LA BUSQUEDA");
                $("#identificacionBusq").focus();
            } else {
                if ($("#identificacionBusq").val().length < 3) {
                    alert("LA IDENTIFICACION DEBE TENER AL MENOS 3 DIGITOS");
                    $("#identificacionBusq").focus();
                } else {
                    $.get('/josandro/clientes/administracion/getinfocliente', {identificacion: $("#identificacionBusq").val()}, setInfoCliente, 'json');
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
//------------------------------------------------------------------------------
function setInfoCliente(datos) {
    $("#modalSeleccionar").modal('hide');
    if (parseInt(datos['error']) === 0) {
        if (parseInt(datos['seleccionar']) === 0) {
            var info = datos['html'];
            var fila = '';
            $.each(info['direcciones'], function (i, direccion) {
                fila = '<tr>';
                fila += '<td>' + direccion['idServicio'] + '</td>';
                if (parseInt(direccion['idTipoServicio']) === 2 && parseInt(direccion['clasificacion']) === 1) {
                    fila += '<td><input type="checkbox" onchange="empaquetar(' + direccion['idServicio'] + ', this)" title="EMPAQUETAR ESTE SERVICIO"></td>';
                } else {
//                    fila += '<td><input type="checkbox" onchange="empaquetar(' + direccion['idServicio'] + ', this)" title="EMPAQUETAR ESTE SERVICIO"></td>';
                    fila += '<td></td>';
                }
                fila += '<td>' + direccion['tiposervicio'] + '</td>';
                fila += '<td>' + direccion['conceptofacturacion'] + '</td>';
                fila += '<td>' + direccion['sucursal'] + '</td>';
                fila += '<td>' + direccion['zona'] + '</td>';
                fila += '<td>' + direccion['barrio'] + '</td>';
                fila += '<td>' + direccion['direccion'] + '</td>';
                fila += '<td>' + direccion['estado'] + '</td>';
                fila += '</tr>';
                $("#tblDireccionesOLD tbody").append(fila);
            });
            if (info['tipocliente'] === 'Persona Natural') {
                $("#clienteHistoricoDir").html(info['nombres'] + ' ' + info['apellidos']);
            } else {
                $("#clienteHistoricoDir").html(info['razonsocial']);
            }
            $("#infoDirInstalacionOLD").show('slow');
            $("#idCliente").val(info['idCliente']);
            $("#tipocliente").val(info['tipocliente']);
            $("#idTipoIdentificacion").val(info['idTipoIdentificacion']);
            $("#identificacion").val(info['identificacion']);
            $("#nombres").val(info['nombres']);
            $("#apellidos").val(info['apellidos']);
            $("#sexo").val(info['sexo']);
            $("#sexoaux").val(info['sexo']);
            $("#razonsocial").val(info['razonsocial']);
            $("#representantelegal").val(info['representantelegal']);
            $("#identificacionreprelegal").val(info['identificacionreprelegal']);
            $("#celular1").val(info['celular1']);
            $("#celular1aux").val(info['celular1']);
            $("#celular2").val(info['celular2']);
            $("#celular2aux").val(info['celular2']);
            $("#celular3").val(info['celular3']);
            $("#celular3aux").val(info['celular3']);
            $("#telefono").val(info['telefono']);
            $("#telefonoaux").val(info['telefono']);
            $("#emailcontacto").val(info['emailcontacto']);
            $("#emailcontactoaux").val(info['emailcontacto']);
            $("#emailfacturacion").val(info['emailfacturacion']);
            $("#emailfacturacionaux").val(info['emailfacturacion']);
            setTipoCliente(info['tipocliente']);
            $("#divInfoCliente input").each(function () {
                $(this).removeAttr('required');
                $(this).attr('readonly', true);
            });
            $("#divInfoCliente select").each(function () {
                $(this).removeAttr('required');
                $(this).attr('disabled', true);
            });
            $("#divLimpiarCliente").show('slow');
            $("#divDetalleServiciosClienteEncontrado").show('slow');
        } else {
            setSeleccionar(datos['html']);
        }
    } else {
        alert("SE HA PRESENTADO UN INCONVENIENTE, JOSANDRO NO HA PODIDO REALIZAR LA OPERACION SOLICITADA.");
    }
}

function selectCliente(idCliente) {
    $.get('/josandro/clientes/administracion/getinfocliente', {idCliente: idCliente}, setInfoCliente, 'json');
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function getInfoTarifa() {
    if ($("#idSucursal").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    if ($("#idTipoServicio").val() === '') {
        alert("POR FAVOR SELECCIONE UN TIPO DE SERVICIO");
        $("#idTipoServicio").focus();
        return;
    }
    $.get('/josandro/tarifas/administracion/seleccionar', {idSucursal: $("#idSucursal").val(), idTipoServicio: $("#idTipoServicio").val()}, setSeleccionar);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function selectTarifa(idTarifa) {
    $.get('/josandro/tarifas/administracion/getTarifa', {idTarifa: idTarifa}, setTarifa, 'json');
    bloqueoAjax();
}
function setTarifa(datos) {
    if ($("#idTarifaOLD").length > 0) {
        var html = '<fieldset><legend>Nueva Tarifa Seleccionada</legend>' + datos['html'] + '</fieldset>';
        $("#idTarifa").val(datos['idTarifa']);
        $("#divInfoTarifa").html(html);
        $("#modalSeleccionar").modal('hide');
    } else {
        if (parseInt(datos['error']) === 0) {
            $("#idTarifa").val(datos['idTarifa']);
            $("#conceptofacturacion").val(datos['tipotarifa']);
            $("#tarifa").val(datos['tarifa']);
            $("#divInfoTarifa").html(datos['html']);
        } else {
            $("#idTarifa").val('');
            $("#conceptofacturacion").val('');
            $("#tarifa").val('');
            $("#divInfoTarifa").html('');
            alert("SE HA PRESENTADO UN INCONVENIENTE EN EL SISTEMA, POR FAVOR VUELVA A INTENTARLO.");
        }
        $("#modalSeleccionar").modal('hide');
    }
    $("#pagoinstalacion").val('');
    $("#pagoinstalacionAux").val('');
}

//------------------------------------------------------------------------------

function setTipoCliente(tipocliente) {
    if (tipocliente === '') {
        $("#divInfoPersonaNatural input").each(function () {
            $(this).removeAttr('required');
        });
        $("#sexo").removeAttr('required');
        $("#sexo").val('Masculino');
        $("#divInfoPersonaJuridica input").each(function () {
            $(this).removeAttr('required');
        });
        $("#divInfoPersonaNatural").hide();
        $("#divInfoPersonaJuridica").hide();
        return;
    }
    if (tipocliente === 'Persona Juridica') {
        $("#divInfoPersonaJuridica input").each(function () {
            $(this).attr('required', true);
        });
        $("#divInfoPersonaNatural input").each(function () {
            $(this).removeAttr('required');
        });
        $("#sexo").removeAttr('required');
        $("#sexo").val('Masculino');
        $("#divInfoPersonaNatural").hide();
        $("#divInfoPersonaJuridica").show('slow');
    } else {
        $("#divInfoPersonaNatural input").each(function () {
            $(this).attr('required', true);
        });
        $("#sexo").attr('required', true);
//        $("#sexo").val('');
        $("#divInfoPersonaJuridica input").each(function () {
            $(this).removeAttr('required');
        });
        $("#divInfoPersonaJuridica").hide();
        $("#divInfoPersonaNatural").show('slow');
    }
}

//------------------------------------------------------------------------------

function registrarDireccion(tipo) {
    var idSucursal = $("#idSucursal").val();
    if (idSucursal === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursal").focus();
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
            $("#infoDirInstalacion").val($("#direccionOK").val());
            $("#idMcpoInstalacion").val($("#idMunicipio").val());
            $("#idBarrioInstalacion").val($("#idBarrio").val());
            $("#idTipoViviendaInstalacion").val($("#idTipoVivienda").val());
            $("#estratoInstalacion").val($("#estrato").val());
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

function getMunicipios(idDepartamento) {
    if (idDepartamento !== '') {
        $.get('getselectmunicipios', {idDepartamento: idDepartamento}, setMunicipios);
        bloqueoAjax();
    } else {
        $("#idMunicipio").html("<option value=''>Seleccione...</option>");
    }
}
function setMunicipios(html) {
    $("#idMunicipio").html(html);
}

//------------------------------------------------------------------------------

function copiarDireccion(tipo) {
    switch (tipo) {
        case 'instalacion':
            direccionCopiada = $("#infoDirInstalacion").val();
            idMcpoCopiado = $("#idMcpoInstalacion").val();
            break;
        case 'facturacion':
            direccionCopiada = $("#infoDirFacturacion").val();
            idMcpoCopiado = $("#idMcpoFacturacion").val();
            break;
        case 'residencia':
            direccionCopiada = $("#infoDirResidencia").val();
            idMcpoCopiado = $("#idMcpoResidencia").val();
            break;
        default:
            direccionCopiada = '';
            idMcpoCopiado = 0;
            alert("SE HA PRESENTADO UN INCONVENIENTE EN EL SISTEMA, POR FAVOR INTENTELO DE NUEVO");
            break;
    }
}
function pegarDireccion(tipo) {
    switch (tipo) {
        case 'instalacion':
            $("#infoDirInstalacion").val(direccionCopiada);
            $("#idMcpoInstalacion").val(idMcpoCopiado);
            break;
        case 'facturacion':
            $("#infoDirFacturacion").val(direccionCopiada);
            $("#idMcpoFacturacion").val(idMcpoCopiado);
            break;
        case 'residencia':
            $("#infoDirResidencia").val(direccionCopiada);
            $("#idMcpoResidencia").val(idMcpoCopiado);
            break;
        default:
            alert("SE HA PRESENTADO UN INCONVENIENTE EN EL SISTEMA, POR FAVOR INTENTELO DE NUEVO");
            break;
    }
}

//------------------------------------------------------------------------------

function getZonas(idSucursal) {
    limpiarInfoTarifa();
    $("#idBarrio").html("<option value=''>Seleccione...</option>");
    if (idSucursal !== '') {
        $.get('getselectzonas', {idSucursal: idSucursal}, setZonas);
        bloqueoAjax();
    } else {
        $("#idZona").html("<option value=''>Seleccione...</option>");
    }
}
function setZonas(html) {
    $("#idZona").html(html);
}

//------------------------------------------------------------------------------

function getBarrios(idZona) {
    if (idZona !== '') {
        $.get('getselectbarrios', {idZona: idZona}, setBarrios);
        bloqueoAjax();
    } else {
        $("#idBarrio").html("<option value=''>Seleccione...</option>");
    }
}
function setBarrios(html) {
    $("#idBarrio").html(html);
}

//------------------------------------------------------------------------------

function limpiarInfoCliente() {
    $("#idCliente").val('');
    $("#divInfoCliente input").each(function () {
        $(this).removeAttr('readonly');
        $(this).attr('required', true);
        $(this).val('');
    });
    $("#celular2").removeAttr('required');
    $("#celular3").removeAttr('required');
    $("#telefono").removeAttr('required');
    $("#emailfacturacion").removeAttr('required');
    $("#divInfoCliente select").each(function () {
        $(this).removeAttr('disabled');
        $(this).attr('required', true);
        $(this).val('');
    });
    $("#divInfoPersonaNatural").hide('slow');
    $("#divInfoPersonaJuridica").hide('slow');
    $("#divLimpiarCliente").hide('slow');
    $("#tipocliente").focus();
    $("#divDetalleServiciosClienteEncontrado").hide('slow');
    $("#tblDireccionesOLD tbody").html('');
    $("#infoDirInstalacionOLD").hide('slow');
}

//------------------------------------------------------------------------------

function validarRegistrar() {
    if ($("#infoDirInstalacion").val() === '' || $("#idMcpoInstalacion").val() === '' || $("#idBarrioInstalacion").val() === '' || $("#idTipoViviendaInstalacion").val() === '' || $("#estratoInstalacion").val() === '') {
        alert("POR FAVOR REGISTRE LA DIRECCION DE INSTALACION DEL SERVICIO.");
        $("#infoDirInstalacion").focus();
        return false;
    }
    if ($("#idTarifa").val() === '') {
        alert("POR FAVOR SELECCIONE UNA TARIFA O PLAN PARA EL SERVICIO");
        $("#btnSeleccionarTarifa").focus();
        return false;
    }
    if ($("#pagoinstalacion").val() === '') {
        alert("POR FAVOR INICIE EL CALCULO PARA EL PAGO DE LA INSTALACION.");
        $("#btnCalcularPagoInstalacion").focus();
        return false;
    }

    if (confirm(" DESEA REGISTRAR ESTE SERVICIO ? ")) {
        bloqueoAjax();
        $("#formServicio select").each(function () {
            $(this).removeAttr('disabled');
        });
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function existeIdentificacion() {
    if ($("#identificacion").val() !== '') {
        $.get('/josandro/clientes/administracion/existeIdentificacion', {identificacion: $("#identificacion").val()}, setExisteIdentificacion, 'json');
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

function subirRespaldo(idServicio) {
    if (idServicio === 0) {
        alert("SE HA PRESENTADO UN INCONVENIENTE CON LA SINCRONIZACION DEL SERVICIO Y JOSANDRO.");
        return false;
    }
    if ($("#tiporespaldo").val() === '') {
        alert("DEBE SELECCIONAR UN TIPO DE RESPALDO");
        $("#tiporespaldo").focus();
        return false;
    }
    if (document.getElementById("respaldo").files.length === 0) {
        alert("DEBE SELECCIONAR UN ARCHIVO");
        $("#respaldo").focus();
        return false;
    }
    if (confirm("¿ DESEA SUBIR ESTE RESPALDO A JOSANDRO ?")) {
        var inputFile = document.getElementById("respaldo");
        var file = inputFile.files[0];
        var formData = new FormData();
        formData.append("idServicio", idServicio);
        formData.append("respaldo", file);
        formData.append("tiporespaldo", $("#tiporespaldo").val());
        $.ajax({
            url: "/josandro/servicios/legalizacion/subirarchivo",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (respuestaServidor) {
            switch (parseInt(respuestaServidor['error'])) {
                case 0:
                    alert("[ OK ] - RESPALDO SUBIDO A JOSANDRO");
                    break;
                case 1:
                    alert("[ ERROR ] - SE HA PRESENTADO UN INCONVENIENTE AL SUBIR EL RESPALDO A JOSANDRO");
                    break;
                case 2:
                    alert("[ ERROR ] - EL ARCHIVO DE EVIDENCIA NO ES VALIDO. \nSOLO SE ADMINTEN: PDF, PNG, JPEG, JPG \nTAMAÑO MAXIMO: 10 MB");
                    break;
            }
            verLegalizar(respuestaServidor['idServicio']);
        });
        bloqueoAjax();
    }
}

//------------------------------------------------------------------------------

function eliminarRespaldo(idRespaldo) {
    if (confirm("ESTA A PUNTO DE ELIMINAR ESTE ARCHIVO, UNA VEZ ELIMINADO NO SE PODRA RECUPERAR. \n\n DESEA ELIMINAR ESTE ARCHIVO ? ")) {
        $.get('/josandro/servicios/legalizacion/eliminararchivo', {idRespaldo: idRespaldo}, setEliminarArchivo, 'json');
        bloqueoAjax();
    }
}
function setEliminarArchivo(respuestaServidor) {
    switch (parseInt(respuestaServidor['error'])) {
        case 0:
            alert("[ OK ] - RESPALDO ELIMINADO DE JOSANDRO");
            break;
        case 1:
            alert("[ ERROR ] - SE HA PRESENTADO UN INCONVENIENTE, EL SERVICIO NO ENCONTRADO EN JOSANDRO");
            break;
        case 2:
            alert("[ ERROR ] - EL PROCESO DE ELIMINACION DEL RESPALDO SE EJECUTO EXITOSAMENTE, SIN EMBARGO NO SE ENCONTRARON RESPALDOS PARA ELIMINAR.");
            break;
        case 3:
            alert("[ ERROR ] - SE HA PRESENTADO UN INCONVENIENTE, RESPALDO NO ELIMINADO DE JOSANDRO");
            break;
    }
    verLegalizar(respuestaServidor['idServicio']);
}

//------------------------------------------------------------------------------

function validarLegalizar() {
    if ($("#idServicio").val() === '') {
        $("#btnLegalizar").attr('disabled', true);
        alert("SE HA PRESENTADO UN INCONVENIENTE EN JOSANDRO, NO ES POSIBLE LEGALIZAR ESTE SERVICIO.");
        return false;
    }
    if (confirm("ESTA A PUNTO DE LEGALIZAR ESTE SERVICIO, UNA VEZ LEGALIZADO NO SE ADMITIRAN CAMBIOS. \n\n DESEA CONTINUAR CON LA LEGALIZACION DE ESTE SERVICIO ? ")) {
        $("#btnLegalizar").attr('disabled', true);
        bloqueoAjax();
        return true;
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function validarCambiarTarifa() {
    var idTarifa = $.trim($("#idTarifa").val());
    if (idTarifa !== '') {
        if (idTarifa !== $.trim($("#idTarifaOLD").val())) {
            if (confirm("¿ DESEA REALIZAR ESTE CAMBIO DE TARIFA ?")) {
                $.post('actualizartarifa', {idTarifa: idTarifa, idServicio: $("#idServicio").val()}, setCambiarTarifa, 'json');
                bloqueoAjax();
            }
        } else {
            alert("LA TARIFA ACTUAL Y LA NUEVA TARIFA SON LAS MISMAS");
            $("#idTarifa").focus();
        }
    } else {
        alert("POR FAVOR SELECCIONE LA NUEVA TARIFA");
        $("#idTarifa").focus();
    }
}

//------------------------------------------------------------------------------

function seleccionarNuevaTarifa(idSucursal, idTipoServicio, numtarifa) {
    $.get('../../tarifas/administracion/seleccionar', {idSucursal: idSucursal, idTipoServicio: idTipoServicio, numtarifa: numtarifa}, setSeleccionar);
    bloqueoAjax();
}

//function selectTarifa(idTarifa, numtarifa) {
//    $.get('../../tarifas/administracion/getTarifa', {idTarifa: idTarifa, numtarifa: numtarifa}, setTarifa, 'json');
//    bloqueoAjax();
//}
//function setTarifa(datos) {
//    var html = '<fieldset><legend>Nueva Tarifa Seleccionada</legend>' + datos['html'] + '</fieldset>';
//    $("#idTarifa").val(datos['idTarifa']);
//    $("#conceptofacturacion").val(datos['tipotarifa']);
//    $("#tarifa").val(datos['tarifa']);
//    $("#pagoinstalacion").val('20,000');
//    $("#pagoinstalacionAux").val('20,000');
//    $("#divInfoTarifa").html(html);
//    $("#modalSeleccionar").modal('hide');
//}

//------------------------------------------------------------------------------

function setCambiarTarifa(respuesta) {
    switch (parseInt(respuesta['ok'])) {
        case 0:
            alert("[ ERROR ] - LA TARIFA NO PUDO SER ACTUALIZADA.");
            break;
        case 1:
            alert("[ OK ] - TARIFA ACTUALIZADA EN JOSANDRO Y ANCHOS DE BANDA ACTUALIZADOS EN LA MIKROTIK.");
            break;
        case 2:
            alert("[ OK ] - TARIFA ACTUALIZADA EN JOSANDRO Y ANCHOS DE BANDA ACTUALIZADOS EN LA MIKROTIK.");
//            alert("[ ERROR ] - LA TARIFA FUE ACTUALIZADA EN JOSANDRO, PERO SE PRESENTO UN INCONVENIENTE AL ACTUALIZAR LOS ANCHOS DE BANDA EN LA MIKROTIK. \n\n ANCHOS DE BANDA NO ACTUALIZADOS EN LA MIKROTIK.");
            break;
    }
//    location.reload();
}

//------------------------------------------------------------------------------

function setBasico(base) {
    var basico = parseInt(base);
    if (isNaN(basico)) {
//        $("#numtvsprincipales").attr('max', 1);
//        $("#numtvadicionales").attr('max', 1);
        if ($("#vlrtarifaAux").length > 0) {
            $("#vlrtarifa").val($("#vlrtarifaAux").val());
            $("#tarifa").val($("#vlrtarifa").val().replace(/\,/g, ''));
        }
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
        if ($("#vlrtarifaAux").length > 0) {
            var vlrtarifa = $("#vlrtarifaAux").val().replace(/\,/g, '');
            switch (parseInt($("#idTipoServicioTarifa").val())) {
                case 1:
                    if (basico === 1) {
                        $("#vlrtarifa").val($("#vlrtarifaAux").val());
                    } else {
                        $("#vlrtarifa").val(formatoMoneda((vlrtarifa * basico), 0));
                    }
                    $("#tarifa").val($("#vlrtarifa").val().replace(/\,/g, ''));
                    break;
                case 2:
                    if (basico === 1) {
                        $("#vlrtarifa").val($("#vlrtarifaAux").val());
                    } else {
                        $("#vlrtarifa").val(formatoMoneda((vlrtarifa * basico), 0));
                    }
                    $("#tarifa").val($("#vlrtarifa").val().replace(/\,/g, ''));
                    break;
            }
        }
    }
}

//------------------------------------------------------------------------------

function formatoMoneda(cnt, cents) {
    cnt = cnt.toString().replace(/\$|\u20AC|\,/g, '');
    if (isNaN(cnt))
        return 0;
    var sgn = (cnt == (cnt = Math.abs(cnt)));
    cnt = Math.floor(cnt * 100 + 0.5);
    cvs = cnt % 100;
    cnt = Math.floor(cnt / 100).toString();
    if (cvs < 10)
        cvs = '0' + cvs;
    for (var i = 0; i < Math.floor((cnt.length - (1 + i)) / 3); i++)
        cnt = cnt.substring(0, cnt.length - (4 * i + 3)) + ','
                + cnt.substring(cnt.length - (4 * i + 3));

    return (((sgn) ? '' : '-') + cnt) + (cents ? '.' + cvs : '');
}

//------------------------------------------------------------------------------

function setLegalizados() {
    $("#pagoinstalacion").val('');
    $("#pagoinstalacionAux").val('');
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
    $("#pagoinstalacion").val('');
    $("#pagoinstalacionAux").val('');
}

//------------------------------------------------------------------------------

function deshabilitar(idServicio) {
    if (confirm("  DESEA SUSPENDER ESTE SERVICIO ? ")) {
        $.get('deshabilitar', {idServicio: idServicio}, setRespuesta, 'json');
        bloqueoAjax();
    }
}
function habilitar(idServicio) {
    if (confirm("  DESEA ACTIVAR ESTE SERVICIO ? ")) {
        $.get('habilitar', {idServicio: idServicio}, setRespuesta, 'json');
        bloqueoAjax();
    }
}

function setRespuesta(respuesta) {
    var msg = "SE HA PRESENTADO UN ERROR";
    switch (parseInt(respuesta['ok'])) {
        case 0:
            msg = " [ ERROR ] EL SERVICIO NO FUE " + respuesta['estado'];
            break;
        case 1:
            msg = " [ OK ] - SERVICIO " + respuesta['estado'] + " EN JOSANDRO Y EN LA MIKROTIK";
//            msg = " [ OK ] - SERVICIO " + respuesta['estado'] + " EN JOSANDRO \n [ ERROR ] EL SERVICIO NO FUE " + respuesta['estado'] + " EN LA MIKROTIK";
            break;
        case 2:
            msg = " [ OK ] - SERVICIO " + respuesta['estado'] + " EN JOSANDRO Y EN LA MIKROTIK";
            break;
    }
    alert(msg);
    location.reload();
}

//------------------------------------------------------------------------------

function cambiarDireccionServicio(idServicio) {
    $.get('cambiardireccionservicio', {idServicio: idServicio}, setCambiarDireccion);
    bloqueoAjax();
}
function setCambiarDireccion(datos) {
    //console.log(datos)
    $("#divContenidoEditDireccion").html(datos);
    $('#modalEditDireccion').modal('show');
}

function validarCambioDireccionServicio() {
    if (confirm(" DESEA GUARDAR LOS CAMBIOS ? ")) {
        bloqueoAjax();
        return true;
    }
    return false;
}


//------------------------------------------------------------------------------

function limpiarInfoTarifa() {
    $("#idTarifa").val('');
    $("#conceptofacturacion").val('');
    $("#tarifa").val('');
    $("#pagoinstalacion").val('');
    $("#pagoinstalacionAux").val('');
    $("#divInfoTarifa").html('');
}

//------------------------------------------------------------------------------

function validarClasificar() {
    var msg = "";
    switch (parseInt($("#clasificacion").val())) {
        case 1:
            msg = "\n  DESEA CONFIRMAR ESTE SERVICIO ?";
            break;
        case 2:
            msg = "\n  DESEA CONFIRMAR ESTE SERVICIO ?";
            break;
        default:
            alert("SE HA PRESENTADO UN ERROR POR FAVOR ACTUALICE LA PAGINA E INTENTE DE NUEVO.");
            return false;
            break;
    }
    if (confirm(msg)) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function verClasificarMultiple() {
    if ($("input[name='checkes[]']:checked").length === 0) {
        alert("DEBE SELECCIONAR AL MENOS UN SERVICIO");
        return;
    }
    var idsServicios = [];
    $("input[name='checkes[]']:checked").each(function () {
        idsServicios.push(this.value);
    });
    $.get('verClasificarMultiple', {idsServicios: idsServicios}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function validarClasificarMultiple() {
    var msgconfirm = "LOS SIGUIENTES SERVICIOS: " + $("#idsServicios").val() + "\n SERAN CONFIRMADOS EN EL SISTEMA"
            + "\n\n  DESEA PROCEDER CON ESTA CONFIRMACION ? ";
    if (confirm(msgconfirm)) {
        bloqueoAjax();
        return true;
    }
    return false;
}

//------------------------------------------------------------------------------

function consultarAbonado() {
    if ($("#identificacion").val() === '') {
        alert("PARA INICIAR LA CONSULTA DEL ABONADO DEBE DIGITAR LA IDENTIFICACION");
        $("#identificacionBusq").focus();
        return false;
    }
    $.get('/josandro/servicios/administracion/consultarabonado', {identificacionBusq: $("#identificacion").val()}, setConsultaAbonado);
    bloqueoAjax();
}
function setConsultaAbonado(datos) {
    //console.log(datos)
    $("#divContenidoClienteEncontrado").html(datos);
    $('#modalDetalleClienteEncontrado').modal('show');
}


//------------------------------------------------------------------------------

function getEstadoCuenta(a, idServicio) {
    $("#divEstadoCuenta").html('');
    var fila = $(a).parents('tr');
    $("#tblHistorial tr").removeClass("danger");
    $(fila).addClass("danger");
    $.get('/josandro/servicios/administracion/getestadocuenta', {idServicio: idServicio}, setEstadoCuenta);
    bloqueoAjax();
}
function setEstadoCuenta(html) {
    $("#divEstadoCuenta").html(html);
}

//------------------------------------------------------------------------------

function calcularPagoInstalacion() {
    if ($("#idSucursal").val() === '') {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE LA SUCURSAL.");
        $("#idSucursal").focus();
        return false;
    }
    if ($("#idTarifa").val() === '' || parseInt($("#idTarifa").val()) === 0) {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE UNA TARIFA DE SERVICIO.");
        $("#btnSeleccionarTarifa").focus();
        return false;
    }
    if ($("#basico").val() === '') {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE EL BASICO.");
        $("#basico").focus();
        return false;
    }
    if ($("#numtvadicionales").val() === '') {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE LA CANTIDAD DE PUNTOS ADICIONALES.");
        $("#numtvadicionales").focus();
        return false;
    }
    if ($("#numlegalizados").val() === '') {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE LA CANTIDAD DE PUNTOS LEGALIZADOS.");
        $("#numlegalizados").focus();
        return false;
    }
    if ($("#idTarifaInstalacion").val() === '') {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE LA TARIFA DE INSTALACION.");
        $("#idTarifaInstalacion").focus();
        return false;
    }
    if ($("#idTarifaInstall").length === 0) {
        alert("PARA CALCULAR EL PAGO DE LA INSTLACION ES NECESARIO QUE SELECCIONE UNA TARIFA DE INSTALACION.");
        $("#btnBuscarTarifaInstall").focus();
        return false;
    }

    var adicionales = $("#numtvadicionales").val();
    var legalizados = $("#numlegalizados").val();

    $.get('calcularpagoinstalacion', {idSucursal: $("#idSucursal").val(), idTarifaInstall: $("#idTarifaInstall").val(), adicionales: $("#numtvadicionales").val(), legalizados: $("#numlegalizados").val()}, setPagoInstalacion, 'json');
    bloqueoAjax();
}
function setPagoInstalacion(datos) {
    if (parseInt(datos['error']) === 0) {
        $("#pagoinstalacion").val(formatoMoneda(datos['pago']));
        $("#pagoinstalacionAux").val(formatoMoneda(datos['pago']));
    } else {
        $("#pagoinstalacion").val('');
        $("#pagoinstalacionAux").val('');
        alert("SE HA PRESENTADO UN INCONVENIENTE, EL VALOR DE INSTALACION NO PUDO SER CALCULADO");
    }
}

//------------------------------------------------------------------------------

function limpiarPagoInstalacion() {
    $("#pagoinstalacion").val('');
    $("#pagoinstalacionAux").val('');
    if ($("#mesanticipado").is(':checked')) {
        if ($("#tarifa").val() !== '') {
            $("#pagoinstalacion").val(0);
            $("#pagoinstalacionAux").val(0);
            $("#btnCalcularPagoInstalacion").attr('disabled', true);
            $("#vlrMesAnticipado").val($("#tarifa").val());
            $("#divVlrMesAnticipado").show('slow');
        } else {
            $("#sincable").prop("checked", true);
            alert("PARA UTILIZAR EL MES ANTICIPADO, ES OBLIGATORIO SELECCIONAR PRIMERO LA TARIFA.");
            $("#idTipoServicio").focus();
        }
    } else {
        $("#btnCalcularPagoInstalacion").removeAttr('disabled');
        $("#vlrMesAnticipado").val(0);
        $("#divVlrMesAnticipado").hide('slow');
    }
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

function empaquetar(idServicio, checkbox) {
    if ($(checkbox).is(':checked')) {
        $("#idEmpaquetado").val(0);
        $.get('empaquetar', {idServicio: idServicio}, setEmpaquetado, 'json');
        bloqueoAjax();
    } else {
        $("#idTipoServicio").removeAttr('disabled');
        $("#idTipoServicio").val('');
        $("#basico").val('');
        $("#numtvsprincipales").html('<option value="">Seleccione...</option>');
        $("#numtvadicionales").html('<option value="">Seleccione...</option>');
        $("#numlegalizados").html('<option value="">Seleccione...</option>');
        $("#idEmpaquetado").val(0);

        $("#infoDirInstalacion").val('');
        $("#idMcpoInstalacion").val('');
        $("#idBarrioInstalacion").val('');
        $("#idTipoViviendaInstalacion").val('');
        $("#estratoInstalacion").val('');
        $("#btnRegistrarDireccion").removeAttr('disabled');
    }
}
function setEmpaquetado(datos) {
    var servicio = datos['servicio'];
    if (servicio['estado'] === 'Cortado') {

    } else {
        $("#infoDirInstalacion").val(servicio['direccion']);
        $("#idMcpoInstalacion").val(servicio['idMunicipio']);
        $("#idBarrioInstalacion").val(servicio['idBarrio']);
        $("#idTipoViviendaInstalacion").val(servicio['idTipoVivienda']);
        $("#estratoInstalacion").val(servicio['estrato']);
        $("#btnRegistrarDireccion").attr('disabled', true);
    }
    $("#idTipoServicio").val(1);
    $("#idTipoServicio").attr('disabled', true);
    $("#basico").val(servicio['basico']);
    setBasico(servicio['basico']);
    $("#numtvsprincipales").val(servicio['numtvsprincipales']);
    $("#numtvadicionales").val(servicio['numtvadicionales']);
    setAdicionales(servicio['numtvadicionales']);
    $("#numlegalizados").val(servicio['numlegalizados']);
    $("#idEmpaquetado").val(servicio['idServicio']);
}

//------------------------------------------------------------------------------

function actualizarInfoCliente(actualizar) {
    if (parseInt(actualizar) === 1) {
        if ($("#tipocliente").val() === 'Persona Natural') {
            $("#sexo").removeAttr('disabled');
            $("#sexo").attr('required', true);
            $("#celular1").removeAttr('readonly');
            $("#celular1").attr('required', true);
            $("#celular2").removeAttr('readonly');
            $("#celular3").removeAttr('readonly');
            $("#telefono").removeAttr('readonly');
            $("#emailcontacto").removeAttr('readonly');
            $("#emailcontacto").attr('required', true);
            $("#emailfacturacion").removeAttr('readonly');
            $("#btnActulizarInfoCliente").css('display', 'none');
            $("#btnCancelarActulizarInfoCliente").css('display', 'inline-block');
            $("#sexo").focus();
        } else {

        }
        $("#actualizarcliente").val(1);
    } else {
        if ($("#tipocliente").val() === 'Persona Natural') {
            $("#sexo").val($("#sexoaux").val());
            $("#sexo").attr('disabled', true);
            $("#sexo").removeAttr('required');
            $("#celular1").attr('readonly', true);
            $("#celular1").removeAttr('required');
            $("#celular1").val($("#celular1aux").val());
            $("#celular2").attr('readonly', true);
            $("#celular2").val($("#celular2aux").val());
            $("#celular3").attr('readonly', true);
            $("#celular3").val($("#celular3aux").val());
            $("#telefono").attr('readonly', true);
            $("#telefono").val($("#telefonoaux").val());
            $("#emailcontacto").attr('readonly', true);
            $("#emailcontacto").removeAttr('required');
            $("#emailcontacto").val($("#emailcontactoaux").val());
            $("#emailfacturacion").attr('readonly', true);
            $("#emailfacturacion").val($("#emailfacturacionaux").val());
            $("#btnActulizarInfoCliente").css('display', 'inline-block');
            $("#btnCancelarActulizarInfoCliente").css('display', 'none');
        } else {

        }
        $("#actualizarcliente").val(0);
    }
}

//------------------------------------------------------------------------------

function getInfoTarifaInstall() {
    if ($("#idSucursal").val() === '') {
        alert("POR FAVOR SELECCIONE UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    $.get('/josandro/tarifas/tarifasinstall/seleccionar', {idSucursal: $("#idSucursal").val(), idTipoServicio: $("#idTipoServicio").val()}, setSeleccionar);
    bloqueoAjax();
}
//------------------------------------------------------------------------------
function selectTarifaInstall(idTarifa) {
    $.get('/josandro/tarifas/tarifasinstall/getTarifa', {idTarifa: idTarifa}, setTarifaInstall, 'json');
    bloqueoAjax();
}
function setTarifaInstall(datos) {
    $("#divInfoTarifaInstall").html(datos['html']);
    $("#modalSeleccionar").modal('hide');
}

//------------------------------------------------------------------------------




