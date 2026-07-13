function subjectsPage() {
    return {
        subjects: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        loading: false,
        saving: false,
        modal: null,
        errors: {},
        form: { subjectId: null, description: '' },

        async init() {
            this.modal = new bootstrap.Modal(document.getElementById('subjectModal'));
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
            const description = String(this.form.description || '').trim();

            if (!description) {
                errors.description = 'Informe a descrição do assunto.';
            } else if (description.length > 20) {
                errors.description = 'A descrição deve ter no máximo 20 caracteres.';
            }

            this.errors = errors;
            return Object.keys(errors).length === 0;
        },

        async load(page = 1) {
            this.loading = true;
            try {
                const payload = await ApiClient.fetchPage('/api/subjects', page, 15);
                this.subjects = payload.data || [];
                this.meta = payload.meta || this.meta;
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            } finally {
                this.loading = false;
            }
        },

        openCreate() {
            this.clearErrors();
            this.form = { subjectId: null, description: '' };
            this.modal.show();
        },

        openEdit(subject) {
            this.clearErrors();
            this.form = { subjectId: subject.subjectId, description: subject.description };
            this.modal.show();
        },

        async save() {
            if (!this.validateForm()) {
                await UiFeedback.validation(UiFeedback.firstErrorMessage(this.errors));
                return;
            }

            this.saving = true;
            const payload = { description: String(this.form.description).trim() };

            try {
                if (this.form.subjectId) {
                    await ApiClient.put(`/api/subjects/${this.form.subjectId}`, payload);
                    await UiFeedback.success('Assunto atualizado com sucesso.');
                } else {
                    await ApiClient.post('/api/subjects', payload);
                    await UiFeedback.success('Assunto criado com sucesso.');
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

        async remove(subjectId) {
            const confirmed = await UiFeedback.confirmDelete('este assunto');
            if (!confirmed) {
                return;
            }

            try {
                await ApiClient.destroy(`/api/subjects/${subjectId}`);
                await UiFeedback.success('Assunto excluído com sucesso.');
                await this.load(this.meta.current_page || 1);
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            }
        },
    };
}
