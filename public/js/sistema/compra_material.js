//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('/josandro/inventario/compramaterial/add', {}, setFormulario);
}
function verDetalle(idCompraMaterial) {
    $.get('/josandro/inventario/compramaterial/detail', {idCompraMaterial: idCompraMaterial}, setFormulario);
}
function verEditar(idCompraMaterial) {
    $.get('/josandro/inventario/compramaterial/edit', {idCompraMaterial: idCompraMaterial}, setFormulario);
}
function verEliminar(idCompraMaterial) {
    $.get('/josandro/inventario/compramaterial/delete', {idCompraMaterial: idCompraMaterial}, setFormulario);
}
//------------------------------------------------------------------------------
function setFormulario(datos) {
    //console.log(datos)
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function seleccionarMaterial() {
    $.get('/josandro/inventario/material/seleccionar', {}, setSeleccionarMaterial);
}
function setSeleccionarMaterial(datos) {
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
                "sFirst": "<i class=\'fa fa-fast-backward\' aria-hidden=\'true\' title=\'Inicio\'></i>",
                "sPrevious": "<i class=\'fa fa-step-backward\' aria-hidden=\'true\' title=\'Anterior\'></i>",
                "sNext": "<i class=\'fa fa-step-forward\' aria-hidden=\'true\' title=\'Siguiente\'></i>",
                "sLast": "<i class=\'fa fa-fast-forward\' aria-hidden=\'true\' title=\'Fin\'></i>",
            }
        },
        "aaSorting": [[0, "desc"]]
    });
    $('#modalAnexar').modal('show');//este muetra mustra los datosyy
}
//------------------------------------------------------------------------------
function selectMaterial(idMaterial) {
    $.get('/josandro/inventario/material/getMaterial', {idMaterial: idMaterial}, setMaterial);
}
function setMaterial(datos) {
    $("#modalAnexar").modal('hide');
    $("#divInfoMaterial").html(datos);
    $("#divInfoMaterial").show('slow');
    $("#fk_material_id").val($("#pk_material_id").val());
}

//------------------------------------------------------------------------------

function validarGuardar() {
    if ($("#fk_material_id").val() === '') {
        alert("DEBE SELECCIONAR UN MATERIAL");
        $("#seleccionarMaterial").focus();
        return false;
    }
    if (confirm("¿ DESEA GUARDAR ESTA COMPRA DE MATERIAL ?")) {
        return true;
    } else {
        return false;
    }
}
