@extends('layouts.app')

@section('title', 'Autores')

@section('content')
<div x-data="authorsPage()" x-init="init()">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Autores</h1>
            <p class="text-secondary mb-0">Cadastro de autores consumindo a API REST.</p>
        </div>
        <button class="btn btn-primary" @click="openCreate()">Novo autor</button>
    </div>

    <div class="card page-card">
        <div class="card-body">
            <div class="table-responsive" x-show="!loading">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="author in authors" :key="author.authorId">
                            <tr>
                                <td x-text="author.authorId"></td>
                                <td x-text="author.name"></td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-secondary" @click="openEdit(author)">Editar</button>
                                    <button class="btn btn-sm btn-outline-danger" @click="remove(author.authorId)">Excluir</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <p class="text-secondary mb-0 mt-3" x-show="authors.length === 0">Nenhum autor cadastrado.</p>
            </div>
            <div x-show="loading" class="text-secondary">Carregando...</div>

            <div class="d-flex justify-content-between align-items-center mt-3" x-show="meta.last_page > 1">
                <span class="small text-secondary"
                      x-text="`Página ${meta.current_page} de ${meta.last_page}`"></span>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary" :disabled="meta.current_page <= 1" @click="load(meta.current_page - 1)">Anterior</button>
                    <button class="btn btn-sm btn-outline-secondary" :disabled="meta.current_page >= meta.last_page" @click="load(meta.current_page + 1)">Próxima</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="authorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" @submit.prevent="save">
                <div class="modal-header">
                    <h2 class="modal-title h5" x-text="form.authorId ? 'Editar autor' : 'Novo autor'"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nome</label>
                    <input
                        class="form-control"
                        :class="{ 'is-invalid': errors.name }"
                        x-model="form.name"
                        @input="clearFieldError('name')"
                        maxlength="40"
                    >
                    <div class="invalid-feedback" x-text="errors.name"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" :disabled="saving">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/authors.js') }}"></script>
@endpush
