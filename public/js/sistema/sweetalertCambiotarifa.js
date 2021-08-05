function sweetAIdentificacion() {
    Swal.fire({
        title: 'BUSCAR',
        html: "<br>\n\
            <u>IDENTIFICACION:</u>",
        input: 'number',
        inputAttributes: {
            autocapitalize: 'off'
        },
        inputValidator: (value) => {
            return !value && 'Campo vacío'
        },
        allowEscapeKey: false,
        focusConfirm: false,
        showCloseButton: true,
        showConfirmButton: true,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText:
                '<i class="fa fa-search fa-lg"></i> Buscar',
        cancelButtonText: 'Cancelar',
        width: 400,
        padding: '4em',
        background: '#fff',
    }).then(function (result) {
        if (result.value) {
            searchCliente(result.value);
        }
    });
}
function sweetAClipboardEmpty() {
    Swal.fire({
        title: "¡ALGO SALIÓ MAL!",
        html: "Datos vacios para realizar la consulta.",
        type: "error",
        timer: 3000,
        showConfirmButton: false,
        showCancelButton: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        focusConfirm: false,
        showCloseButton: true,
        width: 400,
        padding: '4em',
        background: '#fff',
        footer: 'Datos vacios',
        showLoaderOnConfirm: false
    });
}
function sweetAEmpty(messege) {
    Swal.fire({
        title: "¡CAMPO VACÍO!",
        html: messege,
        type: "error",
        timer: 2000,
        showConfirmButton: false,
        showCancelButton: false,
        allowEscapeKey: false,
        focusConfirm: false,
        showCloseButton: true,
        allowOutsideClick: false,
        width: 400,
        padding: '4em',
        background: '#fff',
        footer: 'Null',
        showLoaderOnConfirm: false
    });
}
function sweetAConfirm(messege) {
    Swal.fire({
        html: messege,
        allowEscapeKey: false,
        focusConfirm: false,
        showCloseButton: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText:
                '<i class="fa fa-save fa-lg"></i> Aceptar',
        cancelButtonText: 'Cancelar',
        width: 400,
        padding: '4em',
        background: '#fff',
    }).then(function (result) {
        if (result.value) {
            $('#formCambiotarifa').removeAttr('onsubmit');
            $('#formCambiotarifa').submit();
        }
    });
}
function sweetASave() {
    Swal.fire({
        html: "¿ DESEA REGISTRAR ESTE CAMBIO DE TARIFA ?",
        allowEscapeKey: false,
        focusConfirm: false,
        showCloseButton: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText:
                '<i class="fa fa-save fa-lg"></i> Guardar',
        cancelButtonText: 'Cancelar',
        width: 400,
        padding: '4em',
        background: '#fff',
    }).then(function (result) {
        if (result.value) {
            $('#formCambiotarifa').removeAttr('onsubmit');
            $('#formCambiotarifa').submit();
        }
    });
}
