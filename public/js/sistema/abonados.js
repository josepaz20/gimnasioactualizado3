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

function verRegistrar() {
    $.get('add', {idSucursalBusq: $("#idSucursalBusq").val()}, setFormulario);
    bloqueoAjax();
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
function verCredenciales(idServicio) {
    $.get('credenciales', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function gestionarDirecciones(idServicio) {
    $.get('gestionardirecciones', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}
//------------------------------------------------------------------------------
function verAgregarServicio(tipo, accion, contservicio) {
    $("#idTarifa").val('');
    $("#divInfoTarifa").html('');
    $("#divContenido1").html('');
    if ($("#tiposervicio").val() !== '') {
        $.get('agregarservicio', {tipo: tipo, accion: accion, contservicio: contservicio}, setAgregarServicio);
        bloqueoAjax();
    } else {
        alert("DEBE SELECCIONAR UN TIPO DE SERVICIO");
        $("#tiposervicio").focus();
    }
}
function setAgregarServicio(datos) {
    //console.log(datos)
    $("#divContenido1").html(datos);
    $("#numTarifas").val(0);
    $("#idsTarifasNew").val('');
    $("#tbltarifas tbody").html('');
    $('#modalAgregarServicio').modal('show');
}

//------------------------------------------------------------------------------

function seleccionarCliente() {
    var idSucursal = $("#idSucursal").val();
    var tipoCliente = $("#tipocliente").val();
    if (idSucursal === '') {
        alert("DEBE SELECCIONAR UNA SUCURSAL");
        $("#idSucursal").focus();
        return;
    }
    if (tipoCliente === '') {
        alert("DEBE SELECCIONAR UN TIPO DE CLIENTE");
        $("#tipocliente").focus();
        return;
    }
    if (tipoCliente === 'Empresa') {
        $.get('/josandro/empresas/administracion/seleccionar', {idSucursalBusq: idSucursal}, setSeleccionarEmpresa);
    } else {
        $.get('/josandro/personas/administracion/seleccionar', {idSucursalBusq: idSucursal}, setSeleccionarPersona);
    }
    bloqueoAjax();
}
function setSeleccionarEmpresa(datos) {
    $("#divAnexar").html(datos);//pone los datos
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
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');
}

function selectEmpresa(idEmpresa) {
    $.get('/josandro/empresa/administracion/getEmpresa', {idEmpresa: idEmpresa}, setEmpresa);
}
function setEmpresa(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoCliente").html(datos);
    $("#fk_persona_id_factura").val(0);
    $("#fk_empresa_id_factura").val($("#pk_empresa_id").val());
    $("#cliente").val($("#razonsocial").val());
    $("#ubicacion").val($("#pk_municipio_id option:selected").html() + '-' + $("#pk_departamento_id option:selected").html());
    calcularTotalesConcepto();
    calcularTotales();
}
//------------------------------------------------------------------------------
function setSeleccionarPersona(datos) {
    $("#divAnexar").html(datos);//pone los datos
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
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}

function selectPersona(idPersona) {
    $.get('/josandro/personas/administracion/getPersona', {idPersona: idPersona}, setPersona);
}
function setPersona(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoCliente").html(datos);
    $("#idEmpresa").val(0);
    $("#idPersona").val($("#idPersonaSelect").val());
    $("#idSucursal").val($("#idSucursalSelect").val());
    $("#idZona").val($("#idZonaSelect").val());
    getBarrios($("#idZonaSelect").val(), $("#idBarrioSelect").val());
    $("#dirinstalacion").val($("#direccion").val());
    $("#cliente").val('');
}

//------------------------------------------------------------------------------

function mostrarInfoDetalleCliente() {
    if ($("#divInfoCliente").is(":visible")) {
        $("#mas_menos").attr('class', 'fa fa-plus-circle');
        $("#divInfoCliente").hide('slow');
    } else {
        $("#mas_menos").attr('class', 'fa fa-minus-circle');
        $("#divInfoCliente").show('slow');
    }
    $("#cliente").focus();
}

//------------------------------------------------------------------------------

function getZonas(idSucursal) {
    $("#idZona").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('/josandro/configuracion/zonas/getZonas', {idSucursal: idSucursal}, setZonas);
        bloqueoAjax();
    }
}
function setZonas(datos) {
    $("#idZona").html(datos);
}

function getBarrios(idZona, idBarrio) {
    $("#idBarrio").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('/josandro/barrios/administracion/getBarrios', {idZona: idZona, idBarrio: idBarrio}, setBarrios);
        bloqueoAjax();
    }
}
function setBarrios(datos) {
    $("#idBarrio").html(datos);
}
//------------------------------------------------------------------------------
function getZonasRes(idSucursal) {
    $("#idZonaRes").html('<option value="">Seleccione...</option>');
    if (idSucursal !== '') {
        $.get('/josandro/configuracion/zonas/getZonas', {idSucursal: idSucursal}, setZonasRes);
        bloqueoAjax();
    }
}
function setZonasRes(datos) {
    $("#idZonaRes").html(datos);
}

function getBarriosRes(idZona, idBarrio) {
    $("#idBarrioRes").html('<option value="">Seleccione...</option>');
    if (idZona !== '') {
        $.get('/josandro/barrios/administracion/getBarrios', {idZona: idZona, idBarrio: idBarrio}, setBarriosRes);
        bloqueoAjax();
    }
}
function setBarriosRes(datos) {
    $("#idBarrioRes").html(datos);
}

//------------------------------------------------------------------------------

function seleccionarTarifa(numtarifa) {
    if ($("#numTarifas").val() >= 3) {
        alert("NO ES POSIBLE CARGAR MAS TARIFAS AL SERVICIO");
        return false;
    }
    var infoDirInstalacion = $("#infoDirInstalacion").val();
    if (infoDirInstalacion === '') {
        alert("POR FAVOR INGRESE LA DIRECCION DE INSTALACION PARA DETERMINAR LAS TARIFAS A USAR");
        if ($("#modalAgregarServicio").length > 0) {
            $("#modalAgregarServicio").modal('hide');
        }
        $("#dirInstalacion").focus();
        return;
    }
    if (numtarifa > 1) {
        if ($("#idTarifa" + (numtarifa - 1)).length === 0) {
            alert("POR FAVOR INGRESE LA TARIFA " + (numtarifa - 1));
            return;
        }
    }
    var partes = infoDirInstalacion.split(';');
    if (partes.length > 0) {
        var idSucursal = partes[0], idTipoServicio = 0;
        switch ($("#tipoAddService").val()) {
            case 'Internet':
                idTipoServicio = 1;
                break;
            case 'Television':
                idTipoServicio = 2;
                break;
            case 'HD':
                idTipoServicio = 3;
                break;
            default:
                idTipoServicio = 0;
                break;
        }
        $.get('../../tarifas/administracion/seleccionar', {idSucursalBusq: idSucursal, idTipoServicio: idTipoServicio, numtarifa: numtarifa}, setSeleccionarTarifa);
        bloqueoAjax();
    } else {
        alert("POR FAVOR INGRESE LA DIRECCION DE INSTALACION PARA DETERMINAR LAS TARIFAS A USAR");
        if ($("#modalAgregarServicio").length > 0) {
            $("#modalAgregarServicio").modal('hide');
        }
        $("#dirInstalacion").focus();
    }
}
function setSeleccionarTarifa(datos) {
    $("#divAnexar").html(datos);//pone los datos
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
                "sFirst": "Inicio",
                "sLast": "Fin",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}

function selectTarifa(idTarifa, numtarifa) {
    $.get('../../tarifas/administracion/getTarifa', {idTarifa: idTarifa, numtarifa: numtarifa}, setTarifa, 'json');
    bloqueoAjax();
}
function setTarifa(datos) {
    $("#idTarifa").val(datos['idTarifa']);
    $("#divInfoTarifa").html(datos['html']);
    $("#modalAnexar").modal('hide');
}

//------------------------------------------------------------------------------

function setInstalarGratis(gratis) {
    if (parseInt(gratis) === 1) {
        $("#pagoinstalacion").val(0);
        $("#modalidadpago").val('Factura Instalacion');
        $("#divInstalarGratis").hide('slow');
    } else {
        $("#pagoinstalacion").val('');
        $("#modalidadpago").val('');
        $("#divInstalarGratis").show('slow');
    }
}

//------------------------------------------------------------------------------

function setDireccionInst() {
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
    $("#dirinstalacion").val(direccion);
}

function setDireccionRes() {
    var direccion = "";
    direccion = $("#parte1Res").val() + " " + $("#numero1Res").val();
    if ($("#letra1Res").val() !== '') {
        direccion += $("#letra1Res").val();
    }
    direccion += " # " + $("#numero2Res").val();
    if ($("#letra2Res").val() !== '') {
        direccion += $("#letra2Res").val();
    }
    direccion += " - " + $("#numero3Res").val();
    if ($("#parte2Res").val() !== '') {
        direccion += " " + $("#parte2Res").val();
    }
    $("#direccion").val(direccion);
}

//------------------------------------------------------------------------------

function validarAdd() {
    if ($("#idTarifa").val() === '') {
        alert("DEBE SELECCIONAR UNA TARIFA");
        $("#btnSeleccionarTarifa").focus();
        return false;
    }
    return confirm(" ¿ DESEA REGISTRAR ESTE ABONADO DE INTERNET ? ");
}

//------------------------------------------------------------------------------

function  setInfoLegalizar(idContrato, cliente) {
    $("#idServicioLegal").val(idContrato);
    $("#clienteLegal").val(cliente);
    $("#soporteLegal").val('');
}

//------------------------------------------------------------------------------

function setTipoCliente(idCliente) {
    if ($("#tipocliente").val() === '') {
        $("#infoPersonaJuridica").hide('slow');
        $("#infoPersonaNatural").hide('slow');
    } else if ($("#tipocliente").val() === 'Persona Natural') {
        $.get('../../personas/administracion/getRegistro', {idCliente: idCliente}, setPersonaNatural);
        bloqueoAjax();
    } else {
        $.get('../empresas/administracion/getRegistro', {idCliente: idCliente}, setPersonaNatural);
        bloqueoAjax();
    }
}

function setPersonaNatural(html) {
    $("#infoPersonaJuridica").html('');
    $("#infoPersonaJuridica").hide();
    $("#infoPersonaNatural").html(html);
    $("#infoPersonaNatural").show('slow');
}

function setPersonaJuridica(html) {
    $("#infoPersonaNatural").html('');
    $("#infoPersonaNatural").hide();
    $("#infoPersonaJuridica").html(html);
    $("#infoPersonaJuridica").show('slow');
}

//------------------------------------------------------------------------------

function agregarDireccion(tipodireccion) {
    $("#divAnexarDireccion input").each(function () {
        $(this).val('');
    });
    $("#divAnexarDireccion select").each(function () {
        $(this).val('');
    });
    $("#tipodireccion").val(tipodireccion);
    $("#dlgDirecciones").modal('show');
}

function copiarDireccion(tipodireccion) {
    $("#direccionCopiada").val($("#dir" + tipodireccion).val());
    $("#infoDireccionCopiada").val($("#infoDir" + tipodireccion).val());
}

function pegarDireccion(tipodireccion) {
    if (tipodireccion === 'Instalacion') {
        limpiarInfoTarifa();
    }
    $("#dir" + tipodireccion).val($("#direccionCopiada").val());
    $("#infoDir" + tipodireccion).val($("#infoDireccionCopiada").val());
}

function validarAgregarDireccion() {
    if ($("#tipodireccion").val() === '') {
        alert("SE HA PRESENTADO UN ERROR, POR FAVOR INTENTE AGREGAR LA DIRECCION NUEVAMENTE");
        return;
    }
    var requeridos = false;
    var msg = "FALTA INFORMACION. LOS SIGUIENTES CAMPOS SON REQUERIDOS: \n\n";
    if ($("#idSucursalRes").val() === '') {
        msg += " (*). SUCURSAL \n";
        requeridos = true;
    }
    if ($("#idZonaRes").val() === '') {
        msg += " (*). ZONA \n";
        requeridos = true;
    }
    if ($("#idBarrioRes").val() === '') {
        msg += " (*). BARRIO \n";
        requeridos = true;
    }
    if ($("#tipovivienda").val() === '') {
        msg += " (*). TIPO VIVIENDA \n";
        requeridos = true;
    }
    if ($("#estrato").val() === '') {
        msg += " (*). ESTRATO \n";
        requeridos = true;
    }
    if (requeridos) {
        alert(msg);
        return;
    }
    if ($("#direccion").val() === '') {
        alert("EL CAMPO DIRECCION ES OBLIGATORIO. \n\nPOR FAVOR UTILIZE LA SECCION DE DIRECCION PARA GENERAR LA DIRECCION DESEADA");
        $("#parte1Res").focus();
        return;
    }
    if (confirm(' ¿ DESEA AGREGAR ESTA DIRECCION ? ')) {
        var info = $("#idSucursalRes").val() + ';' +
                $("#idZonaRes").val() + ';' +
                $("#idBarrioRes").val() + ';' +
                $("#idTipoVivienda").val() + ';' +
                $("#estrato").val() + ';' +
                $("#direccion").val() + ';' +
                $("#latitud").val() + ';' +
                $("#longitud").val();
        $("#dir" + $("#tipodireccion").val()).val($("#direccion").val());
        $("#infoDir" + $("#tipodireccion").val()).val(info);
        if ($("#tipodireccion").val() === 'Instalacion') {
            limpiarInfoTarifa();
        }
        $("#dlgDirecciones").modal('hide');
    }
}

function limpiarInfoTarifa() {
    $("#divInfoTarifa").html('');
    $("#tarifaSeleccionada").val('');
    $("#tarifa").val('');
    $("#velsubida").val('');
    $("#velbajada").val('');
    $("#unidadanchobanda").val('');
    $("#idTarifa").val('');
    $("#conceptofacturacion").val('');
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

var map = null;
var marker = null;

function abrirMapa() {
    if ($("#actionMap").attr('class') === 'cerrado') {
        $("#divMap").show('slow');
        setTimeout(cargarMapa, 500);
        $("#actionMap").attr('class', 'abierto');
        $("#txtMap").html('Cerrar Google Maps');
    } else {
        $("#divMap").hide('slow');
        $("#actionMap").attr('class', 'cerrado');
        $("#txtMap").html('Abrir Google Maps');
    }
}

function cargarMapa() {
    if (map === null) {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: 2.930988157916674, lng: -75.28749181188107},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        map.addListener('click', function (e) {
            placeMarkerAndPanTo(e.latLng, map);
        });
    }
}

function placeMarkerAndPanTo(latLng, map) {
    eliminarMarkets();
    marker = new google.maps.Marker({
        position: latLng,
        animation: google.maps.Animation.BOUNCE,
        map: map
    });
    $("#latitud").val(latLng.lat());
    $("#longitud").val(latLng.lng());
//    map.panTo(latLng);
//    console.log(latLng.lng() + ' ' + latLng.lat());
}

function eliminarMarkets() {
    if (marker !== null) {
        marker.setMap(null);
    }
}

function limpiarCoordenadas() {
    $("#latitud").val('');
    $("#longitud").val('');
    eliminarMarkets();
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
    var existe = parseInt(datos['existe']);
    if (existe > 0) {
        alert(' LA IDENTIFICACION <<' + datos['infocliente']['identificacion'] + '>> FUE ENCONTRADA EN EL SISTEMA. \n\n A CONTINUACION SE CARGARA LA INFORMACION DEL TITULAR ENCONTRADO');

        $("#identificacion").val(datos['infocliente']['identificacion']);
        console.log(datos['infocliente']['idTipoIdentificacion'])
        $("#idTipoidentificacion").val(datos['infocliente']['idTipoIdentificacion']);
        $("#tipocliente").val(datos['tipocliente']);
        $("#existe").val(existe);
        if (datos['tipocliente'] === 'Persona Natural') {
            setTipoCliente(datos['infocliente']['idPersona']);
        }
        $("#identificacion").attr('readonly', true);
        $("#idTipoidentificacion").attr('disabled', true);
        $("#tipocliente").attr('disabled', true);
    }
}

//------------------------------------------------------------------------------

function authCodInstalarGratis() {
    if ($.trim($("#codinstalargratis").val()) !== '') {
        $.get('authCodInstalarGratis', {codigo: $.trim($("#codinstalargratis").val())}, setAuthCodInstalarGratis, 'json');
        bloqueoAjax();
    }
}
function setAuthCodInstalarGratis(respuesta) {
    var opciones = '', msg = '';
    if (parseInt(respuesta['ok']) === 1) {
        opciones = '<option value="">Seleccione...</option>' +
                '<option value="1">SI</option>' +
                '<option value="0">NO</option>';
        msg = "[ OK ] - CODIGO VALIDADO EXITOSAMENTE";
    } else {
        opciones = '<option value="">Seleccione...</option>' +
                '<option value="0">NO</option>';
        msg = "[ ERROR ] - CODIGO NO VALIDO";
    }
    $("#instalargratis").html(opciones);
    alert(msg);
}

//------------------------------------------------------------------------------

function validarAgregarServicio() {
    if ($('#idTarifa').val() === '') {
        alert("SELECCIONE UN PLAN O TARIFA PARA EL SERVICIO");
        $('#idTarifa').focus();
        return false;
    }

    if (confirm("¿ DESEA AGREGAR ESTE SERVICIO AL PRESENTE CONTRATO ?")) {
        if ($("#accionAddService").val() === 'add') {
            var nuevaFila = '<tr>' +
                    '<td>' + $("#contServicios").val() + '</td>' +
                    '<td>' + $("#tipoAddService").val() + '</td>' +
                    '<td>' + $("#nombretarifa").val() + '</td>' +
                    '<td>' + $("#vlrtarifa").val() + '</td>' +
                    '<td>' +
                    '<a href="javascript:eliminarServicioAdd(' + $("#contServicios").val() + ')"><i class="fa fa-times"></i></a> &nbsp; ' +
                    '<a href="javascript:editarServicioAdd(' + $("#contServicios").val() + ', \'' + $("#tipoAddService").val() + '\')"><i class="fa fa-edit"></i></a> &nbsp; ' +
                    '<a href="javascript:verServicioAdd(' + $("#contServicios").val() + ')"><i class="fa fa-eye"></i></a>' +
                    '</td>' +
                    '</tr>';
            $("#tblservicioscontratados").find('tbody').append(nuevaFila);

            var infoCampo = $("#tipoAddService").val();
            switch ($("#tipoAddService").val()) {
                case 'Internet':
                    infoCampo += '&&' + $("#numtvprincipales").val();
                    infoCampo += '&&' + $("#numtvadicionales").val();
                    infoCampo += '&&' + $("#numlegalizados").val();
                    break;
                case 'Television':
                    infoCampo += '&&' + $("#numtvprincipales").val();
                    infoCampo += '&&' + $("#numtvadicionales").val();
                    infoCampo += '&&' + $("#numlegalizados").val();
                    break;
                case 'HD':
                    infoCampo += '&&0&&0&&0';
                    break;
            }
            infoCampo += '&&' + $("#contmesgratis").val();
            infoCampo += '&&' + $("#prorrateook").val();
            infoCampo += '&&' + $("#idTarifa").val();
            infoCampo += '&&' + $("#nombretarifa").val();
            infoCampo += '&&' + $("#vlrtarifa").val().replace(/\,/g, '');

            var nuevoCampo = "<input type='hidden' name='servicio_" + $("#contServicios").val() + "' id='servicio_" + $("#contServicios").val() + "' value='" + infoCampo + "'>";
            $("#formAbonadointernet").append(nuevoCampo);

            $("#numServicios").val(parseInt($("#numServicios").val()) + 1);
            $("#contServicios").val(parseInt($("#contServicios").val()) + 1);
        } else {
        }
        $("#modalAgregarServicio").modal('hide');
    }
    return false;
}

//------------------------------------------------------------------------------

function editarServicioAdd(contServicio, tipo) {
    var infoEditar = $("#servicio_" + contServicio).val();
    var partesInfoEditar = infoEditar.split('&&');
    $("#divInfoTarifa1").html('');
    $("#divInfoTarifa2").html('');
    $("#divInfoTarifa3").html('');
    $.when(verAgregarServicio(tipo, 'edit', contServicio))
            .done(setTimeout(function () {
                $.when(selectTarifa(partesInfoEditar[0], 1))
                        .done(function () {
                            $('#idTarifa1').val(partesInfoEditar[0]);
                            $('#nombretarifa1').val(partesInfoEditar[2]);
                            $('#tarifa').val(partesInfoEditar[3]);
                            $("#divInfoTarifa1").append('');
                            setTimeout(function ( ) {
                                $('#fechainitarifa1').val(partesInfoEditar[1]);
                            }, 1500);
                        })
                        .fail(function (error) { //siempre pon el fail, en caso de que algo falle
                            console.error("OCURRIO UN ERROR: ", error);
                        });
                $.when(selectTarifa(partesInfoEditar[4], 1))
                        .done(function () {
                            console.log(partesInfoEditar[4]);
                            $('#idTarifa2').val(partesInfoEditar[4]);
                            $('#nombretarifa2').val(partesInfoEditar[6]);
                            $('#tarifa').val(partesInfoEditar[7]);
                            $("#divInfoTarifa2").append('');
                            setTimeout(function ( ) {
                                $('#fechainitarifa2').val(partesInfoEditar[5]);
                            }, 1500);
                        })
                        .fail(function (error) { //siempre pon el fail, en caso de que algo falle
                            console.error("OCURRIO UN ERROR: ", error);
                        });
                $.when(selectTarifa(partesInfoEditar[8], 1))
                        .done(function () {
                            $('#idTarifa3').val(partesInfoEditar[8]);
                            $('#nombretarifa3').val(partesInfoEditar[10]);
                            $('#tarifa').val(partesInfoEditar[11]);
                            $("#divInfoTarifa3").append('');
                            setTimeout(function ( ) {
                                $('#fechainitarifa3').val(partesInfoEditar[9]);
                            }, 1500);
                        })
                        .fail(function (error) { //siempre pon el fail, en caso de que algo falle
                            console.error("OCURRIO UN ERROR: ", error);
                        });
                $("#modalAgregarServicio").modal('show');
            }, 1500))
            .fail(function (error) { //siempre pon el fail, en caso de que algo falle
                console.error("OCURRIO UN ERROR: ", error);
            });
}

//------------------------------------------------------------------------------

function setMaxLegalizados() {
    $("#numlegalizados").attr('max', $("#numtvadicionales").val());
}

//------------------------------------------------------------------------------

function validarEliminar() {
    return confirm(" ¿ DESEA ELIMINAR ESTE ABONADO ? ");
}

//------------------------------------------------------------------------------

function getDireccionByTipo() {
    $.get('getdireccionbytipo', {tipodireccion: $("#tipodireccion").val(), idServicio: $("#idServicio").val(), tipocliente: $("#tipocliente").val(), idCliente: $("#idCliente").val()}, setDireccionByTipo, 'json');
    bloqueoAjax();
}
function setDireccionByTipo(datos) {
    $("#idZona").val(datos['idZona']);
}

//------------------------------------------------------------------------------

function cambiarDireccion(tipo) {
    if (tipo === 'todas') {
        $.get('cambiardireccion', {
            tipo: tipo,
            idsDirecciones: $("#idsDirecciones").val(),
            idsPersonaDireccion: $("#idsPersonaDireccion").val(),
            idServicio: $("#idServicioCambioDir").val()
        }, setCambiarDireccion);
        bloqueoAjax();
    }
}
function setCambiarDireccion(datos) {
    //console.log(datos)
    $("#divContenidoEditDireccion").html(datos);
    $('#modalEditDireccion').modal('show');
}

//------------------------------------------------------------------------------

function validarCambioDireccion() {
    if ($(".checkesmes:checked").length > 0 && $(".checkesanio:checked").length === 0) {
        alert("POR FAVOR SELECCIONE UN AÑO");
        return false;
    }
    return confirm(' DESEA GUARDAR LOS CAMBIOS ?');
}

//------------------------------------------------------------------------------


