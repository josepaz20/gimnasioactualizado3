function SSactivateResponseError() {
    Swal.fire({
        title: "¡ALGO SALIÓ MAL!",
        text: "El sistema no responde.\n Vuelve a intentarlo.",
        type: "error",
        showConfirmButton: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        showCancelButton: false
    });
}
function SSEmptyDataError() {
    Swal.fire({
        title: "¡ALGO SALIÓ MAL!",
        html: "Se ha presentado un incoveniente, <strong><u>JOSANDRO</u></strong> no ha podido realizar la operacion solicitada.",
        type: "error",
        showConfirmButton: true,
        showCancelButton: false,
        allowEscapeKey: false,
        focusConfirm: false,
        allowOutsideClick: false,
        showCloseButton: true,
        width: 400,
        padding: '4em',
        background: '#fff',
        showLoaderOnConfirm: false
    });
}
function bloqueoAjaxSwal() {
    Swal.fire({
        title: "SU SOLICITUD ESTA SIENDO PROCESADA, POR FAVOR ESPERE...",
        imageUrl: '/josandro/public/img/ellipsis_loader.gif',
        imageWidth: 100,
        imageHeight: 100,
        imageAlt: 'loader.gif',
        showConfirmButton: false,
        showCancelButton: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        focusConfirm: false,
        showCloseButton: false,
        width: 400,
        padding: '4em',
        background: '#fff',
        showLoaderOnConfirm: false
    });
}
function SSSEmptyDB() {
    Swal.fire({
        title: "¡NO SE ENCONTRO DATOS!",
        type: "error",
        showConfirmButton: true,
        showCancelButton: true,
        allowEscapeKey: false,
        focusConfirm: false,
        showCloseButton: true,
        allowOutsideClick: false,
        width: 400,
        padding: '4em',
        background: '#fff',
        showLoaderOnConfirm: false
    });
}
function SSSError() {
    Swal.fire({
        title: "¡ERROR!",
        type: "error",
        timer: 2000,
        showConfirmButton: true,
        showCancelButton: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        focusConfirm: false,
        showCloseButton: true,
        width: 400,
        padding: '4em',
        background: '#fff',
        showLoaderOnConfirm: false
    });
}
function SSSWarning() {
    Swal.fire({
        title: "¡ALERTA!",
        type: "warning",
        timer: 2000,
        showConfirmButton: true,
        showCancelButton: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        focusConfirm: false,
        showCloseButton: true,
        width: 400,
        padding: '4em',
        background: '#fff',
        footer: '!',
        showLoaderOnConfirm: false
    });
}
