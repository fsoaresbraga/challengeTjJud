window.UiFeedback = (function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true,
    });

    function success(message) {
        return Toast.fire({
            icon: 'success',
            title: message,
        });
    }

    function error(message) {
        return Swal.fire({
            icon: 'error',
            title: 'Atenção',
            text: message,
            confirmButtonText: 'Ok',
        });
    }

    function validation(message) {
        return Swal.fire({
            icon: 'warning',
            title: 'Validação',
            text: message,
            confirmButtonText: 'Corrigir',
        });
    }

    async function confirmDelete(entityLabel = 'este registro') {
        const result = await Swal.fire({
            icon: 'warning',
            title: 'Confirmar exclusão',
            text: `Deseja realmente excluir ${entityLabel}?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
        });

        return result.isConfirmed;
    }

    /**
     * Maps Laravel validation errors ({ field: [messages] }) to a flat object.
     */
    function mapApiFieldErrors(error) {
        const apiErrors = error?.payload?.errors || {};
        const mapped = {};

        Object.entries(apiErrors).forEach(([field, messages]) => {
            mapped[field] = Array.isArray(messages) ? messages[0] : String(messages);
        });

        return mapped;
    }

    function firstErrorMessage(errors) {
        const values = Object.values(errors || {}).filter(Boolean);
        return values[0] || 'Verifique os campos destacados.';
    }

    return {
        success,
        error,
        validation,
        confirmDelete,
        mapApiFieldErrors,
        firstErrorMessage,
    };
})();
