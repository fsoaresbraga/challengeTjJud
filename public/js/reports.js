function reportsPage() {
    return {
        exporting: false,

        async exportPdf() {
            if (this.exporting) {
                return;
            }

            this.exporting = true;

            Swal.fire({
                title: 'Gerando relatório...',
                text: 'Aguarde enquanto o PDF é processado no servidor.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch('/api/reports/books-by-author/pdf', {
                    headers: {
                        Accept: 'application/pdf',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    let message = 'Não foi possível gerar o relatório.';
                    try {
                        const payload = await response.json();
                        message = payload.message || message;
                    } catch (e) {
                        // ignore JSON parse errors for non-JSON error bodies
                    }
                    throw new Error(message);
                }

                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'books-by-author.pdf';
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);

                await Swal.fire({
                    icon: 'success',
                    title: 'Relatório pronto',
                    text: 'O download do PDF foi iniciado.',
                    timer: 2500,
                    showConfirmButton: false,
                });
            } catch (error) {
                await UiFeedback.error(error.message || 'Erro ao exportar o relatório.');
            } finally {
                this.exporting = false;
            }
        },
    };
}
