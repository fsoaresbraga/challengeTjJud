function booksPage() {
    return {
        books: [],
        authorOptions: [],
        subjectOptions: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        loadingList: false,
        loadingOptions: false,
        saving: false,
        modal: null,
        priceMask: null,
        errors: {},
        form: {
            bookId: null,
            title: '',
            publisher: '',
            edition: 1,
            publicationYear: new Date().getFullYear(),
            priceMasked: '',
            authors: [],
            subjects: [],
        },

        async init() {
            this.modal = new bootstrap.Modal(document.getElementById('bookModal'));
            await Promise.all([this.loadBooks(1), this.loadOptions()]);
            this.$nextTick(() => this.bindPriceMask());
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

        currentPrice() {
            if (this.priceMask) {
                return Number(this.priceMask.unmaskedValue || 0);
            }

            return ApiClient.parseCurrencyBRL(this.form.priceMasked);
        },

        validateForm() {
            const errors = {};
            const title = String(this.form.title || '').trim();
            const publisher = String(this.form.publisher || '').trim();
            const edition = Number(this.form.edition);
            const publicationYear = Number(this.form.publicationYear);
            const price = this.currentPrice();
            const currentYear = new Date().getFullYear() + 1;

            if (!title) {
                errors.title = 'Informe o título do livro.';
            } else if (title.length > 40) {
                errors.title = 'O título deve ter no máximo 40 caracteres.';
            }

            if (!publisher) {
                errors.publisher = 'Informe a editora.';
            } else if (publisher.length > 40) {
                errors.publisher = 'A editora deve ter no máximo 40 caracteres.';
            }

            if (!Number.isInteger(edition) || edition < 1) {
                errors.edition = 'A edição deve ser um número inteiro maior ou igual a 1.';
            }

            if (!Number.isInteger(publicationYear) || publicationYear < 1000 || publicationYear > 9999) {
                errors.publicationYear = 'Informe um ano válido com 4 dígitos.';
            } else if (publicationYear > currentYear) {
                errors.publicationYear = `O ano não pode ser maior que ${currentYear}.`;
            }

            if (Number.isNaN(price) || price < 0) {
                errors.price = 'Informe um valor válido (maior ou igual a zero).';
            }

            if (!Array.isArray(this.form.authors) || this.form.authors.length === 0) {
                errors.authors = 'Selecione ao menos um autor.';
            }

            if (!Array.isArray(this.form.subjects) || this.form.subjects.length === 0) {
                errors.subjects = 'Selecione ao menos um assunto.';
            }

            this.errors = errors;
            return Object.keys(errors).length === 0;
        },

        bindPriceMask() {
            if (!this.$refs.priceInput || this.priceMask) {
                return;
            }

            this.priceMask = IMask(this.$refs.priceInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: '.',
                        radix: ',',
                        mapToRadix: ['.'],
                        scale: 2,
                        padFractionalZeros: true,
                        normalizeZeros: true,
                    },
                },
            });

            this.priceMask.on('accept', () => {
                this.form.priceMasked = this.priceMask.value;
                this.clearFieldError('price');
            });
        },

        resetForm() {
            this.clearErrors();
            this.form = {
                bookId: null,
                title: '',
                publisher: '',
                edition: 1,
                publicationYear: new Date().getFullYear(),
                priceMasked: '',
                authors: [],
                subjects: [],
            };

            if (this.priceMask) {
                this.priceMask.value = '';
            }
        },

        async loadOptions() {
            this.loadingOptions = true;
            try {
                const [authors, subjects] = await Promise.all([
                    ApiClient.fetchAllPages('/api/authors', 50),
                    ApiClient.fetchAllPages('/api/subjects', 50),
                ]);
                this.authorOptions = authors;
                this.subjectOptions = subjects;
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            } finally {
                this.loadingOptions = false;
            }
        },

        async loadBooks(page = 1) {
            this.loadingList = true;
            try {
                const payload = await ApiClient.fetchPage('/api/books', page, 10);
                this.books = payload.data || [];
                this.meta = payload.meta || this.meta;
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            } finally {
                this.loadingList = false;
            }
        },

        openCreate() {
            this.resetForm();
            this.modal.show();
            this.$nextTick(() => this.bindPriceMask());
        },

        openEdit(book) {
            this.clearErrors();
            this.form = {
                bookId: book.bookId,
                title: book.title,
                publisher: book.publisher,
                edition: book.edition,
                publicationYear: book.publicationYear,
                priceMasked: '',
                authors: (book.authors || []).map((author) => String(author.authorId)),
                subjects: (book.subjects || []).map((subject) => String(subject.subjectId)),
            };
            this.modal.show();
            this.$nextTick(() => {
                this.bindPriceMask();
                if (this.priceMask) {
                    this.priceMask.unmaskedValue = String(book.price ?? 0);
                    this.form.priceMasked = this.priceMask.value;
                }
            });
        },

        async save() {
            if (!this.validateForm()) {
                await UiFeedback.validation(UiFeedback.firstErrorMessage(this.errors));
                return;
            }

            this.saving = true;

            const payload = {
                title: String(this.form.title).trim(),
                publisher: String(this.form.publisher).trim(),
                edition: Number(this.form.edition),
                publicationYear: Number(this.form.publicationYear),
                price: this.currentPrice(),
                authors: this.form.authors.map(Number),
                subjects: this.form.subjects.map(Number),
            };

            try {
                if (this.form.bookId) {
                    await ApiClient.put(`/api/books/${this.form.bookId}`, payload);
                    await UiFeedback.success('Livro atualizado com sucesso.');
                } else {
                    await ApiClient.post('/api/books', payload);
                    await UiFeedback.success('Livro criado com sucesso.');
                }

                this.modal.hide();
                this.clearErrors();
                await this.loadBooks(this.meta.current_page || 1);
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

        async remove(bookId) {
            const confirmed = await UiFeedback.confirmDelete('este livro');
            if (!confirmed) {
                return;
            }

            try {
                await ApiClient.destroy(`/api/books/${bookId}`);
                await UiFeedback.success('Livro excluído com sucesso.');
                await this.loadBooks(this.meta.current_page || 1);
            } catch (error) {
                await UiFeedback.error(ApiClient.validationMessage(error));
            }
        },
    };
}
