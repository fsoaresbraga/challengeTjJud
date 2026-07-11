function authorsPage() {
    return {
        authors: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        loading: false,
        saving: false,
        modal: null,
        errors: {},
        form: { authorId: null, name: '' },

        async init() {
            this.modal = new bootstrap.Modal(document.getElementById('authorModal'));
            await this.load(1);
        },

        clearErrors() {
            this.errors = {};
        },

        clearFieldError(field) {
            if (!this.errors[field]) {
                return;
            }

            const next = { ...this.errors };
            delete next[field];
            this.errors = next;
        },

        validateForm() {
            const errors = {};
            const name = String(this.form.name || '').trim();

            if (!name) {
                errors.name = 'Informe o nome do autor.';
            } else if (name.length > 40) {
                errors.name = 'O nome deve ter no máximo 40 caracteres.';
            }

            this.errors = errors;
            return Object.keys(errors).length === 0;
        },

        async load(page = 1) {
            this.loading = true;
            try {
                const payload = await ApiClient.fetchPage('/api/authors', page, 15);
                this.authors = payload.data || [];
                this.meta = payload.meta || this.meta;
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            } finally {
                this.loading = false;
            }
        },

        openCreate() {
            this.clearErrors();
            this.form = { authorId: null, name: '' };
            this.modal.show();
        },

        openEdit(author) {
            this.clearErrors();
            this.form = { authorId: author.authorId, name: author.name };
            this.modal.show();
        },

        async save() {
            if (!this.validateForm()) {
                await UiFeedback.validation(UiFeedback.firstErrorMessage(this.errors));
                return;
            }

            this.saving = true;
            const payload = { name: String(this.form.name).trim() };

            try {
                if (this.form.authorId) {
                    await ApiClient.put(`/api/authors/${this.form.authorId}`, payload);
                    await UiFeedback.success('Autor atualizado com sucesso.');
                } else {
                    await ApiClient.post('/api/authors', payload);
                    await UiFeedback.success('Autor criado com sucesso.');
                }
                this.modal.hide();
                this.clearErrors();
                await this.load(this.meta.current_page || 1);
            } catch (error) {
                if (error.status === 422) {
                    this.errors = UiFeedback.mapApiFieldErrors(error);
                    await UiFeedback.validation(UiFeedback.firstErrorMessage(this.errors));
                } else {
                    await UiFeedback.error(ApiClient.validationMessage(error));
                }
            } finally {
                this.saving = false;
            }
        },

        async remove(authorId) {
            const confirmed = await UiFeedback.confirmDelete('este autor');
            if (!confirmed) {
                return;
            }

            try {
                await ApiClient.destroy(`/api/authors/${authorId}`);
                await UiFeedback.success('Autor excluído com sucesso.');
                await this.load(this.meta.current_page || 1);
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            }
        },
    };
}
