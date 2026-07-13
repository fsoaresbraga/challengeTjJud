@extends('layouts.app')

@section('title', 'Relatório')

@section('content')
<div x-data="reportsPage()">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Relatório de livros por autor</h1>
            <p class="text-secondary mb-0">
                Exportação em PDF gerada no backend.
            </p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>

    <div class="card page-card">
        <div class="card-body py-5 text-center">
            <p class="mb-4 text-secondary">
                Clique no botão abaixo para gerar o relatório. A página não será recarregada;
                o download iniciará automaticamente ao concluir o processamento.
            </p>
            <button
                type="button"
                class="btn btn-primary btn-lg"
                @click="exportPdf"
                :disabled="exporting"
                x-text="exporting ? 'Gerando...' : 'Exportar PDF'"
            ></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/reports.js') }}"></script>
@endpush
