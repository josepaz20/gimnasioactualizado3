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
    $.get('/josandro/usuarios/usuarios/add', {}, setFormulario);
}
function verDetalle(idUsuario) {
    $.get('/josandro/usuarios/usuarios/detail', {idUsuario: idUsuario}, setFormulario);
}
function verEditar(idUsuario) {
    $.get('/josandro/usuarios/usuarios/edit', {idUsuario: idUsuario}, setFormulario);
}
function verEliminar(idUsuario) {
    $.get('/josandro/usuarios/usuarios/delete', {idUsuario: idUsuario}, setFormulario);
}
function verActivar(idUsuario) {
    $.get('/josandro/usuarios/usuarios/activar', {idUsuario: idUsuario}, setFormulario);
}
function verBloquear(idUsuario) {
    $.get('/josandro/usuarios/usuarios/bloquear', {idUsuario: idUsuario}, setFormulario);
}
function verCambiarcontrasena() {
    $.get('../../usuarios/usuarios/cambiarcontrasena', {}, setFormulario);
}
function verGestionSucursales(idUsuario) {
    $.get('../../usuarios/usuarios/gestionsucursales', {idUsuario: idUsuario}, setFormulario);
}
//------------------------------------------------------------------------------
function setFormulario(datos) {
    $("#divContenido").html(datos);
    $('#modalFormulario').modal('show');
}

//------------------------------------------------------------------------------

function getLogin(idEmpleado) {
    $("#loginRegistro").val('');
    $("#password").val('');
    $("#passwordConfirm").val('');
    $("#nombresapellidos").val('');
    $("#sexo").val('');
    if (idEmpleado !== '') {
        $("#loginRegistro").attr('readonly', true);
        $("#loginRegistro").attr('required', false);
        $("#nombresapellidos").attr('readonly', true);
        $("#nombresapellidos").attr('required', false);
        $("#sexo").attr('readonly', true);
        $("#sexo").attr('required', false);
        $.get('/josandro/usuarios/usuarios/getLogin', {idEmpleado: idEmpleado}, setLogin, 'json');
    } else {
        $("#loginRegistro").attr('readonly', false);
        $("#loginRegistro").attr('required', true);
        $("#nombresapellidos").attr('readonly', false);
        $("#nombresapellidos").attr('required', true);
        $("#sexo").attr('readonly', false);
        $("#sexo").attr('required', true);
    }
}
function setLogin(datos) {
    if (parseInt(datos['error']) === 1) {
        alert("SE HA PRESENTADO UN INCONVENIENTE AL TRATAR DE OBTENER EL LOGIN DE USUARIO. POR FAVOR, INTENTE DE NUEVO. EN CASO DE PERSISTIR EL INCONVENIENTE COMUNIQUESE CON EL ADMINISTRADOR");
        location.reload();
        return;
    }
    $("#loginRegistro").val(datos['login']);
    $("#nombresapellidos").val(datos['nombresapellidos']);
    $("#sexo").val(datos['sexo']);
    $("#password").val('@' + datos['login'] + '#');
    $("#passwordConfirm").val('@' + datos['login'] + '#');
}

function verificarPassword() {
    if ($("#password").val() !== '') {
        if ($("#password").val().length < 6) {
            alert("EL PASSWORD DEBE TENER AL MENOS 6 CARACTERES");
            $("#password").attr('type', 'password');
            $("#passwordConfirm").attr('type', 'password');
            $("#password").val('');
            $("#password").focus();
            return;
        }
    }
    if ($("#password").val() !== '' && $("#passwordConfirm").val() !== '') {
        if ($("#password").val() !== $("#passwordConfirm").val()) {
            alert("EL PASSWORD Y SU CONFIRMACION NO COINCIDEN");
            $("#password").attr('type', 'password');
            $("#passwordConfirm").attr('type', 'password');
            $("#password").val('');
            $("#passwordConfirm").val('');
            $("#password").focus();
        }
    }
}

function mostrarPassword(mostrar, input) {
    if (mostrar) {
        $("#password").attr('type', 'text');
    } else {
        $("#password").attr('type', 'password');
    }
}

function mostrarPasswordConfirm(mostrar) {
    if (mostrar) {
        $("#passwordConfirm").attr('type', 'text');
    } else {
        $("#passwordConfirm").attr('type', 'password');
    }
}

function guardarNuevoPassword() {
    if (confirm("PARA QUE EL CAMBIO DE CONTRASEÑA SEA REGISTRADO LA SESION ACTUAL DEBE CERRARSE \n ¿ DESEA REGISTRAR EL CAMBIO DE CONTRASEÑA ?")) {
        $.post('../../usuarios/usuarios/cambiarcontrasena', $("#formCambiarcontrasena").serialize(), setNuevoPassword, 'json');
        bloqueoAjax();
    }
    return false;
}

function setNuevoPassword(respuesta) {
    switch (parseInt(respuesta['error'])) {
        case 0:
            alert("LA CONTRASEÑA FUE ACTUALIZADA EN JOSANDRO");
            location.href = '/josandro/login/login/cerrarSesion';
            break;
        case 1:
            alert("SE HA PRESENTADO UN ERROR, LA CONTRASEÑA NO FUE ACTUALIZADA");
            $('#modalFormulario').modal('hide');
            break;
        case 2:
            alert("ERROR, LA CONTRASEÑA ACTUAL ES INCORRECTA");
            $('#modalFormulario').modal('hide');
            break;
    }
    return false;
}
