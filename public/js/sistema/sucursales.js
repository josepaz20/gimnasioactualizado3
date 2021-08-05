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
    $("#fk_municipio_id").val(idMunicipio);
}

//------------------------------------------------------------------------------

function getCentrospoblados(idMunicipio) {
    $("#pk_municipio_id").html('<option value="">Seleccione...</option>');
    $("#pk_centro_poblado_id").html('<option value="">Seleccione...</option>');
    $("#fk_centro_poblado_id").val("");
    $.get('/josandro/comercial/ubicacion/getMunicipios', {idMunicipio: idMunicipio}, setMunicipios);
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

//------------------------------------------------------------------------------

function verRegistrar() {
    $.get('add', {}, setFormulario);
    bloqueoAjax();
}
function verDetalle(idSucursal) {
    $.get('detail', {idSucursal: idSucursal}, setFormulario);
    bloqueoAjax();
}
function verEditar(idSucursal) {
    $.get('edit', {idSucursal: idSucursal}, setFormulario);
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
