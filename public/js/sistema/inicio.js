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
    $.get('registrarcambioplan', {}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function consultarAbonado() {
    if ($("#identificacionBusq").val() === '') {
        alert("PARA INICIAR LA CONSULTA DEL ABONADO DEBE DIGITAR LA IDENTIFICACION");
        $("#identificacionBusq").focus();
        return false;
    }
    $.get('/josandro/servicios/administracion/consultarabonado', {identificacionBusq: $("#identificacionBusq").val()}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function validarMigracion() {
    if ($("#formMigracion").attr('accion') === 'verificar' || $("#formMigracion").attr('accion') === 'importar') {
        return confirm(" ¿ DESEA << " + $("#formMigracion").attr('accion').toUpperCase() + " >> ESTE ARCHIVO ? ");
    } else {
        alert("SE HA PRESENTADO UN ERROR, POR FAVOR RECARGUE LA PAGINA E INTENTE DE NUEVO");
        return false;
    }
}

//------------------------------------------------------------------------------

function setAccionMigrar(accion) {
    if ($.trim($("#importar").val()) === '') {
        alert(" POR FAVOR SELECCIONE EL TIPO DE IMPORTACION.");
        $("#importar").focus();
        return false;
    }
    if ($.trim($("#respaldo").val()) === '') {
        alert(" POR FAVOR SELECCIONE UN ARCHIVO CON EXTENSION << .CSV >>");
        $("#respaldo").focus();
        return false;
    }
    if (confirm(" DESEA << " + accion.toUpperCase() + " >> ESTE ARCHIVO ? ")) {
        $("#btnImportar").attr('disabled', true);
        $("#btnVerificar").attr('disabled', true);
        var inputFile = document.getElementById("respaldo");
        var file = inputFile.files[0];
        var formData = new FormData();
        formData.append("respaldo", file);
        formData.append("importar", $("#importar").val());
        $.ajax({
            url: "/josandro/inicio/bandejaentrada/verificar",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (respuestaServidor) {
            var verificacion = respuestaServidor['verificacion'];
            var contErrores = parseInt(respuestaServidor['contErrores']);
            if (contErrores > 0) {
                alert("SE HAN PRESENTADO ERRORES EN EL ARCHIVO, POR FAVOR VERIFIQUE LA LISTA DE ERRORES.");
                $("#btnImportar").attr('disabled', true);
                $("#btnVerificar").removeAttr('disabled');
            } else {
                alert("EL ARCHIVO HA SIDO VERIFICADO CON EXITO Y NO SE HAN PRESENTADO ERRORES. \n\nSI DESEA IMPORTAR ESTOS ABONADOS A JOSANDRO POR FAVOR DE CLICK EN EL BOTON << IMPORTAR >>");
                $("#btnImportar").removeAttr('disabled');
                $("#btnVerificar").attr('disabled', true);
            }
            $("#erroresMigracion").html(verificacion);
        });
        bloqueoAjax();
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------

function importar() {
    if (confirm(" DESEA INICIAR EL PROCESO DE IMPORTACION ? ")) {
        $("#btnVerificar").attr('disabled', true);
        $("#btnImportar").attr('disabled', true);
        $.post('/josandro/inicio/bandejaentrada/importar', {importar: $("#importar").val()}, setImportar, 'json');
        bloqueoAjax();
    }
    return false;
}
function setImportar(respuestaServidor) {
    var contImportados = parseInt(respuestaServidor['contImportados']);
    var contErrores = parseInt(respuestaServidor['contErrores']);
    var errores = respuestaServidor['errores'];
    if (contErrores > 0) {
        $.each(errores, function (index, error) {
            $("#erroresMigracion").html($("#erroresMigracion").html() + "<br>" + error);
        });
        alert("SE HAN PRESENTADO ERRORES EN LA IMPORTACION. \nPOR FAVOR VERIFIQUE EL LISTADO DE ERRORES. \n\n ABONADOS IMPORTADOS: " + contImportados);
    } else {
        alert("LA IMPORTACION SE REALIZO SIN ERRORES. \n\n ABONADOS IMPORTADOS: " + contImportados);
    }
}

//------------------------------------------------------------------------------

function limpiarResultados() {
    if (confirm(" DESEA LIMPIAR LOS RESULTADOS DE VERIFICACION ? ")) {
        $("#erroresMigracion").html('');
    }
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

function reiniciar() {
    if (confirm(" DESEA RE-INICIAR EL PROCESO ? ")) {
        location.reload();
    }
}

//------------------------------------------------------------------------------

function verVentasSinEntregar() {
    $.get('/josandro/inicio/bandejaentrada/verventassinentregar', {}, setFormulario);
    bloqueoAjax();
}

//------------------------------------------------------------------------------

function verNotasCredito(idCobro) {
    $.get('/josandro/cobros/administracion/getnotascreditobycobro', {idCobro: idCobro}, setFormularioAux);
    bloqueoAjax();
}
function setFormularioAux(datos) {
    //console.log(datos)
    $("#divContenidoAux").html(datos);
    $('#modalAux').modal('show');
}

//------------------------------------------------------------------------------


