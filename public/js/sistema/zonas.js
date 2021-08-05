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
function verEditar(idZona) {
    $.get('editar', {idZona: idZona}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idServicio) {
    $.get('detalle', {idServicio: idServicio}, setFormulario);
    bloqueoAjax();
}
function verEliminar(idInternet) {
    $.get('delete', {idInternet: idInternet}, setFormulario);
    bloqueoAjax();
}

function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------


//------------------------------------------------------------------------------