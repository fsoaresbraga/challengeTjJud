@extends('layouts.app')

@section('title', 'Livros')

@section('content')
<div x-data="booksPage()" x-init="init()">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Livros</h1>
            <p class="text-secondary mb-0">CRUD via API com associação de autores e assuntos.</p>
        </div>
        <button class="btn btn-primary" @click="openCreate()" :disabled="loadingOptions">Novo livro</button>
    </div>

    <div class="card page-card mb-4">
        <div class="card-body">
            <div class="table-responsive" x-show="!loadingList">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Valor</th>
                            <th>Autores</th>
                            <th>Assuntos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="book in books" :key="book.bookId">
                            <tr>
                                <td x-text="book.title"></td>
                                <td x-text="book.publisher"></td>
                                <td x-text="book.publicationYear"></td>
                                <td x-text="ApiClient.formatCurrencyBRL(book.price)"></td>
                                <td>
                                    <span class="small" x-text="(book.authors || []).map(a => a.name).join(', ')"></span>
                                </td>
                                <td>
                                    <span class="small" x-text="(book.subjects || []).map(s => s.description).join(', ')"></span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-secondary" @click="openEdit(book)">Editar</button>
                                    <button class="btn btn-sm btn-outline-danger" @click="remove(book.bookId)">Excluir</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <p class="text-secondary mb-0 mt-3" x-show="books.length === 0">Nenhum livro cadastrado.</p>
            </div>
            <div x-show="loadingList" class="text-secondary">Carregando...</div>

            <div class="d-flex justify-content-between align-items-center mt-3" x-show="meta.last_page > 1">
                <span class="small text-secondary"
                      x-text="`Página ${meta.current_page} de ${meta.last_page} (${meta.total} registros)`"></span>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary" :disabled="meta.current_page <= 1" @click="loadBooks(meta.current_page - 1)">Anterior</button>
                    <button class="btn btn-sm btn-outline-secondary" :disabled="meta.current_page >= meta.last_page" @click="loadBooks(meta.current_page + 1)">Próxima</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" @submit.prevent="save">
                <div class="modal-header">
                    <h2 class="modal-title h5" x-text="form.bookId ? 'Editar livro' : 'Novo livro'"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Título</label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.title }"
                                x-model="form.title"
                                @input="clearFieldError('title')"
                                maxlength="150"
                            >
                            <div class="invalid-feedback" x-text="errors.title"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ano</label>
                            <input
                                class="form-control"
                                type="number"
                                :class="{ 'is-invalid': errors.publicationYear }"
                                x-model.number="form.publicationYear"
                                @input="clearFieldError('publicationYear')"
                                min="1000"
                                max="9999"
                            >
                            <div class="invalid-feedback" x-text="errors.publicationYear"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Editora</label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.publisher }"
                                x-model="form.publisher"
                                @input="clearFieldError('publisher')"
                                maxlength="40"
                            >
                            <div class="invalid-feedback" x-text="errors.publisher"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Edição</label>
                            <input
                                class="form-control"
                                type="number"
                                :class="{ 'is-invalid': errors.edition }"
                                x-model.number="form.edition"
                                @input="clearFieldError('edition')"
                                min="1"
                            >
                            <div class="invalid-feedback" x-text="errors.edition"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor</label>
                            <input
                                class="form-control"
                                :class="{ 'is-invalid': errors.price }"
                                x-ref="priceInput"
                                x-model="form.priceMasked"
                            >
                            <div class="invalid-feedback" x-text="errors.price"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Autores</label>
                            <select
                                class="form-select"
                                :class="{ 'is-invalid': errors.authors }"
                                multiple
                                size="6"
                                x-model="form.authors"
                                @change="clearFieldError('authors')"
                            >
                                <template x-for="author in authorOptions" :key="author.authorId">
                                    <option :value="String(author.authorId)" x-text="author.name"></option>
                                </template>
                            </select>
                            <div class="invalid-feedback" x-text="errors.authors"></div>
                            <div class="form-text">Segure Ctrl/Cmd para múltipla seleção. Opções carregadas em lotes.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assuntos</label>
                            <select
                                class="form-select"
                                :class="{ 'is-invalid': errors.subjects }"
                                multiple
                                size="6"
                                x-model="form.subjects"
                                @change="clearFieldError('subjects')"
                            >
                                <template x-for="subject in subjectOptions" :key="subject.subjectId">
                                    <option :value="String(subject.subjectId)" x-text="subject.description"></option>
                                </template>
                            </select>
                            <div class="invalid-feedback" x-text="errors.subjects"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" :disabled="saving" x-text="saving ? 'Salvando...' : 'Salvar'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/books.js') }}"></script>
@endpush
